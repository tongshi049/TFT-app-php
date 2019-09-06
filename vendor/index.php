<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPDebug = 2;
    $mail->STMPAuth = true;
    $mail->Username = 'tshi3';
    $mail->Password = 'Wdmms4hs9ngybs';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('tshi3@ualberta.ca');
    $mail->addAddress('tan3@ualberta.ca');
    $mail->Subject = 'A mail sent from PHP';
    $mail->Body = 'Hello from PHP!';

    $mail->send();

    echo 'Message sent';

} catch (Exception $e) {
    
    echo 'Message not sent: ' . $mail->ErrorInfo;

}
