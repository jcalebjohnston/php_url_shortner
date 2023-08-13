<?php
require_once 'shortlink-service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data['action'] === 'encode') {
        $url = $data['url'];
        $shortLink = ShortLinkService::encode($url);
        echo json_encode(['shortLink' => $shortLink]);
    }elseif ($data['action'] === 'decode') {
        $shortLink = $data['shortLink'];
        $originalUrl = ShortLinkService::decode($shortLink);
        if ($originalUrl) {
            echo json_encode(['originalUrl' => $originalUrl]);
        } else {
            echo json_encode(['error' => 'URL not found']);
        }
    }
    
}
?>