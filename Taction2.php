<?php
session_start();

$servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "budgettracker";
// Database connection
$conn = new mysqli("localhost", "root", "", "budgettracker");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_SESSION["id"];
$date = $_POST["date"];
$amount = $_POST["amount"];
$category = $_POST["category"];
$entry_type = $_POST["entry-type"];
$description = $_POST["notes"];

// Initialize variables
$income = 0;
$expense = 0;
$balance = 0;

// Insert transaction into database
$sql = "INSERT INTO TransactionDetails (id, Date, Amount, Category, Entry_Type, Description)
VALUES ('$id', '$date', '$amount', '$category', '$entry_type', '$description')";

if ($conn->query($sql) === TRUE) {
    // Update totals using SQL queries
    $income = $conn->query("SELECT SUM(Amount) AS total FROM TransactionDetails WHERE Entry_Type = 'income'")->fetch_assoc()['total'] ?? 0;
    $expense = $conn->query("SELECT SUM(Amount) AS total FROM TransactionDetails WHERE Entry_Type = 'expense'")->fetch_assoc()['total'] ?? 0;
    $balance = $income - $expense;

    // Redirect to home with updated totals
    header("Location: home.php?income=$income&expense=$expense&balance=$balance");
    exit(); // Stop further execution after redirection
} else {
    echo "<script> alert('Error: " . $conn->error . "'); </script>";
}
$conn->close();
?>
