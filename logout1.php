<?php
// Start the session
session_start();

// Destroy session to log the user out
session_destroy();

// Redirect to login page
header('Location: login1.php');
exit();
?>
