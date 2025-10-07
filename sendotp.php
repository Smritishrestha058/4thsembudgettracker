<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if (isset($_POST["forget-button"])) {
    $email = $_POST["email"];

    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "budgettracker";

    $conn = new mysqli($servername, $username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $check_email_sql = "SELECT Email FROM UserDetails WHERE Email = ?";
    $stmt = $conn->prepare($check_email_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "<script>alert('This email doesn't exist. Please try again.');</script>";
        echo "<script>window.location.href = 'nav.html';</script>";
        exit();
    } else {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'Smritishrestha058@gmail.com';
            $mail->Password = 'tsai vwjh zxpo kobj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('Smritishrestha058@gmail.com', 'Budget Tracker');
            $mail->addAddress($email);
            $mail->isHTML(true);

            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            $mail->Subject = 'Email Verification';
            $mail->Body = "<p>Your verification code is: <b style='font-size: 30px;'>$verification_code</b></p>";
            $mail->send();

            $sql = "UPDATE UserDetails SET Verification_code = ?, email_verified_at = NULL WHERE Email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $verification_code, $email);
            $stmt->execute();

            echo "<script>alert('Verification code sent to your email.');</script>";
            echo "<script>window.location.href='verify-otp.php?email=$email';</script>";
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    $stmt->close();
    $conn->close();
}
?>
