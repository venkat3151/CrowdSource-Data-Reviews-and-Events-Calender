<?php
class AdminModel{
	
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
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
	

	public function archivedDatasetList(){

        
		$sql = "SELECT DATASET_NAME,DATASET_DESCRIPTION,DATASET_ID from tbl_current_datasets WHERE ARCHIVE='1' order by DATASET_ID desc" ;
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
    }
    
    public function undoArchive($dataset_id){

        $sql = "UPDATE  tbl_current_datasets set ARCHIVE='0' where DATASET_ID=".$dataset_id;
        $stmt1=$this->pdo->prepare($sql);
        echo "executed";
        return  $stmt1->execute();
        echo "super";
        
    }

    public function deleteArchive($id){
        $datasetdelete="DELETE from  spectrum.tbl_archived_datasets where dataset_id=".$id;
        $splitdelete="DELETE from  spectrum.tbl_archived_splits where dataset_id=".$id;
        $answersdelete="DELETE from  spectrum.tbl_archived_answers where dataset_id=".$id;
        $questionsdelete="DELETE from  spectrum.tbl_archived_questions where dataset_id=".$id;
            $stmt1 = $this->pdo->prepare($datasetdelete);
            $stmt2=$this->pdo->prepare($splitdelete);
            $stmt3=$this->pdo->prepare($answersdelete);
            $stmt4=$this->pdo->prepare($questionsdelete);
           if($stmt3->execute() and $stmt4->execute() and $stmt2->execute() and $stmt1->execute()){
            return 1;
             }
            else{ 
                    return 0;
                }
    }

 public function selectBlob($dataset_id) {

        $sql = "SELECT dataset_filetype,
                        dataset_file,dataset_name
                   FROM tbl_archived_datasets
                  WHERE DATASET_ID = :datasetId;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(":datasetId" => $dataset_id));
        $stmt->bindColumn(1, $dataset_filetype);
        $stmt->bindColumn(2, $dataset_file, PDO::PARAM_LOB);
        $stmt->bindColumn(3, $dataset_name, PDO::PARAM_STR,12);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    } 
        public function selectCurrentBlob($dataset_id) {

        $sql = "SELECT dataset_filetype,
                        dataset_file,dataset_name
                   FROM tbl_current_datasets
                  WHERE DATASET_ID = :datasetId;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(":datasetId" => $dataset_id));
        $stmt->bindColumn(1, $dataset_filetype);
        $stmt->bindColumn(2, $dataset_file, PDO::PARAM_LOB);
        $stmt->bindColumn(3, $dataset_name, PDO::PARAM_STR,12);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    } 

    public function getallData($dataset_id) {

        $sql = "select * from tbl_splitted_datasets where DATASET_ID=".$dataset_id;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    } 

    public function getUbitName($dataset_id) {

        $sql = "select DISTINCT UBIT_NAME from tbl_splitted_datasets where UBIT_NAME is NOT NULL AND DATASET_ID=".$dataset_id;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    } 

    public function getSplitFile($dataset_id,$ubit_name) {

        $sql = "select * from tbl_splitted_datasets where DATASET_ID=".$dataset_id." and UBIT_NAME='".$ubit_name."'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    } 

    public function getDatasetData($dataset_id) {

        $sql = "select * from tbl_current_datasets where DATASET_ID=".$dataset_id;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    } 

     public function getCurrentDatasets() {

        $sql = "select * from tbl_current_datasets WHERE ARCHIVE='false' ORDER BY DATASET_ID desc";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    } 

    public function getDatasetQuestions($dataset_id) {

        $sql = "select QUESTION from tbl_dataset_questions where DATASET_ID=".$dataset_id;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    } 

    public function publishDataset($dataset_id){

        $sql = "UPDATE  tbl_current_datasets set PUBLISH='1' where DATASET_ID=".$dataset_id;
        $stmt1=$this->pdo->prepare($sql);
        return  $stmt1->execute();
        

    }

    public function unPublishDataset($dataset_id){

        $sql = "UPDATE  tbl_current_datasets set PUBLISH='0' where DATASET_ID=".$dataset_id;
        $stmt1=$this->pdo->prepare($sql);
        return  $stmt1->execute();
        

    } 

    public function getTransactions() {

        $sql = "select * from tbl_transaction ORDER BY ID desc";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    } 
    
    public function check($dataset_id)
    {
        $count = "SELECT COUNT(*) as count FROM tbl_splitted_datasets WHERE  UBIT_NAME is null AND DATASET_ID=".$dataset_id;
        $stmt2=$this->pdo->prepare($count);
        $stmt2->execute();
        $result=$stmt2->fetch();
       return $result;

    }

    public function archiveDataset($dataset_id){

        $a= new AdminModel();
        $sow = $a->check($dataset_id);
        $sara=$sow[0];
       
        if($sara==0){
        $sql = "UPDATE  tbl_current_datasets set ARCHIVE='1' where DATASET_ID=".$dataset_id;
        $stmt1=$this->pdo->prepare($sql);
        return  $stmt1->execute();
        }
        else{
            return 0;
        }
        
      
     
        
        
        

    }


    public function getNumOfSplits($dataset_id){

     
        $sql = "select count(*) as num from tbl_splitted_datasets where DATASET_ID=".$dataset_id;
        $stmt1=$this->pdo->prepare($sql);
        $stmt1->execute();
        $result=$stmt1->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    

    public function getStudentsReviewed($dataset_id){

     
        $sql = "select count(*) as num from tbl_splitted_datasets where UBIT_NAME IS NOT NULL AND FILE_SUBMITTED_TIME IS NOT NULL AND DATASET_ID=".$dataset_id;
        $stmt1=$this->pdo->prepare($sql);
        $stmt1->execute();
        $result=$stmt1->fetch(PDO::FETCH_ASSOC);
        return $result;
    }



	//Desctruction of DB Object
	public function __destruct() {
       
        $this->pdo = null;
    }

}


 ?>