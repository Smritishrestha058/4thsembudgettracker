<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "budgettracker";

// Create Connection
$conn = new mysqli($servername, $username, $password, $database);

//Check connection
if ($conn->connect_error) {
    die("Connection failed: ");
}
else{
    echo "Connection is sucessful";
}
$conn->close();
?>