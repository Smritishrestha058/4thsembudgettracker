<?php
// session_start();

$login = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['Email'] ?? '';
    $password = $_POST['Password'] ?? '';

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

    // Prepare and execute query to retrieve the hashed password and email verification status
    $sql = "SELECT id, Name, Password, email_verified_at, is_admin FROM UserDetails WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($idfromDB, $namefromDB, $hashedPasswordFromDB, $emailVerifiedAt, $isadminfromDB);
        $stmt->fetch();

        // Check if the email is verified
        if (is_null($emailVerifiedAt)) {
            $showError = "Please verify your email before logging in.";
        } else {
            // Verify the password
            if (password_verify($password, $hashedPasswordFromDB)) {
                $login = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $namefromDB; // Store email as the username or use another identifier
                $_SESSION['id'] = $idfromDB;
                $_SESSION['is_admin'] = $isadminfromDB;
                
                if ($isadminfromDB == 1) {
                    header("Location: admin/dashboard.php"); // Redirect to admin page
                } else {
                    header("Location: home.php"); // Redirect to user page
                }
                if ($isadminfromDB == 1) {
                    $_SESSION['admin_loggedin'] = true;
                    $_SESSION['admin_name'] = $namefromDB;
                    $_SESSION['admin_id'] = $idfromDB;
                    $_SESSION['email'] = $email;
                    header("Location: admin/dashboard.php"); // Redirect to admin dashboard
                } else {
                    $_SESSION['user_loggedin'] = true;
                    $_SESSION['user_name'] = $namefromDB;
                    $_SESSION['user_id'] = $idfromDB;
                    $_SESSION['email'] = $email;
                    header("Location: home.php"); // Redirect to user home page
                }
                exit;
            } else {
                $showError = "Invalid Credentials";  
            }
        }
    } else {
        $showError = "No user found with this email.";
    }

    $stmt->close();
    $conn->close();
}

// Display error if there is one
if ($showError) {
    echo "<script>alert('$showError'); setTimeout(() => { window.history.back(); }, 1000);</script>";
    exit;
}

?>
