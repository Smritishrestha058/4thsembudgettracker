<?php
session_start();
// Database connection
$conn = new mysqli("localhost", "root", "", "budgettracker");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize totals
$totalSpent = 0;
$totalbudget= 0;


// Fetch total income
$stmt = $conn->prepare("SELECT Total_Budget FROM BudgetDetails WHERE id = ? ORDER BY date_added DESC LIMIT 1");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $totalbudget = $row["Total_Budget"];
    // $income = $row['total_income'] ?? 0;
}

// Fetch total expense
$stmt = $conn->prepare("SELECT SUM(Amount) AS total_expense FROM TransactionDetails WHERE Entry_Type = 'expense' AND id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$totalSpent = 0;
if ($row = $result->fetch_assoc()) {
    $totalSpent = $row['total_expense'] ?? 0;
}
// Calculate balance
$remainingBudget = $totalbudget - $totalSpent;

echo json_encode(['remainingBudget' => $remainingBudget,'totalBudget' => $totalbudget, 'totalSpent' => $totalSpent]);

// $balance = $income - $expense;

$conn->close();
?>

