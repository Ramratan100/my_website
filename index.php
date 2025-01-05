<?php
// Assuming you have a database or array of products.
$products = [
    ['product_id' => 1, 'name' => 'Product 1', 'description' => 'This is product 1 description', 'price' => 29.99, 'image' => 'product1.jpg'],
    ['product_id' => 2, 'name' => 'Product 2', 'description' => 'This is product 2 description', 'price' => 39.99, 'image' => 'product2.jpg'],
    ['product_id' => 3, 'name' => 'Product 3', 'description' => 'This is product 3 description', 'price' => 49.99, 'image' => 'product3.jpg'],
    // Add more products here...
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <link rel="stylesheet" href="istyle.css"> <!-- Link to external CSS -->
</head>
<body>
    <header>
        <div class="logo">
            <img src="https://www.example.com/path/to/logo.jpg" alt="Shopify Logo"> <!-- External Logo -->
        </div>
        <nav>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <main>
        <section class="products">
            <h2>Featured Products</h2>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image"> <!-- Product Image -->
                        <h3 class="product-name"><?php echo $product['name']; ?></h3>
                        <p class="product-description"><?php echo substr($product['description'], 0, 100); ?>...</p>
                        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                        <a href="add_to_cart.php?product_id=<?php echo $product['product_id']; ?>" class="btn-add-to-cart">Add to Cart</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Shopify. All rights reserved.</p>
    </footer>
</body>
</html>
