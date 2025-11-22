<?php
session_start();
require 'db_connection.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Check if the user has searched for a specific order
$search_order_id = isset($_GET['search_order_id']) ? $_GET['search_order_id'] : null;

if ($search_order_id) {
    // Fetch the specific order by Order ID (only one order)
    $sql = "SELECT * FROM orders WHERE user_id = ? AND id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $search_order_id);
} else {
    // Fetch the most recent order for the logged-in user (only one order)
    $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        .search-container {
            float: right;
            margin-top: 20px;
        }

        .search-container input[type="text"] {
            padding: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-container button {
            padding: 5px 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #45a049;
        }

        .order {
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        img {
            width: 50px;
            height: auto;
        }

        header {
            background-color: #ff9aa2; /* Soft Coral Pink */
            padding: 20px 0;
            text-align: center;
            text-color: black;

        }

        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #fff;
        }

            

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">Shopping Portal</div>
            <ul class="nav-links">
                <li><a href="home.html">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="about-us.html">About Us</a></li>
                <li><a href="cust.php">Product</a></li>
            </ul>
        </nav>
    </header>

    <h1>My Orders</h1>

    <!-- Search Form -->
    <div class="search-container">
        <form action="" method="GET">
            <input type="text" name="search_order_id" placeholder="Search Order ID" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <div>
        <?php
        if ($result->num_rows > 0) {
            if ($search_order_id) {
                echo "<h2>Search Result for Order ID: " . htmlspecialchars($search_order_id) . "</h2>";
            } else {
                echo "<h2>Your Most Recent Order</h2>";
            }

            // Display the order (only one order)
            $order = $result->fetch_assoc();
            echo "<div class='order'>";
            echo "<h3>Order ID: " . htmlspecialchars($order['id']) . "</h3>";
            echo "<p><strong>Order Date:</strong> " . htmlspecialchars($order['created_at']) . "</p>";
            echo "<p><strong>Full Name:</strong> " . htmlspecialchars($order['full_name']) . "</p>";
            echo "<p><strong>Contact Number:</strong> " . htmlspecialchars($order['contact_number']) . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($order['address']) . "</p>";
            echo "<p><strong>Pincode:</strong> " . htmlspecialchars($order['pincode']) . "</p>";
            echo "<p><strong>Order Total:</strong> $" . htmlspecialchars($order['order_total']) . "</p>";

            // Fetch the order items for this order
            $order_id = $order['id'];
            $item_sql = "SELECT * FROM order_items WHERE order_id = ?";
            $item_stmt = $conn->prepare($item_sql);
            $item_stmt->bind_param("i", $order_id);
            $item_stmt->execute();
            $item_result = $item_stmt->get_result();

            if ($item_result->num_rows > 0) {
                echo "<h4>Ordered Products:</h4>";
                echo "<table>";
                echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Image</th></tr>";

                // Loop through each product in the order
                while ($item = $item_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($item['product_name']) . "</td>";
                    echo "<td>$" . htmlspecialchars($item['price']) . "</td>";
                    echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                    echo "<td>$" . htmlspecialchars($item['total']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($item['p_image']) . "' alt='" . htmlspecialchars($item['product_name']) . "'></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No items found for this order.</p>";
            }

            $item_stmt->close();
            echo "</div><hr>";
        } else {
            if ($search_order_id) {
                echo "<p>No order found with ID: " . htmlspecialchars($search_order_id) . "</p>";
            } else {
                echo "<p>No orders found.</p>";
            }
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
