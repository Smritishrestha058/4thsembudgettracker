<?php

// Configuration
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'budgettracker';

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$username = $_POST['username'];
$email = $_POST['email'];
$total_budget = $_POST['total_budget'];
$start_date = $_POST['start_date'];
$reminder_threshold = $_POST['reminder_threshold'];
$saving_goal = $_POST['saving_goal'];

// Insert data into the UserDetails table
$sql = "
    INSERT INTO UserDetail (username, email)
    VALUES ('$username', '$email')
";

if ($conn->query($sql) === TRUE) {
    $user_id = $conn->insert_id;
    // Check if the user_id exists in the UserDetails table
    $sql = "SELECT * FROM UserDetail WHERE id = '$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Insert data into the BudgetDetails table
        $sql = "
            INSERT INTO BudgetDetail (id, Total_Budget, Start_Date, Reminder_Threshold, Saving_goal)
            VALUES ('$user_id', '$total_budget', '$start_date', '$reminder_threshold', '$saving_goal')
        ";
        if ($conn->query($sql) === TRUE) {
            echo "Data inserted successfully";
        } else {
            echo "Error inserting data: " . $conn->error;
        }
    } else {
        echo "Error: User ID does not exist in the UserDetails table.";
    }
} else {
    echo "Error inserting data: " . $conn->error;
}

// Close Connection
$conn->close();

?>