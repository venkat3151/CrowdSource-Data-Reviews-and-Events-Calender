<?php
    session_start();
    $ubit_name=$_SESSION['ubit_name'];
    include "model/studentModel.php";
    $obj=new StudentModel();
    $datasets=$obj->displayReviewedDatasets($ubit_name);
    
    if(sizeof($datasets)==0){
        
    }
   else{
        $_SESSION['reviewedDatasets']=$datasets;
    }    
   
?>
<html> 
    <body>
         
 


<?php include "studentHeader.php";?>
   
      <div class="row" style='height:100%'>
      <div class="col-sm-12 col-md-12 col-lg-2" id ="details"  >
            <div  >
              <div><p> <?php echo $ubit_name;?></p>  </div>
              <div class="navAnchors"><a href="/ubspectrum/crowdsource/studentHome.php">Home</a></div>
              <div class="navAnchors"> <a href="/ubspectrum/crowdsource/model/signout.php">Logout</a></div>
          
              <!-- <div>
                        <a href="/ubspectrum/crowdsource/studentHome.php">Home</a>
              </div>
              <div >
                <a href="/ubspectrum/crowdsource/model/signout.php">Signout</a>
              </div> -->
              <br>

            </div>		
      </div>
        <div class="heading col-sm-12 col-md-12 col-lg-10 ">
          <div class="container ">
                 
              <div id="datasetsAll" >
                  <?php  if(sizeof($datasets)!=0)   { ?>           
                        <p>The following are the list of datasets that you have already reviewed.</p>
                        <?php 
                            for($i=0;$i<sizeof($datasets);$i++){
                                $name=$datasets[$i]["DATASET_NAME"];
                                $description=$datasets[$i]["DATASET_DESCRIPTION"];
                                $_SESSION['DATASET_DESCRIPTION']=$description;
                                $id=$datasets[$i]["DATASET_ID"];
                        ?>            
                        <a href=" <?php echo "/ubspectrum/crowdsource/studentSignUp.php?dataset_id=".$id?>"> 
                        <div class="wow slideInUp col-sm-12 col-md-6 col-lg-6" data-wow-duration="3s">
                              <div class="card  ">
                              <div class="card-body ">
                                  <h2 class="card-title"><?php echo $name?> </h2>                
                                  <p class="card-text"><?php echo $description ?></p>                    
                              </div>
                          </div>            
                          </div>
                        </a>
                          <?php }?>
                    <?php }
                    else{ ?>
                     <div class="jumbotron d-flex align-items-center ">
                      <div class="container text-center">
                        <div id="jumboicon"><i class="fa fa-hourglass"></i></div>
                          <h2>You have not submitted any reviews yet !</h2>
                       </div>
                    </div>
                    <?php }?>
             </div>
          </div>
     </div>
     
    
</div>

    </body> 
    <script src="/ubspectrum/crowdsource/js/wow.min.js"></script>
    <script>
     new WOW().init();
    </script>
 </html>