<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Checkout</title>
  <style>
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

    .container {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"], input[type="number"], textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .error-msg {
      color: red;
      font-size: 14px;
      margin-bottom: 15px;
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

    .total-price {
      font-weight: bold;
      text-align: right;
    }

    .checkout-btn {
      background-color: #ff9aa2;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
      border-radius: 5px;
    }

    .checkout-btn:hover {
      background-color: #e88890;
    }

    .button-container {
      display: flex;
      justify-content: flex-end;
      margin-top: 20px;
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

    input:invalid {
      border-color: red;
    }
  </style>
</head>
<body>
  <header>
    <h1>Checkout</h1>
    <nav>
      <ul class="nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="cust.php">Products</a></li>
        <li><a href="cart.php">My Cart</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h2>Your Cart Items</h2>
    <table id="cart-table">
      <thead>
        <tr>
          <th>Product Image</th>
          <th>Product Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody id="cart-items">
        <!-- Filled by JS -->
      </tbody>
    </table>

    <div class="total-price">
      <p id="total">Total: $0.00</p>
    </div>

    <div class="checkout-form">
      <h2>Enter Your Details</h2>
      <form id="checkout-form" action="save_order.php" method="POST" novalidate>
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" maxlength="50" required>
        <span class="error-msg" id="full_name-error"></span>

        <label for="contact_number">Contact Number:</label>
        <input type="text" id="contact_number" name="contact_number" maxlength="10" required>
        <span class="error-msg" id="contact_number-error"></span>

        <label for="address">Address:</label>
        <textarea id="address" name="address" rows="4" maxlength="200" required></textarea>
        <span class="error-msg" id="address-error"></span>

        <label for="pincode">Pincode:</label>
        <input type="number" id="pincode" name="pincode" maxlength="6" required>
        <span class="error-msg" id="pincode-error"></span>

        <input type="hidden" id="order_total" name="order_total">
        <input type="hidden" id="cart_items" name="cart_items">

        <div class="button-container">
          <button type="submit" class="checkout-btn">Place Order</button>
        </div>
      </form>
    </div>

    <div class="empty-cart" id="empty-cart" style="display:none;">
      <p>Your cart is empty.</p>
    </div>
  </div>

  <footer>
    <p>&copy; 2024 Gift Shop. All rights reserved.</p>
  </footer>

  <script>
    function loadCart() {
      const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
      const cartTable = document.getElementById('cart-items');
      const totalElement = document.getElementById('total');
      const emptyCartMessage = document.getElementById('empty-cart');
      let totalAmount = 0;

      cartTable.innerHTML = '';

      if (cartItems.length === 0) {
        document.querySelector('table').style.display = 'none';
        emptyCartMessage.style.display = 'block';
        return;
      }

      cartItems.forEach(item => {
        const itemTotal = (item.price * item.quantity).toFixed(2);
        totalAmount += parseFloat(itemTotal);

        const row = `
          <tr>
            <td><img src="${item.image}" alt="${item.name}" style="width:50px;"></td>
            <td>${item.name}</td>
            <td>$${item.price.toFixed(2)}</td>
            <td>${item.quantity}</td>
            <td>$${itemTotal}</td>
          </tr>
        `;
        cartTable.innerHTML += row;
      });

      totalElement.innerHTML = `Total: $${totalAmount.toFixed(2)}`;
      document.getElementById('order_total').value = totalAmount.toFixed(2);
      document.getElementById('cart_items').value = JSON.stringify(cartItems);
    }

    function showError(id, message) {
      document.getElementById(id + '-error').textContent = message;
      document.getElementById(id).style.borderColor = message ? 'red' : '#ccc';
    }

    function validateField(id, maxLength, regex = null) {
      const input = document.getElementById(id);
      const value = input.value.trim();

      if (value.length === 0) {
        showError(id, "This field is required.");
        return false;
      } else if (value.length > maxLength) {
        input.value = value.substring(0, maxLength);
        showError(id, `Max ${maxLength} characters allowed.`);
        return false;
      } else if (regex && !regex.test(value)) {
        showError(id, `Invalid ${id.replace("_", " ")} format.`);
        return false;
      } else {
        showError(id, "");
        return true;
      }
    }

    document.getElementById('checkout-form').addEventListener('submit', function (e) {
      const isNameValid = validateField('full_name', 50);
      const isContactValid = validateField('contact_number', 10, /^\d{10}$/);
      const isAddressValid = validateField('address', 200);
      const isPincodeValid = validateField('pincode', 6, /^\d{6}$/);

      if (!isNameValid || !isContactValid || !isAddressValid || !isPincodeValid) {
        e.preventDefault();
      }
    });

    window.onload = function () {
      loadCart();

      document.getElementById('full_name').addEventListener('input', () => validateField('full_name', 50));
      document.getElementById('contact_number').addEventListener('input', () => validateField('contact_number', 10, /^\d{0,10}$/));
      document.getElementById('address').addEventListener('input', () => validateField('address', 200));
      document.getElementById('pincode').addEventListener('input', () => validateField('pincode', 6, /^\d{0,6}$/));
    };
  </script>
</body>
</html>
