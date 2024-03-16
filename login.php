<?php

require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

// Secret key for JWT signing (you should use a long, randomly generated string)
$secretKey = "prithvi";

// Function to generate a JWT token
function generateJWT($username, $secretKey) {
    $payload = [
        "username" => $username,
        "iat" => time(), // Issued at time
        "exp" => time() + (60 * 60) // Expiration time (1 hour from now)
    ];
    return JWT::encode($payload, $secretKey, 'HS256');
}


// Function to verify JWT token
function verifyJWT($jwt, $secretKey) {
    try {
        $decoded = JWT::decode($jwt, $secretKey,array('HS256'));
        return (array) $decoded;
    } catch (Exception $e) {
        return false;
    }
}


$servername = "";
$username = "root";
$password = "";
$database = "ecommerce";

// eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InNpeGZpcmUiLCJpYXQiOjE3MTAxNzM0MjksImV4cCI6MTcxMDE3NzAyOX0.JzmooXIiwgMGXk9UcK2FmmJ_j3RoFzAEP0i-AqhYoyk


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
    // echo "Connection Passed\n";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
    
        // Retrieve user from database
        $sql = "SELECT * FROM Users WHERE username = '$username'";
        $result = $conn->query($sql);
    
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if($username=='admin' && $password=='admin'){

            header("Location: update.html");
            exit();
        }

if ($result->num_rows > 0) {
    // User found, generate JWT token
    $secretKey = "prithvi";
    $token = JWT::encode(["username" => $username], $secretKey,'HS256');
    
    // Store token in session for future verification
    session_start();
    $_SESSION['jwt_token'] = $token;
    
    echo "Login successful";
    $_SESSION['username'] = $username;
    echo "<script>window.localStorage.setItem('username', '$username');</script>";
    header("Location: index.html?username=$username");
    exit();
} else {
    echo "Invalid username or password";
}

    }

}
    
    $conn->close();
?>
