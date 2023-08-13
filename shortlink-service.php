<?php
class ShortLinkService {
    private static $dbHost = 'localhost';
    private static $dbUsername = 'root';
    private static $dbPassword = '';
    private static $dbName = 'shortener';

    private static function generateShortUrl($length = 6) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }
    
    public static function encode($url) {
        $shortUrl = self::generateShortUrl();
        
        $conn = new mysqli(self::$dbHost, self::$dbUsername, self::$dbPassword, self::$dbName);
        $stmt = $conn->prepare("INSERT INTO short_links (short_url, original_url) VALUES (?, ?)");
        $stmt->bind_param("ss", $shortUrl, $url);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        
        return "http://$shortUrl";
    }
    
    public static function decode($shortLink) {
        $shortUrl = substr($shortLink, strrpos($shortLink, '/') + 1);
        
        $conn = new mysqli(self::$dbHost, self::$dbUsername, self::$dbPassword, self::$dbName);
        $stmt = $conn->prepare("SELECT original_url FROM short_links WHERE short_url = ?");
        $stmt->bind_param("s", $shortUrl);
        $stmt->execute();
        $stmt->bind_result($originalUrl);
        if (!$stmt->fetch()) {
            $originalUrl = null;  // Return null if not found
        }
        $stmt->close();
        $conn->close();
        
        return $originalUrl;
    }    
}
?>