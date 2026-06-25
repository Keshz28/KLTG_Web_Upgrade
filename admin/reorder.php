<?php
/* ============================================================================
 *                               reorder.php
 *
 *   Admin — drag-and-drop reordering endpoint (works with admin/js/dragreorder.js).
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
// AJAX endpoint: persist drag-and-drop row order for whitelisted admin tables.
// Receives: table=<key>, ids[]=<id in new top-to-bottom order>
// Writes the order column = 1..N. No page reload involved.

ob_start();
include 'functions.php';   // gives $db + session
ob_end_clean();            // discard any incidental output so JSON stays clean

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// Whitelist of reorderable tables. Every one follows the site convention of
// <table>_id / <table>_order columns, so the columns are derived from the key.
// (Keys are whitelisted, so the derived identifiers are safe to interpolate.)
$allowed = [
    'klglance',
    'explorekl_wtd', 'explorekl_hs', 'explorekl_kl4k', 'explorekl_p', 'explorekl_pwor',
    'explorekl_nl', 'explorekl_ss', 'explorekl_wte_sf', 'explorekl_wte_c', 'explorekl_wte_r',
    'beyondkl_es', 'beyondkl_h', 'beyondkl_hs', 'beyondkl_w', 'beyondkl_i',
    'medical_tourism_hc', 'medical_tourism_dtl', 'medical_tourism_der', 'medical_tourism_oph', 'medical_tourism_ps',
    'accommodation_top', 'accommodation_h', 'accommodation_bh', 'accommodation_bks',
    'place_shop', 'highlights', 'spa', 'event', 'banner',
];

$key = isset($_POST['table']) ? $_POST['table'] : '';
if (!in_array($key, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Unknown table']);
    exit;
}
$m = ['table' => $key, 'id' => $key . '_id', 'order' => $key . '_order'];

$ids = isset($_POST['ids']) ? $_POST['ids'] : [];
if (is_string($ids)) {
    $ids = array_filter(array_map('trim', explode(',', $ids)), 'strlen');
}
if (!is_array($ids) || count($ids) === 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No ids provided']);
    exit;
}

$sql  = "UPDATE {$m['table']} SET {$m['order']} = ? WHERE {$m['id']} = ?";
$stmt = mysqli_prepare($db, $sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Prepare failed']);
    exit;
}

mysqli_begin_transaction($db);
$pos = 1;
$ok  = true;
foreach ($ids as $id) {
    $idi = (int)$id;
    mysqli_stmt_bind_param($stmt, 'ii', $pos, $idi);
    if (!mysqli_stmt_execute($stmt)) {
        $ok = false;
        break;
    }
    $pos++;
}
mysqli_stmt_close($stmt);

if ($ok) {
    mysqli_commit($db);
    echo json_encode(['success' => true, 'count' => count($ids)]);
} else {
    mysqli_rollback($db);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Update failed']);
}
