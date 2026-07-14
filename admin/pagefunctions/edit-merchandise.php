<?php
/* ============================================================================
 *                       pagefunctions/edit-merchandise.php
 *
 *   Handles POSTs from admin/edit-merchandise.php:
 *     add_merch / edit_merch / delete_merch  (products, with image upload)
 *     add_merch_category / delete_merch_category  (categories)
 *
 *   Included from functions.php AFTER the central CSRF guard, so every POST
 *   here is already token-checked. Image uploads land in assets/img/merchandise/
 *   (paths are relative to admin/, matching the other pagefunctions handlers).
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */

$merch_dir       = "../assets/img/merchandise/";
$merch_allowed   = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$merch_max_bytes = 40 * 1024 * 1024; // 40 MB — large originals are auto-downscaled below

/**
 * Downscale + re-encode a large uploaded image in place so the public grid stays
 * light (project pattern: keep stored images web-sized). Long edge is capped at
 * 1600px; smaller images and unsupported types are left untouched.
 */
function merch_optimize_image(string $path, string $ext): void
{
    if (!extension_loaded('gd') || !is_file($path)) return;
    $maxEdge = 1600;

    $info = @getimagesize($path);
    if (!$info) return;
    [$w, $h] = $info;
    if ($w <= 0 || $h <= 0 || max($w, $h) <= $maxEdge) return; // already small enough

    switch ($ext) {
        case 'jpg': case 'jpeg': $src = @imagecreatefromjpeg($path); break;
        case 'png':              $src = @imagecreatefrompng($path);  break;
        case 'gif':              $src = @imagecreatefromgif($path);  break;
        case 'webp':             $src = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false; break;
        default: return;
    }
    if (!$src) return;

    $scale = $maxEdge / max($w, $h);
    $nw = max(1, (int) round($w * $scale));
    $nh = max(1, (int) round($h * $scale));

    $dst = imagecreatetruecolor($nw, $nh);
    if (in_array($ext, ['png', 'webp'], true)) {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagefilledrectangle($dst, 0, 0, $nw, $nh, imagecolorallocatealpha($dst, 0, 0, 0, 127));
    }
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

    switch ($ext) {
        case 'jpg': case 'jpeg': @imagejpeg($dst, $path, 82); break;
        case 'png':              @imagepng($dst, $path, 6);   break;
        case 'gif':              @imagegif($dst, $path);      break;
        case 'webp':             if (function_exists('imagewebp')) @imagewebp($dst, $path, 82); break;
    }
    imagedestroy($src);
    imagedestroy($dst);
}

/**
 * Validate + move an uploaded product image. Returns the stored filename on
 * success, or null on failure (pushing a message onto $errors2).
 */
function merch_handle_upload(array $file): ?string
{
    global $merch_dir, $merch_allowed, $merch_max_bytes, $errors2;

    if (!isset($file['name']) || $file['name'] === '' || ($file['error'] ?? 0) !== UPLOAD_ERR_OK) {
        $code = $file['error'] ?? UPLOAD_ERR_NO_FILE;
        $map = [
            UPLOAD_ERR_INI_SIZE   => "Image is larger than the server upload limit (upload_max_filesize). Use a smaller image.",
            UPLOAD_ERR_FORM_SIZE  => "Image is larger than the form allows.",
            UPLOAD_ERR_PARTIAL    => "The image was only partially uploaded — please try again.",
            UPLOAD_ERR_NO_FILE    => "No image was selected.",
            UPLOAD_ERR_NO_TMP_DIR => "Server is missing a temporary folder for uploads.",
            UPLOAD_ERR_CANT_WRITE => "Server could not write the uploaded file to disk.",
            UPLOAD_ERR_EXTENSION  => "A PHP extension blocked the upload.",
        ];
        error_log("merch upload failed: err_code=$code content_length=" . ($_SERVER['CONTENT_LENGTH'] ?? '?') . " post_max=" . ini_get('post_max_size'));
        array_push($errors2, $map[$code] ?? "No image was uploaded.");
        return null;
    }
    if (!is_dir($merch_dir)) {
        @mkdir($merch_dir, 0775, true);
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $merch_allowed, true)) {
        array_push($errors2, "Only JPG, JPEG, PNG, GIF & WEBP files are allowed.");
        return null;
    }
    if (getimagesize($file['tmp_name']) === false) {
        array_push($errors2, "Uploaded file is not a valid image.");
        return null;
    }
    if ($file['size'] > $merch_max_bytes) {
        array_push($errors2, "Image is too large (max 10 MB).");
        return null;
    }

    // Unique, safe filename so re-uploads never collide.
    $base     = preg_replace('/[^A-Za-z0-9_-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
    $filename = $base . '_' . time() . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $merch_dir . $filename)) {
        array_push($errors2, "Failed to save the uploaded image.");
        return null;
    }
    merch_optimize_image($merch_dir . $filename, $ext);
    return $filename;
}

// ── Safety guard ──────────────────────────────────────────────────────────────
// Every action below needs the merchandise tables. If db_migration_merchandise.sql
// hasn't been run yet (e.g. a fresh deploy), mysqli_prepare() would return false and
// mysqli_stmt_bind_param() would throw a fatal TypeError on PHP 8. Detect the missing
// table once and fail with a clear admin message instead of a white screen.
$merch_actions = ['add_merch', 'edit_merch', 'delete_merch', 'add_merch_category',
    'delete_merch_category', 'save_merch_settings', 'update_order_status', 'delete_order'];
if (array_intersect($merch_actions, array_keys($_POST))) {
    foreach (['merchandise', 'merchandise_category', 'merchandise_settings', 'merchandise_orders'] as $t) {
        $chk = mysqli_query($db, "SHOW TABLES LIKE '$t'");
        if (!$chk || mysqli_num_rows($chk) === 0) {
            array_push($errors2, "Merchandise tables are missing — run db_migration_merchandise.sql and db_migration_merchandise_orders.sql before managing merchandise.");
            return; // included file: stops this handler, control returns to functions.php
        }
    }
}

// ── Oversized-upload guard ──────────────────────────────────────────────────
// When an upload exceeds post_max_size, PHP silently discards $_POST AND $_FILES,
// so none of the actions below fire and the page just reloads with no product and
// no message. Detect that here (only on admin pages, where the toast is shown).
if (empty($_POST)
    && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
    && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0
    && strpos($_SERVER['PHP_SELF'] ?? '', '/admin/') !== false) {
    array_push($errors2, "Upload too large — it exceeded the server limit (post_max_size = "
        . ini_get('post_max_size') . "). Please use a smaller image.");
}

// ── Add category ────────────────────────────────────────────────────────────
if (isset($_POST['add_merch_category'])) {
    $name = trim($_POST['category_name'] ?? '');
    if ($name !== '') {
        $ordRes  = mysqli_query($db, "SELECT COALESCE(MAX(merchandise_category_order), 0) + 1 AS nextord FROM merchandise_category");
        $nextOrd = ($ordRes && ($r = mysqli_fetch_assoc($ordRes))) ? (int) $r['nextord'] : 1;
        $stmt = mysqli_prepare($db, "INSERT INTO merchandise_category (name, merchandise_category_order) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'si', $name, $nextOrd);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        array_push($errors2, "Category added.");
    } else {
        array_push($errors2, "Category name is required.");
    }
}

// ── Delete category (products in it become uncategorised) ────────────────────
if (isset($_POST['delete_merch_category'])) {
    $cid = (int) ($_POST['category_id'] ?? 0);
    if ($cid > 0) {
        $stmt = mysqli_prepare($db, "UPDATE merchandise SET category_id = NULL WHERE category_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $cid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($db, "DELETE FROM merchandise_category WHERE merchandise_category_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $cid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        array_push($errors2, "Category deleted.");
    }
}

// ── Add product ──────────────────────────────────────────────────────────────
if (isset($_POST['add_merch'])) {
    $filename = merch_handle_upload($_FILES['fileToUpload'] ?? []);
    if ($filename !== null) {
        $name  = trim($_POST['name'] ?? '');
        $desc  = trim($_POST['description'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $url   = trim($_POST['buy_url'] ?? '');
        $cid   = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int) $_POST['category_id'] : null;

        $ordRes  = mysqli_query($db, "SELECT COALESCE(MAX(merchandise_order), 0) + 1 AS nextord FROM merchandise");
        $nextOrd = ($ordRes && ($r = mysqli_fetch_assoc($ordRes))) ? (int) $r['nextord'] : 1;

        $stmt = mysqli_prepare($db, "INSERT INTO merchandise (name, description, image, category_id, price, buy_url, merchandise_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssissi', $name, $desc, $filename, $cid, $price, $url, $nextOrd);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        array_push($errors2, "Product added.");
    }
}

// ── Edit product ──────────────────────────────────────────────────────────────
if (isset($_POST['edit_merch'])) {
    $id    = (int) ($_POST['merchandise_id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $url   = trim($_POST['buy_url'] ?? '');
    $cid   = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int) $_POST['category_id'] : null;

    if ($id > 0) {
        // Optional new image; otherwise keep the current one.
        $filename = $_POST['current_image'] ?? '';
        if (isset($_FILES['fileToUpload']) && ($_FILES['fileToUpload']['name'] ?? '') !== '') {
            $new = merch_handle_upload($_FILES['fileToUpload']);
            if ($new !== null) {
                // Remove the previous image file if it was replaced.
                if ($filename !== '' && is_file($merch_dir . $filename)) {
                    @unlink($merch_dir . $filename);
                }
                $filename = $new;
            }
        }

        $stmt = mysqli_prepare($db, "UPDATE merchandise SET name = ?, description = ?, image = ?, category_id = ?, price = ?, buy_url = ? WHERE merchandise_id = ?");
        mysqli_stmt_bind_param($stmt, 'sssissi', $name, $desc, $filename, $cid, $price, $url, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        array_push($errors2, "Product updated.");
    }
}

// ── Delete product ────────────────────────────────────────────────────────────
if (isset($_POST['delete_merch'])) {
    $id = (int) ($_POST['merchandise_id'] ?? 0);
    if ($id > 0) {
        // Look up the image so we can remove the file too.
        $stmt = mysqli_prepare($db, "SELECT image FROM merchandise WHERE merchandise_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($row && $row['image'] !== '' && is_file($merch_dir . $row['image'])) {
            @unlink($merch_dir . $row['image']);
        }

        $stmt = mysqli_prepare($db, "DELETE FROM merchandise WHERE merchandise_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        array_push($errors2, "Product deleted.");
    }
}

// ── Save store settings (WhatsApp number + payment QR) ──────────────────────────
if (isset($_POST['save_merch_settings'])) {
    // Keep digits only for the WhatsApp number (wa.me needs no +, spaces or dashes).
    $wa = preg_replace('/\D+/', '', $_POST['whatsapp_number'] ?? '');

    // Optional new QR image; otherwise keep the current one.
    $qr = '';
    $curRes = mysqli_query($db, "SELECT payment_qr FROM merchandise_settings WHERE id = 1");
    if ($curRes && ($cr = mysqli_fetch_assoc($curRes))) { $qr = $cr['payment_qr']; }

    if (isset($_FILES['payment_qr']) && ($_FILES['payment_qr']['name'] ?? '') !== '') {
        $newQr = merch_handle_upload($_FILES['payment_qr']);
        if ($newQr !== null) {
            if ($qr !== '' && is_file($merch_dir . $qr)) { @unlink($merch_dir . $qr); }
            $qr = $newQr;
        }
    }

    $stmt = mysqli_prepare($db, "UPDATE merchandise_settings SET whatsapp_number = ?, payment_qr = ? WHERE id = 1");
    mysqli_stmt_bind_param($stmt, 'ss', $wa, $qr);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    array_push($errors2, "Store settings saved.");
}

// ── Update an order's status ────────────────────────────────────────────────────
if (isset($_POST['update_order_status'])) {
    $oid    = (int) ($_POST['order_id'] ?? 0);
    $status = $_POST['order_status'] ?? '';
    $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
    if ($oid > 0 && in_array($status, $allowed, true)) {
        $stmt = mysqli_prepare($db, "UPDATE merchandise_orders SET status = ? WHERE order_id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $status, $oid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        array_push($errors2, "Order status updated.");
    }
}

// ── Delete an order (and its receipt file) ──────────────────────────────────────
if (isset($_POST['delete_order'])) {
    $oid = (int) ($_POST['order_id'] ?? 0);
    if ($oid > 0) {
        $receipt_dir = "../assets/img/receipts/";
        $stmt = mysqli_prepare($db, "SELECT receipt_image FROM merchandise_orders WHERE order_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $oid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($row && $row['receipt_image'] !== '' && is_file($receipt_dir . $row['receipt_image'])) {
            @unlink($receipt_dir . $row['receipt_image']);
        }

        $stmt = mysqli_prepare($db, "DELETE FROM merchandise_orders WHERE order_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $oid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        array_push($errors2, "Order deleted.");
    }
}
