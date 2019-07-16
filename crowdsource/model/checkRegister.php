<?php
include "studentModel.php";
require 'PHPMailerAutoload.php';
	session_start();
	$_SESSION['ubit_name']=$_POST['ubit_name'];
   
    $_SESSION['first_name']=$_POST['first_name'];
   
    $_SESSION['last_name']=$_POST['last_name'];
   
    $_SESSION['password']=$_POST['password'];
    $password=$_SESSION['password'];
	$_SESSION['password_confirmation']=$_POST['password_confirmation'];
    $password_confirmation=$_SESSION['password_confirmation'];
    if($password==$password_confirmation){
        $ubit_name=$_SESSION['ubit_name'];
        $ubitmail=$ubit_name.'@buffalo.edu';
        $Generator = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $token= substr(str_shuffle($Generator),0,5);
        $_SESSION['token']=$token;
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

        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'Here is your temporary token: '.$token.'.<br/>This will be your permanent token only when a specific dataset is assigned to you.<br/><br/>.You may have to request a new token if the session of this token expires. ';
                        
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      
        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            // $sql2=("DELETE FROM tbl_student_signup where ubitname = '$ubit_name' and dataset_id='$did'");
            // mysqli_query($conn,$sql2);

        }
        else{
            echo "<script>window.addEventListener('load', 
            function() { 
                $('#myModal').modal('show');
            }, false);</script>" ;
        }
       
    }else{
        header("Location: ../register.php?passwordsNotMatch=true");
    }

    if(isset($_GET['save'])){
            if($_SESSION['token']==$_GET['token']){
                $token=$_SESSION['token'];
                $ubit_name=$_SESSION['ubit_name'];
                $first_name=$_SESSION['first_name'];
                $last_name=$_SESSION['last_name'];
                $password=$_SESSION['password'];
                $obj= new StudentModel();
                $result=$obj->registerUser($ubit_name,$first_name,$last_name,$password,$token);
                if($result==1){
                    header("Location: ../index.php");
                }else{
                    echo "Please enter correct token";
                }
            }
    }


?>