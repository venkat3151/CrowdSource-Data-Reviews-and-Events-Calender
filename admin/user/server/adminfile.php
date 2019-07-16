<?php
include "adminViewModel.php";

$host="stark.cse.buffalo.edu";
$username="spectrum_user";
$password="Spectrum2019!";
$databasename="spectrum"; 

session_start();
$stmt=new AdminModel();
$sessionUser = $_SESSION['sessionUser'];

$transdbh = new PDO('mysql:host=stark.cse.buffalo.edu;dbname=spectrum', $username, $password);
if(isset($_POST['updateEmail']) and isset($_POST['updateRole']) and isset($_POST['updateFullname'])){
	$EMAIL=$_POST['updateEmail'];
	$FULL_NAME=$_POST['updateFullname'];
	$ROLE=$_POST['updateRole'];
	$Updated=$stmt->adminUpdate($EMAIL,$FULL_NAME,$ROLE);
    if($Updated==1){

		$transstmt = $transdbh->prepare("insert into tbl_transaction values('',?,?,?,?)");
		$application = "User Management";
		$action = "Updated Admin " .$FULL_NAME;
		$date = date("Y-m-d H:i:s");
		//$user = "test@gmail.com";
		$transstmt->bindParam(1,$application);
		$transstmt->bindParam(2,$sessionUser);
		$transstmt->bindParam(3,$action);
		$transstmt->bindParam(4,$date);
		$transstmt->execute();
		header('Location: ../userManagement.php');
  }
}


else if(isset($_GET['delete']) and ($_GET['delete']==true)){
$email=$_GET['EMAIL'];
//echo $email;
$deleted=$stmt->admindelete($email);

        $transstmt = $transdbh->prepare("insert into tbl_transaction values('',?,?,?,?)");
		$application = "User Management";
		$action = "Deleted Admin " .$email;
		$date = date("Y-m-d H:i:s");
		//$user = "test@gmail.com";
		$transstmt->bindParam(1,$application);
		$transstmt->bindParam(2,$sessionUser);
		$transstmt->bindParam(3,$action);
		$transstmt->bindParam(4,$date);
		$transstmt->execute();

return $deleted;
}



else if(isset($_GET['approve']) and ($_GET['approve']==true)){
	$email=$_GET['email'];
	//echo $email;
	$approve=$stmt->adminApprove($email);
	if($approve==1){

		$transstmt = $transdbh->prepare("insert into tbl_transaction values('',?,?,?,?)");
		$application = "User Management";
		$action = "Approved Admin " .$FULL_NAME;
		$date = date("Y-m-d H:i:s");
		//$user = "test@gmail.com";
		$transstmt->bindParam(1,$application);
		$transstmt->bindParam(2,$sessionUser);
		$transstmt->bindParam(3,$action);
		$transstmt->bindParam(4,$date);
		$transstmt->execute();


		return $approve;
	}


}
else if(isset($_GET['reject']) and ($_GET['reject']==true)){
	$email=$_GET['email'];
	//echo $email;
	$reject=$stmt->adminReject($email);
if($reject==1){

	 $transstmt = $transdbh->prepare("insert into tbl_transaction values('',?,?,?,?)");
		$application = "User Management";
		$action = "Rejected Admin " .$FULL_NAME;
		$date = date("Y-m-d H:i:s");
		//$user = "test@gmail.com";
		$transstmt->bindParam(1,$application);
		$transstmt->bindParam(2,$sessionUser);
		$transstmt->bindParam(3,$action);
		$transstmt->bindParam(4,$date);
		$transstmt->execute();


	return $reject;
}

	
}





?>