<?php
require("mailer/class.phpmailer.php");



    
$mail = new PHPMailer();
$mail->SetLanguage("en", "./");
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = "localhost"; // SMTP server
$mail->Username = "dominavi";
$mail->Password = "p@G3uiAT";

    
$mail->From = "admin@pageui.com";
$mail->FromName   = "PageUI Admin";

$mail->AddAddress("shpoffo@gmail.com", "Zak");

    
$mail->Subject = "2 mailing";
$mail->Body = "confirb !";
$mail->WordWrap = 50;

    
if(!$mail->Send())
{
   echo "Message was not sent";
   echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
   echo "Message has been sent";
}

    
?>