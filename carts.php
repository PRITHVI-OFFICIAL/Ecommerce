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



if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI']==='/carts') {
    // Fetch products from the database
    $sql = "SELECT username, id, title, price, description, category, image, rating__rate, rating__count FROM carts";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row as JSON
        $products = array();
        while ($row = $result->fetch_assoc()) {
            $product = array(
                "username"=> $row["username"],
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    
    $username  = $_POST["username"];
$productid = $_POST["productid"];
    // Insert user into database
    $sql_product = "SELECT * FROM products WHERE id = $productid";
    $result_product = $conn->query($sql_product);
    
    if ($result_product->num_rows > 0) {
        // Product found, fetch its details
        $row = $result_product->fetch_assoc();
        $product_title = $row['title'];
        $product_price = $row['price'];
        $prod_description = $row["description"];
        $prod_category = $row["category"];
        $prod_image = $row["image"];
        $rating__rate = (float) $row["rating__rate"]; 
        $rating__count = (int) $row["rating__count"];
        
        // Insert the product details along with username into the carts table
        $sql_cart = "INSERT INTO carts (username, id, title, price, description, category, image, rating__rate, rating__count) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        // Prepare the SQL statement
        $stmt = $conn->prepare($sql_cart);
        
        // Bind parameters
        $stmt->bind_param("sisssssss", $username, $productid, $product_title, $product_price, $prod_description, $prod_category, $prod_image, $rating__rate, $rating__count);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Cart item inserted successfully
            // Redirect user to cart page after displaying success message

            header("Location: carts.html");
         
           
            exit();
        } else {
            // Error inserting into cart
            echo "Error inserting into cart: " . $stmt->error;
        }
    } else {
        // Product not found
        echo "Product not found";
    }
    
    // Close the prepared statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
