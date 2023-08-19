<?php
require_once 'shortlink-service.php';

function validateUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function isNotEmpty($url) {
    return trim($url) !== '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data['action'] === 'encode') {
        $url = $data['url'];
        
        if (!isNotEmpty($url) || !validateUrl($url)) {
            echo json_encode(['error' => 'Invalid URL format']);
            exit;
        }

        $shortLink = ShortLinkService::encode($url);
        echo json_encode(['shortLink' => $shortLink]);

    } elseif ($data['action'] === 'decode') {
        $shortLink = $data['shortLink'];

        if (!isNotEmpty($shortLink) || !validateUrl($shortLink)) {
            echo json_encode(['error' => 'Invalid URL format']);
            exit;
        }
    
        $originalUrl = ShortLinkService::decode($shortLink);
        if ($originalUrl) {
            echo json_encode(['originalUrl' => $originalUrl]);
        } else {
            echo json_encode(['error' => 'URL not found']);
        }
    }

}
?>