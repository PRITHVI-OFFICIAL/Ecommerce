<?php

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
    echo "Connection Passed";
}


if(isset($_POST['submit'])) {
    $id= $_POST['id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    $rating_rate = $_POST['rating_rate'];
    $rating_count = $_POST['rating_count'];

    if( $id!="" && $title != "" && $price != "" && $description != "" && $category != "" && $image != "" && $rating_rate != "" && $rating_count != "") {
        $sql = "INSERT INTO products ( id , title, price, description, category, image, rating__rate, rating__count) VALUES ( '$id','$title', $price, '$description', '$category', '$image', '$rating_rate', '$rating_count')";

        if (mysqli_query($conn, $sql)) {
            echo "Product Added Successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "All fields are required.";
    }
}
?>
