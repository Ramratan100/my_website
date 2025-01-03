<?php
session_start();
include 'db_connect.php';  // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your cart.";
    exit;
}

$user_id = $_SESSION['user_id'];  // Get the logged-in user ID

// Fetch the cart items for the logged-in user
$sql = "SELECT Order_Items.order_item_id, Products.name, Order_Items.quantity, Order_Items.price
        FROM Order_Items
        JOIN Products ON Order_Items.product_id = Products.product_id
        WHERE Order_Items.user_id = :user_id AND Order_Items.order_id IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Output each cart item
    echo "<h2>Your Cart</h2>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Product: " . $row["name"] . " - Quantity: " . $row["quantity"] . " - Price: $" . $row["price"] . "<br>";
    }
} else {
    echo "Your cart is empty.";
}
?>