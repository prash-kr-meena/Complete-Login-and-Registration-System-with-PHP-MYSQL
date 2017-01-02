<?php
require 'class.phpmailer.php';
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = 'smtp';
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';

$mail->Host = 'ssl://smtp.gmail.com';
$mail->Port = 587;

//auth information
$mail->Username = "prashantkumarpk044@gmail.com";
$mail->Password = "9013157432";

$mail->IsHTML(true);
$mail->SingleTo = true;

//Sender Info
$mail->From = "Authentication.com";
$mail->FromName = "User Authentication";

