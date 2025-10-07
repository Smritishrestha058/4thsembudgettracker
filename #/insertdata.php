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

$sql = "INSERT INTO Userdetails (Name, Email, Password)
VALUES ('David', 'Davidsmith@example.com', 'Baneshwor')";

if ($conn->query($sql) === TRUE){
    echo "New Record created successfully";
} else{
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>