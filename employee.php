<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default password is empty
$dbname = "luxury_stay";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the 'employee' table exists
$checkTable = "SHOW TABLES LIKE 'employee'";
$tableResult = $conn->query($checkTable);
if ($tableResult === false) {
    die("Error checking table: " . $conn->error);
}

if ($tableResult->num_rows == 0) {
    // Create the 'employee' table if it doesn't exist
    $createTable = "CREATE TABLE employee (
        employee_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        work_type VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        phone_number VARCHAR(20) NOT NULL,
        work_time VARCHAR(50) NOT NULL
    )";

    if (!$conn->query($createTable)) {
        die("Error creating table: " . $conn->error);
    }
}

// Handle form submission securely
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['name']) && !empty($_POST['work_type']) && !empty($_POST['email']) 
        && !empty($_POST['phone_number']) && !empty($_POST['work_time'])) {

        $name = trim($_POST['name']);
        $work_type = trim($_POST['work_type']);
        $email = trim($_POST['email']);
        $phone_number = trim($_POST['phone_number']);
        $work_time = trim($_POST['work_time']);

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO employee (name, work_type, email, phone_number, work_time) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("sssss", $name, $work_type, $email, $phone_number, $work_time);

        if ($stmt->execute()) {
            echo "<script>alert('New employee added successfully!'); window.location.href='employee_management.php';</script>";
        } else {
            die("Execute failed: " . $stmt->error);
        }

        $stmt->close();
    } else {
        echo "<script>alert('All fields are required!');</script>";
    }
}

// Fetch employee data
$sql = "SELECT employee_id, name, work_type, email, phone_number, work_time FROM employee";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link rel="stylesheet" href="employee_managaement.css">
</head>
<body>
    <header>
        <h1>Employee Management</h1>
    </header>
    <nav>
        <div class="menu">
            <a href="home.html">Home</a>
            <a href="employee_management.php">Employee</a>
            <a href="booking.html">Booking</a>
            <a href="payment.html">Payment</a>
            <a href="admin_login.php">Admin</a>
            <a href="register.php">User</a>
            <a href="room_management.php">Rooms</a> 
        </div>
    </nav>
    <main>
        <section class="section">
            <h2>Add New Employee</h2>
            <form action="employee_management.php" method="post">
                <label>Name:</label>
                <input type="text" name="name" required><br>
                <label>Work Type:</label>
                <input type="text" name="work_type" required><br>
                <label>Email:</label>
                <input type="email" name="email" required><br>
                <label>Phone Number:</label>
                <input type="text" name="phone_number" required><br>
                <label>Work Time:</label>
                <input type="text" name="work_time" required><br>
                <button type="submit">Add Employee</button>
            </form>
        </section>
        
        <section class="section">
            <h2>Employee List</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Work Type</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Work Time</th>
                    </tr>
                </thead>
                <tbody id="employeeTable">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["employee_id"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["work_type"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["phone_number"] . "</td>";
                            echo "<td>" . $row["work_time"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No employees available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy;All rights reserved shafe.</p>
    </footer>
</body>
</html>
