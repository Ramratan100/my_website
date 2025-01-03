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

// Get active order (only 'Pending' orders should be considered)
$sql = "SELECT * FROM Orders WHERE user_id = :user_id AND status = 'Pending'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the order exists
if ($order) {
    $order_id = $order['order_id'];
    
    // Handle order confirmation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
        // Optionally, you could add more processing here (e.g., confirm the order, send email, etc.)
        $sql = "UPDATE Orders SET status = 'Confirmed' WHERE order_id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        echo "<p>Your order has been confirmed!</p>";
    }
} else {
    echo "<p>No pending orders found.</p>";
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
            <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars($order['total_amount']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
        </div>

        <!-- Confirm Order Button -->
        <form method="POST" action="shipping.php" class="confirm-form">
            <button type="submit" name="confirm_order" class="btn-confirm">Confirm Order</button>
        </form>

        <?php endif; ?>
    </div>
</body>
</html>
