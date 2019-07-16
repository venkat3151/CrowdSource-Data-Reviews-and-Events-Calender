<?php
    if(isset($_SESSION)){
        session_destroy();
    }
    session_start();
    include "model/studentModel.php";
    $obj=new StudentModel();
    $datasets=$obj->displayAllDatasets();
    
    if(sizeof($datasets)==0){
        
    }
   else{
        $_SESSION['datasets']=$datasets;
    }    
   
?>

<?php
//include "model/studentModel.php";
require 'model/PHPMailerAutoload.php';
    //session_start();
    if(isset($_POST['Register'])){
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
        header("Location: register.php?passwordsNotMatch=true");
    }

  

    }elseif(isset($_GET['save'])){
        if($_SESSION['token']==$_GET['token']){
            $token=$_SESSION['token'];
            $ubit_name=$_SESSION['ubit_name'];
            $first_name=$_SESSION['first_name'];
            $last_name=$_SESSION['last_name'];
            $password=$_SESSION['password'];
            $obj= new StudentModel();
            $result=$obj->registerUser($ubit_name,$first_name,$last_name,$password,$token);
            if($result==1){
                header("Location: /ubspectrum/crowdsource/index.php");
            }else{
                echo "Please enter correct token";
            }
        }
}
?>
<html> 
    <body>
         
 


    <?php include "studentHeader.php";?>
    <div class="heading container">
  
    <div class="modal" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">                 
                                <h4 class="modal-title">Please enter the token send to your mail.</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="GET" class="form-group" action="">
                                <div class="modal-body">
                                 <!--    <input type="text" name="token" placeholder="Valid Token">                     
                                    <input type="submit" class="btn btn-primary" name="save" value="Submit"/> -->
                                


                                 <div class="row mb-3">
                                <div class="col-xs-12 col-md-8">
                                      <input type="text" name="token" class="form-control" maxlength="64" placeholder="Token" required>     
                                </div>
                                <input type="submit" class="btn btn-primary" name="save" value="Submit"/>
                            </div></div>
                            </form>

                        </div>
                    </div>
                </div>
           
        <div class=" col-lg-12 ">
           
             <div class="col-sm-12 col-md-12 col-lg-4 wow pulse center" data-wow-duration="2s"  id="bujjiForm">
               
             
                     <div class="description"><h4> Started reviewing a dataset ? Enter your credentials to pick up where you left  ! </h4> </div>
                   <?php if(isset($_GET['passwordsNotMatch']) and ($_GET['passwordsNotMatch']==true)){?>
                        <h5 class="error">Passwords do not match</h5>
                   <?php }?>
                  

                            <form class="form-group" method="POST" action="" enctype="multipart/form-data">
                    <div class="row mb-3">
                            <div class=" col-xs-12 col-md-6 ">
			    					<div class="form-group">
			                <input type="text" name="first_name" id="first_name" class="form-control input-sm" placeholder="First Name">
			    					</div>
			    				</div>
			    				<div class=" col-xs-12 col-md-6 ">
			    					<div class="form-group">
			    						<input type="text" name="last_name" id="last_name" class="form-control input-sm" placeholder="Last Name">
			    					</div>
			    				</div>
			    			</div>

			    			<div class="form-group">
			    				<input type="text" name="ubit_name" id="ubit_name" class="form-control input-sm" placeholder="UBIT Name">
			    			</div>

			    			<div class="row mb-3">
                            <div class=" col-xs-12 col-md-6 ">
			    					<div class="form-group">
			    						<input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password">
			    					</div>
			    				</div>
			    				<div class=" col-xs-12 col-md-6 ">
			    					<div class="form-group">
			    						<input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-sm" placeholder="Confirm Password">
			    					</div>
			    				</div>
			    			</div>
			    			
			    			<input type="submit" value="Register" name="Register" class="btn btn-info btn-block">
			    		
			    

			    			
			    		
			    		</form>
                                </div>
                            
                            
                             </div>
                          
   
</div>

    </body> 
    <script src="/ubspectrum/crowdsource/js/wow.min.js"></script>
    <script>
     new WOW().init();
    </script>
 </html>