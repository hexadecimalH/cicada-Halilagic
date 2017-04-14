<?php 
require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
ob_start();
    $message = '';
    $mail = new PHPMailer;

    $mail->SMTPDebug = 3;                                 // Enable verbose debug output
    $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'contactformhcg@gmail.com';         // SMTP username
    $mail->Password = 'passwordpassword';                 // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom( $_POST['senderEmail'], $_POST['name']);
    $mail->addAddress('info@hcg.rs', 'CEO Halilagic');     // Add a recipient
    $mail->addReplyTo( $_POST['senderEmail'],$_POST['name']);

    $mail->isHTML(true);                                  // Set email format to HTML
    $content = '<p style="text-align:center">'.htmlentities($_POST['content']).'</p><br/><p> This mail has been sent from hcg.rs contact form</p>';
    $mail->Subject = $_POST['subject'];
    $mail->Body    = $content;
    $mail->AltBody = htmlentities($_POST['content']);
    if(!$mail->send()) {
        $message .= "We are sorry! There must be some technical issues, please try contacting us again or directly to our e-mail address <b>info@hcg.rs</b>";
    } else {
        $message .='Your e-mail has been succesfully sent. We will contact you as soon as possible. ';
    }
    ob_end_clean(); 
    header('Location: index.php?message='.urlencode($message));

    //die() might work too
    exit();


?>