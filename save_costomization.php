<?php
// Database connection settings
$host = 'localhost';
$dbname = 'custom';
$user = 'root';
$pass = '';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if POST data is set
if (isset($_POST['stickerSrc'], $_POST['textLeft'], $_POST['textTop'], 
    $_POST['stickerLeft'], $_POST['stickerTop'], $_POST['stickerWidth'])) {

    // Retrieve and sanitize the POST data
    $stickerSrc = filter_var($_POST['stickerSrc'], FILTER_SANITIZE_STRING);
    $textLeft = filter_var($_POST['textLeft'], FILTER_VALIDATE_FLOAT);
    $textTop = filter_var($_POST['textTop'], FILTER_VALIDATE_FLOAT);
    $stickerLeft = filter_var($_POST['stickerLeft'], FILTER_VALIDATE_FLOAT);
    $stickerTop = filter_var($_POST['stickerTop'], FILTER_VALIDATE_FLOAT);
    $stickerWidth = filter_var($_POST['stickerWidth'], FILTER_VALIDATE_FLOAT);

    // Prepare and execute the SQL statement
    try {
        $stmt = $pdo->prepare("INSERT INTO customizations (stickerSrc, textLeft, textTop, stickerLeft, stickerTop, stickerWidth) 
                                VALUES (:stickerSrc, :textLeft, :textTop, :stickerLeft, :stickerTop, :stickerWidth)");

        $stmt->execute([
            ':stickerSrc' => $stickerSrc,
            ':textLeft' => $textLeft,
            ':textTop' => $textTop,
            ':stickerLeft' => $stickerLeft,
            ':stickerTop' => $stickerTop,
            ':stickerWidth' => $stickerWidth
        ]);

        echo "Customization saved successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: Missing required fields.";
}
?>