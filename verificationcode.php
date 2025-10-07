<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if (isset($_POST["signup"])) {
    $name = $_POST["Name"];
    $email = $_POST["Email"];
    $password = $_POST["Password"];

    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "budgettracker";

    // Create connection
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists
    $check_email_sql = "SELECT Email FROM UserDetails WHERE Email = ?";
    $stmt = $conn->prepare($check_email_sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exists
        echo "<script>alert('Email already exists. Please use a different email.');</script>";
        echo "<script>window.location.href = 'nav.html';</script>"; // Redirect back to signup page
        exit();
    } else {
        // Email does not exist, proceed with sending verification code
        $mail = new PHPMailer(true);

        try {
            // Send using SMTP
            $mail->isSMTP();

            // Set the SMTP server to send through
            $mail->Host = 'smtp.gmail.com';

            // Enable SMTP authentication
            $mail->SMTPAuth = true;

            // SMTP username
            $mail->Username = 'Smritishrestha058@gmail.com';

            // SMTP password
            $mail->Password = 'tsai vwjh zxpo kobj';

            // Enable TLS encryption
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // TCP port to connect to
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('Smritishrestha058@gmail.com', 'Budget Tracker');

            // Add a recipient
            $mail->addAddress($email, $name);

            // Set email format to HTML
            $mail->isHTML(true);

            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

            $mail->Subject = 'Email verification';
            $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

            $mail->send();

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert into users table
            $sql = "INSERT INTO UserDetails (Name, Email, Password, Verification_code, email_verified_at) 
                    VALUES (?, ?, ?, ?, NULL)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $verification_code);
            $stmt->execute();

            echo "<script>alert('Verification code sent to your email.');</script>";
            header("Location: email-verification.php?email=" . $email);
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>