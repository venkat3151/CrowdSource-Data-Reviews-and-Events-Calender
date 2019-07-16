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
<html> 
    <body>
          <div class="modal" id="myModal">
               <div class="modal-dialog">
                   <div class="modal-content">
                       <div class="modal-header">                 
                               <h4 class="modal-title">Please enter your UBIT Nam.</h4>
                               <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form method="GET" class="form-group" action="">
                                <div class="modal-body">
                                 <div class="row mb-3">
                                <div class="col-xs-12 col-md-8">
                                      <input type="text" id="ubit_name" name="ubit_name" class="form-control" maxlength="64" placeholder="UBIT Name" required>     
                                </div>
                                <input id="forgotPasswordSubmit" type="submit" class="btn btn-primary" name="save" value="Submit"/>
                            </div></div>
                            </form>

                        </div>
                    </div>
                </div>

 <div class="modal" id="mailModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">                 
                                <h4 class="modal-title">The token has been sent to your mail. Please login again. </h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            

                        </div>
            </div>
    </div>


    <?php include "studentHeader.php";?>
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
             <!-- <div class="col-sm-12 col-md-12 col-lg-4 wow pulse" data-wow-duration="2s"  id="bujjiForm">
               
             
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
                                      <input type="text" name="token" class="form-control" maxlength="64" placeholder="Dataset Specific Token" required>      
                               </div> 
                            </div>
                           <div class="row  mb-3">
                                <div class="col-md-4 d-none d-md-block"></div>
                                <div class="col-xs-3">
                                    <input type="submit" class="btn btn-primary" name="checkLogin" value="Submit"/>
                                </div>

                                
                            </div>
           
                      </form>
                             </div> -->


        
        <div class="col-sm-12 col-md-12 col-lg-4 wow pulse" data-wow-duration="2s"  id="bujjiForm">
                <div class="description"><h4> Started reviewing a dataset ? Enter your credentials to pick up where you left  ! </h4> </div>
                <?php if(isset($_GET['invalid']) and ($_GET['invalid']==true)){?>
                     <h5 class="error">Please check if you are entering correct details.</h5>
                <?php }?>
        	
                <form class="form-group" method="POST" action="model/checkLogin.php" enctype="multipart/form-data">
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
			    				<input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email Address">
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
			    			
			    			<input type="submit" value="Register" class="btn btn-info btn-block">
			    		
			    		</form>
			    	</div>
	    	
        
        






             <?php  if(sizeof($datasets)!=0)   { ?>
            <div id="datasetsAll" class="heading col-sm-12 col-md-12 col-lg-12">
            <?php  
            
            for($i=0;$i<sizeof($datasets);$i++){
                $name=$datasets[$i]["DATASET_NAME"];
                $description=$datasets[$i]["DATASET_DESCRIPTION"];
                $_SESSION['DATASET_DESCRIPTION']=$description;
                $id=$datasets[$i]["DATASET_ID"];
            ?>
            
          <a href=" <?php echo "studentSignUp.php?datasetid=".$id."&amp;datasetname=".$name?>">  <div class="wow bounceInUp col-sm-12 col-md-6 col-lg-6">
                <div class="card  ">
                <div class="card-body ">
                    <h2 class="card-title"><?php echo $name?> </h2>
                
                    <p class="card-text"><?php echo $description ?></p>
                    
                </div>
               <!--  <div class="card-body"><a class="btn btn-primary">hi</a></div>
 -->
                </div>
            
            </div></a>


            

            
            <?php }?>
               </div>  
                </div>

<?php }
else{ ?>
  <br>
    <div class="jumbotron d-flex align-items-center">
  <div class="container text-center">
    <div id="jumboicon"><i class="fa fa-hourglass"></i></div>

   <h2>Coming up with more datasets. Stay tuned !</h2>
  </div>
</div>
<?php }?>
   
</div>

    </body> 
    <script src="/ubspectrum/crowdsource/js/wow.min.js"></script>
    <script>
     new WOW().init();
    </script>
 </html>