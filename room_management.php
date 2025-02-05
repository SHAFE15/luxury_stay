<?php
// Connect to database
$servername = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default password is empty
$dbname = "luxury_stay";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_type = $_POST['room_type'];
    $capacity = $_POST['capacity'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $sql = "INSERT INTO rooms (room_type, capacity, price, status) VALUES ('$room_type', '$capacity', '$price', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New room added successfully!'); window.location.href='room_management.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch rooms data
$sql = "SELECT room_id, room_type, capacity, price, status FROM rooms";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
    <link rel="stylesheet" href="room_management.css">
</head>
<body>
    <header>
        <h1>Room Management</h1>
    </header>
    <nav>
        <div class="menu">
            <a href="home.html">Home</a>
            <a href="employee.php">Employee</a>
            <a href="booking.html">Booking</a>
            <a href="payment.html">Payment</a>
            <a href="admin_login.php">Admin</a>
            <a href="register.php">User</a>
            <a href="room_management.php">Room</a>
        </div>
    </nav>
    <main>
        <section class="section">
            <h2>Add New Room</h2>
            <form action="room_management.php" method="post">
                <label>Room Type:</label>
                <input type="text" name="room_type" required><br>
                <label>Capacity:</label>
                <input type="number" name="capacity" required><br>
                <label>Price:</label>
                <input type="number" name="price" step="0.01" required><br>
                <label>Status:</label>
                <select name="status">
                    <option value="Available">Available</option>
                    <option value="Booked">Booked</option>
                    <option value="Under Maintenance">Under Maintenance</option>
                </select><br>
                <button type="submit">Add Room</button>
            </form>
        </section>
        
        <section class="section">
            <h2>Room List</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Room ID</th>
                        <th>Room Type</th>
                        <th>Capacity</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="roomTable">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["room_id"] . "</td>";
                            echo "<td>" . $row["room_type"] . "</td>";
                            echo "<td>" . $row["capacity"] . "</td>";
                            echo "<td>" . $row["price"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No rooms available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy;All rights reserved shafe.</p>
    </footer>
    
    <?php $conn->close(); ?>
</body>
</html>
