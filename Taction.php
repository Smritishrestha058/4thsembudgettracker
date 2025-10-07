<?php
    session_start();

    $id = $_SESSION["id"];
    $date = $_POST["date"];
    $amount = $_POST["amount"];
    $category = $_POST["category"];
    $entry_type = $_POST["entry-type"];
    $description = $_POST["notes"];
    
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

    $income = 0;
    $expense = 0;
    $balance = 0;

    $sql = "INSERT INTO TransactionDetails2 (id, Date, Amount, Category, Entry_Type, Description)
    VALUES ('$id', '$date', '$amount', '$category', '$entry_type', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "<script> alert('Data saved successfully'); </script>";
        if ($entryType == 'income') {
            $income += $amount; // Update income
        } else {
            $expense += $amount; // Update expense
        }

        // Calculate balance
        $balance = $income - $expense;
header("Location: home.php");
        // $income = $conn->query("SELECT SUM(Amount) AS total FROM TransactionDetails2 WHERE Entry_Type = 'income'")->fetch_assoc()['total'] ?? 0;
        // $expense = $conn->query("SELECT SUM(Amount) AS total FROM TransactionDetails2 WHERE Entry_Type = 'expense'")->fetch_assoc()['total'] ?? 0;
        // $balance = $income - $expense;
        // // header("Location: home.php");
        // // exit(); // Stops script execution after redirect
        // echo json_encode(['success' => true, 'income' => $income, 'expense' => $expense, 'balance' => $balance]);
    //     echo "<script>
    // document.getElementById('income').textContent = $income;
    // document.getElementById('expense').textContent = $expense;
    // document.getElementById('balance').textContent = $balance;
    // </script>";

        // exit();
    } else {
        echo json_encode(['success' => false, 'message' => "Error: " . $conn->error]);
    }
    // } else {
    //     echo "Error: " . $sql . "<br>" . $conn->error;
    // }
    
$conn->close();
?>

<!-- if ($conn->query($sql) === TRUE) {
    // Calculate updated totals
    $income = $conn->query("SELECT SUM(amount) AS total FROM TransactionDetails WHERE type = 'income'")->fetch_assoc()['total'] ?? 0;
    $expense = $conn->query("SELECT SUM(amount) AS total FROM TransactionDetails WHERE type = 'expense'")->fetch_assoc()['total'] ?? 0;
    $balance = $income - $expense;

    // Return updated values as JSON
    echo json_encode(['success' => true, 'income' => $income, 'expense' => $expense, 'balance' => $balance]);
} else {
    echo json_encode(['success' => false, 'message' => "Error: " . $conn->error]);
} -->