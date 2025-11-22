<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login1.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'gift'); // Adjust with your DB credentials

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$userId = $_SESSION['user_id'];
$sqlUser = "SELECT first_name, last_name, email, mobile_number, address FROM users WHERE id = $userId";
$userResult = $conn->query($sqlUser);
$user = $userResult->fetch_assoc();

// Fetch products
$sqlProducts = "SELECT * FROM products"; // Adjust table name as needed
$productResult = $conn->query($sqlProducts);
$products = [];
while ($row = $productResult->fetch_assoc()) {
    $products[] = $row; // Store products in an array
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
  body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #ff9aa2; /* Soft Coral Pink */
            padding: 20px 0;
            text-align: center;
        }

        . .logo {
            font-size: 36px;
            font-weight: bold;
            color: #fff;
        }

        nav {
            margin-top: 15px;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin-right: 15px;
        }

        nav ul li:last-child {
            margin-right: 0;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px;
            transition: background 0.3s;
        }

        nav ul li a:hover {
            background-color: #575757;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff65;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #ff9aa2;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
            text-align: center;
        }

        .profile {
            display: none; /* Initially hidden */
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            margin: 20px;
        }

        .navigation {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin: 20px;
            display: flex; /* Use flexbox for horizontal alignment */
            justify-content: space-between; /* Distribute space evenly */
            flex-wrap: wrap; /* Allow wrapping if the screen is narrow */
        }

        .products-container {
            display: flex; /* Use flexbox for horizontal layout */
            justify-content: space-around; /* Space items evenly */
            flex-wrap: wrap; /* Allow wrapping */
        }

        .navigation h2 {
            margin-bottom: 15px;
        }

        .product {
            cursor: pointer;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .product-inner {
            display: flex;
            flex-direction: column; /* Stack image on top of text */
            align-items: center; /* Center align items horizontally */
            text-align: center; /* Center align text */
        }

        .product img {
            width: 100px; /* Adjust image size as needed */
            height: auto;
            margin-bottom: 5px; /* Space between image and text */
        }

        .product-category {
            display: none; /* Initially hidden */
            padding: 20px;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
        }

        .product-card {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
            margin: 10px;
            padding: 15px;
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
        }
.pink-button {
    background-color: #ff9aa2; /* Light Pink */
    color: #fff; /* White text */
    padding: 10px 10px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    width: 100%;
    transition: background-color 0.3s;
}

.pink-button:hover {
    background-color: #ff1493; /* Darker Pink on hover */
}

.button-container {
    display: flex; /* Use flexbox for horizontal alignment */
    justify-content: space-between; /* Space items evenly */
    margin-top: 10px; /* Space between the buttons and product details */
}

.button-container button {
    flex: 1; /* Make buttons take equal width */
    margin-right: 10px; /* Space between buttons */
}

.button-container button:last-child {
    margin-right: 0; /* Remove right margin for the last button */
}

        footer {
            text-align: center;
            padding: 20px;
            background-color:  #ff9aa2;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Customer Dashboard</h1>
        <nav>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="cust.php">Products</a></li>
                <li><a href="cart.php">My Cart</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="order.php">My orders</a></li>
                <li><a href="#" onclick="toggleProfile()">Profile</a></li> <!-- Profile link -->


            </ul>
        </nav>
    </header>

    <main>
        <section class="profile" id="profile">
            <h2>Profile Information</h2>
            <p><strong>First Name:</strong> <?php echo $user['first_name']; ?></p>
            <p><strong>Last Name:</strong> <?php echo $user['last_name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Mobile Number:</strong> <?php echo $user['mobile_number']; ?></p>
            <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
        </section>

        <!-- Navigation Area -->
        <div class="navigation">
            <h2>Categories</h2>
            <div class="product" onclick="showCategory('pillows')">
                <div class="product-inner">
                    <img src="pillow.jpg" alt="Pillow">
                    <h3>Pillows</h3>
                </div>
            </div>
            <div class="product" onclick="showCategory('mugs')">
                <div class="product-inner">
                    <img src="mug.jpg" alt="Mug">
                    <h3>Mugs</h3>
                </div>
            </div>
            <div class="product" onclick="showCategory('teddies')">
                <div class="product-inner">
                    <img src="teddy-bear.jpg" alt="Teddy Bear">
                    <h3>Teddy Bears</h3>
                </div>
            </div>
            <div class="product" onclick="showCategory('diaries')">
                <div class="product-inner">
                    <img src="diary.jpg" alt="Diary">
                    <h3>Diaries</h3>
                </div>
            </div>
            <div class="product" onclick="showCategory('bottles')">
                <div class="product-inner">
                    <img src="bottle.jpg" alt="Bottle">
                    <h3>Bottles</h3>
                </div>
            </div>
        </div>

        <section class="products">
            <h2>Available Products</h2>

            <?php
            // Group products by category
            $productCategories = ['pillows', 'mugs', 'teddies', 'diaries', 'bottles'];
            foreach ($productCategories as $category) {
                echo "<div class='product-category' id='{$category}' style='display: none;'>
                        <h3>" . ucfirst($category) . "</h3>
                        <div class='product-list'>";
                
foreach ($products as $product) {
    if ($product['category'] == $category) {
        echo "<div class='product-card'>
                <img src='{$product['image']}' alt='{$product['name']}'>
                <h3>{$product['name']}</h3>
                <p><strong>Price:</strong> \${$product['price']}</p> <!-- Display price -->
                <p><strong>Description:</strong> {$product['description']}</p> <!-- Display description -->
                <input type='number' id='quantity-{$product['id']}' value='1' min='1'>
                <button class='add-to-cart pink-button' onclick=\"addToCart('{$product['name']}', {$product['price']}, 'quantity-{$product['id']}', '{$product['image']}')\">Add to Cart</button> <!-- Add class for pink button -->
              </div>";
    }
}

                
                echo "  </div>
                      </div>";
            }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Gift Shop. All rights reserved.</p>
    </footer>

    <script>
        function toggleProfile() {
            const profileSection = document.getElementById('profile');
            profileSection.style.display = profileSection.style.display === 'none' ? 'block' : 'none';
        }

        function showCategory(category) {
            const categories = document.querySelectorAll('.product-category');
            categories.forEach(cat => {
                cat.style.display = 'none'; // Hide all categories
            });
            document.getElementById(category).style.display = 'block'; // Show selected category
        }

        function addToCart(name, price, quantityId, image) {
            const quantity = document.getElementById(quantityId).value;
            const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

            const itemIndex = cartItems.findIndex(item => item.name === name);
            if (itemIndex > -1) {
                cartItems[itemIndex].quantity += parseInt(quantity);
            } else {
                cartItems.push({ name, price, quantity: parseInt(quantity), image });
            }

            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            alert(`${name} added to cart!`);
        }
    </script>
</body>
</html> 