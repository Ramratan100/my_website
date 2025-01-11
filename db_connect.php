<?php
// Database connection details
$servername = "10.0.1.50";  // Database server IP or hostname
$username = "net_user";      // Database username
$password = "password";      // Database password
$database = "eCommerceDB";    // Database name

try {
    // Create a PDO connection (for security and flexibility)
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    // Set the PDO error mode to exception for better debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "WELCOME!"; // Optional: You can confirm the connection if needed
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// If the connection is successful, display options to the user
if ($pdo) {
    // HTML form with Login and Registration options
    echo '
    <html>
    <body>
        <h2>MYGURUKULAM Powered by OpsTree</h2>
        <form method="post">
            <input type="submit" name="action" value="Login" />
            <input type="submit" name="action" value="Register" />
        </form>
    </body>
    </html>
    ';

    // Check the action chosen by the user
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            // Redirect based on the user's choice
            if ($_POST['action'] === 'Login') {
                header('Location: login.php');
                exit;
            } elseif ($_POST['action'] === 'Register') {
                header('Location: register.php');
                exit;
            }
        }
    }
}
?>
