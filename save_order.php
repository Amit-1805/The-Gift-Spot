<?php
session_start();
require 'db_connection.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get order details from POST request
$full_name = $_POST['full_name'];
$contact_number = $_POST['contact_number'];
$address = $_POST['address'];
$pincode = $_POST['pincode'];
$order_total = $_POST['order_total'];

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Insert order into the orders table
$sql = "INSERT INTO orders (user_id, full_name, contact_number, address, pincode, order_total) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssi", $user_id, $full_name, $contact_number, $address, $pincode, $order_total);

if ($stmt->execute()) {
    // Get the last inserted order ID
    $order_id = $stmt->insert_id;

    // Get the cart items from the request
    $cart_items = json_decode($_POST['cart_items'], true);

    // Prepare the SQL statement for order items
    $item_sql = "INSERT INTO order_items (order_id, product_name, price, quantity, total, p_image) VALUES (?, ?, ?, ?, ?, ?)";
    $item_stmt = $conn->prepare($item_sql);

    foreach ($cart_items as $item) {
        // Calculate the total for each item
        $item_total = $item['price'] * $item['quantity'];

        // Bind parameters and execute the statement (including the product image)
        $item_stmt->bind_param("issiis", $order_id, $item['name'], $item['price'], $item['quantity'], $item_total, $item['image']);
        $item_stmt->execute();
    }

    // Close the prepared statements
    $item_stmt->close();
    $stmt->close();

    // Fetch order items from the database
    $item_fetch_sql = "SELECT product_name, price, quantity, total, p_image FROM order_items WHERE order_id = ?";
    $fetch_stmt = $conn->prepare($item_fetch_sql);
    $fetch_stmt->bind_param("i", $order_id);
    $fetch_stmt->execute();
    $result = $fetch_stmt->get_result();

    // HTML with CSS for a more attractive display
    echo "
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #ff9aa2;
            text-align: center;
        }
        h3 {
            color: #333;
            margin-bottom: 10px;
        }
        p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
        }
        .order-info, .delivery-info, .order-items {
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #ff9aa2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .button:hover {
            background-color: ##ff9aa2;
        }
    </style>

    <div class='container'>
        <h2>Thank you for your order!</h2>
        <h3>Your Order Details:</h3>
        <div class='order-info'>
            <p><strong>Order ID:</strong> " . $order_id . "</p>
            <p><strong>Name:</strong> " . htmlspecialchars($full_name) . "</p>
            <p><strong>Contact Number:</strong> " . htmlspecialchars($contact_number) . "</p>
            <p><strong>Address:</strong> " . htmlspecialchars($address) . "</p>
            <p><strong>Pincode:</strong> " . htmlspecialchars($pincode) . "</p>
            <p><strong>Order Total:</strong> $" . number_format($order_total, 2) . "</p>
        </div>
        
        <h3>Ordered Items:</h3>
        <div class='order-items'>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>";

    // Loop through each item in the result and display, including the product image
    while ($row = $result->fetch_assoc()) {
        echo "
            <tr>
                <td>" . htmlspecialchars($row['product_name']) . "</td>
                <td><img src='" . htmlspecialchars($row['p_image']) . "' class='product-img'></td>
                <td>$" . number_format($row['price'], 2) . "</td>
                <td>" . $row['quantity'] . "</td>
                <td>$" . number_format($row['total'], 2) . "</td>
            </tr>";
    }

    echo "
                </tbody>
            </table>
        </div>
        
        <h3>Delivery Details:</h3>
        <div class='delivery-info'>
            <p>Your order will be delivered to the address provided above.</p>
            <p>Expected delivery time is 3-5 business days.</p>
        </div>
        
        <a href='cust.php' class='button'>Continue Shopping</a>
    </div>";

    // Close the fetch statement
    $fetch_stmt->close();
} else {
    echo "Error: " . $stmt->error;
}

// Close the database connection
$conn->close();
?>
