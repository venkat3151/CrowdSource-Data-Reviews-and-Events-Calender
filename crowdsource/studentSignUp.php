<html>
<head>
  <link rel = "stylesheet/css" href="../bootstrap/css/bootstrap.css" >   
  <script src="js/jquery.js"></script>
  <script src="js/popper.js"></script>
  <script src="../bootstrap/js/bootstrap.js"></script> 
</head>

<?php
include "model/studentModel.php"; 
    session_start();
    if(isset($_GET['dataset_id']))
    {
        $_SESSION['did']=$_GET['dataset_id'];
        $did=$_SESSION['did'];
        $sm=new StudentModel();
        $data=$sm->getDataset($did); 
        $ubit_name=$_SESSION['ubit_name'];  

    }
     ?>
  
  

<body>
<?php include "studentHeader.php";?>

<div class="row" style='height:100%'>
      <div class="col-sm-12 col-md-12 col-lg-2" id ="details" >
            <div  >
            <div><p> <?php echo $ubit_name;?></p>  </div>
            <div>
						<a href="/ubspectrum/crowdsource/studentHome.php"> Home</a>
                    </div>
                    <div >
						<a href="/ubspectrum/crowdsource/submittedReviews.php">Edit Submitted Reviews</a>
					</div>
					<div >
						<a href="/ubspectrum/crowdsource/model/signout.php">Logout</a>
					</div>
        
              <br>

            </div>		
      </div>
        <div class="heading col-sm-12 col-md-12 col-lg-10 ">
          <div class="container ">
                   
          <h2><?php echo $data['DATASET_NAME'] ?> </h2> 
                   <div class="description"> <p><?php echo $data['DATASET_DESCRIPTION'] ?> </p></div>
                    <label><h4>Please read the below details to proceed further.</h4></label>
                    <ul>
                        <li>You will be assigned a dataset if you choose to proceed.</li>
                        <li>The dataset can be a pdf or csv file</li>
                        <li>You need to submit your review within two weeks from the time of its assignment.</li>
                        <li>You can edit your reviews even after submission.</li>
                       
                    </ul>
                    
                 
                    <a href=<?php echo "/ubspectrum/crowdsource/model/checkDataset.php?dataset_id=".$did.""?> class="btn btn-primary">Proceed</a>
          </div>
     </div>
     
    
</div>





        
</body>
<!-- <script>
    $('#signUp').on('hidden.bs.modal', function () {
  window.location.href="index.php";
});
</script> -->

</html>