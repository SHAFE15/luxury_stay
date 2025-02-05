<?php
// Start session to track login status
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luxury_stay";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $input_password = $_POST['password'];

    // Check if admin exists
    $stmt = $conn->prepare("SELECT admin_id, password FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($admin_id, $stored_password);
    
    if ($stmt->fetch() && password_verify($input_password, $stored_password)) {
        // Password match
        $_SESSION['admin_id'] = $admin_id;
        header("Location: home.html"); // Redirect to dashboard
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Login</h1>
        </header>
        
        <main>
            <form action="admin_login.php" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>

            <div class="links">
                <a href="admin_registration.php" class="btn-register">Register</a>
                <a href="forgot_password.php" class="btn-forgot-password">Forgot Password?</a>
            </div>
        </main>

        <footer>
            <p>&copy;shafe</p>
        </footer>
    </div>
</body>
</html>
