<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "budgettracker";

// Get email from the URL
$email = isset($_GET['email']) ? $_GET['email'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted verification code
    $user_code = $_POST['user_code'];

    // Connect to the database
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the stored verification code for the provided email
    $sql = "SELECT id, Name, verification_code, email_verified_at FROM UserDetails WHERE Email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the email is already verified
        if ($row['email_verified_at'] !== NULL) {
            echo "Your email is already verified.";
        } else {
            // Compare the user-entered code with the stored code
            if ($user_code == $row['verification_code']) {
                // Update the email_verified_at column to mark as verified
                $update_sql = "UPDATE UserDetails SET email_verified_at = NOW() WHERE Email = '$email'";
                if ($conn->query($update_sql) === TRUE) {
                    echo "Email verification successful! Please login to continue.";
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['user_name'] = $row['Name'];
                    $_SESSION['email'] = $email;
                    
                    header ("Location: home.php");
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "Invalid verification code. Please try again.";
            }
        }
    } else {
        echo "No user found with this email.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            font-size: 20px;
        }
        .email_verification{
            width: 50%;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input{
            width: 50%;
            height: 40px;
            margin-top: 10px;
            font-size: 30px;


        }
        .btn {
                width: 50%;
                margin-top: 20px;
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
            
            .btn:hover {
                background-color:#db3729;
            }

    </style>
</head>
<body>
    <div class="email_verification">
        <h2>Email Verification</h2>
        <p>Please enter the verification code sent to your email: <?php echo htmlspecialchars($email); ?></p>
        
        <form method="post" action="">
            <label for="user_code">Verification Code:</label><br>
            <input type="text" name="user_code" id="user_code" required><br>
            <button type="submit" class="btn">Verify</button>
        </form>
    </div>
</body>
</html>
