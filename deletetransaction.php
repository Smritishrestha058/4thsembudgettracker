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
if (isset($_GET['tid']) && isset($_SESSION['id'])) {
    $transaction_id = $_GET['tid'];
    // $user_id = $_SESSION['id'];

    // Prepare and check the delete statement
    $stmt = $conn->prepare("DELETE FROM TransactionDetails WHERE Transaction_id = ?");
    
    // Check if the prepare() was successful
    if ($stmt === false) {
        die("Error preparing the SQL statement: " . $conn->error);
    }

    // Bind parameters and execute the statement if prepare was successful
    $stmt->bind_param("i", $transaction_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data deleted successfully'); window.location.href='home.php';</script>";
    } else {
        echo "Error deleting transaction: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request";
}

$conn->close();
?>
