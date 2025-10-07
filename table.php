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

// Create UserDetails Table
$sql = "
    CREATE TABLE UserDetail (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL
    )
";

if ($conn->query($sql) === TRUE) {
    echo "Table UserDetail created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Insert Data into UserDetails Table
$sql = "
    INSERT INTO UserDetail (username, email)
    VALUES ('john_doe', 'john.doe@example.com')
";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted into UserDetail table successfully";
} else {
    echo "Error inserting data: " . $conn->error;
}

// Create BudgetDetails Table
$sql = "
    CREATE TABLE BudgetDetail (
        BudgetID INT AUTO_INCREMENT PRIMARY KEY,
        id INT(6),
        Total_Budget VARCHAR(50) NOT NULL,
        Start_Date DATETIME,
        Reminder_Threshold INT,
        Saving_goal INT,
        FOREIGN KEY (id) REFERENCES UserDetail(id)
    )
";

if ($conn->query($sql) === TRUE) {
    echo "Table BudgetDetail created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Insert Data into BudgetDetails Table
$sql = "
    INSERT INTO BudgetDetail (id, Total_Budget, Start_Date, Reminder_Threshold, Saving_goal)
    VALUES (1, '1000.00', '2022-01-01', 500, 200)
";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted into BudgetDetail table successfully";
} else {
    echo "Error inserting data: " . $conn->error;
}

// Close Connection
$conn->close();

?>