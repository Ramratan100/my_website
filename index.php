<?php
include 'db_connect.php';  // Include the database connection

// Prepare SQL query to fetch all products
$sql = "SELECT * FROM products";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display products
foreach ($products as $product) {
    echo "<div>";
    echo "<h2>" . $product['name'] . "</h2>";
    echo "<p>" . $product['description'] . "</p>";
    echo "<p>Price: $" . $product['price'] . "</p>";
    echo "<a href='add_to_cart.php?product_id=" . $product['product_id'] . "'>Add to Cart</a>";
    echo "</div>";
}
?>