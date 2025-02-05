<?php
// Database connection
$servername = "localhost";
$username = "root"; // your MySQL username
$password = ""; // your MySQL password
$dbname = "luxury_stay";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user registration (POST method)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Get the input data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password

    // Insert the data into the database
    $sql = "INSERT INTO users (name, email, phone_number, password) VALUES ('$name', '$email', '$phone_number', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle user deletion (GET method)
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];

    // Delete the user with the given ID
    $sql = "DELETE FROM users WHERE user_id = $user_id";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all registered users
$sql = "SELECT user_id, name, email, phone_number FROM users";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <nav>
        <div class="menu">
            <a href="home.html">Home</a>
            <a href="employee.php">Employee</a>
            <a href="booking.html">Booking</a>
            <a href="payment.html">Payment</a>
            <a href="admin_login.php">Admin</a>
            <a href="room_management.php">Rooms</a> 
        </div>
    </nav>
<h2>User Registration</h2>

<form method="post" action="">
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" required><br><br>
    
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>
    
    <label for="phone_number">Phone Number:</label><br>
    <input type="text" id="phone_number" name="phone_number" required><br><br>
    
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>
    
    <input type="submit" name="register" value="Register">
</form>

<h2>Registered Users</h2>

<?php if ($result->num_rows > 0): ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone_number']; ?></td>
                <td><a href="?delete=<?php echo $row['user_id']; ?>">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No users found</p>
<?php endif; ?>

</body>
</html>
