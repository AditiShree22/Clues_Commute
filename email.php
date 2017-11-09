<?php
require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();                            // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                     // Enable SMTP authentication
$mail->Username = 'shopclueskhan007';          // SMTP username
$mail->Password = 'shopclues0408'; // SMTP password
$mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                          // TCP port to connect to

$mail->setFrom('shopclueskhan007@gmail.com', 'CluesCommute');
$mail->addReplyTo('shopclueskhan007@gmail.com', 'CluesCommute');
$mail->addAddress('akansharathore6@gmail.com');   // Add a recipient

$mail->isHTML(true);  // Set email format to HTML

$bodyContent = '<h1>Email verification</h1>';
$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>CluesCommute</b></p>';

$mail->Subject = 'Email from Localhost by CluesCommute';
$mail->Body    = $bodyContent;

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>
