<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

require 'includes/init.php';

$email = '';
$subject = '';
$message = '';
$sent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $errors = [];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = 'Please enter a valid email address';
    }

    if ($subject == '') {
        $errors[] = 'Please enter a subject';
    }

    if ($message == '') {
        $errors[] = 'Please enter a message';
    }

    if (empty($errors)) {

        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPDebug = 2;
            $mail->STMPAuth = true;
            $mail->Username = 'PHPMailer';
            $mail->Password = 'ozzljrhtsnzlrlkec';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tongshi777@gmail.com');
            $mail->addAddress('tan3@ualberta.ca');
            // $mial->addReplyTo($email);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();

            $sent = true;

        } catch (Exception $e) {

            $errors[] = $mail->ErrorInfo;

        }
    }

}

?>

<?php require 'includes/header.php';?>

<div class="container">

    <div class="row row-header">
        <h2>Contact</h2>
    </div>

    <?php if ($sent): ?>
        <div class="row row-content">
            <p>Message sent.</p>
        </div>
    <? else: ?>

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?=$e;?></li>
                <?php endforeach;?>
            </ul>
        <?php endif;?>

    <?php endif;?>

    <div class="col-8">
        <form action="" method="post" id="formContact">

            <div class="form-group row">
                <label for="email" class="col-2">Your email</label>
                <div class="col-6">
                    <input type="email" id="email" name="email" placeholder="Your email"
                    class="form-control" value="<?=htmlspecialchars($email);?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="subject" class="col-2">Subject</label>
                <div class="col-6">
                <input id="subject" name="subject" placeholder="Subject" class="form-control"
                    value="<?=htmlspecialchars($subject);?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="message" class="col-2">Message</label>
                <div class="col-6">
                <input id="message" name="message" placeholder="Message" class="form-control"
                    value="<?=htmlspecialchars($message);?>">
                </div>
            </div>

            <div class="offset-2"><button class="btn btn-primary">Send</button></div>

        </form>
    </div>

</div>
<?php require 'includes/footer.php';?>