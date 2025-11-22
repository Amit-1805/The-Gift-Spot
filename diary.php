<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "gift"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Assuming data is sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and assign POST data to variables
    $productName = sanitize_input($_POST["product_name"]);
    $productDescription = sanitize_input($_POST["product_description"]);
    $productPrice = sanitize_input($_POST["product_price"]);
    $productImage = sanitize_input($_POST["product_image"]); // Assuming the image is a URL or file path
    $productQuantity = sanitize_input($_POST["product_quantity"]);

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $productName, $productDescription, $productPrice, $productImage, $productQuantity);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Product details saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
