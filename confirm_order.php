<?php
session_start();
include 'db_connect.php';  // Ensure the DB connection is set up properly

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit;
}

// Get user id from session
$user_id = $_SESSION['user_id'];

// Get active order with 'Pending' status
$sql = "SELECT * FROM Orders WHERE user_id = :user_id AND status = 'Pending'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Debugging output for session user_id and query result
echo "User ID: " . $user_id . "<br>"; // Check session data
if ($order) {
    echo "Order found: <pre>";
    print_r($order);  // Display order details for debugging
    echo "</pre>";
} else {
    echo "No pending orders found for this user.<br>";
    exit; // Exit if no order found
}

// Handle order confirmation after form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    // Get the shipping address from the form submission
    $shipping_address = trim($_POST['shipping_address']);
    
    // Check if shipping address is provided
    if (empty($shipping_address)) {
        echo "Please provide a valid shipping address.";
        exit;
    }

    // Calculate the total amount by summing all order item prices
    $sql = "SELECT SUM(Order_Items.quantity * Order_Items.price) AS total_amount
            FROM Order_Items 
            WHERE order_id = :order_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':order_id', $order['order_id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_amount = $result['total_amount'] ?? 0;

    // Update the order status, shipping address, and total amount
    $sql = "UPDATE Orders 
            SET shipping_address = :shipping_address, total_amount = :total_amount, status = 'Confirmed' 
            WHERE order_id = :order_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':shipping_address', $shipping_address);
    $stmt->bindParam(':total_amount', $total_amount);
    $stmt->bindParam(':order_id', $order['order_id']);
    $stmt->execute();

    // Redirect to the payments page (or display_products.php as per your requirement)
    header('Location: payments.php');  // Redirect to payments.php to complete the payment
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order</title>
    <link rel="stylesheet" href="cstyle.css">  <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Order Details</h2>

        <?php if ($order): ?>
        <!-- Display Order Details -->
        <div class="order-details">
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
            <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
        </div>

        <!-- Shipping Address Form -->
        <div class="shipping-info">
            <form method="POST" action="confirm_order.php" class="shipping-form">
                <label for="shipping_address">Shipping Address:</label><br>
                <textarea name="shipping_address" id="shipping_address" rows="4" required class="input-text"></textarea><br>
                <button type="submit" name="confirm_order" class="btn-confirm">Confirm Order</button>
            </form>
        </div>

        <?php else: ?>
        <p>No active order found.</p>
        <?php endif; ?>
    </div>
</body>
</html>