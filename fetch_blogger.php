<?php
// fetch_blogger.php

// Google Blogger API
$apiKey = "AIzaSyARErRCSmyD1dikK0ndYipEuORgXIeLRB8"; 
$blogId = "1732826187557117921";

// Check if single post requested
if (isset($_GET['postid'])) {
    $postId = preg_replace('/[^0-9]/', '', $_GET['postid']); // sanitize (numeric only)
    $url = "https://www.googleapis.com/blogger/v3/blogs/$blogId/posts/$postId?key=$apiKey&fetchImages=true";
} else {
    // Pagination token
    $pageToken = isset($_GET['pageToken']) ? "&pageToken=" . urlencode($_GET['pageToken']) : "";
    $labels    = isset($_GET['labels']) ? "&labels=" . urlencode($_GET['labels']) : "";

    // API URL for list
    $url = "https://www.googleapis.com/blogger/v3/blogs/$blogId/posts?key=$apiKey&fetchImages=true&maxResults=30$pageToken$labels";
}

// Cache file (store results for 5 minutes)
$cacheKey = md5($url);
$cacheFile = __DIR__ . "/cache/$cacheKey.json";
$cacheTime = 300; // 5 minutes

// Make sure cache folder exists
if (!file_exists(__DIR__ . "/cache")) {
    mkdir(__DIR__ . "/cache", 0777, true);
}

// Serve from cache if fresh
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    header('Content-Type: application/json');
    echo file_get_contents($cacheFile);
    exit;
}

// Fetch from API
$response = file_get_contents($url);
if ($response !== false) {
    file_put_contents($cacheFile, $response); // save to cache
    header('Content-Type: application/json');
    echo $response;
} else {
    http_response_code(500);
    echo json_encode(["error" => "Unable to fetch blog posts"]);
}
