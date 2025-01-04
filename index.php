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
            <h1>Shopify</h1> <!-- Your website's name -->
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
                        <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                        <h3 class="product-name"><?php echo $product['name']; ?></h3>
                        <p class="product-description"><?php echo substr($product['description'], 0, 100); ?>...</p>
                        <p class="product-price">$<?php echo $product['price']; ?></p>
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