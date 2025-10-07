
<?php
header('Content-Type: application/json');
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "budgettracker");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Retrieve 'from' and 'to' dates from GET request
$fromDate = $_GET['from'] ?? null;
$toDate = $_GET['to'] ?? null;

if (!$fromDate || !$toDate) {
    echo json_encode(["error" => "Invalid or missing 'from' and 'to' date parameters."]);
    exit;
}

// SQL query to fetch data based on the date range
$stmt = $conn->prepare("
    SELECT Category, Date, Entry_Type, SUM(Amount) AS Total 
    FROM TransactionDetails 
    WHERE id = ? AND Date BETWEEN ? AND ? 
    GROUP BY Category
");

if (!$stmt) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("iss", $_SESSION['id'], $fromDate, $toDate);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output JSON data
echo json_encode($data);

// Clean up
$stmt->close();
$conn->close();
