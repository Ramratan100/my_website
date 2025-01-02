<?php
// place_order.php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $total_amount = $_POST['total_amount'];
    $shipping_address = $_POST['shipping_address'];

    // Insert into Orders table
    $sql = "INSERT INTO Orders (user_id, total_amount, shipping_address) 
            VALUES ('$user_id', '$total_amount', '$shipping_address')";

    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;
        
        // Insert order items
        foreach ($_POST['products'] as $product_id => $quantity) {
            $price = $_POST['prices'][$product_id]; // Assume prices are passed in form too
            $sql = "INSERT INTO Order_Items (order_id, product_id, quantity, price)
                    VALUES ('$order_id', '$product_id', '$quantity', '$price')";
            $conn->query($sql);
        }

        echo "Order placed successfully. Your order ID is $order_id.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!-- Sample HTML form to place an order -->
<form method="post">
    User ID: <input type="text" name="user_id" required><br>
    Total Amount: <input type="text" name="total_amount" required><br>
    Shipping Address: <textarea name="shipping_address" required></textarea><br>

    <!-- Products and quantities -->
    Product 1: <input type="number" name="products[1]" value="1"><br>
    Product 2: <input type="number" name="products[2]" value="1"><br>

    <input type="submit" value="Place Order">
</form>