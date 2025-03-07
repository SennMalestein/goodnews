<?php
// image-proxy.php
$imageUrl = isset($_GET['url']) ? $_GET['url'] : '';
if (!$imageUrl) {
    http_response_code(400);
    echo 'No image URL provided';
    exit;
}

$ch = curl_init($imageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$imageData = curl_exec($ch);

if (curl_errno($ch)) {
    http_response_code(500);
    error_log('CURL Error: ' . curl_error($ch)); // Log to server error log
    echo 'Failed to fetch image: ' . curl_error($ch);
} else {
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    header("Content-Type: $contentType");
    echo $imageData;
}

curl_close($ch);
?>