<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "budgettracker";

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE BudgetDetails (
    BudgetID INT AUTO_INCREMENT PRIMARY KEY,
    id INT(6),
    Total_Budget VARCHAR(50) NOT NULL,
    Start_Date DATETIME,
    Reminder_Threshold INT,
    Saving_goal INT,
    date_added datetime,
    FOREIGN KEY (id) REFERENCES UserDetails(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE){
    echo "Table BudgetDetails created successfully";
} else{
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>