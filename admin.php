<?php
// Database configuration
$host = 'localhost'; // Change if needed
$dbname = 'gift'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

// Create a new PDO instance 
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Fetch totals from the database
try {
    $totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalProducts = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalCustomizations = $db->query("SELECT COUNT(*) FROM order_items")->fetchColumn(); // Corrected table name
    $totalSales = $db->query("SELECT SUM(order_total) FROM orders")->fetchColumn(); // Corrected table name
    $totalSales = $totalSales ? "$" . number_format($totalSales, 2) : "$0.00"; // Format sales total
} catch (PDOException $e) {
    die("Could not retrieve data: " . $e->getMessage());
}

// Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $mobileNumber = $_POST['mobile_number'];
    
    $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, mobile_number) VALUES (?, ?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $email, $mobileNumber]);
}

// Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $productName = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    $stmt = $db->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
    $stmt->execute([$productName, $description, $price]);
}

// Add Order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_order'])) {
    $fullName = $_POST['full_name'];
    $contactNumber = $_POST['contact_number'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $instructions = $_POST['instructions'];
    $orderTotal = $_POST['order_total'];
    
    $stmt = $db->prepare("INSERT INTO orders (full_name, contact_number, address, pincode, instructions, order_total) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fullName, $contactNumber, $address, $pincode, $instructions, $orderTotal]);
}

// Delete User
if (isset($_GET['delete_user'])) {
    $userId = $_GET['delete_user'];
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);
}

// Delete Product
if (isset($_GET['delete_product'])) {
    $productId = $_GET['delete_product'];
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);
}

// Delete Order
if (isset($_GET['delete_order'])) {
    $orderId = $_GET['delete_order'];
    $stmt = $db->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
}

// Fetch Orders and Order Items
$orders = $db->query("SELECT * FROM orders")->fetchAll(PDO::FETCH_ASSOC);
$orderItems = $db->query("SELECT * FROM order_items")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            height: 100vh;
            background-color: #fff5f5;
            color: #333;
        }
        .sidebar {
            width: 250px;
            background-color: #ff6f61; /* Coral Pink */
            color: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            height: 100%;
            position: fixed;
            left: 0;
            top: 0;
        }
        .sidebar .logo {
            font-size: 1.6em;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }
        .sidebar ul {
            list-style-type: none;
        }
        .sidebar ul li {
            margin-bottom: 15px;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1.2em;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar ul li a:hover {
            background-color: #ff897d; /* Light Coral */
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            overflow-y: auto;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header-left {
            font-size: 1.4em;
            font-weight: bold;
        }
        .header-right .profile {
            background-color: #ff6f61;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        .section {
            margin-top: 20px;
        }
        .section h2 {
            border-bottom: 3px solid #ff6f61;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        .metrics {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .metric-card {
            background: linear-gradient(135deg, #ff9a8b, #ff6f61); /* Coral Pink Gradient */
            padding: 20px;
            border-radius: 10px;
            width: 22%;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }
        .metric-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .metric-card h3 {
            font-size: 1.4em;
            margin-bottom: 10px;
            color: #fff;
            z-index: 2;
            position: relative;
        }
        .metric-card span {
            font-size: 2.5em;
            font-weight: bold;
            color: #fff;
            z-index: 2;
            position: relative;
        }
        .form-container {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .form-container input,
        .form-container button {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
        }
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #ff6f61; /* Coral Pink */
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2; /* Light Gray */
        }
        tr:hover {
            background-color: #ffe6e6; /* Light Coral */
        }
        a {
            color: #ff6f61; /* Coral Pink */
        }

.header-right {
    display: flex;
    align-items: center;
    margin-left: auto; /* Align to the right */
}

.logout-button {
    background-color: #ff6f61; /* Coral Pink */
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 15px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    text-decoration: none; /* Remove underline from link */
}

.logout-button a {
    color: white; /* Ensure the link inside the button is white */
    text-decoration: none; /* Remove underline from link */
}

.logout-button:hover {
    background-color: #ff897d; /* Lighter Coral on hover */
    transform: translateY(-2px); /* Slight lift effect */
}

.logout-button:focus {
    outline: none; /* Remove default outline */
}

    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">Admin Dashboard</div>
        <ul>
            <li><a href="#" onclick="showSection('metrics')">Dashboard</a></li>
            <li><a href="#" onclick="showSection('users')">Manage Users</a></li>
            <li><a href="#" onclick="showSection('products')">Manage Products</a></li>
            <li><a href="#" onclick="showSection('orders')">Manage Orders</a></li>
            <li><a href="#" onclick="showSection('order_items')">Manage Order Items</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <div class="header-left">Welcome to the Admin Dashboard</div>
            <div class="logout-button">
                <li><a href="logout.php">Logout</a></li>
            </div>
        </header>

        <!-- Metrics Section -->
        <div class="section" id="metrics">
            <h2>Metrics Overview</h2>
            <div class="metrics">
                <div class="metric-card">
                    <h3>Total Users</h3>
                    <span><?php echo $totalUsers; ?></span>
                </div>
                <div class="metric-card">
                    <h3>Total Products</h3>
                    <span><?php echo $totalProducts; ?></span>
                </div>
                <div class="metric-card">
                    <h3>Total Customizations</h3>
                    <span><?php echo $totalCustomizations; ?></span>
                </div>
                <div class="metric-card">
                    <h3>Total Sales</h3>
                    <span><?php echo $totalSales; ?></span>
                </div>
            </div>
        </div>

        <!-- Manage Users Section -->
        <div class="section" id="users" style="display: none;">
            <h2>Manage Users</h2>
            <div class="form-container">
                <form method="POST">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="text" name="mobile_number" placeholder="Mobile Number" required>
                    <button type="submit" name="add_user">Add User</button>
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = $db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($users as $user) {
                        echo "<tr>
                            <td>{$user['id']}</td>
                            <td>{$user['first_name']}</td>
                            <td>{$user['last_name']}</td>
                            <td>{$user['email']}</td>
                            <td>{$user['mobile_number']}</td>
                            <td><a href='?delete_user={$user['id']}'>Delete</a></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Manage Products Section -->
<div class="section" id="products" style="display: none;">
    <h2>Manage Products</h2>
    <div class="form-container">
        <form method="POST">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="text" name="category" placeholder="Category" required> <!-- Adjusted Category -->
            <input type="text" name="description" placeholder="Description" required> <!-- Adjusted Description -->
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="text" name="image" placeholder="Image Address" required> <!-- Single Field for Image Address -->
            <button type="submit" name="add_product">Add Product</button>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th> <!-- Category Column -->
                <th>Description</th> <!-- Description Column -->
                <th>Price</th>
                <th>Image</th> <!-- Image Column -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $products = $db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($products as $product) {
                // Use the image address directly from the database
               $imagePath = $product['image'];  // Assuming the image path is stored in 'image'
                echo "<tr>
                    <td>{$product['id']}</td>
                    <td>{$product['name']}</td>
                    <td>{$product['category']}</td> <!-- Display Category -->
                    <td>{$product['description']}</td> <!-- Display Description -->
                    <td>$" . number_format($product['price'], 2) . "</td>
                    <td><img src='{$imagePath}' alt='{$product['name']}' style='width: 50px; height: auto;'></td> <!-- Display Image -->
                    <td><a href='?delete_product={$product['id']}'>Delete</a></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

        <!-- Manage Orders Section -->
        <div class="section" id="orders" style="display: none;">
            <h2>Manage Orders</h2>
            <div class="form-container">
                <form method="POST">
                    <input type="text" name="full_name" placeholder="Full Name" required>
                    <input type="text" name="contact_number" placeholder="Contact Number" required>
                    <input type="text" name="address" placeholder="Address" required>
                    <input type="text" name="pincode" placeholder="Pincode" required>
                    <input type="text" name="instructions" placeholder="Instructions (optional)">
                    <input type="number" name="order_total" placeholder="Order Total" step="0.01" required>
                    <button type="submit" name="add_order">Add Order</button>
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Pincode</th>
                        <th>Order Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($orders as $order) {
                        echo "<tr>
                            <td>{$order['id']}</td>
                            <td>{$order['full_name']}</td>
                            <td>{$order['contact_number']}</td>
                            <td>{$order['address']}</td>
                            <td>{$order['pincode']}</td>
                            <td>$" . number_format($order['order_total'], 2) . "</td>
                            <td><a href='?delete_order={$order['id']}'>Delete</a></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Order Items Section -->
        <div class="section" id="order_items" style="display: none;">
            <h2>Order Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($orderItems as $item) {
                        echo "<tr>
                            <td>{$item['id']}</td>
                            <td>{$item['order_id']}</td>
                            <td>{$item['product_name']}</td>
                            <td>$" . number_format($item['price'], 2) . "</td>
                            <td>{$item['quantity']}</td>
                            <td>$" . number_format($item['total'], 2) . "</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>
</body>
</html>
