<?php

  require_once "../../../events/Models/DatabaseConnector.php";

  class Login extends DatabaseConnector {

    public static function checkCredentials($username, $password) {
      // connect to database
      $conn = self::getDB();

      // process and call query
      $username = $conn->real_escape_string($username);
      $checkCredentials = "SELECT * from tbl_admin WHERE EMAIL='".$username."'";
      
      // get result
      $result = mysqli_query($conn, $checkCredentials);

      
      if ($result != NULL) {
        // get the result
        $r = mysqli_fetch_assoc($result);
        if ($r != NULL) {
        // verify the password
     //   print_r($r);
          
        if (hash("sha3-256", $password) == $r['PASSWORD']) {
          session_start();
          $_SESSION['admin']=$r;
          if ($r['ROLE'] == "super") {
           
            // start the session if the username and password is correct
            session_start();
            $_SESSION['sessionID'] = session_id();
            $_SESSION['sessionUser'] = $username;
          // redirect to the homepage
          include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/superHeader.php";
          } 
        else if($r['ROLE'] == "crowd")
          {
            
              // start the session if the username and password is correct
              session_start();
              $_SESSION['sessionID'] = session_id();
              $_SESSION['sessionUser'] = $username;
            // redirect to the homepage
            include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/crowdHeader.php";
          }
       
        else if($r['ROLE'] == "event")
          {
           
              // start the session if the username and password is correct
              session_start();
              $_SESSION['sessionID'] = session_id();
              $_SESSION['sessionUser'] = $username;
            // redirect to the homepage
            header("Location: ubsepctrum/admin/events/eventsAdmin.php");
          }
 
        
          }
          else{
            header("Location: ../signin.php?invalid=true");
          }
        
        }
        else {
          header("Location: ../signin.php?invalid=true");
        }
      } else {
        header("Location: ../signin.php?invalid=true");
      }
    } 


    public static function signUp($firstName, $lastName, $email, $password1, $role) {
      $conn = self::getDB();

       // $host="localhost";
       // $username="root";
       // $password="";
       // $databasename="spectrum";
       // $dbh = new PDO('mysql:host=localhost;dbname=spectrum', $username, $password);


      // $fname = $conn->real_escape_string($firstName);
      // $lname = $conn->real_escape_string($lastName);
      // $em = $conn->real_escape_string($email);
      // $pass = $conn->real_escape_string($password);
      // $role = $conn->real_escape_string($role);
      



      $hash = hash("sha3-256", $password1);
      $fullName = $firstName ." ". $lastName;
      $tempToken = uniqid();
      
      $status = "PENDING";

       
         
      $stmt = $conn->prepare("INSERT INTO tbl_admin(EMAIL, PASSWORD,FULL_NAME,TEMP_TOKEN, STATUS, ROLE) VALUES ('$email','$hash','$fullName','$tempToken','$status', '$role');");

                

                // $stmt->bind_param(
                // $email,
                // $hash,
                // $fullName,
                // $tempToken,$status);

 

      // $stmt = $dbh->prepare("insert into tbl_admin values('',?,?,?,?,?)");
      // $stmt->bindParam(1,$email);
      // $stmt->bindParam(2,$hash);
      // $stmt->bindParam(3,$fullName);
      // $stmt->bindParam(4,$tempToken);
      // $stmt->bindParam(5,$pending);



                $stmt->execute();
                $stmt->close();

               header("Location: /ubspectrum/admin/user/signin.php");
    }
  }




 ?>
