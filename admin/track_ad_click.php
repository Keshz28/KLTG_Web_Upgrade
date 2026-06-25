<?php
/* ============================================================================
 *                            track_ad_click.php
 *
 *   Records an advertisement click (pairs with edit-advertisement.php).
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
include('functions.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

$banner_name = 'Advertisement Popup';

// Ensure the banner exists in the banner lookup table
$safe_name = mysqli_real_escape_string($db, $banner_name);
mysqli_query($db, "INSERT IGNORE INTO banner (banner_name) VALUES ('$safe_name')");

// Record the click
$query = "INSERT INTO banner_reach (banner_name, date) VALUES ('$safe_name', NOW())";
$ok = mysqli_query($db, $query);

echo json_encode(['ok' => (bool)$ok]);
