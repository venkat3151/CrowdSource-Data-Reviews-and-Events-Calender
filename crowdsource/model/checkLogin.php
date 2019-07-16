<head>
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../css/header.css">
  <script src="../js/jquery.js"></script>
  <script src="../js/popper.js"></script>
  <script src="../js/bootstrap.js"></script>
</head>
<?php
include "studentModel.php";
session_start();
if(isset($_POST['checkLogin']) and $_POST['checkLogin']=='Submit'){
	
	$_SESSION['ubit_name']=$_POST['ubit_name'];
	$ubit_name=$_SESSION['ubit_name'];
	$_SESSION['password']=$_POST['password'];
	$password=$_SESSION['password'];
	$obj= new StudentModel();
	$result=$obj->checkUserLogin($ubit_name,$password);
	if(sizeof($result)==1){
		
		
		header("Location: ../studentHome.php");
		}
		
	else if($result==null){
		header("Location: ../index.php?invalid=true");
	}
	else{
		echo "Working on servers.Try again later";
	}
}elseif(isset($_POST['forgotPassword']) and $_POST['forgotPassword']=='forgotPassword'){
	$ubit_name=$_POST['ubit_name'];
	
	$sm= new StudentModel();
	$res=$sm->checkUser($ubit_name); 
	if(sizeof($res)==1){
		
		$password=$res[0]['user_password'];
		require 'PHPMailerAutoload.php';
	
			
			$ubitmail=$ubit_name.'@buffalo.edu';
			
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
			$mail->Body    = 'Here is your password'.$password;
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		  
			if(!$mail->send()) {
				echo 'Message could not be sent.';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			}
			else{
				echo "<script>window.addEventListener('load', 
            function() { 
                $('#forgotAck').modal('show');
            }, false);</script>" ;
			}






        
    }else{
		echo "<script>window.addEventListener('load', 
		function() { 
			$('#notRegistered').modal('show');
		}, false);</script>" ;
}
}
else{
	echo " Wrong way!";
}
?>

				<div class="modal" id="forgotAck">
                     <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">                 
                                <h4 class="modal-title">An email has been sent to you with your old password.</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="GET" class="form-group" action="">
                                <div class="modal-body">
                                Please login using that !
                            </div></div>
                            </form>

                        </div>
					</div>

					<div class="modal" id="notRegistered">
                     <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">                 
                                <h4 class="modal-title">Our records show that you are not a registered user.</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="GET" class="form-group" action="">
                                <div class="modal-body">
                                Please sign up first !
                            </div></div>
                            </form>

                        </div>
					</div>
					
					<script>
    $('#forgotAck').on('hidden.bs.modal', function () {
  window.location.href="/ubspectrum/crowdsource/index.php";
});

$('#notRegistered').on('hidden.bs.modal', function () {
  window.location.href="/ubspectrum/crowdsource/index.php";
}); 



</script>