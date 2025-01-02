<?php
session_start();
include 'db_connect.php';  // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit;
}

// Get user id from session
$user_id = $_SESSION['user_id'];

// Get product details from the form
$product_id = $_POST['product_id'];

// Check if the user has an active order
$sql = "SELECT * FROM Orders WHERE user_id = :user_id AND status = 'Pending'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// If no active order, create a new one
if (!$order) {
    // Create a new order
    $sql = "INSERT INTO Orders (user_id, total_amount, status) VALUES (:user_id, 0, 'Pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $order_id = $pdo->lastInsertId();
} else {
    $order_id = $order['order_id'];
}

// Get product details for the order
$sql = "SELECT price FROM Products WHERE product_id = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':product_id', $product_id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);
$product_price = $product['price'];

// Insert item into the order
$sql = "INSERT INTO Order_Items (order_id, product_id, quantity, price) 
        VALUES (:order_id, :product_id, 1, :price)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':order_id', $order_id);
$stmt->bindParam(':product_id', $product_id);
$stmt->bindParam(':price', $product_price);
$stmt->execute();

// Redirect to the order confirmation page
header('Location: view_order.php');
exit;
?>