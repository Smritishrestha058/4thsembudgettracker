<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "budgettracker");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize totals
$income = 0;
$expense = 0;
$balance = 0;

// Fetch total income
$stmt = $conn->prepare("SELECT SUM(Amount) AS total_income FROM TransactionDetails WHERE Entry_Type = 'income' AND  id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $income = $row['total_income'] ?? 0;
}

// Fetch total expense
$stmt = $conn->prepare("SELECT SUM(Amount) AS total_expense FROM TransactionDetails WHERE Entry_Type = 'expense' AND id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $expense = $row['total_expense'] ?? 0;
}
// Calculate balance
$balance = $income - $expense;

$conn->close();
?>
