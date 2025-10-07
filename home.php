<?php
session_start();

include 'calculate_totals.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="css/responsive.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" /> -->
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="navbar" id="navbar">
            <a href="javascript:void(0);" class="togglebutton" onclick="opensidenav()">
                <i class="fa fa-bars"></i>
            </a>
            <div class="logo">
                <img src="images\logo.png" alt="Budget Tracker Logo" onclick="showDashboard()"> <!-- Replace with your logo -->
            </div>
            <ul class="nav-links">
                <li><a href="#Home" onclick="showDashboard()">Home</a></li>
                <li><a href="#overview" onclick="showoverview()">Overview</a></li>
                <li><a href="#transaction" onclick="showtransactions()">Transactions</a></li>
                <li><a href="#contact" onclick="showcontactform()">Contact Us</a></li>
            </ul>
            <div class="profile-menu">
            <?php echo $_SESSION['email']?>
                <img src="images/Profile.png" alt="Profile Picture" class="profile-icon" onclick="toggleProfileDropdown()">
                <div id="profileDropdown" class="dropdown-content">
                    <a href="logout.php" onclick="logout()">Log Out</a> <!-- Logout option -->
                </div>
            </div>
        </nav>
        <div class="sidenav" id="sidenav" style="display: none;">
            <a href="javascript:void(0);" class="icon" onclick="closesidenav()">&times;</a>
            <a href="#">Home</a>
            <a href="#">Overview</a>
            <a href="#">Transactions</a>
            <a href="#">Contact</a>
        </div>
    </header>

    <main>
        <div id="dashboard" class="dashboard">
            <div class="content-wrapper">
                <!-- Enhanced Welcome Section -->
                <div class="welcome-section box">
                    <h2>Welcome,  <?php echo $_SESSION['user_name']?>!</h2>
                    <p>We're glad to see you again! Let’s get started by reviewing your budget and finances.</p>
                    
                </div>

                <!-- Sidebar for quick links or information -->
                <aside class="sidebar">
                    <h2>Tips for Successful Budgeting</h2>
                    <ul id="tips-list">
                        <li>Track every expense to avoid overspending.</li>
                        <li>Set realistic savings goals each month.</li>
                        <li>Allocate funds for emergencies.</li>
                    </ul>
                </aside>
                

                <!-- Summary Section with horizontal balance cards -->
                <div class="summary-section">
                    <div class="balance-card-container">
                        <div class="balance-card">
                            <p class="balance-title">Total Balance</p>
                            <div class="balance-details">
                                <span class="currency">Rs.</span>
                                <span class="remaining_balance" id="balance"><?php echo isset($balance) ? $balance : '0'; ?></span>
                            </div>
                        </div>
                        <div class="balance-card">
                            <p class="balance-title">Expenses</p>
                            <div class="balance-details">
                                <span class="currency">Rs.</span>
                                <span class="expenses" id="expense"><?php echo isset($expense) ? $expense : '0'; ?></span>
                            </div>
                        </div>
                        <div class="balance-card">
                            <p class="balance-title">Income</p>
                            <div class="balance-details">
                                <span class="currency">Rs.</span>
                                <span class="income" id="income"><?php echo isset($income) ? $income : '0'; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Line Chart Section -->
                <div class="linechart-section">
                    <h2 class="section-title">Analytics</h2>
                    <div class="linechart-container">
                        <canvas id="linechart" width="1200" height="400"></canvas>
                    </div>
                </div>
            </div>
        

        <!-- How Budgeting Helps Section -->
        <section class="how-budgeting-helps">
            <h2>How Budgeting Helps You Save</h2>
            <p>Budgeting helps you understand where your money goes, identify areas to cut costs, and increase your savings. Setting clear goals, tracking spending, and regularly reviewing your budget allows you to be more mindful about your financial decisions.</p>
            <p>Start small by setting a monthly budget for essential categories like groceries and utilities. Over time, expand your budget to include savings, entertainment, and other categories to build a solid financial foundation.</p>
        </section>

<!-- Budget Status and Progress Bar -->
<div class="budget-expenses-container">
    <div class="budget-status-container box">
        <h1 class="section-title">Budget Status</h1>
        <div class="budget-summary">
            <div class="total-budget">
                <p class="budget-label">Total Budget:</p>
                <!-- <span id="display-budget" class="budget-amount">Not Set</span> -->
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "budgettracker";
                
                $conn = new mysqli($servername, $username, $password, $dbname);
            
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                // Fetch the budget from the database
                $stmt = $conn->prepare("SELECT Total_Budget FROM BudgetDetails WHERE id = ? ORDER BY date_added DESC LIMIT 1");
                
                // Check if prepare() was successful
                if (!$stmt) {
                    // If prepare() failed, output the error message and exit
                    die("Prepare failed: " . $conn->error);
                }
                
                // Bind parameters and execute if prepare was successful
                $stmt->bind_param("i", $_SESSION['id']);  // Assuming you are using session to store user ID
                $stmt->execute();
                $result = $stmt->get_result();
                
                // Check if budget is set
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $budget = $row["Total_Budget"];  // Get the budget from the result
                    
                    // Check if the budget is set (not empty)
                    if ($budget !== null && $budget !== "") {
                        // If budget is set, display it
                        echo "Rs." . $budget;
                    }
                } else {
                    // If no user is found (shouldn't happen if session is correctly set)
                    echo "Not set";
                }
                
                $stmt->close();
                $conn->close();
                ?>
            </div>
            <div class="remaining-budget">
                <p class="budget-label">Remaining Budget:</p>
                <span id="display-remaining-budget" class="budget-amount"></span>
            </div>
        </div>
        <button id="setbudgetbtn" class="set-budget-btn" onclick="openbudget()">Set Budget</button>
        <div class="progress-container">
            <div class="progress-bar" id="progress-bar">
                <div class="progress" id="progress"></div>
            </div>
            <p id="progress-text">0% of Budget Used</p>
        </div>
    </div>
</div>


        <!-- Recent Transactions List -->
        <div class="transaction-container">
            <div class="transaction-header">
                <h2>Recent Transactions</h2>
                <button id="add-btn" class="add-btn" onclick="openadd()">+ Add New</button>
            </div>
            <div class="transaction">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th colspan="2">Options</th>
                        </tr>
                    </thead>
                    <tbody id="transactionTable">
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "budgettracker";
                        // Assuming $servername, $username, $password, and $dbname are defined elsewhere
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        
                        // Use prepared statements to avoid SQL injection
                        $stmt = $conn->prepare("SELECT Transaction_id, Date, Amount, Category, Entry_type FROM TransactionDetails WHERE id = ? AND MONTH(Date) = MONTH(CURDATE()) AND YEAR(Date) = YEAR(CURDATE()) ");
                        $stmt->bind_param("i", $_SESSION['id']);  // "i" stands for integer type
                        
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>".$row["Date"]."</td>
                                <td>".$row["Category"]."</td>
                                <td>".$row["Entry_type"]."</td>
                                <td>".$row["Amount"]."</td>
                                <td><a href='update.php?tid=".$row["Transaction_id"]." &dt=".$row["Date"]." &cat=".$row["Category"]." &et=".$row["Entry_type"]." &amt=".$row["Amount"]."'>Edit</a></td>
                                <td><a href='deletetransaction.php?tid=" . $row["Transaction_id"] . "' onclick='return confirm(\"Are you sure you want to delete this transaction?\")'>Delete</a></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No transactions found</td></tr>";
                        }
                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <button class="submit-btn" onclick="seeAllTransactions()">See All Transactions</button>
            </div>
        </div>

        <!-- Budget Form -->
        <div id="budgetForm" class="expanded-form">
            <div class="form-header">
                <a href="javascript:void(0)" class="closeform" onclick="closebudget()">×</a>
                <h1 class="form-title">Set Budget</h1>
            </div>
            <form class="budgetform" action="action.php" method="post"> 
                <div class="input-group">
                    <label for="total-budget">Select or Enter Budget</label>
                    <div class="budget-options">
                        <button type="button" class="budget-option" onclick="setBudgetAmount(500)">500</button>
                        <button type="button" class="budget-option" onclick="setBudgetAmount(1000)">1000</button>
                        <button type="button" class="budget-option" onclick="setBudgetAmount(5000)">5000</button>
                        <button type="button" class="budget-option" onclick="setBudgetAmount(10000)">10000</button>
                        
                    </div>
                    <input type="number" id="total-budget" name="total-budget" placeholder="Enter total budget" required>
                </div>
                <div class="input-group">
                    <label for="start-date">Set Start Date</label>
                    <input type="date" id="start-date" name="start-date" required>
                </div>

                <div class="input-group">
                    <label for="reminder-threshold">Set Reminder Threshold:</label>
                    <input type="number" id="reminder-threshold" name="reminder-threshold" placeholder="Enter % threshold (e.g., 80)">
                    <button type="button" class="budget-option" onclick="setReminder()">Set Reminder</button>
                </div>

                <div class="input-group">
                    <label for="saving-goal">Set a Savings Goal:</label>
                    <input type="number" id="saving-goal" name="saving-goal" placeholder="Enter saving goal (e.g., Rs. 5000)">
                </div>

                <div class="input-group">
                    <h3>Budgeting Tip:</h3>
                    <p id="budget-tip">Tip: Consider reducing your entertainment expenses to save more!</p>
                </div>

                <button type="submit" id="budgetbtn" class="set-budget-btn">Ok</button>
            </form>
        </div>

        <!-- Transaction Form -->
        <div id="transactionForm" class="expanded-form">
            <form id="addform" action="Taction2.php" method="post">
                <div class="form-header">
                    <a href="javascript:void(0)" class="closeform" onclick="closeadd()">×</a>
                    <h1 class="form-title">Add a New Transaction</h1>
                </div>
                <div class="input-group">
                    <label for="date">Date</label>
                    <input class="form-control" type="date" name="date" id="date" max="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="input-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" placeholder="Select a category" required>
                        <option value="Khaja">Khaja</option>
                        <option value="Khaja nakhayeko">Khaja nakhayeko</option>
                        <option value="AMC Book">AMC Book</option>
                        <option value="health">Health Care</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="Bus">Bus</option>
                        <option value="bank">bank</option>
                        <option value="salary">Salary</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="entry-type">Entry Type</label>
                    <select id="entry-type" name="entry-type" required>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="entry-amount">Amount</label>
                    <input type="number" name="amount" id="entry-amount" placeholder="0.00" required>
                </div>
                <div class="input-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" cols="20" rows="5" id="entry-description" placeholder="Notes"></textarea>
                </div>
                <button id="add-entry-btn" class="submit-btn" type="submit">Submit</button>
            </form>
        </div>
    </div>

    <div class="overview-container" id="overview-container" style="display:none;">
        <form action="" method="GET" id="filterform">
            <div class="form-group">
                <label>From Date</label>
                <input type="date" name="fromdate" id="fromdate">
            </div>
            <div class="form-group">
                <label>To Date</label>
                <input type="date" name="todate" id="todate">
            </div>
            <div class="form-group">
                <button type="button" id="filterButton">Filter</button>
            </div>
            <div class="form-group">
                <button type="button" id="resetButton">Reset</button>
            </div>
        </form>
        <div class="barchart-container">
            <canvas id="expensesChart" width="400" height="400"></canvas>
            <canvas id="incomeChart" width="400" height="400"></canvas>
        </div>
        <div class="lineChart-container">
            <canvas id="lineChart" width="1200" height="600"></canvas>   
        </div>
    <!-- <canvas id="myChart"></canvas>
    <canvas id="lineChart"></canvas> -->

    </div>

    <div class="transaction-history-container" id="transaction-history-container"  style="display: none;">
            <div class="transaction-header">
                <h2>Transaction History</h2>
            </div>
            <div class="transaction">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th colspan="2">Options</th>
                        </tr>
                    </thead>
                    <tbody id="transactionTable">
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "budgettracker";
                        // Assuming $servername, $username, $password, and $dbname are defined elsewhere
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        
                        // Use prepared statements to avoid SQL injection
                        $stmt = $conn->prepare("SELECT Transaction_id, Date, Amount, Category, Entry_type FROM TransactionDetails WHERE id = ?");
                        $stmt->bind_param("i", $_SESSION['id']);  // "i" stands for integer type
                        
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>".$row["Date"]."</td>
                                <td>".$row["Category"]."</td>
                                <td>".$row["Entry_type"]."</td>
                                <td>".$row["Amount"]."</td>
                                <td><a href='update.php?tid=".$row["Transaction_id"]." &dt=".$row["Date"]." &cat=".$row["Category"]." &et=".$row["Entry_type"]." &amt=".$row["Amount"]."'>Edit</a></td>
                                <td><a href='deletetransaction.php?tid=" . $row["Transaction_id"] . "' onclick='return confirm(\"Are you sure you want to delete this transaction?\")'>Delete</a></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No transactions found</td></tr>";
                        }
                        
                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <button class="submit-btn" onclick="seeAllTransactions()">See All Transactions</button>
            </div>
        </div>
    <div class="contact" id="contact" style="display: none;">
        <div class="contact_form" id="contact_form">
          <h1>Contact Us</h1>
          <form action="admin/contact_process.php" method="post">
            <div class="input-group">
              <input type="text" name="Name" id="Name" placeholder="Name" required>
            </div>
            <div class="input-group">
              <input type="email" name="Email" id="Email" placeholder="Email" required>
            </div>
            <div class="input-group">
              <textarea cols="" rows="5" name="msg" placeholder="Your message here"></textarea>
            </div>
            <input type="submit" class="Send" value="Send" name="Send">
          </form>
        </div>
    </div>
    
    <div class="profile-section" id="profile-section">
        
    </div>

    </main>

    <footer class="footer" id="footer">
        <div class="footer-content">
            <div class="about-section">
                <h3>About Us</h3>
                <p>Budget Tracker is dedicated to helping you manage your finances and reach your financial goals with ease.</p>
            </div>
            <div class="quick-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#Home" onclick="showDashboard()">Home</a></li>
                    <li><a href="#overview" onclick="showoverview()">Overview</a></li>
                    <li><a href="#contact" onclick="showcontactform()">Contact Us</a></li>
                </ul>
            </div>
            <div class="contact-info">
                <h3>Contact Us</h3>
                <p>Email: support@budgettracker.com</p>
                <p>Phone: +91 12345 67890</p>
            </div>
        </div>
        <p>&copy; 2024 Budget Tracker. All Rights Reserved.</p>
    </footer>

    
    <script src="js/script1.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        // $(document).ready( function () {
        //     $('#myTable').DataTable();
        // } );
//         $(document).ready(function () {
//     console.log($('#myTable').length); // Should be 1 if the table exists
//     $('#myTable').DataTable();
// });
    </script>
</body>

</html>

<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['Send'])){
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $msg = $_POST['msg'];

//Load Composer's autoloader
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'Smritishrestha058@gmail.com';                     //SMTP username
    $mail->Password   = 'tsai vwjh zxpo kobj';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('Smritishrestha058@gmail.com', 'Contact form');
    $mail->addAddress('Smritishrestha058@gmail.com', 'Contact form');     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Test Contact form';
    $mail->Body    =  "Sender Name - $name <br> Sender Email - $email <br> Message - $msg";

    $mail->send();
    echo "<div class='success' id='success-message'>Message has been sent</div>";
    echo "<script>
                setTimeout(function() {
                    document.getElementById('success-message').style.display = 'none';
                }, 3000);
              </script>";
} catch (Exception $e) {
    echo "<div class='alert' id='alert-message'>Message couldn't be sent</div>";
    echo "<script>
                setTimeout(function() {
                    document.getElementById('alert-message').style.display = 'none';
                }, 3000);
              </script>";
}
}

?>