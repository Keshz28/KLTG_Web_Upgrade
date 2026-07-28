<?php
/* ============================================================================
 *                     pagefunctions/edit-sectionnav.php
 *
 *   Add / edit / delete for the section-navigation tile tables that sit at the
 *   top of four editors: explorekl_nav, beyondkl_nav, accommodation_nav and
 *   medical_tourism_nav. These four were previously buried at the bottom of
 *   edit-mt.php, which made them impossible to find from any page but Medical
 *   Tourism.
 *
 *   All four tables share the same shape: id, name, orderof, display, filename.
 *
 *   Fixed while moving:
 *     - `if ($filename = "")` was an assignment, not a comparison. It blanked
 *       $filename every time a replacement image was uploaded and never ran the
 *       unlink it was guarding, so old tile images were left behind and a failed
 *       upload wiped the row's filename.
 *     - SQL was built by string interpolation.
 *     - uploadimage() refused to overwrite, so re-uploading a file of the same
 *       name silently stored an empty filename.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */

/**
 * One nav table's add/edit/delete.
 *
 * $table  — e.g. 'explorekl_nav'
 * $folder — image folder under assets/img/, e.g. 'explorekl'
 * $keys   — [add, edit, delete] submit-button names
 * $label  — human name used in the toast
 */
function cms_sectionnav_crud(mysqli $db, string $table, string $folder, array $keys, string $label): void
{
    [$addKey, $editKey, $deleteKey] = $keys;

    $flash = static function (string $type, string $msg): void {
        $_SESSION['alert_type'] = $type;
        $_SESSION['alert_msg']  = $msg;
    };
    $back = static function (): void {
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    };

    /* ------------------------------------------------------------------ ADD */
    if (isset($_POST[$addKey])) {
        $name    = urlencode((string) ($_POST['name'] ?? ''));
        $orderof = (int) ($_POST['orderof'] ?? 0);
        $display = (int) ($_POST['display'] ?? 0);

        $filename = cms_store_image($_FILES['fileToUploaddtl'] ?? null, $folder, $err);
        if ($err !== null) { $flash('error', $err); $back(); }

        $stmt = mysqli_prepare($db, "INSERT INTO `$table` (name, orderof, display, filename) VALUES (?,?,?,?)");
        if ($stmt) {
            $file = (string) $filename;
            mysqli_stmt_bind_param($stmt, 'siis', $name, $orderof, $display, $file);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $flash($ok ? 'success' : 'error', $ok ? "Added new $label navigation" : 'Could not add: ' . mysqli_error($db));
        } else {
            $flash('error', 'Database error: ' . mysqli_error($db));
        }
        $back();
    }

    /* ----------------------------------------------------------------- EDIT */
    if (isset($_POST[$editKey])) {
        $id      = (int) ($_POST['id'] ?? 0);
        $name    = (string) ($_POST['name'] ?? '');
        $orderof = (int) ($_POST['orderof'] ?? 0);
        $display = (int) ($_POST['display'] ?? 0);
        $current = basename((string) ($_POST['filename'] ?? ''));
        if ($id <= 0) { $flash('error', 'Missing record id — nothing was saved.'); $back(); }

        $uploaded = cms_store_image($_FILES['fileToUploaddtl'] ?? null, $folder, $err);
        if ($err !== null) { $flash('error', $err); $back(); }
        $filename = $uploaded ?? $current;

        $stmt = mysqli_prepare($db, "UPDATE `$table` SET name=?, orderof=?, display=?, filename=? WHERE id=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'siisi', $name, $orderof, $display, $filename, $id);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Remove the superseded image only after the row points at the new one.
            if ($ok && $uploaded !== null && $current !== '' && $current !== $uploaded) {
                $old = '../assets/img/' . trim($folder, '/') . '/' . $current;
                if (is_file($old)) @unlink($old);
            }
            $flash($ok ? 'success' : 'error', $ok ? "Saved $label navigation" : 'Could not save: ' . mysqli_error($db));
        } else {
            $flash('error', 'Database error: ' . mysqli_error($db));
        }
        $back();
    }

    /* --------------------------------------------------------------- DELETE */
    if (isset($_POST[$deleteKey])) {
        $id       = (int) ($_POST['id'] ?? 0);
        $filename = basename((string) ($_POST['filename'] ?? ''));
        if ($id <= 0) { $flash('error', 'Missing record id — nothing was deleted.'); $back(); }

        $stmt = mysqli_prepare($db, "DELETE FROM `$table` WHERE id=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            $ok = mysqli_stmt_execute($stmt);
            $rows = $ok ? mysqli_stmt_affected_rows($stmt) : 0;
            mysqli_stmt_close($stmt);

            if ($ok && $rows > 0 && $filename !== '') {
                $path = '../assets/img/' . trim($folder, '/') . '/' . $filename;
                if (is_file($path)) @unlink($path);
            }
            $flash($ok ? 'success' : 'error', $ok ? "Deleted $label navigation" : 'Could not delete: ' . mysqli_error($db));
        } else {
            $flash('error', 'Database error: ' . mysqli_error($db));
        }
        $back();
    }
}

cms_sectionnav_crud($db, 'medical_tourism_nav', 'medical_tourism',
    ['addnewnavmt', 'editnavmt', 'deletenavmt'], 'Medical Tourism');

cms_sectionnav_crud($db, 'accommodation_nav', 'accommodation',
    ['addnewnavaccommodation', 'editnavaccommodation', 'deletenavaccommodation'], 'Place To Stay');

cms_sectionnav_crud($db, 'beyondkl_nav', 'beyondkl',
    ['addnewnavbeyondkl', 'editnavbeyondkl', 'deletenavbeyondkl'], 'Beyond KL');

cms_sectionnav_crud($db, 'explorekl_nav', 'explorekl',
    ['addnewnavexplorekl', 'editnavexplorekl', 'deletenavexplorekl'], 'Explore KL');
