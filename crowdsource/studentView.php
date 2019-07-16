 <head>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/header.css">
   <script src="js/bootstrap.js"></script>
   <script src="js/jquery.js"></script>
   <link rel="stylesheet" href="css/jquery-confirm.css">
   <script type="text/javascript" src="js/jquery-confirm.js"></script>
  
  <script src="js/popper.js"></script>

</head>


<?php 
session_start();
// if(isset($_GET['submit'])){ 

//    $_SESSION['answer']=$_GET['answer'];

//    $_SESSION['poll']=$_GET['gridRadios'];
// 		echo "<script>window.addEventListener('load', 
// 		                        function() { 
// 		                            $('#reviewConf').modal('show');
// 		                        }, false);</script>";
// 	}
//  else{
	  include "model/studentModel.php";
 
  // session_start();
  $ubname=$_SESSION['ubit_name'];

	$did=$_GET['dataset_id'];
	$_SESSION['did']=$did;
	$csdr=new StudentModel();
	$assignedDataSet=$csdr->displayAssignedDataSet($ubname,$did);
	$_SESSION['splitId']=$assignedDataSet[0]['SPLIT_FILE_ID'];
	$assignedQuestions=$csdr->displayQuestions($did);
	if(!$assignedQuestions or !$assignedDataSet):{
		echo "Please try again later.";
	}endif;
// }
?>


<body>
	<?php include "studentHeader.php"; ?>
	<div class="row" style='height:100%'>
      <div class="col-sm-12 col-md-12 col-lg-2" id ="details"  >
            <div  >
						<div><p> <?php echo $ubit_name;?></p>  </div>
						<div>	<a href="/ubspectrum/crowdsource/studentHome.php">Home</a>	</div>
              <div >	<a href="/ubspectrum/crowdsource/submittedReviews.php"> Edit submitted Reviews</a></div>
              <div> <a href="/ubspectrum/crowdsource/model/signout.php">Logout</a></div>
          
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
					<h3><?php echo $assignedDataSet[0][0]?></h3>
		<div class="description"><p><?php echo $assignedDataSet[0][1]?></p></div>

		<a href="file.php" target="blank" class="btn btn-primary center"><i class="fa fa-download" aria-hidden="true"> </i> Download dataset</a>
 <h4 class="center error">Please enter your responses after reviewing the dataset.</h4>
<div class="heading ">
		<form class=" form-group " action="model/submitAnswers.php" id ="studentform">
			<?php for ($x = 0; $x <sizeof($assignedQuestions); $x++) {?>
			  <div class="row ">
			  	<div class="col-xs-12 col-md-12 col-lg-12 ">
				   <label><?php echo $assignedQuestions[$x]['QUESTION']?><span class="required error">*</span></label>
				      
				   <textarea  name="answer[]" class="scroll" placeholder="Type your answer here..." required onfocus="init(this);"></textarea> 
				   </div>
			  </div><?php } ?>

<!-- 			  
			  <fieldset class="form-group center"> -->
			    <div class=" row">
			      <legend class="col-form-label col-lg-8"><label>What do you think about the case study?</label><span class="required error">*</span></legend>
			      <div class="col-xs-12 col-md-4 ">
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
			  <!-- </fieldset> -->
			
				<div class="reason"> 
						<div class="form-group heading" >
									<label>Why do you think so?</label>
									<textarea  name="reason" class="scroll" placeholder="Type your answer here "  required onfocus="init(this);" ></textarea> 
					</div>
				</div>
				<div class="form-group ">
				    <div class="col-sm-6">
						<button type="button" id="submit" class="btn btn-primary"  data-toggle="modal" data-target="#reviewConf">Update</button>			    
					</div>
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
                                 <!-- <input type="submit" class="btn btn-primary" name="save" value="Submit"/> -->
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


