<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Customized Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
        }
        .image-container {
            position: relative;
            text-align: center;
            max-width: 500px;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        .custom-text {
            position: absolute;
            color: #000;
            font-size: 20px;
            word-wrap: break-word;
        }
        .sticker {
            position: absolute;
        }
        input, button {
            margin: 10px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Enter Customization ID</h1>
    <form action="" method="get">
        <label for="customization_id">Customization ID:</label>
        <input type="number" name="customization_id" id="customization_id" required>
        <button type="submit">View Customization</button>
    </form>

<?php
if (isset($_GET['customization_id'])) {
    $customization_id = (int)$_GET['customization_id'];

    $conn = new mysqli("localhost", "root", "", "customizations_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT text_left, text_top, sticker_left, sticker_top, sticker_width, sticker_path, color, product_image_path FROM customizations WHERE id = ?");
    $stmt->bind_param("i", $customization_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $textLeft = $row['text_left'];
        $textTop = $row['text_top'];
        $stickerLeft = $row['sticker_left'];
        $stickerTop = $row['sticker_top'];
        $stickerWidth = $row['sticker_width'];
        $stickerPath = $row['sticker_path']; // Path to the sticker image file
        $textColor = $row['color'];
        $productImagePath = $row['product_image_path'];

        echo '<div class="image-container">';
        echo '<img src="' . $productImagePath . '" alt="Customized Product Image">';

        // Display the sticker if it exists
        if (!empty($stickerPath)) {
            echo '<img src="' . $stickerPath . '" class="sticker" style="left:' . $stickerLeft . '; top:' . $stickerTop . '; width:' . $stickerWidth . ';">';
        }

        echo '<div class="custom-text" style="left:' . $textLeft . '; top:' . $textTop . '; color:' . $textColor . ';">Your Custom Text</div>';
        echo '</div>';
    } else {
        echo '<p>No customization found with ID ' . $customization_id . '.</p>';
    }

    $stmt->close();
    $conn->close();
}
?>
</body>
</html>
