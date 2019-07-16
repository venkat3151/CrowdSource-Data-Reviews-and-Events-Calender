<?php
// $host="localhost";
//  $username="root";
//  $password="";
//  $databasename="spectrum";
//  $dbh = new PDO('mysql:host=localhost;dbname=spectrum', $username, $password);
session_start(); 
 $host="stark.cse.buffalo.edu";
 $username="spectrum_user";
 $password="Spectrum2019!";
 $databasename="spectrum";
 try {
 $dbh = new PDO('mysql:host=stark.cse.buffalo.edu;dbname=spectrum', $username, $password);
 $transdbh = new PDO('mysql:host=stark.cse.buffalo.edu;dbname=spectrum', $username, $password);
 $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
//Get contents from the form

        
   
 $datasetname=$_POST['name'];
 $description=$_POST['description'];
 $split=$_POST['split'];
 $question = $_POST['question'];
 $username = $_SESSION['sessionUser'];

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

//Insert full file into the database
$stmt = $dbh->prepare("insert into tbl_current_datasets values('',?,?,?,?,?,'','')");
$stmt->bindParam(1,$datasetname);
$stmt->bindParam(2,$description);
$stmt->bindParam(3,$file_type);
$stmt->bindParam(4,$data);
$stmt->bindParam(5,$split);
if($stmt->execute()){

        $transstmt = $transdbh->prepare("insert into tbl_transaction values('',?,?,?,?)");
        $application = "CrowdSource Data Reviews";
        $action = "Import a Dataset";
        $date = date("Y-m-d H:i:s");
        //$user = "test@gmail.com";
        $transstmt->bindParam(1,$application);
        $transstmt->bindParam(2,$username);
        $transstmt->bindParam(3,$action);
        $transstmt->bindParam(4,$date);
        $transstmt->execute();


        $currentID = $dbh->lastInsertId();
        $query2=0;

        //Insert splitted files into the database
        for($i=2;$i<sizeof($files1);$i++){   
                $sql = "INSERT INTO tbl_splitted_datasets(DATASET_ID,MIME,FILE) VALUES(?,?,?)";
                $blob = fopen($dir."/".$scanned_directory[$i], 'rb');
                //echo $dir."\\".$scanned_directory[$i];
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(1,$currentID);
                $stmt->bindParam(2,$file_type);
                $stmt->bindParam(3,$blob,PDO::PARAM_LOB);
                if($stmt->execute()){
                        $query2=$query2+1;
                }
        }
        $isCountone = (count($question) == 1);
        $isEmptyquestion = empty($question[0]);
        $value = $isCountone && $isEmptyquestion;
        // var_dump($isCountone);
        // var_dump($isEmptyquestion);
        // var_dump($value);

        if(!$value){
        if($query2==sizeof($scanned_directory)){
                $query3=0;
                //Insert questions from the dataset into page
                for($i=0;$i<count($question);$i++){
                        $stmtques = $dbh->prepare("insert into tbl_dataset_questions values(?,'',?)");
                        $stmtques->bindParam(1,$currentID);
                        $stmtques->bindParam(2,$question[$i]);
                        if($stmtques->execute()){
                                $query3=$query3+1;
                        }
                }
                if($query3=count($question)){
                    header("Location: ../datasetsView.php");
                }
                else{
                        echo "Some questions are not inserted properly. Please delete the entire dataset and try again";
                }
        }
    }else{
        header("Location: ../datasetsView.php");
    }

}
else{
   //     print_r($dbh->errorInfo());

        echo "Something went wrong. Please try again page";
}
 } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
?>