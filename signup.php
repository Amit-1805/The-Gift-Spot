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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and assign POST data to variables
    $firstName = sanitize_input($_POST["firstName"]);
    $lastName = sanitize_input($_POST["lastName"]);
    $email = sanitize_input($_POST["registerEmail"]);
    $mobileNumber = sanitize_input($_POST["mobileNumber"]);
    $address = sanitize_input($_POST["address"]);
    $pincode = sanitize_input($_POST["pincode"]);
    $password = password_hash(sanitize_input($_POST["registerPassword"]), PASSWORD_BCRYPT); // Hash the password

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, mobile_number, address, pincode, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $firstName, $lastName, $email, $mobileNumber, $address, $pincode, $password);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Display the thank you note and auto redirect to login page
        echo "
        <div style='text-align:center; margin-top:50px;'>
            <h1 style='font-size: 3em; font-weight: bold; color: green;'>Thank You for Registering!</h1>
            <p style='font-size: 1.5em;'>You will be redirected to the login page in 3 seconds...</p>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = 'login1.php';
            }, 3000); // 3 seconds redirect
        </script>
        ";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
