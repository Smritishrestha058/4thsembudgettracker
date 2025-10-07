<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="contact" id="contact">
        <div class="contact_form" id="contact_form">
          <h1>Contact Us</h1>
          <form action="" method="post">
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
    $mail->Password   = 'wuhy oqej smfk jmhx';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('Smritishrestha058@gmail.com', 'Contact form');
    $mail->addAddress('Smritishrestha058@gmail.com', 'Contact form');     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Test Contact form';
    $mail->Body    = "Sender Name - $name <br> Sender Email - $email <br> Message - $msg";

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

}
?>