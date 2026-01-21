<?php
//200126
// fetch_blogger.php

// Google Blogger API
$apiKey = "AIzaSyARErRCSmyD1dikK0ndYipEuORgXIeLRB8"; 
$blogId = "1732826187557117921";
$response = false;

if(!empty($_GET['q'])) {
    $labels    = !empty($_GET['labels']) ? "&labels=" . urlencode($_GET['labels']) : "";
    $searchQuery = !empty($_GET['q']) ? urlencode($_GET['q']) : "";
    $url = "https://www.googleapis.com/blogger/v3/blogs/$blogId/posts/search?q=".$searchQuery."&key=$apiKey&fetchImages=true&maxResults=100$labels";

    // Fetch from API
    $response = file_get_contents($url);
}

if ($response !== false) {
    header('Content-Type: application/json');
    echo $response;
} else {
    http_response_code(500);
    echo json_encode(["error" => "Unable to fetch blog posts"]);
}