<?php
require 'class.phpmailer.php';
$mail = new PHPMailer();
$mail->SMTPDebug = 0; // debugging : 0 none ,  1 = errors and messages, 2 = messages only

$mail->SMTPAuth = true;

$mail->SMTPSecure = 'ssl';
$mail->IsSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465;

//auth information
$mail->Username = "prashantkumarpk044@gmail.com";
$mail->Password = "9013157432";

$mail->IsHTML(true);
$mail->SingleTo = true;

//Sender Info
$mail->From = "Authentication.com";
$mail->FromName = "User Authentication";

