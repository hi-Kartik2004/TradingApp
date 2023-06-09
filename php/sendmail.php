<?php
ob_start(); // Start output buffering
error_reporting(0);

require_once("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);


function sendVerificationMail($receiver)
{
    global $mail;
    $mail->SMTPDebug = 0; // Disable debug output
    $mail->SMTPDebug = false;

    // $baseurl = "http://localhost:3000/?verify=";
    $code = rand(1000, 9999);
    $subject = "Training | Verfication link inside!";
    $_SESSION["verification_code"] = $code;
    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';        //gmail             //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'hi.kartikeyasaini@gmail.com';      //Gmail userid              //SMTP username
        $mail->Password   = smtpPassword;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('hi.kartikeyasaini@gmail.com', 'Trading Arena | Learn to Trade');
        // $mail->addAddress('kud', 'Joe User');     //Add a recipient
        $mail->addAddress($receiver);               //Name is optional

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = 'Your verification Link is for User verifcation is <b>' . baseurl . $code . '</b> and is valid for next 10mins only.';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $result = $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    if ($result) {
        $_SESSION["err"]["err_msg"] = "Verfication mail has been sent to your mail id";
        header("location:../?login");
    }
    return $code;
}


function sendForgotMail($receiver, $password)
{
    global $mail;
    $mail->SMTPDebug = 0; // Disable debug output
    $mail->SMTPDebug = false;

    // $baseurl = "http://localhost:3000/?verify=";
    $code = rand(1000, 9999);
    $subject = "Trading Arena | Verfication link inside!";
    $_SESSION["forgot_code"] = $code;
    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';        //gmail             //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'hi.kartikeyasaini@gmail.com';      //Gmail userid              //SMTP username
        $mail->Password   = smtpPassword;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('hi.kartikeyasaini@gmail.com', 'Trading Arena | Learn to Trade');
        // $mail->addAddress('kud', 'Joe User');     //Add a recipient
        $mail->addAddress($receiver);               //Name is optional

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = 'Your Trading Arena account password is <b>' . $password . '</b> if it was not you who generated this mail, contact support!';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $result = $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    if ($result) {
        $_SESSION["err"]["err_msg"] = "Mail has been sent to your mail id";
        header("location:../?login");
    }
    // return $code;
}

ob_end_clean(); // Clean the output buffer without displaying it



// sendVerificationCode('kudlu2004@gmail.com');
