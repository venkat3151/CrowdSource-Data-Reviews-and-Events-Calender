<?php 
include "studentModel.php";
session_start();
$ubit_name=$_SESSION['ubit_name'];
$dataset_id=$_GET['dataset_id'];
$obj=new StudentModel();

$result=$obj->checkSubmission($ubit_name,$dataset_id);

if(sizeof($result)==1){
    if($result[0]['FILE_SUBMITTED_TIME']){
    header("Location: ../studentEditReview.php");
    }else{
        header("Location: ../studentView.php?dataset_id=".$dataset_id);
    }
}
else{
    $selectSplit=$obj->selectSplitFile($ubit_name,$dataset_id);
   if($selectSplit!=0)
   {
      // echo $selectSplit;
   header("Location: ../studentView.php?dataset_id=".$dataset_id);
}else{
    echo "something went wrong";
}
}

?>
