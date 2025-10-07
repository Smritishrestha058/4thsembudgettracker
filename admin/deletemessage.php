<?php
session_start();
// include 'db_connection.php'; // Ensure this file sets up your $conn variable
    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "budgettracker";

    //Create Connection
    $conn = new mysqli($servername, $username, $db_password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

// Check if transaction ID is provided in URL
if (isset($_GET['mid']) && isset($_SESSION['id'])) {
    $id = $_GET['mid'];
    // $user_id = $_SESSION['id'];

    // Prepare and check the delete statement
    $stmt = $conn->prepare("DELETE FROM contactmessages WHERE id = ?");
    
    // Check if the prepare() was successful
    if ($stmt === false) {
        die("Error preparing the SQL statement: " . $conn->error);
    }

    // Bind parameters and execute the statement if prepare was successful
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data deleted successfully'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request";
}

$conn->close();
?>
