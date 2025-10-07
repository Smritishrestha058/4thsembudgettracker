<?php
session_start();
$conn = new mysqli("localhost", "root", "", "budgettracker");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Count total users
$countQuery = "SELECT COUNT(*) AS total_users FROM UserDetails";
$countResult = $conn->query($countQuery);
$totalUsers = 0;

if ($countResult->num_rows > 0) {
    $row = $countResult->fetch_assoc();
    $totalUsers = $row['total_users'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="adminpagestyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script>
        function showUsers() {
            let users = document.getElementById("user-section");
            let messages = document.getElementById("messages");

            if (users && messages) {
                users.style.display = "block";
                messages.style.display = "none";
            } else {
                console.error("Error: Elements not found");
            }
        }

        function showMessages() {
            let users = document.getElementById("user-section");
            let messages = document.getElementById("messages");

            if (users && messages) {
                users.style.display = "none";
                messages.style.display = "block";
            } else {
                console.error("Error: Elements not found");
            }
        }

    </script>
</head>
<body>
    <div class="admin-panel">
        <div class="sidenav">
            <div class="logo" id="logo">
                <img src="logo.png" alt="Budget Tracker Logo" onclick="showUsers()"> <!-- Replace with your logo -->
            </div>
            
            <ul class="nav-links">
                <div class="link">
                    <i class="fa-solid fa-user"></i>
                    <li><a href="#home" onclick="showUsers()">User Management</a></li>
                </div>
                <div class="link">
                    <i class="fa-solid fa-envelope"></i>
                    <li><button id="messagesLink" onclick="showMessages()">Messages</button></li>
                </div>
                <div class="link">
                    <i class="fa-solid fa-gear"></i>
                    <li><a href="#settings" onclick="showSettings()">Settings</a></li>
                </div>
                <div class="link">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <li><a href="admin_logout.php" onclick="showLogout()">Logout</a></li>
                </div>
            </ul>
        </div>
        <div class="dashboard">
            <header>
                <nav class="navbar">
                    <h1>Dashboard</h1>
                    <div class="profile-menu" id="profile-menu">
                        <?php echo $_SESSION['email']?>
                        <img src="Profile.png" alt="Profile Picture" class="profile-icon" onclick="toggleProfileDropdown()">
                        
                    </div>
                </nav>
            </header>
            <div class="welcome-section box">
                <h2>Welcome to the admin panel,  <?php echo $_SESSION['admin_name']?>!</h2> 
            </div>
            <div class="user-section" id="user-section">
                <div class="stats">
                    <i class="fa-solid fa-users"></i>
                    <div id="user-count">
                        <h3><?php echo $totalUsers; ?></h3>
                        <h3>Users</h3>
                    </div>
                </div>
                <div class="users" id="users">
                    <div class="user-search">
                        <h2>User Details</h2>
                        <form action="" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];}?>" placeholder="Search Email...">
                                <button type="submit" class="search-btn">Search</button>
                            </div>
                        </form>
                    </div>    
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Verification Code</th>
                                <th>Email Verified At</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $conn = new mysqli("localhost", "root", "", "budgettracker");
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            
                            if (isset($_GET['search']) && !empty($_GET['search']))
                            {
                                $filtervalues = $_GET['search'];
                                $sql = "SELECT id, Name, Email, Verification_code, email_verified_at FROM UserDetails WHERE email LIKE '%$filtervalues%'";
                                $result = $conn->query($sql);
                            }
                            else{
                                $query = "SELECT id, Name, Email, Verification_code, email_verified_at FROM UserDetails";
                                $result = $conn->query($query);
                            }

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                    <td>{$row["id"]}</td>
                                    <td>{$row["Name"]}</td>
                                    <td>{$row["Email"]}</td>
                                    <td>{$row["Verification_code"]}</td>
                                    <td>{$row["email_verified_at"]}</td>
                                    <td><a href='deleteuser.php?uid=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No users found</td></tr>";
                            }
                        
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
        </div>

        <div class="messages" id="messages" style="display:none;">
            <h2>Contact Messages</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Message</th>
                        <th>Time</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "budgettracker");
                    
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    
                    $query = "SELECT id, name, message, timestamp FROM ContactMessages ORDER BY timestamp DESC";
                    $result = $conn->query($query);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                            <td>{$row["name"]}</td>
                            <td>{$row["message"]}</td>
                            <td>{$row["timestamp"]}</td>
                            <td><a href='deletemessage.php?mid=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this message?\")'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No messages found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
    
</body>
</html>

