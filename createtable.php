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

// sql to create table
$sql = "CREATE TABLE UserDetails (
id INT(6) AUTO_INCREMENT PRIMARY KEY,
Name VARCHAR(50) NOT NULL,
Email VARCHAR(50) UNIQUE,
Password VARCHAR(255),
Verification_code INT(6),
email_verified_at DATETIME
)";

if ($conn->query($sql) === TRUE){
    echo "Table UserDetails created successfully";
} else{
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>