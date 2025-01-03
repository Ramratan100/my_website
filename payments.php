<?php
session_start();
include 'db_connect.php';  // Ensure the DB connection is set up properly

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit;
}

// Get user id from session
$user_id = $_SESSION['user_id'];

// Get active order with 'Shipped' status or 'Pending' status as appropriate
$sql = "SELECT * FROM Orders WHERE user_id = :user_id AND status IN ('Shipped', 'Pending')";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "No valid order found for payment.";
    exit;
}

$order_id = $order['order_id'];

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['payment_method'])) {
        $payment_method = $_POST['payment_method'];
        $amount = $order['total_amount'];  // Fetch total amount of the order

        // Insert payment into Payments table
        $sql = "INSERT INTO Payments (order_id, payment_method, amount, payment_status)
                VALUES (:order_id, :payment_method, :amount, 'Completed')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':amount', $amount);
        $stmt->execute();

        // Update the order status to 'Paid' or 'Completed'
        $sql = "UPDATE Orders SET status = 'Paid' WHERE order_id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        // Redirect to display_products.php after successful payment
        header('Location: display_products.php');
        exit;
    } else {
        // If payment method is not selected, show an error
        echo "Please select a payment method.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link rel="stylesheet" href="pstyle.css">  <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Complete Your Payment</h2>

        <!-- Payment Method Form -->
        <form method="POST" action="payments.php" class="payment-form">
            <div class="payment-option">
                <input type="radio" name="payment_method" value="Credit Card" id="credit-card" required>
                <label for="credit-card">Credit Card</label>
            </div>
            <div class="payment-option">
                <input type="radio" name="payment_method" value="PayPal" id="paypal">
                <label for="paypal">PayPal</label>
            </div>

            <button type="submit" class="btn-submit">Confirm Payment</button>
        </form>
    </div>
</body>
</html>