<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: cust.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | The Gift Spot</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background: url('bg.jpeg') no-repeat center center fixed;
      background-size: cover;
      color: #333;
    }

    header {
      background-color: #ff9aa2;
      padding: 15px 20px;
      position: sticky;
      top: 0;
      z-index: 999;
    }

    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .logo {
      font-size: 28px;
      font-weight: bold;
      color: white;
    }

    .menu-toggle {
      font-size: 30px;
      color: white;
      cursor: pointer;
      display: none;
    }

    .nav-links {
      display: flex;
      gap: 15px;
      list-style: none;
    }

    .nav-links li a {
      color: white;
      text-decoration: none;
      font-size: 16px;
      padding: 6px 10px;
      border-radius: 4px;
      transition: background 0.3s;
    }

    .nav-links li a:hover {
      background-color: #ff6f61;
    }

    /* Login form container */
    .container {
      max-width: 400px;
      margin: 100px auto 40px auto;
      background-color: rgba(255, 255, 255, 0.85);
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button[type="submit"] {
      background-color: #007bff;
      color: white;
      padding: 12px;
      width: 100%;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button[type="submit"]:hover {
      background-color: #0056b3;
    }

    .error-message {
      color: red;
      text-align: center;
      margin-bottom: 15px;
    }

    .signup-link {
      text-align: center;
      margin-top: 20px;
    }

    .signup-link a {
      color: #007bff;
      text-decoration: none;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      .menu-toggle {
        display: block;
      }

      .nav-links {
        flex-direction: column;
        align-items: flex-start;
        display: none;
        width: 100%;
        background-color: #ff9aa2;
        margin-top: 10px;
      }

      .nav-links.active {
        display: flex;
      }

      .nav-links li {
        width: 100%;
        padding: 10px;
        border-top: 1px solid #fff3f3;
      }

      .nav-links li a {
        display: block;
        width: 100%;
      }

      .logo {
        font-size: 24px;
      }

      .container {
        margin: 60px 20px;
        padding: 20px;
      }
    }

    @media (max-width: 480px) {
      .logo {
        font-size: 20px;
      }

      button[type="submit"] {
        font-size: 14px;
        padding: 10px;
      }

      input[type="email"],
      input[type="password"] {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

  <header>
    <nav>
      <div class="logo">The Gift Spot</div>
      <div class="menu-toggle" onclick="toggleMenu()">☰</div>
      <ul class="nav-links" id="navLinks">
        <li><a href="home.html">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="contact.html">Contact Us</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="product.html">Product</a></li>
        <li><a href="admin.php">Admin</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h2>Login</h2>
    <?php if (isset($_GET['error'])): ?>
      <p class="error-message">Invalid email or password. Please try again.</p>
    <?php endif; ?>
    <form action="login.php" method="POST">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required />

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required />

      <button type="submit">Login</button>
    </form>

    <div class="signup-link">
      <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
    </div>
  </div>

  <script>
    function toggleMenu() {
      document.getElementById('navLinks').classList.toggle('active');
    }
  </script>
</body>
</html>
