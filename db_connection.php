<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'gift';

// Create a connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
