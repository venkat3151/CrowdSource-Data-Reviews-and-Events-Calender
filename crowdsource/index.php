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
    if(isset($_POST['Register'])){
     $sm=new StudentModel();    
	$_SESSION['ubit_name']=$_POST['ubit_name'];
    $res=$sm->checkUser($_SESSION['ubit_name']); 
    if(sizeof($res)==1){
        echo "<script>window.addEventListener('load', 
            function() { 
                $('#checkUser').modal('show');
            }, false);</script>" ;
    }else{
    require 'model/PHPMailerAutoload.php';
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
        }
        else{
            echo "<script>window.addEventListener('load', 
            function() { 
                $('#myModal').modal('show');
            }, false);</script>" ;
        }
    }
    // }else{
    //     header("Location: index.php?passwordsNotMatch=true");
    // }
  
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
                echo "<script>window.addEventListener('load', 
                function() { 
                    $('#registerSuccess').modal('show');
                }, false);</script>" ;
            }else{
                echo "Please enter correct token";
            }
        }
}
?>
<html> 
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
    <?php include "studentHeader.php";?>
    <body  ng-app="ngPatternExample" >  
    <script>
  angular.module('ngPatternExample', [])
    .controller('ExampleController', ['$scope', function($scope) {
      $scope.regex = /^[^`~!@#$%\^&*()_+={}|[\]\\:';"<>?,./1-9]*$/;
      $scope.message="form-control";
   $scope.textclass="form-control";
   $scope.textubit="form-control";
   $scope.incomplete = true;
   $scope.error = true;
      $scope.$watch('pass',function() {$scope.test();});
$scope.$watch('passconf',function() {$scope.test();});
$scope.$watch('model1', function() {$scope.test();});
 $scope.$watch('ubit_name', function() {$scope.test();});

$scope.test = function() {
  if($scope.model1.length===1){
      $scope.textclass="form-control is-invalid";
    //  $scope.error = true;
  }else{
    //$scope.error = false;
    $scope.textclass="form-control is-valid";
  }
  if($scope.ubit_name.length<4){
      $scope.textubit="form-control is-invalid";
      //$scope.error = true;
  }else{
   // $scope.error = false;
    $scope.textubit="form-control is-valid";
  }
  if ($scope.pass !== $scope.passconf) {
  //  $scope.error = true;
    $scope.message="form-control is-invalid";
    } else {
    //$scope.error = false;
    $scope.message="form-control is-valid";
  }
  if($scope.model1.length===1 || $scope.ubit_name.length<4 || $scope.pass !== $scope.passconf ){
    $scope.incomplete = true;
  }else{
    $scope.incomplete = false;
    $scope.error = false;
  }

 
}; 
 
    }])
    .directive('myDirective', function() {
        function link(scope, elem, attrs, ngModel) {
            ngModel.$parsers.push(function(viewValue) {
              var reg = /^[^`~!@#$%\^&*()_+={}|[\]\\:';"<>?,./0-9]*$/;
              // if view values matches regexp, update model value
              if (viewValue.match(reg)) {
                return viewValue;
              }
              // keep the model value as it is
              var transformedValue = ngModel.$modelValue;
              ngModel.$setViewValue(transformedValue);
              ngModel.$render();
              return transformedValue;
            });
        }

        return {
            restrict: 'A',
            require: 'ngModel',
            link: link
        };      
    });
</script>

        
    
                 <div class="modal" id="checkUser">
                     <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">                 
                                <h4 class="modal-title">Our records show that you are an existing user.</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="GET" class="form-group" action="">
                                <div class="modal-body">
                                Please login or use forgot password option.
                            </div></div>
                            </form>

                        </div>
                    </div>

                    <div class="modal" id="forgotPasswordForm">
                     <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">                 
                                <h4 class="modal-title">Please enter your UBIT name in the box below.</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                            <form method="POST" class="form-group" action="/ubspectrum/crowdsource/model/checkLogin.php">
                                <input type="text" name="ubit_name" />
                                <button type='submit'name="forgotPassword" value="forgotPassword" class="btn btn-primary">Send me email!</button>
                            </form>
                               
                            </div>
                        </div>
                           

                        </div>
                    </div>


                    <div class="modal" id="registerSuccess">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">                 
                                <h4 class="modal-title">You are successfully registered.</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="GET" class="form-group" action="">
                                <div class="modal-body">
                                Please login with your details.
                            </div></div>
                            </form>

                        </div>
                    </div>      


                    <div class="modal" id="modalRegisterForm">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">                 
                                <h4 class="modal-title">Register</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                        <div class="modal-body">
                    <div class="col-sm-12 col-md-12 col-lg-12 center" data-wow-duration="2s"  >
               
             
               <!-- <div class="description"><h4> Started reviewing a dataset ? Enter your credentials to pick up where you left  ! </h4> </div> -->
             <?php if(isset($_GET['passwordsNotMatch']) and ($_GET['passwordsNotMatch']==true)){?>
                  <h5 class="error">Passwords do not match</h5>
             <?php }?>
             <div ng-controller="ExampleController" >
             <form class="form-signin" style="margin: 0 auto; width:250px" align="center" action="" method="post">
      
      <!--<h8 id="error" align="center">Username or password is incorrect</h8>-->
      <h8 id="error" align="center"></h8>
      <label for="first_name">
      <input type="text" id="first_name" ng-model="model1" ng-class="textclass" size=40 placeholder="First Name" name="first_name" required autofocus  my-directive></label>
      <label for="last_name">
      <input type="text" id="last_name" ng-model="model2" name="last_name" size=40 class="form-control" placeholder="Last Name"   my-directive></label>
      <label for="ubit_name">
      <input type="text" id="ubit_name" ng-model="ubit_name" name="ubit_name" size=40 ng-class="textubit" placeholder="UBIT Name" required  ></label>
      <label for="password">
      <input type="password" id="password" ng-model="pass" name="password" size=40 ng-class="message"  placeholder="Password" required></label>
      <label for="password_confirmation">
      <input type="password" ng-class="message" ng-model="passconf" ng-change="compare()" name="password_confirmation" placeholder="Confirm Password"  size=40 required></label>

      <input type="submit" value="Register" ng-disabled="error || incomplete" name="Register" class="btn btn-info btn-block">
            <!-- <button ng-disabled=true>uhu uhu</button> -->
    </form> </div>

                   
             </div>
                          </div>
                        </div>
                    </div>
                </div>

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
 


    
    <div class="heading container">
  
           
        <div class=" col-lg-12 ">
            <div class="col-sm-12 col-md-12 col-lg-8">
                <p style="line-height:3rem; text-align:justify; margin-right:10%">
Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
<br>
It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing so<p>
              <button id="datasetsAllbutton" type="button" class="btn btn-primary">View All</button>
               <!-- <img  src="/ubspectrum/crowdsource/img/arrow.jpg"/> -->
           </div>
             <div class="col-sm-12 col-md-12 col-lg-4 wow pulse" data-wow-duration="2s"  id="bujjiForm">
               
             
                     <div class="description"><h4> Started reviewing a dataset ? Enter your credentials to pick up where you left  ! </h4> </div>
                   <?php if(isset($_GET['invalid']) and ($_GET['invalid']==true)){?>
                        <h5 class="error">Please check if you are entering correct details.</h5>
                   <?php }?>
                     <form class="form-group" method="POST" action="model/checkLogin.php" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-xs-12 col-md-3 text-md-right">
                                </div>
                                <div class=" col-xs-12 col-md-6 ">
                                    <input type="text" name="ubit_name" id="name" class="form-control" maxlength="64" placeholder="UBIT Name" required />
                              </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xs-12 col-md-3 text-md-right">
                                </div>
                              <div class="col-xs-12 col-md-6 ">
                                      <input type="password" name="password" class="form-control" maxlength="64" placeholder="Password" required>      
                               </div> 
                            </div>
                           <div class="row  mb-3">
                                <div class="col-md-4 d-none d-md-block"></div>
                                <div class="col-xs-3">
                                    <input type="submit" class="btn btn-primary" name="checkLogin" value="Submit"/>
                                </div>
                            
                                
                            </div>
                           
                                <div class="col-xs-12 col-md-6">
                                <!-- <input type="button" id="registerModal" value="Not a user ? Sign up here"/> -->
                                <a href="#" id="registerModal"  >Not a user ? Sign up here</a>
                                </div>
                                <!-- <div class="col-md-4 d-none d-md-block"></div> -->
                                <div class="col-xs-12 col-md-6">
                                <a href="#" id="forgotPasswordSubmit"  >Forgot password</a>
                      </form>
                                </div>
                            
                            
                             </div>
                             <div id="datasetsAll" class="heading col-sm-12 col-md-12 col-lg-12">
                              <?php  if(sizeof($datasets)!=0)   { ?>
           
            <?php  
            
            for($i=0;$i<sizeof($datasets);$i++){
                $name=$datasets[$i]["DATASET_NAME"];
                $description=$datasets[$i]["DATASET_DESCRIPTION"];
                $_SESSION['DATASET_DESCRIPTION']=$description;
                $id=$datasets[$i]["DATASET_ID"];
            ?>
            
         <div class="wow SlideInUp col-sm-12 col-md-6 col-lg-6"  >
                <div class="card  " data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                <div class="card-body ">
                    <h2 class="card-title"><?php echo $name?> </h2>
                
                    <p class="card-text"><?php echo $description ?></p>
                    
                </div>
                </div>
            
            </div>


            

            
            <?php }?>
                 
               

<?php }
else{ ?>
  <br>
    <div class="jumbotron d-flex align-items-center">
  <div class="container text-center">
    <div id="jumboicon"><i class="fa fa-hourglass"></i></div>

   <h2> Stay tuned for new datasets !</h2>
  </div>
</div>
<?php }?>
    </div>
</div>




    </body> 
    <script src="/ubspectrum/crowdsource/js/wow.min.js"></script>
    <script>
     new WOW().init();
    </script>
    <script>
    $('#checkUser').on('hidden.bs.modal', function () {
  window.location.href="/ubspectrum/crowdsource/index.php";
});

$('#registerSuccess').on('hidden.bs.modal', function () {
  window.location.href="/ubspectrum/crowdsource/index.php";
}); 



</script>

<!-- <script>

  $('input').on('blur', validateInput);
//   alert($('#error').text());
  function validateInput() {
          let input = $(this);
          let isRequired = input.attr('required') ? true : false;
          let type = input.data('type') || 'text';
          let isValid = false;
          if (isRequired && input.val() != '') {
              isValid = true;
          }

          if (!isValid) {
              input.removeClass('is-valid').addClass('is-invalid');
              
          }
          let value = input.val();

          switch (type) {
              case 'text':
                  break;
              case 'email':
                  break;

          }

          if (isValid) {
              input.removeClass('is-invalid').addClass('is-valid');
          } else {
              input.removeClass('is-valid').addClass('is-invalid');
          }
      }
</script> -->
 </html>