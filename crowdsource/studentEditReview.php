 <head>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="css/header.css">
  <script src="js/jquery.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.js"></script>
</head>


<?php  
  include "model/studentModel.php"; 
  session_start();
   
 // session_start();
	$ubit_name=$_SESSION['ubit_name'];
	// $token=$_SESSION['token'];
	$did=$_SESSION['did'];

	$csdr=new StudentModel();
	$assignedDataSet=$csdr->displayAssignedDataSet($ubit_name,$did);
	$_SESSION['splitId']=$assignedDataSet[0]['SPLIT_FILE_ID'];
	$splitId=$_SESSION['splitId'];
	$assignedQuestions=$csdr->displayQuestions($did);
	
	if(!$assignedQuestions or !$assignedDataSet):{
		echo "Please try again later.";
	}endif;

	$reviewedAnswers=$csdr->displayReviewedAnswers($ubit_name,$did,$splitId);
	// print_r($reviewedAnswers);
	if(sizeof($reviewedAnswers)==0){
		header("Location: studentView.php");
	}
	
?>
<?php include "studentHeader.php"; ?>

<body>
	
	<div class="row" style='height:100%'>
      <div class="col-sm-12 col-md-12 col-lg-2" id="details"  >
            <div >
						<div><p> <?php echo $ubit_name;?></p>  </div>
						<div>	<a href="/ubspectrum/crowdsource/studentHome.php">Home</a>	</div>
              <div >	<a href="/ubspectrum/crowdsource/submittedReviews.php"> Edit submitted Reviews</a></div>
              <div> <a href="/ubspectrum/crowdsource/model/signout.php">Logout</a></div>
						
				
              <br>

            </div>		
      </div>
        <div class="heading col-sm-12 col-md-12 col-lg-10 ">
          <div class="container ">
					<div><p style="color:red">Please note that you cannot edit your previous answers but can add more to the existing ones.</p></div>
					<h2><?php echo $assignedDataSet[0][0]?></h2>
		<p><?php echo $assignedDataSet[0][1]?></p>

		<div><a href="file.php"  class="btn btn-primary" target="blank"><i class="fa fa-download"></i> Download dataset</a></div>
		
		<form class="col-lg-12 " action="model/submitAnswers.php" method="GET">
			<?php for ($x = 0; $x <sizeof($assignedQuestions); $x++) {?>
			  <div class="form-group heading" >
				   <label><?php echo $assignedQuestions[$x]['QUESTION']?></label>
				   <!-- <textarea   class="scroll" placeholder="Type here" disabled="true" ><?php echo $reviewedAnswers[$x]['ANSWER'] ?></textarea> -->

				   <div class="alert alert-secondary" role="alert">
				   <?php echo $reviewedAnswers[$x]['ANSWER'] ?>
				   </div>
				    <?php if($reviewedAnswers[$x]['edits']!='[]'){?>
				    	<h5>Previous edits:</h5>
				    	<?php
				    	$my=json_decode($reviewedAnswers[$x]['edits'], true);
				    	for($i=0;$i<sizeof($my);$i++){if($my[$i]!=""){?>
							  
							   <div class="alert alert-secondary" role="alert">
				   <?php echo $my[$i]?>
				   </div>
				    	<?php }}} ?>
				    	<textarea  name="editAnswer[]" class="scroll" placeholder="Type your answer here " onfocus="init(this);"></textarea> 

			  </div><?php } ?>
			  <!-- <fieldset class="form-group"> -->
			    <div class="row">
			      <label class="col-form-label col-lg-12">What do you think about the case study?</label>
			      <div class="col-lg-4">
			        <div class="form-check">
			          <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="Fishy" required onClick="getResults(this)">
			          <label class="form-check-label" for="gridRadios1">
			            Fishy !
			          </label>
			        </div>
			        <div class="form-check">
			          <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="Not worth investigating" onClick="getResults(this)">
			          <label class="form-check-label" for="gridRadios2">
			            Not worth investigating.
			          </label>
			        </div>
			        <div class="form-check">
			          <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios3" value="Interesting" onClick="getResults(this)" >
			          <label class="form-check-label" for="gridRadios3">
			            Interesting !
			          </label>
			        </div>
			      </div>
					</div>
					<div class="reason"> 
						<div class="form-group heading" >
									<label>Why do you think so?</label>
									<textarea  name="reason" class="scroll" placeholder="Type your answer here " required onfocus="init(this);" ></textarea> 
					</div>
				</div>
			  <!-- </fieldset> -->
			  <div class="form-group row">
				    <div class="col-sm-6">
				      <button type="button" id="submit" class="btn btn-primary"  data-toggle="modal" data-target="#reviewConf">Update</button>			    
			</div>
			  </div>
		


		  <div class="modal" id="reviewConf">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">                 
                                <h5 class="modal-title">Are you sure you want to submit?
                                </h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                                 <div class="modal-body">
																	 <button type='submit' name='submit' class="btn btn-success" >yes</button>
																		<button class="btn btn-danger" data-dismiss="modal">No</button>
                            </div>
                           
                        </div>
                    </div>
								</div> 
							</form> 
            
          </div>
     </div>
     
    
</div>

	
<script >
$(document).ready(function() {
    $(".reason").hide();

});
function getResults(elem) {
	if(elem.checked){
		$(".reason").show();
	}
    //elem.checked && elem.value == "Yes" ? $(".text").show() : $(".text").hide();
};
	var observe;
	if (window.attachEvent) {
	    observe = function (element, event, handler) {
	        element.attachEvent('on'+event, handler);
	    };
	}
	else {
	    observe = function (element, event, handler) {
	        element.addEventListener(event, handler, false);
	    };
	}
	function init (text) {
	   // var text = document.getElementById('textarea');
	    function resize () {
	        text.style.height = '150px';
	        text.style.height = text.scrollHeight+'px';
	    }
	    /* 0-timeout to get the already changed text */
	    function delayedResize () {
	        window.setTimeout(resize, 0);
	    }
	    observe(text, 'change',  resize);
	    observe(text, 'cut',     delayedResize);
	    observe(text, 'paste',   delayedResize);
	    observe(text, 'drop',    delayedResize);
	    observe(text, 'keydown', delayedResize);

	    resize();
	}
</script>
</body>


