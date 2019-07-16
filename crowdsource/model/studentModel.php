<?php
class StudentModel{
	
	// const DB_HOST = 'localhost';
 //    const DB_NAME = 'spectrum';
 //    const DB_USER = 'root';
 //    const DB_PASSWORD = ''; 


    const DB_HOST = 'stark.cse.buffalo.edu';
    const DB_NAME = 'spectrum';
    const DB_USER = 'spectrum_user';
    const DB_PASSWORD = 'Spectrum2019!'; 
	private $pdo = null;
	

    // Creation of DB Object
    public function __construct() {
        $conStr = sprintf("mysql:host=%s;dbname=%s;charset=utf8", self::DB_HOST, self::DB_NAME);

        try {
            $this->pdo = new PDO($conStr, self::DB_USER, self::DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
	
	// Display all the available datasets for the student
	public function displayAllDatasets() {

        $sql = "SELECT distinct(a.DATASET_ID),a.DATASET_NAME,a.DATASET_DESCRIPTION  FROM tbl_current_datasets a,tbl_splitted_datasets b  WHERE a.PUBLISH='1' and a.ARCHIVE='0' and  a.DATASET_ID=b.DATASET_ID and UBIT_NAME is NULL";

        $stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
    } 


    public function checkUserLogin($ubit_name,$password){
    	$sql = "SELECT * FROM tbl_reviewers WHERE UBIT_NAME=? AND user_password=?";
    	$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1, $ubit_name, PDO::PARAM_STR,12);
		$stmt->bindParam(2, $password, PDO::PARAM_STR,12);        
		$stmt->execute();
		$result = $stmt->fetchAll();
		//echo $result;
		return $result;

    }


	// To display the dataset assigned to a student.
	 public function displayAssignedDataSet($ubitname,$did)

	{
	//	$ubName=$ubitname;
		$sql = "SELECT a.DATASET_NAME,a.DATASET_DESCRIPTION,a.DATASET_ID,b.SPLIT_FILE_ID from tbl_splitted_datasets b,tbl_current_datasets a WHERE b.UBIT_NAME=? and b.DATASET_ID=? and b.DATASET_ID=a.DATASET_ID" ;
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1, $ubitname, PDO::PARAM_STR,12);
		$stmt->bindParam(2, $did, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}


// To display the dataset assigned to a student.
	 public function displayReviewedAnswers($ubit_name,$did,$splitId)

	{
		$sql = "SELECT b.ANSWER,b.edits from tbl_review_answers b WHERE b.DATASET_ID=? and  b.split_file_id=? and b.UBIT_NAME=?" ;
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$did , PDO::PARAM_INT);
		$stmt->bindParam(2,$splitId , PDO::PARAM_INT);
		$stmt->bindParam(3,$ubit_name , PDO::PARAM_STR,12);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}

	public function displayAnswers($ubit_name,$did)

	{
		$sql = "SELECT b.ANSWER from tbl_review_answers b WHERE b.DATASET_ID=? and b.UBIT_NAME=?" ;
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$did , PDO::PARAM_INT);
		$stmt->bindParam(2,$ubit_name , PDO::PARAM_STR,12);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}

	//To display the questions of the particulat dataset
	public function displayQuestions($did)

	{
		$sql = "SELECT b.QUESTION_ID,b.QUESTION from tbl_dataset_questions b,tbl_current_datasets a WHERE b.DATASET_ID=? and  b.DATASET_ID=a.DATASET_ID" ;
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$did , PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}


	public function submitReview($did,$splitId,$ubname,$answer,$poll)

	{
		$sql = "SELECT QUESTION_ID from  tbl_dataset_questions WHERE DATASET_ID=?" ;
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$did , PDO::PARAM_INT);
		if($stmt->execute()){
			$result = $stmt->fetchAll();			
			$counter=0;
			for($i=0;$i<count($answer);$i++){
					$sql = "INSERT INTO tbl_review_answers(DATASET_ID,SPLIT_FILE_ID,QUESTION_ID,UBIT_NAME,ANSWER,edits) values(?,?,?,?,?,'[]')" ;
					$stmt = $this->pdo->prepare($sql);
					$stmt->bindParam(1,$did , PDO::PARAM_INT);
					$stmt->bindParam(2,$splitId , PDO::PARAM_INT);
					$stmt->bindParam(3,$result[$i]['QUESTION_ID'] , PDO::PARAM_INT);
					$stmt->bindParam(4,$ubname , PDO::PARAM_STR,12);
					$stmt->bindParam(5,$answer[$i] , PDO::PARAM_STR,12);
					// $stmt->bindParam(6,$)
					if($stmt->execute()){
						$counter=$counter+1;
					}
				} 
				if($counter==count($answer)){
				$sql1 = "UPDATE tbl_splitted_datasets set FILE_SUBMITTED_TIME=CURRENT_TIMESTAMP,Poll=? where SPLIT_FILE_ID=?" ;
				$stmt = $this->pdo->prepare($sql1);
				$stmt->bindParam(1,$poll , PDO::PARAM_STR,12);
				$stmt->bindParam(2,$splitId , PDO::PARAM_INT);
				if($stmt->execute()){
					return 1;
				}
				else{
					return 0;
				}
			}
				else{
					return 0;
				}
		}
		else{
			return -1;
		}
	}

 public function selectBlob($split_id) {

        $sql = "SELECT mime,
                        file,ubit_name
                   FROM tbl_splitted_datasets
                  WHERE split_file_id = :split_file_id;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(":split_file_id" => $split_id));
        $stmt->bindColumn(1, $mime);
        $stmt->bindColumn(2, $file, PDO::PARAM_LOB);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    } 

     public function submitEdit($did,$splitId,$ubname,$answer,$poll) {

     
		$q= "SELECT edits,QUESTION_ID from  tbl_review_answers WHERE DATASET_ID=? and SPLIT_FILE_ID=? and UBIT_NAME=?";
		$stmt1 = $this->pdo->prepare($q);
     	$stmt1->bindParam(1,$did , PDO::PARAM_INT);
     	$stmt1->bindParam(2,$splitId , PDO::PARAM_INT);
     	$stmt1->bindParam(3,$ubname , PDO::PARAM_STR,12);
		$stmt1->execute();
     	$qs=$stmt1->fetchAll();
     	$count=sizeof($qs);
     	$c=0;
        for($i=0;$i<sizeof($qs);$i++){
     	    $json=json_decode($qs[$i]['edits'],TRUE);
	     	array_push($json, $answer[$i]);
	     	$encoded=json_encode($json);     
     	 	$qid=$qs[$i]['QUESTION_ID'];
     	    $sql1 = "UPDATE tbl_review_answers set edits='$encoded' where SPLIT_FILE_ID='$splitId' and QUESTION_ID='$qid' and UBIT_NAME='$ubname'";
        	$stm=$this->pdo->prepare($sql1);
 			if($stm->execute())
 			{
 				$c=$c+1;
 			}
 		}
 		if($count==$c){
			$sql1 = "UPDATE tbl_splitted_datasets set FILE_SUBMITTED_TIME=CURRENT_TIMESTAMP,Poll=? where SPLIT_FILE_ID=?" ;
			$stmt = $this->pdo->prepare($sql1);
			$stmt->bindParam(1,$poll , PDO::PARAM_STR,12);
			$stmt->bindParam(2,$splitId , PDO::PARAM_INT);
			if($stmt->execute()){
				return true;
			}
			else{
				return false;
			}
 		
 		}else{
 			return false;
 		}

   
    } 



         public function getDataset($did) {

     
		$q= "SELECT DATASET_NAME,DATASET_DESCRIPTION from  tbl_current_datasets WHERE DATASET_ID=?";
		$stmt1 = $this->pdo->prepare($q);
     	$stmt1->bindParam(1,$did , PDO::PARAM_INT);
		$stmt1->execute();
     	$qs=$stmt1->fetch(PDO::FETCH_ASSOC);
 		return $qs;
		 }
		 
		 public function getPassword($UBIT)
		 {
			 $sql = "SELECT user_password from tbl_reviewers where ubit_name = '$UBIT'";
			 $stmt1 = $this->pdo->prepare($sql);
			 $stmt1->execute();
			 return $stmt1->fetchAll();


		 }

		 public function registerUser($ubit_name,$first_name,$last_name,$password,$token){
			 $sql= " insert into tbl_reviewers values(?,?,?,?,?)";
			 $stmt1 = $this->pdo->prepare($sql);
			 $stmt1->bindParam(1,$ubit_name , PDO::PARAM_STR,12);
			 $stmt1->bindParam(2,$first_name , PDO::PARAM_STR,12);
			 $stmt1->bindParam(3,$last_name , PDO::PARAM_STR,12);
			 $stmt1->bindParam(4,$password , PDO::PARAM_STR,12);
			 $stmt1->bindParam(5,$token , PDO::PARAM_STR,12);
			return $stmt1->execute();

		 }
		 public function checkSubmission($ubit_name,$dataset_id){
			$sql = "SELECT * FROM tbl_splitted_datasets WHERE UBIT_NAME=? AND DATASET_ID=?";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindParam(1, $ubit_name, PDO::PARAM_STR,12);
			$stmt->bindParam(2, $dataset_id, PDO::PARAM_STR,12);        
			$stmt->execute();
			$result = $stmt->fetchAll();
			//echo $result;
			return $result;
	
		}
		public function selectSplitFile($ubit_name,$dataset_id){
			$sql="SELECT MIN(SPLIT_FILE_ID) AS ID FROM tbl_splitted_datasets WHERE UBIT_NAME is NULL and  DATASET_ID='$dataset_id'";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll();
			$b=$result[0]['ID'];
			$s=("UPDATE tbl_splitted_datasets set UBIT_NAME='$ubit_name',FILE_ASSIGNED_TIME=CURRENT_TIMESTAMP WHERE DATASET_ID='$dataset_id' and SPLIT_FILE_ID='$b'");
			$st = $this->pdo->prepare($s);
			if($st->execute()){
				return $b;
			}else return 0;

		}
		public function displayReviewedDatasets($ubit_name){
			$sql="SELECT b.DATASET_ID,b.DATASET_NAME,b.DATASET_DESCRIPTION from tbl_splitted_datasets a, tbl_current_datasets b where a.UBIT_NAME='$ubit_name' and a.DATASET_ID=b.DATASET_ID and b.ARCHIVE=0 and b.PUBLISH=1";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;

		}

		 
		public function checkUser($ubname)
		{
			$sql = "SELECT * from tbl_reviewers where ubit_name = '$ubname'";
			$stmt1 = $this->pdo->prepare($sql);
			$stmt1->execute();
			return $stmt1->fetchAll();
	

		}


   
    
	//Desctruction of DB Object
	public function __destruct() {
       
        $this->pdo = null;
    }

}


 ?>