<?php
// db_config.php
// Database connection parameters
$servername = "10.0.1.50";
$username = "net_user";
$password = "password";
$database = "eCommerceDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>