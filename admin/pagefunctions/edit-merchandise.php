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
$merch_max_bytes = 10 * 1024 * 1024; // 10 MB

/**
 * Validate + move an uploaded product image. Returns the stored filename on
 * success, or null on failure (pushing a message onto $errors2).
 */
function merch_handle_upload(array $file): ?string
{
    global $merch_dir, $merch_allowed, $merch_max_bytes, $errors2;

    if (!isset($file['name']) || $file['name'] === '' || ($file['error'] ?? 0) !== UPLOAD_ERR_OK) {
        array_push($errors2, "No image was uploaded.");
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
    return $filename;
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
        $price = trim($_POST['price'] ?? '');
        $url   = trim($_POST['buy_url'] ?? '');
        $cid   = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int) $_POST['category_id'] : null;

        $ordRes  = mysqli_query($db, "SELECT COALESCE(MAX(merchandise_order), 0) + 1 AS nextord FROM merchandise");
        $nextOrd = ($ordRes && ($r = mysqli_fetch_assoc($ordRes))) ? (int) $r['nextord'] : 1;

        $stmt = mysqli_prepare($db, "INSERT INTO merchandise (name, image, category_id, price, buy_url, merchandise_order) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssissi', $name, $filename, $cid, $price, $url, $nextOrd);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        array_push($errors2, "Product added.");
    }
}

// ── Edit product ──────────────────────────────────────────────────────────────
if (isset($_POST['edit_merch'])) {
    $id    = (int) ($_POST['merchandise_id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
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

        $stmt = mysqli_prepare($db, "UPDATE merchandise SET name = ?, image = ?, category_id = ?, price = ?, buy_url = ? WHERE merchandise_id = ?");
        mysqli_stmt_bind_param($stmt, 'ssissi', $name, $filename, $cid, $price, $url, $id);
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
