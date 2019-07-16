<?php

session_start();
if(isset($_SESSION['admin'])){
      $admin=$_SESSION['admin'];
      if($admin['ROLE']=='super'){
        include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/superHeader.php";
      }
      else if($admin['ROLE']=='crowd'){
         include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/crowdHeader.php";
			}
		 }
      else{
        header("Location: /ubspectrum/admin/user/signin.php");
      }  
include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/crowdsource/model/adminModel.php";
include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/crowdsource/model/studentModel.php";
$obj= new AdminModel();
$stuObj = new studentModel();
$_SESSION['id']=$_GET['datasetid'];
$id=$_SESSION['id'];
$_SESSION['ubit']=$_GET['ubit'];
$ubit_name=$_SESSION['ubit'];
$split = $obj->getSplitFile($id,$ubit_name);
$splitnum= $split[0]['SPLIT_FILE_ID'];
$_SESSION['splitId']=$splitnum;
$reviewedAnswers =  $stuObj->displayReviewedAnswers($ubit_name,$id,$splitnum);
$assignedQuestions = $stuObj->displayQuestions($id);
?>

<html>
<head><title>Answers | CrowdSource Data Reviews</title></head>
<body>
<div class="heading">
	<div class=" container">
		<h4>Reviewed by <?php echo $ubit_name; ?></h4>
		<div class="row">
			<div  class="col-sm-12 col-md-12 col-lg-8" >
				<?php for ($x = 0; $x <sizeof($assignedQuestions); $x++) {?>
				  <div class="form-group heading " id ="qsnans">
					   <h5><?php echo $assignedQuestions[$x]['QUESTION']?></h5>				  
					   <div class="ans"><?php echo $reviewedAnswers[$x]['ANSWER'] ?></div>			
	           		    <?php if($reviewedAnswers[$x]['edits']!='[]'){?>              
					    	<h6>The following are the edits for the above review: </h6>
					    	<?php $my=json_decode($reviewedAnswers[$x]['edits'], true);
					    		for($i=0;$i<sizeof($my);$i++){if($my[$i]!=""){?>							   
					   				<div class="ans"><?php echo $my[$i]?></div><div class='borders'></div><?php }}} ?>
									<div class='borders'></div>
				 	    </div><?php } ?>		
				</div>
			<div class="col-sm-12 col-md-12 col-lg-4 " id="outerdetails" >
				<div id ="details" class="col-lg-12 col-md-6 col-sm-6 alert-primary">
					<div>
						<a href="/ubspectrum/admin/crowdsource/datasetsView.php"> Back to previous page</a>
					</div>
					<div >
						<a href="/ubspectrum/crowdsource/file.php"  class="btn btn-primary" target="blank"><i class="fa fa-download"></i> Download dataset</a>
					</div>
					<br>

					<label>Assigned Time : <?php echo $split[0]['FILE_ASSIGNED_TIME']; ?></label>
					<label>Submitted Time :<?php echo $split[0]['FILE_SUBMITTED_TIME']; ?></label>	
				</div>		
			</div>
	    </div>
	</div>
</div>
</body>
</html>