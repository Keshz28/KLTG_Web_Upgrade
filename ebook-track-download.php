<?php
/* ============================================================================
 *                          ebook-track-download.php
 *
 *   Fire-and-forget endpoint that records an e-book download. Called via
 *   navigator.sendBeacon() from the download button on ebook.php, so the actual
 *   file is still served directly by Apache (fast) while the counter ticks here.
 *
 *   Public POST (no admin session) — increments ebook_download for one ebook id.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
include('admin/functions.php');   // gives $db (with correct env-resolved credentials)

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id > 0) {
    $stmt = mysqli_prepare($db, "UPDATE ebook SET ebook_download = ebook_download + 1 WHERE ebook_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

http_response_code(204); // No Content — nothing for the beacon to read.
