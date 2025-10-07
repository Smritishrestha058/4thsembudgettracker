<?php
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "budgettracker";

// Create Connection
$conn = new mysqli($servername, $username, $db_password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch values from GET
$dt = isset($_GET['dt']) ? trim($_GET['dt']) : '';
$cat = isset($_GET['cat']) ? $_GET['cat'] : '';
$et = isset($_GET['et']) ? $_GET['et'] : '';
$amt = isset($_GET['amt']) ? $_GET['amt'] : '';
$tid = isset($_GET['tid']) ? $_GET['tid'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <title>Update Transaction</title>
    <style>
         .expanded-form {
             width: 100%;
             max-width: 500px;
             margin: 100px auto;
             background-color: #fafafc;
             border-radius: 20px;
             box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
             padding: 30px;
             z-index: 100;
             /* transition: right 0.5s ease-in-out;
             overflow-y: auto; */
            }
            
            /* When the form is opened */
            .expanded-form.open {
                right: 0; /* Slide in from right */
            }
            
            .closeform {
                font-size: 30px; /* Make the close button large */
                background: none;
                border: none;
                cursor: pointer;
                color: #333; /* Color of the close button */
                z-index: 200; /* Ensure it stays on top */
                text-decoration: none;
            }
            
            /* Additional form styling */
            .input-group {
                margin-bottom: 20px;
            }
            
            input, 
            select, 
            textarea {
                width: 100%;
                padding: 10px;
                font-size: 14px;
                border: 1px solid #ddd;
                border-radius: 10px;
                margin-top: 5px;
                box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
            }
            
            .form-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }
            .form-title {
                font-size: 24px;
                color: #333;
            }
            #transactionForm .submit-btn {
                width: 100%;
                background-color: #ff6f61;
                color: #fff;
                font-size: 1rem;
                font-weight: 600;
                border: none;
                border-radius: 5px;
                padding: 12px;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            
            #transactionForm .submit-btn:hover {
                background-color:#db3729;
            }
            
            </style>
</head>
<body>
<div id="transactionForm" class="expanded-form">
    <form id="addform" action="" method="get">
        <div class="form-header">
            <h1 class="form-title">Update Transaction</h1>
            <a href="javascript:void(0)" class="closeform" onclick="closeadd()">×</a>
        </div>
        <input type="hidden" name="submitted" value="true">
        <input type="hidden" name="tid" value="<?php echo htmlspecialchars($tid); ?>">
        <div class="input-group">
            <label for="date">Date</label>
            <input class="form-control" type="date" name="dt" id="date" value="<?php echo htmlspecialchars($dt); ?>" required>
        </div>
        <div class="input-group">
            <label for="category">Category</label>
            <input type="text" name="cat" id="category" value="<?php echo htmlspecialchars($cat); ?>" required>
        </div>
        <div class="input-group">
            <label for="entry-type">Entry Type</label>
            <select id="entry-type" name="et" required>
                <option value="income" <?php echo $et == "income" ? "selected" : ""; ?>>Income</option>
                <option value="expense" <?php echo $et == "expense" ? "selected" : ""; ?>>Expense</option>
            </select>
        </div>
        <div class="input-group">
            <label for="entry-amount">Amount</label>
            <input type="number" name="amt" id="entry-amount" value="<?php echo htmlspecialchars($amt); ?>" required>
        </div>
        <button id="btn" class="submit-btn" type="submit">Update</button>
    </form>
</div>



<?php
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['submitted']) && $_GET['submitted'] === "true") {
    $date = str_replace('T', ' ', $_GET['dt']);
    $category = $_GET['cat'];
    $entry_type = $_GET['et'];
    $amount = $_GET['amt'];
    $transaction_id = $_GET['tid'];

    $stmt = $conn->prepare("UPDATE TransactionDetails SET Date = ?, Category = ?, Entry_Type = ?, Amount = ? WHERE Transaction_id = ?");
    $stmt->bind_param("ssssi", $date, $category, $entry_type, $amount, $transaction_id);

    if ($stmt->execute()) {
        // echo "Transaction updated successfully.";
        echo "<script> alert('Transaction updated successfully.'); </script>";
        echo "<script>window.location.href = 'home.php';</script>";
    } else {
        echo "Error updating transaction: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
</body>
</html>
