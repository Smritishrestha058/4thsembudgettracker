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

// $sql = "DROP TABLE IF EXISTS TransactionDetails";
// $conn->query($sql);

// sql to create table
$sql = "CREATE TABLE TransactionDetails (
Transaction_id INT(6) AUTO_INCREMENT PRIMARY KEY,
id INT(6),
Date DATE,
Amount INT NOT NULL,
Category VARCHAR(50),
Entry_Type VARCHAR(50),
Description VARCHAR(255),
FOREIGN KEY (id) REFERENCES UserDetails(id) ON DELETE CASCADE
)";



if ($conn->query($sql) === TRUE){
    echo "Table TransactionDetails created successfully";
} else{
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>