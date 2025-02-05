<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to login page
    exit();
}

echo "<h1>Welcome, Admin!</h1>";
echo "<p>You are successfully logged in.</p>";
?>
