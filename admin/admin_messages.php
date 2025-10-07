<?php
// Database connection
$host = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "budgettracker"; // Change if necessary

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages
$sql = "SELECT * FROM ContactMessages ORDER BY timestamp DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Contact Messages</title>
    <link rel="stylesheet" href="adminpagestyle.css">
</head>
<body>
<header>
        <nav class="navbar">
            <h1>Dashboard</h1>
        </nav>
    </header>
    <div class="dashboard">
    <div class="sidenav">
        <ul class="nav-links">
            <li><a href="dashboard.php" onclick="showUsers()">User Management</a></li>
            <li><a href="admin_messages.php" onclick="showMessages()">Messages</a></li>
        </ul>
    </div>
    <div class="messages">
    <h2>Contact Messages</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Message</th>
            <th>Time</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                <td><?php echo $row['timestamp']; ?></td>
            </tr>
        <?php } ?>
    </table>
        </div>
</body>
</html>
<div class="users">
        <h2>User Details</h2>
        <table>
            <thead>
                <tr>

                    <th>Name</th>
                    <th>Message</th>
                    <th>Time</th>
        </tr>
            </thead>
            <tbody id="transactionTable">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "budgettracker";
                // Assuming $servername, $username, $password, and $dbname are defined elsewhere
                $conn = new mysqli($servername, $username, $password, $dbname);
                
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                // Use prepared statements to avoid SQL injection
                $sql = "SELECT * FROM ContactMessages ORDER BY timestamp DESC";
$result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["Name"] . "</td>
                        <td>" . $row["Message"] . "</td>
                        <td>" . $row["Timestamp"] . "</td>
                        
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No transactions found</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

<?php $conn->close(); ?>
