<?php
class ShortLinkService {
    private static $dbHost = 'localhost';
    private static $dbUsername = 'root';
    private static $dbPassword = '';
    private static $dbName = 'your_db_name';

    private static function generateShortCode($length = 6) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }
    
    public static function encode($url) {
        $shortCode = self::generateShortCode();
        
        $conn = new mysqli(self::$dbHost, self::$dbUsername, self::$dbPassword, self::$dbName);
        $stmt = $conn->prepare("INSERT INTO short_links (short_code, original_url) VALUES (?, ?)");
        $stmt->bind_param("ss", $shortCode, $url);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        
        return "http://$shortCode";
    }
    
    public static function decode($shortLink) {
        $shortCode = substr($shortLink, strrpos($shortLink, '/') + 1);
        
        $conn = new mysqli(self::$dbHost, self::$dbUsername, self::$dbPassword, self::$dbName);
        $stmt = $conn->prepare("SELECT original_url FROM short_links WHERE short_code = ?");
        $stmt->bind_param("s", $shortCode);
        $stmt->execute();
        $stmt->bind_result($originalUrl);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
        
        return $originalUrl;
    }
}
?>