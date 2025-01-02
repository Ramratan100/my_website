<?php
session_start();
include 'db_connect.php';  // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You are not logged in.";
    header('Location: login.php');  // Redirect to login page if not logged in
    exit;
}

// Get user id from session
$user_id = $_SESSION['user_id'];

// Check if the shipping address is posted and the order confirmation button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order']) && !empty($_POST['shipping_address'])) {
    $shipping_address = $_POST['shipping_address'];

    // Get active order (only 'Pending' orders should be considered)
    $sql = "SELECT * FROM Orders WHERE user_id = :user_id AND status = 'Pending'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        $order_id = $order['order_id'];

        // Update the shipping address in the Orders table
        $sql = "UPDATE Orders SET shipping_address = :shipping_address WHERE order_id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':shipping_address', $shipping_address);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        // Optionally, update the order status to 'Confirmed'
        $sql = "UPDATE Orders SET status = 'Confirmed' WHERE order_id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Shipping Address</title>
    <link rel="stylesheet" href="cstyle.css">  <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Confirm Shipping Address</h2>

        <!-- Shipping Address Form -->
        <form method="POST" action="shipping.php" class="shipping-form">
            <label for="shipping_address">Shipping Address:</label>
            <textarea name="shipping_address" id="shipping_address" rows="6" placeholder="Enter your shipping address" required></textarea>

            <button type="submit" name="confirm_order" class="btn-confirm">Confirm Order</button>
        </form>

    </div>
</body>
</html>
