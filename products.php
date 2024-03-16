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
    // echo "Connection Passed\n";
}
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);


// Endpoint handling
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI']==='/products') {
    // Fetch products from the database
    $sql = "SELECT id, title, price, description, category, image, rating__rate, rating__count FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row as JSON
        $products = array();
        while ($row = $result->fetch_assoc()) {
            $product = array(
                "id" => $row["id"],
                "title" => $row["title"],
                "price" => (float) $row["price"],
                "description" => $row["description"],
                "category" => $row["category"],
                "image" => $row["image"],
                "rating" => array(
                    "rate" => (float) $row["rating__rate"],
                    "count" => (int) $row["rating__count"]
                )
            );
            $products[] = $product;
        }
        echo json_encode($products);
        return json_encode($products);
        exit(); // Exit to prevent further execution
    } else {
        echo "No products found";
        exit(); // Exit to prevent further execution
    }

}

if (preg_match('/\/products\/(\d+)/', $request_uri, $matches)) {
    // The id will be captured in the first group of the match
    $id = $matches[1];

    $sql = "SELECT * FROM products WHERE id = $id";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        header('Content-Type: application/json'); // Set the response content type to JSON
        echo json_encode($product);
        return json_encode($product); // Return the product details as JSON
    } else {

    }
       

    
    
    // Print "Hello id"
    echo "Hello $id";
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

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
}

// If the endpoint/method is not `/products` with GET method
// http_response_code(404);
// echo "Invalid endpoint or method";

// Close connection
$conn->close();

?>