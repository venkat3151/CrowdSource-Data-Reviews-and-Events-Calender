<?php
include "model/studentModel.php"; 
require 'model/PHPMailerAutoload.php';
session_start();
$UBIT=$_GET['UBIT'];
echo $UBIT;
if($_GET['forget']==true):{
	
	$forget=$csdr->forgetPassword($UBIT);
    if($forget!=null)
    {
        $mail = new PHPMailer;
        // $mail->SMTPDebug = 2;
        $mail->isSMTP();     
        $mail->Port = 587;                                 // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'ubspectrumuser@gmail.com';                 // SMTP username
        $mail->Password = 'Spectrum2019!';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $mail->From = 'ubspectrumuser@gmail.com';
        $mail->FromName = 'Ub Spectrum';
        $mail->addAddress(''.$ubitmail);     
        $mail->WordWrap = 50;     
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Temporary Token';
        $mail->Body    = 'Here is your temporary token:  '.$forget.'.<br/>This will be your permanent token only when a specific dataset is assigned to you.<br/><br/>.You may have to request a new token if the session of this token expires. ';
                        
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      
        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            

        }

    }
    

}


?>
