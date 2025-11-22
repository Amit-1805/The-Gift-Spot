<?php
session_start();
require 'db_connection.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the last order ID from the session or URL (if necessary)
$order_id = $_SESSION['last_order_id'] ?? null; // Store this in the session after placing the order

if ($order_id) {
    // Fetch order details
    $sql = "SELECT o.full_name, o.contact_number, o.address, o.pincode, o.order_total, 
                   oi.product_name, oi.price, oi.quantity, oi.total, oi.image
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            WHERE o.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the order exists
    if ($result->num_rows > 0) {
        // Display delivery details
        echo "<h2>Order Confirmation</h2>";
        echo "<h3>Delivery Details</h3>";
        $order = $result->fetch_assoc(); // Get the first order record

        echo "<p>Name: " . htmlspecialchars($order['full_name']) . "</p>";
        echo "<p>Contact Number: " . htmlspecialchars($order['contact_number']) . "</p>";
        echo "<p>Address: " . htmlspecialchars($order['address']) . "</p>";
        echo "<p>Pincode: " . htmlspecialchars($order['pincode']) . "</p>";
        echo "<h3>Order Total: $" . htmlspecialchars($order['order_total']) . "</h3>";

        // Display product details
        echo "<h3>Ordered Products</h3>";
        echo "<table>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Image</th></tr>";
        
        // Reset pointer to fetch all items
        $result->data_seek(0); // Reset the result pointer
        
        while ($item = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($item['product_name']) . "</td>";
            echo "<td>$" . htmlspecialchars($item['price']) . "</td>";
            echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
            echo "<td>$" . htmlspecialchars($item['total']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($item['image']) . "' alt='" . htmlspecialchars($item['product_name']) . "' width='50'></td>"; // Adjust width as needed
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No order details found.</p>";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "<p>Order ID not found.</p>";
}

// Close the database connection
$conn->close();
?>
