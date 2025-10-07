
<?php
session_start(); // Start the session

// Database connection
$conn = new mysqli("localhost", "root", "", "budgettracker");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the statement
$stmt = $conn->prepare("SELECT DATE(Date) AS Date, Entry_Type, SUM(Amount) AS Total 
FROM TransactionDetails 
WHERE id = ? AND WEEK(Date) = WEEK(CURDATE()) AND YEAR(Date) = YEAR(CURDATE())
GROUP BY DATE(Date), Entry_Type");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $stmt->error);
}

$data = []; // Initialize an array to store the results
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Set header for JSON response
header('Content-Type: application/json');
echo json_encode($data);

$stmt->close(); // Close the statement
$conn->close(); // Close the connection
?>