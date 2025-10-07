<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "budgettracker");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$type = $_POST['type'];
$amount = floatval($_POST['amount']);

// Insert transaction
$sql = "INSERT INTO TransactionDetails (type, amount) VALUES ('$type', $amount)";
if ($conn->query($sql) === TRUE) {
    // Calculate updated totals
    $income = $conn->query("SELECT SUM(amount) AS total FROM TransactionDetails WHERE type = 'income'")->fetch_assoc()['total'] ?? 0;
    $expense = $conn->query("SELECT SUM(amount) AS total FROM TransactionDetails WHERE type = 'expense'")->fetch_assoc()['total'] ?? 0;
    $balance = $income - $expense;

    // Return updated values as JSON
    echo json_encode(['success' => true, 'income' => $income, 'expense' => $expense, 'balance' => $balance]);
} else {
    echo json_encode(['success' => false, 'message' => "Error: " . $conn->error]);
}

$conn->close();
?>
