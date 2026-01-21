<?php
// Validate and sanitize input
$filename = isset($_POST['banner_filename']) ? trim($_POST['banner_filename']) : '';
$name = isset($_POST['banner_name']) ? trim($_POST['banner_name']) : '';
$clicks = isset($_POST['clicks']) ? (int)$_POST['clicks'] : 0;

// Validate required fields
if (empty($filename) || empty($name)) {
    http_response_code(400);
    exit('Invalid data');
}

// URL encode for security
$filename = urlencode($filename);
$name = urlencode($name);

$ch = curl_init('https://www.kltheguide.com.my/admin/functions.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "banner=banner&banner_filename=" . $filename . "&banner_name=" . $name . "&clicks=" . $clicks);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

if ($curlError) {
    http_response_code(500);
    exit('API Error');
}

echo $response;
