<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "customizations_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$textLeft = $_POST['textLeft'] ?? '';
$textTop = $_POST['textTop'] ?? '';
$stickerLeft = $_POST['stickerLeft'] ?? '';
$stickerTop = $_POST['stickerTop'] ?? '';
$stickerWidth = $_POST['stickerWidth'] ?? '';
$color = $_POST['color'] ?? '';
$productImageSrc = $_POST['productImageSrc'] ?? '';
$stickerPath = "";

// Define the stickers directory
$stickerDirectory = 'stickers/';

// Create the directory if it doesn't exist
if (!is_dir($stickerDirectory)) {
    mkdir($stickerDirectory, 0777, true); // 0777 gives full permissions, and true allows creation of nested directories
}

// Handle the sticker image upload
if (isset($_FILES['coverimg']) && $_FILES['coverimg']['error'] == 0) {
    $stickerImageName = $_FILES['coverimg']['name'];
    $stickerImageTmpName = $_FILES['coverimg']['tmp_name'];
    
    // Destination to save the uploaded sticker image
    $stickerFileName = uniqid() . '_' . basename($stickerImageName);
    $stickerPath = $stickerDirectory . $stickerFileName;

    // Move uploaded file to the destination
    if (move_uploaded_file($stickerImageTmpName, $stickerPath)) {
        // Use the file path directly
    } else {
        die("Failed to upload sticker image.");
    }
} else {
    die("No sticker image uploaded or an error occurred during the upload.");
}

// Insert customization details into the database
$stmt = $conn->prepare("INSERT INTO customizations (text_left, text_top, sticker_left, sticker_top, sticker_width, sticker_path, color, product_image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $textLeft, $textTop, $stickerLeft, $stickerTop, $stickerWidth, $stickerPath, $color, $productImageSrc);

if ($stmt->execute()) {
    echo "Customization saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
