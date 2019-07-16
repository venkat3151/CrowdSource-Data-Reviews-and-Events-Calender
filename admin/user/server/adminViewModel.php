<?php
class AdminModel{
	
	// const DB_HOST = 'localhost';
 //    const DB_NAME = 'spectrum';
 //    const DB_USER = 'root';
	// const DB_PASSWORD = '';
	
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

	
    public function adminview(){
		$sql = "SELECT * from tbl_admin WHERE STATUS='APPROVED' ORDER BY ADMIN_ID DESC";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
    }
    
    public function admindelete($EMAIL){
        $sql = "DELETE from tbl_admin where EMAIL='$EMAIL'";
        $stmt = $this->pdo->prepare($sql);
      
        return $stmt->execute();

    }

    public function adminSelect($EMAIL){
        $sql = "SELECT * from tbl_admin where EMAIL='$EMAIL'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function adminUpdate($EMAIL,$FULL_NAME,$ROLE){
       

        $sql  = "UPDATE tbl_admin SET FULL_NAME='$FULL_NAME', ROLE='$ROLE' WHERE EMAIL='$EMAIL'";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute();

    }

    public function adminValidation($EMAIL,$PASSWORD){
        $sql = "SELECT tbl_admin.EMAIL, tbl_user_roles.ROLE, tbl_admin.PASSWORD FROM tbl_admin INNER JOIN tbl_user_roles ON tbl_admin.ROLE=tbl_user_roles.ROLE WHERE EMAIL='$EMAIL'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
         if($EMAIL==$result[0][0] and $PASSWORD==$result[0][2] and $result[0][1]=='super'){

             header("Location: ../homepage.php");}
        
         else if($EMAIL==$result[0][0] and $PASSWORD==$result[0][2] and $result[0][1]=='crowd'){
                header("Location: ../../crowdsource/datasetsView.php");
         }
        else if($EMAIL==$result[0][0] and $PASSWORD==$result[0][2] and $result[0][1]=='event'){
                header("Location: ../../events/eventsAdmin.php");
        }
        else{
            header("Location: http://stark.cse.buffalo.edu/ubspectrum/admin/user/signin.php?invalid=true");
        }
    }

    public function adminPendingList(){

        $sql = "SELECT * from tbl_admin where STATUS='PENDING'";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
        
    }

    public function adminApprove($email){
        //echo $email;
        $sql = "UPDATE tbl_admin SET STATUS='APPROVED' WHERE EMAIL='$email'";
        $stmt = $this->pdo->prepare($sql);
        if($stmt->execute()){
            return 1;
        }
    }

    public function adminReject($email){

        //echo $email;

        $sql = "UPDATE tbl_admin SET STATUS='REJECT' WHERE EMAIL='$email'";
        $stmt = $this->pdo->prepare($sql);
        if($stmt->execute()){
            return 1;
        }
    }

    

	//Desctruction of DB Object
	public function __destruct() {
       
        $this->pdo = null;
    }

}


 ?>