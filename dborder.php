<?php
// Database connection parameters
$host = 'localhost';        // Your database host, usually 'localhost'
$dbname = 'gift';  // Replace with your actual database name
$username = 'root'; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// You can close the connection later in your script using $conn->close();
?>
