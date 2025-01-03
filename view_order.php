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

// Get active order
$sql = "SELECT * FROM Orders WHERE user_id = :user_id AND status = 'Pending'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if an active order exists
if ($order) {
    $order_id = $order['order_id'];

    // Get order items
    $sql = "SELECT Order_Items.*, Products.name FROM Order_Items 
            JOIN Products ON Order_Items.product_id = Products.product_id 
            WHERE order_id = :order_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Details</title>
        <link rel="stylesheet" href="vstyle.css">  <!-- Link to your CSS file -->
    </head>
    <body>
        <div class="order-container">
            <h2>Order Details</h2>
            <div class="order-items">
                <?php
                foreach ($order_items as $item) {
                    echo '<div class="order-item">';
                    echo '<p class="product-name">' . htmlspecialchars($item['name']) . ' x ' . $item['quantity'] . '</p>';
                    echo '<p class="product-price">$' . number_format($item['price'], 2) . '</p>';
                    echo '</div>';
                }
                ?>

            </div>

            <!-- Shipping Address Form or Confirmation -->
            <div class="shipping-info">
                <?php
                if (empty($order['shipping_address'])) {
                    echo '<form action="confirm_order.php" method="POST" class="shipping-form">
                            <label for="shipping_address">Shipping Address:</label><br>
                            <textarea name="shipping_address" id="shipping_address" rows="4" required class="input-text"></textarea><br>
                            <button type="submit" name="confirm_order" class="btn-confirm">Confirm Order</button>
                          </form>';
                } else {
                    echo "<p class='address'>Shipping Address: " . htmlspecialchars($order['shipping_address']) . "</p>";
                    echo '<form action="shipping.php" method="POST" class="btn-container">
                            <button type="submit" class="btn-shipping">Proceed to Shipping</button>
                          </form>';
                }
                ?>
            </div>

            <!-- Button to go back to Products Page -->
            <div class="btn-container">
                <form action="display_products.php" method="GET">
                    <button type="submit" class="btn-products">Go to Products</button>
                </form>
            </div>
        </div>
    </body>
    </html>

    <?php
} else {
    echo "<p>No active order found.</p>";
}
?>
