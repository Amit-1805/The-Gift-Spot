<?php
session_start();
header('Content-Type: application/json');

// Database connection (replace with your actual connection details)
$conn = new mysqli('localhost', 'root', '', 'gift');

// Check for connection errors
if ($conn->connect_error) {
    die(json_encode(['loggedIn' => false, 'error' => 'Database connection failed']));
}

// Check if user is logged in
if (isset($_SESSION['email']) || isset($_SESSION['mobile'])) {
    // Get email or mobile from session
    $email = $_SESSION['email'] ?? null;
    $mobile = $_SESSION['mobile'] ?? null;

    // Prepare SQL statement to check user by email or mobile
    if ($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
    } elseif ($mobile) {
        $sql = "SELECT * FROM users WHERE mobile = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $mobile);
    }

    if (isset($stmt)) {
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo json_encode(['loggedIn' => true, 'userId' => $user['id']]); // Assuming 'id' is the primary key in the users table
        } else {
            echo json_encode(['loggedIn' => false]);
        }

        $stmt->close();
    }
} else {
    echo json_encode(['loggedIn' => false]);
}

$conn->close();
?>
