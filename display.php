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
            text-align: center;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        select, button {
            margin: 10px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Select Customization</h1>
    <form action="display_image.php" method="get">
        <label for="customization_id">Select Customization:</label>
        <select name="customization_id" id="customization_id">
            <?php
            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "customizations_db"; // Replace with your actual database name

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve all customizations
            $query = "SELECT id, color FROM customizations";
            $result = $conn->query($query);

            // Check if any customizations are found
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($_GET['customization_id']) && $_GET['customization_id'] == $row['id']) ? 'selected' : '';
                    echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['color'] . ' Mug</option>';
                }
            } else {
                echo '<option value="">No customizations found</option>';
            }

            $conn->close();
            ?>
        </select>
        <button type="submit">View Customization</button>
    </form>

    <?php
    // Display selected customization image
    $customization_id = isset($_GET['customization_id']) ? (int)$_GET['customization_id'] : 0;

    if ($customization_id > 0) {
        echo '<div class="image-container">';
        echo '<h2>Your Customized Product</h2>';
        echo '<img src="fetch.php?customization_id=' . $customization_id . '" alt="Customized Product Image">';
        echo '</div>';
    }
    ?>
</body>
</html>
