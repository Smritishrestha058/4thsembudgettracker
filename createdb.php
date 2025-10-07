<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create Connection
$conn = new mysqli($servername, $username, $password);

//Check connection
if ($conn->connect_error) {
    die("Connection failed: ");
}
//Create Database
$sql="CREATE DATABASE budgettracker";
if ($conn->query($sql) === TRUE){
    echo "Database created successfully";
}
else{
    echo "Error creating database: " . $conn->error;
}
$conn->close();
?>
