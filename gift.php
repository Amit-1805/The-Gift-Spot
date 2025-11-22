<?php
// Database configuration
$host = 'localhost'; // Database host
$dbname = 'gift'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $image = $_POST['image'] ?? '';

    // Validate input data
    if (empty($name) || empty($price) || empty($quantity) || empty($image)) {
        echo 'All fields are required.';
        exit;
    }

    // Prepare SQL statement
    $sql = "INSERT INTO products (name, price, quantity, image) VALUES (:name, :price, :quantity, :image)";
    $stmt = $pdo->prepare($sql);

    // Bind parameters and execute statement
    try {
        $stmt->execute([
            ':name' => $name,
            ':price' => $price,
            ':quantity' => $quantity,
            ':image' => $image
        ]);
        echo 'Product added successfully!';
    } catch (PDOException $e) {
        echo 'Failed to add product: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request method.';
}
?>
