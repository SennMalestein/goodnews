<?php
header('Content-Type: application/json');

$apiUrl = 'https://api.apitube.io/v1/news/everything?q=(sentiment.overall.polarity:positive%20AND%20language.name:English)&api_key=api_live_x9K7obU63Ksnf1HkmSeO09ToagdkIpYW2CIvGTcR';

// Use cURL to fetch the data from the API
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if any
$response = curl_exec($ch);

if (curl_errno($ch)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Failed to fetch API data: ' . curl_error($ch)]);
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode !== 200) {
        header('HTTP/1.1 ' . $httpCode);
        echo json_encode(['error' => 'API returned status: ' . $httpCode]);
    } else {
        echo $response;
    }
}

curl_close($ch);
?>