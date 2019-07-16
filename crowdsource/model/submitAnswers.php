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
    $csdr=new StudentModel();
    $did=$_SESSION['did'];
    $splitId=$_SESSION['splitId'];
    $ubname=$_SESSION['ubit_name'];

    if(isset($_GET['answer'])){
    $answer=$_GET['answer'];
    $poll=$_GET['gridRadios'];
    $reason=$_GET['reason'];
    $poll=$poll.'::'.$reason;
    $submitAns=$csdr->submitReview($did,$splitId,$ubname,$answer,$poll);

    
    if($submitAns==1):{
       echo "<script>window.addEventListener('load', 
                                function() { 
                                    $('#submission').modal('show');
                                }, false);</script>";
    }
    elseif($submitAns==0):{
        echo " Something  went wrong. Please try again.(Unable to insert all answers)";
    }
    else:{
        echo " Something  went wrong. Please try again.(Unable to find questions of the dataset)";
    }
    endif;                                                                                                                   
}
else if(isset($_GET['editAnswer'])){
   
        $answer=$_GET['editAnswer'];
        $reason=$_GET['reason'];
        $poll=$_GET['gridRadios'];
        $poll=$poll.'::'.$reason;
        $submitAns=$csdr->submitEdit($did,$splitId,$ubname,$answer,$poll);
        if($submitAns==true):{
        echo "<script>window.addEventListener('load', 
                                function() { 
                                    $('#submission').modal('show');
                                }, false);</script>";
    }
    else:{
     echo " Something  went wrong. Please try again.(Unable to find questions of the dataset)";
    }
    endif;


}

?>
 <div class="modal" id="submission">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">                 
                                <h5 class="modal-title">Thank you for submitting your review !
                                </h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                                 <div class="modal-body">
                                 <!-- <input type="submit" class="btn btn-primary" name="save" value="Submit"/> -->
                             You can always edit your review!
                            </div>
                           
                        </div>
                    </div>
                </div>


                <script>
    $('#submission').on('hidden.bs.modal', function () {
  window.location.href="/ubspectrum/crowdsource/studentHome.php";
});
</script>
