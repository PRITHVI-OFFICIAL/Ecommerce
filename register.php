<?php

require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = "Prithvi";



// // Function to generate a JWT token
// function generateJWT($username, $password, $secretKey) {
//     $payload = [
//         "username" => $username,
//         "password" => $password
//     ];

//     $res = JWT::encode($payload, $secretKey, 'HS256');
//     echo "Encode: $res"; 
//     //$dec = JWT::decode($res, $secretKey,  array('HS256'));
   

//     try {
//         $decoded = JWT::decode($res, new Key($secretKey, 'HS256'));
//         print_r($decoded);
//     } catch (ExpiredException $e) {
//         throw new Exception('Token expired');
//     } catch (SignatureInvalidException $e) {
//         throw new Exception('Invalid token signature');
//     } catch (BeforeValidException $e) {
//         throw new Exception('Token not valid yet');
//     } catch (Exception $e) {
//         throw new Exception('Invalid token');
//     }




//     return $res;
// }


// Function to verify JWT token
function verifyJWT($jwt, $secretKey) {
    try {
        $decoded = JWT::decode($jwt, $secretKey, array('HS256'));
        return (array) $decoded;
    } catch (Exception $e) {
        return false;
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "ecommerce";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
    // echo "Connection Passed\n";
}
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    echo $username;
    $password = $_POST["password"];

    // Check if username already exists
    $check_query = "SELECT * FROM Users WHERE username = '$username'";
    $result = $conn->query($check_query);
    if ($result->num_rows > 0) {
        // Username already exists
        http_response_code(400); // Bad request
        echo json_encode(array("message" => "Username already exists"));
        exit();
    }

    // Insert user into database
    $sql = "INSERT INTO Users (username, password) VALUES ('$username', '$password')";
    if ($conn->query($sql) === TRUE) {
        // Generate JWT token for the newly registered user
        //echo "Registration successful";
        $_SESSION['username'] = $username;
        echo "
        <script>window.localStorage.setItem('username', '$username');
        var un = window.localStorage.getItem('username');
        console.log(un,123);
        </script>";
        header("Location: index.html?username=$username");
        //header("Location: index.html"); // Redirect to home screen
        exit();
        //echo json_encode(array("token" => $jwt)); // Return JWT token
    } else {
        echo json_encode(array("message" => "Error registering user"));
    }
}

$conn->close();
?>
