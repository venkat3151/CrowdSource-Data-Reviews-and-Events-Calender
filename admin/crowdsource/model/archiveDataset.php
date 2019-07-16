<?php

 // $host="localhost";
 // $username="root";
 // $password="";
 // $databasename="spectrum";

 $host="stark.cse.buffalo.edu";
 $username="spectrum_user";
 $password="Spectrum2019!";
 $databasename="spectrum";

 session_start(); 
 $sessionusername = $_SESSION['sessionUser'];
 $dbh = new PDO('mysql:host=stark.cse.buffalo.edu;dbname=spectrum', $username, $password);


$conn = new mysqli($host,$username,$password,$databasename);

$_SESSION['id']=$_GET['datasetid'];
$id=$_SESSION['id'];


$datasetcopy="INSERT INTO spectrum.tbl_archived_datasets SELECT * FROM spectrum.tbl_current_datasets where dataset_id=".$id;
$splitcopy="INSERT INTO spectrum.tbl_archived_splits SELECT * FROM spectrum.tbl_splitted_datasets where dataset_id=".$id;
$answerscopy="INSERT INTO spectrum.tbl_archived_answers SELECT * FROM spectrum.tbl_review_answers where dataset_id=".$id;
$questionscopy="INSERT INTO spectrum.tbl_archived_questions SELECT * FROM spectrum.tbl_dataset_questions where dataset_id=".$id;

if($conn->query($datasetcopy) and $conn->query($splitcopy) and $conn->query($questionscopy) and $conn->query($answerscopy) ){
	$datasetdelete="DELETE from  spectrum.tbl_current_datasets where dataset_id=".$id;
	$splitdelete="DELETE from  spectrum.tbl_splitted_datasets where dataset_id=".$id;
	$answersdelete="DELETE from  spectrum.tbl_review_answers where dataset_id=".$id;
	$questionsdelete="DELETE from  spectrum.tbl_dataset_questions where dataset_id=".$id;
	if($conn->query($answersdelete) and $conn->query($questionsdelete) and  $conn->query($splitdelete) and $conn->query($datasetdelete) ){
		$transstmt = $dbh->prepare("insert into tbl_transaction values('',?,?,?,?)");
        $application = "CrowdSource Data Review";
        $action = "Archive Dataset".$id;
        $date = date("Y-m-d H:i:s");
        //$user = "test@gmail.com";
        $transstmt->bindParam(1,$application);
        $transstmt->bindParam(2,$sessionusername);
        $transstmt->bindParam(3,$action);
        $transstmt->bindParam(4,$date);
        $transstmt->execute();
		header("Location: /ubspectrum/admin/crowdsource/datasetsView.php");
	}
}
else{
	echo "Unable to archive the data, please try again later";
}


?>