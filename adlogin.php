<?php
session_start();

// Hardcoded admin credentials (hashed password)
$admin_email = 'admin@gmail.com';
$admin_password_hash = password_hash('admin', PASSWORD_DEFAULT); // Hash the password

// Check if already logged in as admin
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: adlogin.php'); // Redirect to admin dashboard
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate credentials
    if ($email === $admin_email && password_verify($password, $admin_password_hash)) {
        $_SESSION['admin_logged_in'] = true; // Set session variable
        session_regenerate_id(); // Regenerate session ID
        header('Location: admin.php'); // Redirect to admin dashboard
        exit;
    } else {
        $error_message = "Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff5f8; /* Soft Pinkish White */
            background-image: url('bg.jpeg'); /* Update the path to your image */
            background-size: cover; /* Ensures the image covers the entire background */
            background-position: center; /* Centers the image */
            color: #333;
        }

               header {
            background-color: #ff9aa2; /* Soft Coral Pink */
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
            background-color: #007bff;
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
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">The Gift Spot</div>
            <ul class="nav-links">
                <li><a href="home.html">Home</a></li>
                <li><a href="login1.php">Login</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="all.html">Product</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </nav>
    </header>        
    <div class="container">
        <h2>Admin Login</h2>
        <?php
        // Display an error message if login failed
        if (isset($error_message)) {
            echo "<p class='error-message'>" . $error_message . "</p>";
        }
        ?>
        <form action="#" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
