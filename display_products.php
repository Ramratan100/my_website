<?php
session_start();
include 'db_connect.php';  // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit;
}

// Add logout button
echo '<div class="logout-container">';
echo '<form action="logout.php" method="POST">';
echo '<button type="submit" class="logout-button">Logout</button>';
echo '</form>';
echo '</div>';

// Fetch products
$sql = "SELECT Products.product_id, Products.name, Products.description, Products.price, Products.image_url, Categories.category_name
        FROM Products
        JOIN Categories ON Products.category_id = Categories.category_id";
$result = $pdo->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="dstyle.css">  <!-- Link to your CSS file -->
</head>
<body>
    <div class="products-container">
        <?php
        if ($result->rowCount() > 0) {
            // Output each product
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="product-card">';
                
                // Display the product image using the image_url from the database
                $image_url = htmlspecialchars($row['image_url']); // Get the image URL from the database
                echo '<img src="' . $image_url . '" alt="' . htmlspecialchars($row["name"]) . '" class="product-image">';
                
                echo '<div class="product-info">';
                echo '<h3 class="product-name">' . htmlspecialchars($row["name"]) . '</h3>';
                echo '<p class="product-description">' . htmlspecialchars($row["description"]) . '</p>';
                echo '<p class="product-price">$' . htmlspecialchars($row["price"]) . '</p>';
                echo '<p class="product-category">Category: ' . htmlspecialchars($row["category_name"]) . '</p>';
                echo '<form method="POST" action="add_to_cart.php">';
                echo '<input type="hidden" name="product_id" value="' . $row["product_id"] . '">';
                echo '<button type="submit" class="add-to-cart-btn">Add to Cart</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
    </div>
</body>
</html>