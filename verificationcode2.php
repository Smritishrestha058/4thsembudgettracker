<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
 
    //Load Composer's autoloader
    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
 
    if (isset($_POST["signup"]))
    {
        $name = $_POST["Name"];
        $email = $_POST["Email"];
        $password = $_POST["Password"];

        $servername = "localhost";
        $username = "root";
        $db_password = "";
        $dbname = "budgettracker";
 
        //Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
 
        try {
 
            //Send using SMTP
            $mail->isSMTP();
 
            //Set the SMTP server to send through
            $mail->Host = 'smtp.gmail.com';
 
            //Enable SMTP authentication
            $mail->SMTPAuth = true;
 
            //SMTP username
            $mail->Username = 'Smritishrestha058@gmail.com';
 
            //SMTP password
            $mail->Password = 'wuhy oqej smfk jmhx';
 
            //Enable TLS encryption;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
 
            //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $mail->Port = 587;
 
            //Recipients
            $mail->setFrom('Smritishrestha058@gmail.com', 'Budget Tracker');
 
            //Add a recipient
            $mail->addAddress($email, $name);
 
            //Set email format to HTML
            $mail->isHTML(true);
 
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
 
            $mail->Subject = 'Email verification';
            $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
 
            $mail->send();
            // echo 'Message has been sent';
 
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
 
            //Create Connection
            $conn = new mysqli($servername, $username, $db_password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // insert in users table 
            $sql = "INSERT INTO UserDetails(Name, Email, Password, Verification_code, email_verified_at) 
            VALUES ('$name', '$email', '$hashedPassword', '$verification_code', NULL)";

            mysqli_query($conn, $sql);
            echo "Message has been sent";
            header("Location: email-verification.php?email=" . $email);
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
?>