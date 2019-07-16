<?php
    if(isset($_SESSION)){
        session_destroy();
    }
    session_start();
    include "model/studentModel.php";
    $obj=new StudentModel();
    $datasets=$obj->displayAllDatasets();
?>
<html>
       
       <body>
       <?php include "studentHeader.php";?>
       <div class="heading">
           
        <div class="container">
    
        <form name="frmForgot" id="frmForgot" method="post" onSubmit="return validate_forgot();">
        <h1>Forgot Password?</h1>
            <?php if(!empty($success_message)) { ?>
            <div class="success_message"><?php echo $success_message; ?></div>
            <?php } ?>

            <div id="validation-message">
                <?php if(!empty($error_message)) { ?>
            <?php echo $error_message; ?>
            <?php } ?>
            </div>

            <div class="field-group">
                <div><label for="username">Username</label></div>
                <div><input type="text" name="user-login-name" id="user-login-name" class="input-field"> Or</div>
            </div>
            
            <div class="field-group">
                <div><label for="email">Email</label></div>
                <div><input type="text" name="user-email" id="user-email" class="input-field"></div>
            </div>
            
            <div class="field-group">
                <div><input type="submit" name="forgot-password" id="forgot-password" value="Submit" class="form-submit-button"></div>
            </div>	
        </form>
                </div>
                </div>
       