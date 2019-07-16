<?php
    require_once "DatabaseConnector.php";

    class EventContacts extends DatabaseConnector {

        public static function getAll($eventId){
            $temparray = array();
            $conn = self::getDB();

            $fetchQuery =$conn->prepare("SELECT `CONTACT_ID`,
            `EVENT_ID`,
            `CONTACT_TYPE`,
            `PERSON_NAME`,
            `ADDITIONAL_INFO`
            FROM tbl_event_contacts WHERE EVENT_ID = ?");

            $fetchQuery->bind_param("i", $eventId);
            $fetchQuery->execute();
            $result = $fetchQuery->get_result();
            if($result != NULL){
                while($row = mysqli_fetch_assoc($result))
                {
                    $temparray[] = $row;
                }
            }

            return $temparray;
        }

        public static function getRecurringAll($eventId){
            $temparray = array();
            $conn = self::getDB();

            $fetchQuery =$conn->prepare("SELECT `CONTACT_ID`,
            `EVENT_ID`,
            `CONTACT_TYPE`,
            `PERSON_NAME`,
            `ADDITIONAL_INFO`
            FROM tbl_recur_event_contacts WHERE EVENT_ID = ?");

            $fetchQuery->bind_param("i", $eventId);
            $fetchQuery->execute();
            $result = $fetchQuery->get_result();
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
