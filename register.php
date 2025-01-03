<?php
session_start();
include 'db_connect.php';  // Include your database connection

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];  // You should hash the password before storing
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert user into Users table
    $sql = "INSERT INTO Users (username, email, password, first_name, last_name, phone) 
            VALUES (:username, :email, :password, :first_name, :last_name, :phone)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Redirect to login page after successful registration
    header('Location: login.php');
    exit;
}
?>

<!-- HTML Form for Registration -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="rstyle.css"> <!-- Link to external CSS -->
</head>
<body>
    <header>
        <div class="logo">
            <h1>Shopify</h1> <!-- Your website's name -->
        </div>
    </header>

    <main>
        <section class="register-form">
            <h2>Create Account</h2>
            <form method="POST" action="register.php">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <input type="text" name="first_name" placeholder="First Name" required>
                </div>
                <div class="input-group">
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
                <div class="input-group">
                    <input type="text" name="phone" placeholder="Phone" required>
                </div>
                <button type="submit" class="btn-submit">Register</button>
            </form>
            <p class="redirect-link">Already have an account? <a href="login.php">Login here</a></p>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Shopify. All rights reserved.</p>
    </footer>
</body>
</html>
