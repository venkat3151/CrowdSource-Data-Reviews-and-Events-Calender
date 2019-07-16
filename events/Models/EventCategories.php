<?php
    require_once "DatabaseConnector.php";

    class EventCategories extends DatabaseConnector {

        public static function getAll(){
            $temparray = array();
            $conn = self::getDB();

            $fetchCategoriesQuery = "SELECT CATEGORY_ID, NAME, ICON, DESCRIPTION, COLOR FROM tbl_categories";

            $result = mysqli_query($conn,$fetchCategoriesQuery);
            if($result != NULL){
                while($row = mysqli_fetch_assoc($result))
                {
                    $temparray[] = $row;
                }
            }

            return $temparray;
        }

        public static function getCategoriesForEvent($eventId) {
            $temparray = array();
            $conn = self::getDB();

            $fetchCategoriesQuery =$conn->prepare("SELECT tbl_categories.CATEGORY_ID AS CATEGORY_ID, tbl_categories.NAME AS NAME, tbl_categories.ICON AS ICON, tbl_categories.DESCRIPTION AS DESCRIPTION, tbl_categories.COLOR AS COLOR FROM tbl_categories INNER JOIN tbl_event_categories ON tbl_categories.CATEGORY_ID = tbl_event_categories.CATEGORY_ID WHERE tbl_event_categories.EVENT_ID = ?");
            $fetchCategoriesQuery->bind_param("i", $eventId);
            $fetchCategoriesQuery->execute();
            $result = $fetchCategoriesQuery->get_result();
            if($result != NULL){
                while($row = mysqli_fetch_assoc($result))
                {
                    $temparray[] = $row;
                }
            }

            return $temparray;
        }

        public static function getRecurringCategoriesForEvent($eventId){
            $temparray = array();
            $conn = self::getDB();

            $fetchCategoriesQuery =$conn->prepare("SELECT tbl_categories.CATEGORY_ID AS CATEGORY_ID, tbl_categories.NAME AS NAME, tbl_categories.ICON AS ICON, tbl_categories.DESCRIPTION AS DESCRIPTION, tbl_categories.COLOR AS COLOR FROM tbl_categories INNER JOIN tbl_recur_event_categories ON tbl_categories.CATEGORY_ID = tbl_recur_event_categories.CATEGORY_ID WHERE tbl_recur_event_categories.EVENT_ID = ?");
            $fetchCategoriesQuery->bind_param("i", $eventId);
            $fetchCategoriesQuery->execute();
            $result = $fetchCategoriesQuery->get_result();
            if($result != NULL){
                while($row = mysqli_fetch_assoc($result))
                {
                    $temparray[] = $row;
                }
            }

            return $temparray;
        }

    }

?>
