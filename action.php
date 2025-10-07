<?php
    session_start();

    $id = $_SESSION["id"];
    $totalbudget = $_POST["total-budget"];
    $startdate = $_POST["start-date"];
    $reminder_threshold = $_POST["reminder-threshold"];
    $saving_goal = $_POST["saving-goal"];

    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "budgettracker";

    //Create Connection
    $conn = new mysqli($servername, $username, $db_password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $user_id = $conn->insert_id;
    $sql = "INSERT INTO BudgetDetails (id, Total_Budget, Start_Date, Reminder_Threshold, Saving_goal, date_added)
    VALUES ('$id', '$totalbudget', '$startdate', '$reminder_threshold', '$saving_goal', NOW())";

    if ($conn->query($sql) === TRUE) {

        // Redirect to index.html after successful insertion
        header("Location: home.php");
        echo "<script> alert('Data saved successfully'); </script>";
        exit(); // Stops script execution after redirect
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
$conn->close();
?>

