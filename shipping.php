\<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user id from session
$user_id = $_SESSION['user_id'];

// Get active order
$sql = "SELECT * FROM Orders WHERE user_id = :user_id AND status = 'Pending'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);
$order_id = $order['order_id'];

// Add shipping details to the Shipping table
$tracking_number = 'Tracking123';  // You can generate this dynamically
$shipping_date = date('Y-m-d H:i:s');
$delivery_date = NULL; // Set delivery date when available

$sql = "INSERT INTO Shipping (order_id, tracking_number, shipping_date, delivery_date)
        VALUES (:order_id, :tracking_number, :shipping_date, :delivery_date)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':order_id', $order_id);
$stmt->bindParam(':tracking_number', $tracking_number);
$stmt->bindParam(':shipping_date', $shipping_date);
$stmt->bindParam(':delivery_date', $delivery_date);
$stmt->execute();

// Update the order status to 'Shipped'
$sql = "UPDATE Orders SET status = 'Shipped' WHERE order_id = :order_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();

// Redirect to Payments page
header('Location: payments.php');
exit;
?>