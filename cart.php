<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Check if this is a new session or a new login
if (!isset($_SESSION['cart_initialized']) || $_SESSION['cart_initialized'] !== $_SESSION['user_id']) {
    $_SESSION['cart_initialized'] = $_SESSION['user_id']; // Set a flag for cart initialization
    $new_session = true; // This is a new session or login
} else {
    $new_session = false; // The user is already logged in
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #ff9aa2;
            padding: 20px 0;
            text-align: center;
        }

        .logo {
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

        .cart-container {
            padding: 20px;
        }

        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .total-price {
            font-weight: bold;
            text-align: right;
        }

        .checkout-container {
            text-align: right;
            margin-top: 20px;
        }

        .checkout-btn {
            background-color: #ff9aa2;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .checkout-btn:hover {
            background-color: #e88890;
        }

        .empty-cart {
            text-align: center;
            margin: 50px 0;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #ff9aa2;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Gift Shop</div>
        <nav>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="cust.php">Products</a></li>
                <li><a href="cart.php">My Cart</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="cart-container">
        <table id="cart-table">
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="cart-items">
                <!-- Cart items will be inserted here by JavaScript -->
            </tbody>
        </table>

        <div class="total-price">
            <p id="total">Total: $0.00</p>
        </div>

        <!-- Checkout Button -->
        <div class="checkout-container">
            <button onclick="window.location.href='checkout.php'" class="checkout-btn">Checkout</button>
        </div>
    </div>

    <div class="empty-cart" id="empty-cart" style="display:none;">
        <p>Your cart is empty.</p>
    </div>

    <footer>
        <p>&copy; 2024 Gift Shop. All rights reserved.</p>
    </footer>

    <script>
        // Check if it's a new session (from PHP)
        const isNewSession = <?php echo $new_session ? 'true' : 'false'; ?>;

        if (isNewSession) {
            // Clear the cart in localStorage if this is a new session
            localStorage.removeItem('cartItems');
        }

        // Function to load cart from localStorage and display it in the table
        function loadCart() {
            const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            const cartTable = document.getElementById('cart-items');
            const totalElement = document.getElementById('total');
            const emptyCartMessage = document.getElementById('empty-cart');
            let totalAmount = 0;

            cartTable.innerHTML = ''; // Clear the table content

            if (cartItems.length === 0) {
                document.querySelector('table').style.display = 'none';
                emptyCartMessage.style.display = 'block';
                return;
            }

            // Loop through the cart items and create rows in the table
            cartItems.forEach((item, index) => {
                const itemTotal = (item.price * item.quantity).toFixed(2);
                totalAmount += parseFloat(itemTotal);

                const row = `
                    <tr>
                        <td><img src="${item.image}" alt="${item.name}" style="width:50px;"></td>
                        <td>${item.name}</td>
                        <td>$${item.price.toFixed(2)}</td>
                        <td>${item.quantity}</td>
                        <td>$${itemTotal}</td>
                        <td><button onclick="removeItem(${index})">Remove</button></td>
                    </tr>
                `;
                cartTable.innerHTML += row;
            });

            totalElement.innerHTML = `Total: $${totalAmount.toFixed(2)}`;
        }

        // Function to remove an item from the cart
        function removeItem(index) {
            let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            cartItems.splice(index, 1); // Remove item at the specified index

            // Update the localStorage and reload the cart
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            loadCart();
        }

        // Load the cart when the page loads
        window.onload = loadCart;
    </script>
</body>
</html>
