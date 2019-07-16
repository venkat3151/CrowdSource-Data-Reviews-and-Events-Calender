<?php

require_once "DatabaseConnector.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

class StudentUpdate extends DatabaseConnector {


  public static function studentUpdateRecurCheck($email, $token) {
    $conn = self::getDB();

    $email = $conn->real_escape_string($email);
    $token = $conn->real_escape_string($token);

    $checkCredentials = "SELECT RECURING_EVENT_ID, APPROVAL_STATUS, ADDED_BY, UPDATE_TOKEN from tbl_recur_events WHERE ADDED_BY='".$email."'";

    // get result
    $result = mysqli_query($conn, $checkCredentials);
    if($result != NULL){
        while($row = mysqli_fetch_assoc($result))
        {
            if ($row['UPDATE_TOKEN'] == $token) {
              return $row;
              exit();
            }
        }
    } else {
      echo "none";
    }
  }
  public static function studentUpdateCheck($email, $token) {
    $conn = self::getDB();

    $email = $conn->real_escape_string($email);
    $token = $conn->real_escape_string($token);

    $checkCredentials = "SELECT ID, APPROVAL_STATUS, ADDED_BY, UPDATE_TOKEN from tbl_events WHERE ADDED_BY='".$email."'";

    // get result
    $result = mysqli_query($conn, $checkCredentials);
    if($result != NULL){
        while($row = mysqli_fetch_assoc($result))
        {
            if ($row['UPDATE_TOKEN'] == $token) {
              return $row;
              exit();
            }
        }
    } else {
      echo "none";
    }
  }

}

 ?>
