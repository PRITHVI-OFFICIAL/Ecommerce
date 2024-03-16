<?php
require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;

session_start();

// Check if token exists in session
if(isset($_SESSION['jwt_token'])){
    $token = $_SESSION['jwt_token'];
    
    // Verify token
    $secretKey = "prithvi";
    try {
        $decoded = JWT::decode($token, $secretKey, array('HS256'));
        echo "Token is valid. Username: " . $decoded->username;
    } catch (Exception $e) {
        echo "Token is invalid";
    }
} else {
    echo "Token not found";
}
?>
