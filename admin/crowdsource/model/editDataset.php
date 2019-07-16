<?php
// $host="localhost";
//  $username="root";
//  $password="";
//  $databasename="spectrum";
 //$dbh = new PDO('mysql:host=localhost;dbname=spectrum', $username, $password);

 include "adminModel.php";
 $obj= new AdminModel();

 $host="stark.cse.buffalo.edu";
 $username="spectrum_user";
 $password="Spectrum2019!";
 $databasename="spectrum";
 session_start(); 
 $dbh = new PDO('mysql:host=stark.cse.buffalo.edu;dbname=spectrum', $username, $password);
 $transdbh = new PDO('mysql:host=stark.cse.buffalo.edu;dbname=spectrum', $username, $password);
 

$did = $_POST['did'];
$did = intval($did);
$question=$obj->getDatasetQuestions($did);
$isPublished=$obj->getDatasetData($did);
$qcount = count($question);

//Getting Values from Form 
$datasetname=$_POST['name'];
$description=$_POST['description'];

if($isPublished[0]['PUBLISH']==1){
	//Updating Dataset Data in TBL_CURRENT_DATASETS
	$sql = "UPDATE tbl_current_datasets SET DATASET_NAME = ?, DATASET_DESCRIPTION = ?WHERE DATASET_ID = ?";
	$stmt = $dbh->prepare($sql);
	$stmt->execute([$datasetname, $description, $did]);
}else{
	$split=$_POST['split'];

	$data = file_get_contents($_FILES['file']['tmp_name']);
 	$file_type = $_FILES['file']['type'];
 	$NameOriginal = $_FILES['file']['name'];
 	$dpath=$_FILES['file']['tmp_name'];

 	//Move the file to uploads directory
	$uploads_dir = '../uploads/'.$NameOriginal;
	move_uploaded_file($dpath, $uploads_dir);

	//Execute python commands to split the file
	$command='python3.6 splitFiles.py '.$uploads_dir.' '.$split.' '.$file_type;
	$output = shell_exec($command);

	//Find path to splitted files folder inside uploads directory 
	$changedNames = explode(".", $NameOriginal);
	$changedName=$changedNames[0];
	$pathWholeDataset=pathinfo(realpath($uploads_dir), PATHINFO_DIRNAME);
	$dir= $pathWholeDataset.'/'.$changedName;
	$files1 = scandir($dir);
	$scanned_directory = array_diff(scandir($dir), array('..', '.'));

    $sql = "UPDATE tbl_current_datasets SET DATASET_NAME = ?, DATASET_DESCRIPTION = ?, DATASET_FILETYPE = ?, DATASET_FILE = ?, DATASET_SPLITNUM = ? WHERE DATASET_ID = ?";

    $stmt = $dbh->prepare($sql);
	$stmt->execute([$datasetname, $description, $file_type, $data, $split, $did]);

	for($i=2;$i<sizeof($files1);$i++){   
                //$sql = "INSERT INTO tbl_splitted_datasets(DATASET_ID,MIME,FILE) VALUES(?,?,?)";
                $sql = "UPDATE tbl_splitted_datasets SET MIME = ?, FILE = ? WHERE DATASET_ID = ?";
                $blob = fopen($dir."/".$scanned_directory[$i], 'rb');
                //echo $dir."\\".$scanned_directory[$i];
                $stmt = $dbh->prepare($sql);
                $stmt->execute([$file_type, $blob, $currentID]);
        }
}


//Deleting and Inserting Data in tbl_dataset_question
$question = $_POST['question'];
$currentID = $did;
$questionsdelete = "DELETE from tbl_dataset_questions where dataset_id=".$currentID;
$delete = $dbh->prepare($questionsdelete);
$delete->execute();
for($i=0;$i<count($question);$i++){
        $stmtques = $dbh->prepare("insert into tbl_dataset_questions values(?,'',?)");
        $stmtques->bindParam(1,$currentID);
        $stmtques->bindParam(2,$question[$i]);
        $stmtques->execute();
    }

// if($qcount == 0){
//     for($i=0;$i<count($question);$i++){
//         $stmtques = $dbh->prepare("insert into tbl_dataset_questions values(?,'',?)");
//         $stmtques->bindParam(1,$currentID);
//         $stmtques->bindParam(2,$question[$i]);
//         $stmtques->execute();
//     }
// }else{
// 	for($i=0;$i<count($question);$i++){
//         $stmtques = $dbh->prepare("insert into tbl_dataset_questions values(?,'',?)");
//         $stmtques->bindParam(1,$currentID);
//         $stmtques->bindParam(2,$question[$i]);
//         $stmtques->execute();
//     }
// }
	


// else{
//     $questionidsql = "SELECT QUESTION_ID from tbl_dataset_questions where DATASET_ID = ".$currentID;
//     $questionid = $conn->query($questionidsql);
// 	for($i=0;$i<count($question);$i++){
//         $stmtques = $dbh->prepare("insert into tbl_dataset_questions values(?,'',?)");
//         $stmtques->bindParam(1,$currentID);
//         $stmtques->bindParam(2,$question[$i]);
//  }
// }  



$username = $_SESSION['sessionUser'];
$transstmt = $transdbh->prepare("insert into tbl_transaction values('',?,?,?,?)");
$application = "CrowdSource Data Review";
$action = "Update Dataset" .$did;
$date = date("Y-m-d H:i:s");
//$user = "test@gmail.com";
$transstmt->bindParam(1,$application);
$transstmt->bindParam(2,$username);
$transstmt->bindParam(3,$action);
$transstmt->bindParam(4,$date);
$transstmt->execute();

                
 header("Location: ../datasetsView.php");
                         
    

?>