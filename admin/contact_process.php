<?php
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// // Include PHPMailer
// require 'vendor/autoload.php';

//Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
 
    //Load Composer's autoloader
    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
// Database connection
$host = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "budgettracker"; // Change if necessary

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['Name']);
    $email = $_POST['Email']; // Not stored, only used for email
    $message = $conn->real_escape_string($_POST['msg']);

    // Insert into database
    $sql = "INSERT INTO ContactMessages (name, message) VALUES ('$name', '$message')";
    if ($conn->query($sql) === TRUE) {
        // Send email to admin using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'Smritishrestha058@gmail.com'; // Replace with your email
            $mail->Password = 'tsai vwjh zxpo kobj'; // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender & recipient
            $mail->setFrom($email, $name);
            $mail->addAddress('Smritishrestha058@gmail.com'); // Replace with admin's email
            $mail->addReplyTo($email, $name);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "New Contact Form Submission from $name";
            $mail->Body = "<p><strong>Name:</strong> $name</p>
                           <p><strong>Email:</strong> $email</p>
                           <p><strong>Message:</strong></p>
                           <p>$message</p>";

            // Send email
            $mail->send();
            echo "Message sent successfully!";
        } catch (Exception $e) {
            echo "Message stored, but email failed: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
