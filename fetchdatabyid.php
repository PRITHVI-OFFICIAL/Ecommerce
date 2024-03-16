<?php

// Assuming you have already established a database connection
// Modify the connection details as per your setup
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
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
    // echo "Connection Passed";
}
// $link = $_SERVER['PHP_SELF'];
//     $link_array = explode('/',$link);
// echo end($link_array);

// Endpoint handling
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    // echo $id;

    // Query to fetch product details based on ID
    $sql = "SELECT * FROM products WHERE id = $id";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        header('Content-Type: application/json'); // Set the response content type to JSON
        echo json_encode($product);
        return json_encode($product); // Return the product details as JSON
    } else {
        echo json_encode(null); // Return null if product not found
    }
}
// // If the endpoint/method is not `/products` with GET method
// http_response_code(404);
// echo "Invalid endpoint or method";

// Close connection
$conn->close();

?>