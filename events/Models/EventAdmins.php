<?php

require_once "DatabaseConnector.php";

class EventAdmins extends DatabaseConnector {

    public static function getAll(){
        $temparray = array();
        $conn = self::getDB();
        $query = "SELECT ADMIN_ID, EMAIL FROM tbl_admin INNER JOIN tbl_user_roles ON tbl_admin.ADMIN_ID = tbl_user_roles.USER_ID WHERE tbl_admin.RANK = 1 AND tbl_user_roles.ROLE_ID = (select ROLE_ID From tbl_roles WHERE ROLE_NAME = '');";

        $result = mysqli_query($conn,$query);
        if($result != NULL){
            while($row = mysqli_fetch_assoc($result))
            {
                $temparray[] = $row;
            }
        }

        return $temparray;
    }

    protected static function getEmailField($value){
        return $value['Email'];
    }

    public static function getAllEmails(){
        $allAdmins = self::getAll();
        return array_map(array('EventAdmins', 'getEmailField'), $allAdmins)
    }
}


?>