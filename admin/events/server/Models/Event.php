<?php
  require_once "../../../events/Models/DatabaseConnector.php";
  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  class Event extends DatabaseConnector {

        public static function getAll() {
          $conn = self::getDB();
          $temparray = array();

          $fetchEventsQuery = "SELECT RECURING_EVENT_ID, APPROVAL_STATUS, NAME, VENUE, DATE_FORMAT(START_TIME, '%Y-%m-%dT%TZ') AS START_TIME,DATE_FORMAT(END_TIME, '%Y-%m-%dT%TZ') AS END_TIME, CATEGORY, DESCRIPTION, LINK, PHONE, EMAIL  FROM tbl_recur_events";
          $result = mysqli_query($conn,$fetchEventsQuery);

          if($result != NULL){
              while($row = mysqli_fetch_assoc($result))
              {
                  $temparray[] = $row;
              }
          }
          return $temparray;
        }

        public static function getRecurringEvent($id) {
          // connect to database
          $conn = self::getDB();

          // process and call query
          $id = $conn->real_escape_string($id);
          $id = (int) $id;

          $eventInfo = "SELECT NAME, VENUE, START_TIME, END_TIME, DESCRIPTION, LINK, COST, PHONE, EMAIL, UB_CAMPUS_LOCATION, RECURING_EVENT_ID, ADDED_BY, LAST_DATE, APPROVAL_STATUS, REPEAT_BY FROM tbl_recur_events WHERE RECURING_EVENT_ID = '$id'";
          $result = mysqli_query($conn, $eventInfo);

          if ($result != NULL) {
            // get the result
            $r = mysqli_fetch_assoc($result);

            return json_encode($r);
          }
        }

        public static function getEventInfo($id) {
          // connect to database
          $conn = self::getDB();

          // process and call query
          $username = $conn->real_escape_string($id);
          $eventInfo = "SELECT ID, NAME, VENUE, START_TIME, END_TIME, DESCRIPTION, LINK, COST, PHONE, EMAIL, UB_CAMPUS_LOCATION, ADDED_BY from tbl_events WHERE ID='".$id."'";

          // get result
          $result = mysqli_query($conn, $eventInfo);

          if ($result != NULL) {
            // get the result
            $r = mysqli_fetch_assoc($result);

            return json_encode($r);
          } else {
            //echo '<script type="text/javascript">', 'callAlert();','</script>';
          }
        }

        public static function getEventToken($id) {
          // connect to database
          $conn = self::getDB();

          // process and call query
          $username = $conn->real_escape_string($id);
          $eventInfo = "SELECT ID, NAME, UPDATE_TOKEN ADDED_BY from tbl_events WHERE ID='".$id."'";

          // get result
          $result = mysqli_query($conn, $eventInfo);

          if ($result != NULL) {
            // get the result
            $r = mysqli_fetch_assoc($result);

            return $r;
          } else {
            return NULL;
          }
        }

        public static function deleteEvent($eventId, $addedBy) {
          // connect to database
          $conn = self::getDB();

          // process and call query
          $eventId = $conn->real_escape_string($eventId);
          $addedBy = $conn->real_escape_string($addedBy);

          $eventInfo = "UPDATE tbl_events SET RECURING_EVENT_ID=NULL, APPROVAL_STATUS='delete' WHERE ID='".$eventId."'";

          // get result
          mysqli_query($conn, $eventInfo);

          $stmt =$conn->prepare("INSERT INTO tbl_transaction
              (`APPLICATION`,
              `UBIT_NAME`,
              `ACTION`,
              `TIMESTAMP`)
              VALUES
              (?,?,?,?)");

          $date = date("Y-m-d H:i:s");

          session_start();
          if (isset($_SESSION['sessionUser'])) {
              $user = $_SESSION['sessionUser'];
          } else {
            $user = $addedBy;
          }

          $delete = "Event: " . $eventId . " deleted";
          $application = "Events Calendar";

          $stmt->bind_param("ssss", $application, $user, $delete, $date);

          $stmt->execute();
          $stmt->close();
        }


        public static function deleteRecurringEvent($eventId, $addedBy) {
          // connect to database
          $conn = self::getDB();

          // process and call query
          $eventId = $conn->real_escape_string($eventId);
          $addedBy = $conn->real_escape_string($addedBy);

          $eventInfo = "UPDATE tbl_recur_events SET APPROVAL_STATUS='delete' WHERE RECURING_EVENT_ID='".$eventId."'";

          // get result
          mysqli_query($conn, $eventInfo);

          $eventId = $conn->real_escape_string($eventId);
          $eventInfo = "UPDATE tbl_events SET APPROVAL_STATUS='delete' WHERE RECURING_EVENT_ID='".$eventId."'";

          // get result
          mysqli_query($conn, $eventInfo);

          $stmt =$conn->prepare("INSERT INTO tbl_transaction
              (`APPLICATION`,
              `UBIT_NAME`,
              `ACTION`,
              `TIMESTAMP`)
              VALUES
              (?,?,?,?)");

          $date = date("Y-m-d H:i:s");

          session_start();
          if (isset($_SESSION['sessionUser'])) {
              $user = $_SESSION['sessionUser'];
          } else {
            $user = $addedBy;
          }

          $delete = "Recurring Event: " . $eventId . " deleted";
          $application = "Events Calendar";

          $stmt->bind_param("ssss", $application, $user, $delete, $date);

          $stmt->execute();
          $stmt->close();
        }

        public static function updateEvent($eventId, $name, $addedBy, $venue, $startTime, $endTime, $description, $link, $cost, $phone, $email,$ubCampusLocation="", $approvalStatus = "pending", $categories="", $contacts=array()) {
          $conn = self::getDB();

          $name = $conn->real_escape_string($name);
          $venue = $conn->real_escape_string($venue);
          $startTime = $conn->real_escape_string($startTime);
          $endTime = $conn->real_escape_string($endTime);
          $description = $conn->real_escape_string($description);
          $link = $conn->real_escape_string($link);
          $cost = $conn->real_escape_string($cost);
          $phone = $conn->real_escape_string($phone);
          $email = $conn->real_escape_string($email);
          $ubCampusLocation = $conn->real_escape_string($ubCampusLocation);
          $categories = $conn->real_escape_string($categories);
          $approvalStatus = $conn->real_escape_string($approvalStatus);
          $addedBy = $conn->real_escape_string($addedBy);
          $categories = $conn->real_escape_string($categories);
          $eventId = $conn->real_escape_string($eventId);

          $eventId = (int)$eventId;

          $stmt = $conn->prepare("UPDATE tbl_events SET
            NAME=?,
            VENUE=?,
            START_TIME=DATE_FORMAT('".$startTime."', '%Y-%m-%dT%TZ'),
            END_TIME=DATE_FORMAT('".$endTime."', '%Y-%m-%dT%TZ'),
            DESCRIPTION=?,
            LINK=?,
            COST=?,
            PHONE=?,
            EMAIL=?,
            UB_CAMPUS_LOCATION=?,
            APPROVAL_STATUS=?,
            ADDED_BY=? WHERE ID='".$eventId."'");

          $stmt->bind_param("ssssdsssss", $name, $venue, $description, $link,
            $cost, $phone, $email, $ubCampusLocation, $approvalStatus, $addedBy);

          $stmt->execute();
          $last_id = $conn->insert_id;
          $stmt->close();

          if(strlen($categories) > 0){
              $categoryIdList = explode(",",$categories);
              $stmt =$conn->prepare("DELETE FROM tbl_event_categories
                WHERE EVENT_ID='".$eventId."'");
                $stmt->execute();
                $stmt->close();

                $stmt =$conn->prepare("INSERT INTO tbl_event_categories
                    (`EVENT_ID`,
                    `CATEGORY_ID`)
                    VALUES
                    (?,?);
                ");
              foreach ($categoryIdList as $categoryId) {
                  $stmt->bind_param("ii", $eventId, $categoryId);
                  $stmt->execute();
              }
              $stmt->close();
          }

          if(sizeof($contacts) > 0){

            $stmt =$conn->prepare("DELETE FROM tbl_event_contacts
              WHERE EVENT_ID='".$eventId."'");
              $stmt->execute();
              $stmt->close();

              $stmt =$conn->prepare("INSERT INTO tbl_event_contacts
                  (`EVENT_ID`,
                   `CONTACT_TYPE`,
                   `PERSON_NAME`,
                   `ADDITIONAL_INFO`)
                  VALUES
                  (?,?,?,?);
              ");
              foreach ($contacts as $contact) {
                  $stmt->bind_param("isss", $eventId, $contact['type'], $contact['name'], $contact['info']);
                  $stmt->execute();
              }
              $stmt->close();
          }
          $stmt =$conn->prepare("INSERT INTO tbl_transaction
              (`APPLICATION`,
              `UBIT_NAME`,
              `ACTION`,
              `TIMESTAMP`)
              VALUES
              (?,?,?,?)");

          $date = date("Y-m-d H:i:s");

          session_start();
          if (isset($_SESSION['sessionUser'])) {
              $user = $_SESSION['sessionUser'];
          } else {
            $user = $addedBy;
          }

          $delete = "Event: " . $eventId . " updated";
          $application = "Events Calendar";

          $stmt->bind_param("ssss", $application, $user, $delete, $date);

          $stmt->execute();
          $stmt->close();
        }
        public static function updateRecurringEvent($repeat, $lastDay, $eventId, $name, $addedBy, $venue, $startTime, $endTime, $description, $link, $cost, $phone, $email,$ubCampusLocation="", $approvalStatus = "pending", $categories="", $contacts=array()) {
          $conn = self::getDB();

          $name = $conn->real_escape_string($name);
          $venue = $conn->real_escape_string($venue);
          $startTime = $conn->real_escape_string($startTime);
          $endTime = $conn->real_escape_string($endTime);
          $description = $conn->real_escape_string($description);
          $link = $conn->real_escape_string($link);
          $cost = $conn->real_escape_string($cost);
          $phone = $conn->real_escape_string($phone);
          $email = $conn->real_escape_string($email);
          $ubCampusLocation = $conn->real_escape_string($ubCampusLocation);
          $categories = $conn->real_escape_string($categories);
          $approvalStatus = $conn->real_escape_string($approvalStatus);
          $addedBy = $conn->real_escape_string($addedBy);
          $categories = $conn->real_escape_string($categories);
          $eventId = $conn->real_escape_string($eventId);
          $repeat = $conn->real_escape_string($repeat);
          $lastDay = $conn->real_escape_string($lastDay);

          $eventId = (int)$eventId;

          $stmt = $conn->prepare("UPDATE tbl_recur_events SET
            NAME=?,
            VENUE=?,
            START_TIME=DATE_FORMAT('".$startTime."', '%Y-%m-%dT%TZ'),
            END_TIME=DATE_FORMAT('".$endTime."', '%Y-%m-%dT%TZ'),
            DESCRIPTION=?,
            LINK=?,
            COST=?,
            PHONE=?,
            EMAIL=?,
            UB_CAMPUS_LOCATION=?,
            APPROVAL_STATUS=?,
            ADDED_BY=?,
            REPEAT_BY=?,
            LAST_DATE=? WHERE RECURING_EVENT_ID='".$eventId."'");

          $stmt->bind_param("ssssdsssssss", $name, $venue, $description, $link,
            $cost, $phone, $email, $ubCampusLocation, $approvalStatus, $addedBy,
            $repeat, $lastDay);

          $stmt->execute();
          $last_id = $conn->insert_id;
          $stmt->close();

          if(strlen($categories) > 0){
              $categoryIdList = explode(",",$categories);
              $stmt =$conn->prepare("DELETE FROM tbl_recur_event_categories
                WHERE EVENT_ID='".$eventId."'");
                $stmt->execute();
                $stmt->close();

                $stmt =$conn->prepare("INSERT INTO tbl_recur_event_categories
                    (`EVENT_ID`,
                    `CATEGORY_ID`)
                    VALUES
                    (?,?);
                ");
              foreach ($categoryIdList as $categoryId) {
                  $stmt->bind_param("ii", $eventId, $categoryId);
                  $stmt->execute();
              }
              $stmt->close();
          }

          if(sizeof($contacts) > 0){

            $stmt =$conn->prepare("DELETE FROM tbl_recur_event_contacts
              WHERE EVENT_ID='".$eventId."'");
              $stmt->execute();
              $stmt->close();

              $stmt =$conn->prepare("INSERT INTO tbl_recur_event_contacts
                  (`EVENT_ID`,
                   `CONTACT_TYPE`,
                   `PERSON_NAME`,
                   `ADDITIONAL_INFO`)
                  VALUES
                  (?,?,?,?);
              ");
              foreach ($contacts as $contact) {
                  $stmt->bind_param("isss", $eventId, $contact['type'], $contact['name'], $contact['info']);
                  $stmt->execute();
              }
              $stmt->close();
          }

          $stmt =$conn->prepare("INSERT INTO tbl_transaction
              (`APPLICATION`,
              `UBIT_NAME`,
              `ACTION`,
              `TIMESTAMP`)
              VALUES
              (?,?,?,?)");

          $date = date("Y-m-d H:i:s");

          session_start();
          if (isset($_SESSION['sessionUser'])) {
              $user = $_SESSION['sessionUser'];
          } else {
            $user = $addedBy;
          }

          $delete = "Recurring Event: " . $eventId . " updated";
          $application = "Events Calendar";

          $stmt->bind_param("ssss", $application, $user, $delete, $date);

          $stmt->execute();
          $stmt->close();

        }

        public static function updateFlyer($eventId, $additionalFile, $additionalFileSize, $additionalFileType) {
          $conn = self::getDB();
          $eventId = $conn->real_escape_string($eventId);
          $additionalFile= $conn->real_escape_string($additionalFile);
          $additionalFileSize = $conn->real_escape_string($additionalFileSize);
          $additionalFileType = $conn->real_escape_string($additionalFileType);

          $stmt = $conn->prepare("UPDATE tbl_events SET
            ADDITIONAL_FILE= '$additionalFile',
            ADDITIONAL_FILE_SIZE=?,
            ADDITIONAL_FILE_TYPE=? WHERE ID='".$eventId."'");

          $stmt->bind_param("is", $additionalFileSize, $additionalFileType);

          $stmt->execute();
          $last_id = $conn->insert_id;
          $stmt->close();
        }

        public static function updateRecurringFlyer($eventId, $additionalFile, $additionalFileSize, $additionalFileType) {
          $conn = self::getDB();
          $eventId = $conn->real_escape_string($eventId);
          $additionalFile= $conn->real_escape_string($additionalFile);
          $additionalFileSize = $conn->real_escape_string($additionalFileSize);
          $additionalFileType = $conn->real_escape_string($additionalFileType);

          $stmt = $conn->prepare("UPDATE tbl_recur_events SET
            ADDITIONAL_FILE= '$additionalFile',
            ADDITIONAL_FILE_SIZE=?,
            ADDITIONAL_FILE_TYPE=? WHERE RECURING_EVENT_ID='".$eventId."'");

          $stmt->bind_param("is", $additionalFileSize, $additionalFileType);

          $stmt->execute();
          $last_id = $conn->insert_id;
          $stmt->close();
        }

        public static function getEventsByRecurringId($id) {
          // connect to database
          $conn = self::getDB();

          // process and call query
          $id = $conn->real_escape_string($id);
          $id = (int) $id;

          $eventInfo = "SELECT ID, NAME, VENUE, APPROVAL_STATUS, RECURING_EVENT_ID, DATE_FORMAT(START_TIME, '%Y-%m-%dT%TZ') AS START_TIME,DATE_FORMAT(END_TIME, '%Y-%m-%dT%TZ') AS END_TIME, CATEGORY, DESCRIPTION FROM tbl_events WHERE RECURING_EVENT_ID = '$id'";
          $result = mysqli_query($conn, $eventInfo);
          $temparray = array();
          if($result != NULL){
              while($row = mysqli_fetch_assoc($result))
              {
                  $temparray[] = $row;
              }
          }
          return $temparray;
        }

        public static function updateAll($recurringId, $name, $addedBy, $venue, $startTime, $endTime, $description, $link,
         $cost, $phone, $email,$ubCampusLocation="", $approvalStatus = "pending", $categories="", $contacts=array()) {
          $conn = self::getDB();

          $name = $conn->real_escape_string($name);
          $venue = $conn->real_escape_string($venue);
          $startTime = $conn->real_escape_string($startTime);
          $endTime = $conn->real_escape_string($endTime);
          $description = $conn->real_escape_string($description);
          $link = $conn->real_escape_string($link);
          $cost = $conn->real_escape_string($cost);
          $phone = $conn->real_escape_string($phone);
          $email = $conn->real_escape_string($email);
          $ubCampusLocation = $conn->real_escape_string($ubCampusLocation);
          $categories = $conn->real_escape_string($categories);
          $approvalStatus = $conn->real_escape_string($approvalStatus);
          $addedBy = $conn->real_escape_string($addedBy);
          $categories = $conn->real_escape_string($categories);
          $recurringId = $conn->real_escape_string($recurringId);

          $recurringId = (int)$recurringId;
          $stmt = $conn->prepare("UPDATE tbl_events SET
            NAME=?,
            VENUE=?,
            DESCRIPTION=?,
            LINK=?,
            COST=?,
            PHONE=?,
            EMAIL=?,
            UB_CAMPUS_LOCATION=?,
            APPROVAL_STATUS=?,
            ADDED_BY=? WHERE RECURING_EVENT_ID='".$recurringId."'");

          $stmt->bind_param("ssssdsssss", $name, $venue, $description, $link,
            $cost, $phone, $email, $ubCampusLocation, $approvalStatus, $addedBy);

          $stmt->execute();
          $last_id = $conn->insert_id;
          $stmt->close();

          if(strlen($categories) > 0){
              $categoryIdList = explode(",",$categories);
              $stmt =$conn->prepare("DELETE FROM tbl_event_categories
                WHERE EVENT_ID='".$recurringId."'");
                $stmt->execute();
                $stmt->close();

                $stmt =$conn->prepare("INSERT INTO tbl_event_categories
                    (`EVENT_ID`,
                    `CATEGORY_ID`)
                    VALUES
                    (?,?);
                ");
              foreach ($categoryIdList as $categoryId) {
                  $stmt->bind_param("ii", $recurringId, $categoryId);
                  $stmt->execute();
              }
              $stmt->close();
          }

          if(sizeof($contacts) > 0){

            $stmt =$conn->prepare("DELETE FROM tbl_event_contacts
              WHERE EVENT_ID='".$recurringId."'");
              $stmt->execute();
              $stmt->close();

              $stmt =$conn->prepare("INSERT INTO tbl_event_contacts
                  (`EVENT_ID`,
                   `CONTACT_TYPE`,
                   `PERSON_NAME`,
                   `ADDITIONAL_INFO`)
                  VALUES
                  (?,?,?,?);
              ");
              foreach ($contacts as $contact) {
                  $stmt->bind_param("isss", $recurringId, $contact['type'], $contact['name'], $contact['info']);
                  $stmt->execute();
              }
              $stmt->close();

              // update master recurring event
              $stmt = $conn->prepare("UPDATE tbl_recur_events SET
                NAME=?,
                VENUE=?,
                DESCRIPTION=?,
                LINK=?,
                COST=?,
                PHONE=?,
                EMAIL=?,
                UB_CAMPUS_LOCATION=?,
                APPROVAL_STATUS=?,
                ADDED_BY=? WHERE RECURING_EVENT_ID='".$recurringId."'");

              $stmt->bind_param("ssssdsssss", $name, $venue, $description, $link,
                $cost, $phone, $email, $ubCampusLocation, $approvalStatus, $addedBy);

              $stmt->execute();
              $last_id = $conn->insert_id;
              $stmt->close();

              if(strlen($categories) > 0){
                  $categoryIdList = explode(",",$categories);
                  $stmt =$conn->prepare("DELETE FROM tbl_recur_event_categories
                    WHERE EVENT_ID='".$recurringId."'");
                    $stmt->execute();
                    $stmt->close();

                    $stmt =$conn->prepare("INSERT INTO tbl_recur_event_categories
                        (`EVENT_ID`,
                        `CATEGORY_ID`)
                        VALUES
                        (?,?);
                    ");
                  foreach ($categoryIdList as $categoryId) {
                      $stmt->bind_param("ii", $recurringId, $categoryId);
                      $stmt->execute();
                  }
                  $stmt->close();
              }

              if(sizeof($contacts) > 0){

                $stmt =$conn->prepare("DELETE FROM tbl_recur_event_contacts
                  WHERE EVENT_ID='".$recurringId."'");
                  $stmt->execute();
                  $stmt->close();

                  $stmt =$conn->prepare("INSERT INTO tbl_recur_event_contacts
                      (`EVENT_ID`,
                       `CONTACT_TYPE`,
                       `PERSON_NAME`,
                       `ADDITIONAL_INFO`)
                      VALUES
                      (?,?,?,?);
                  ");
                  foreach ($contacts as $contact) {
                      $stmt->bind_param("isss", $recurringId, $contact['type'], $contact['name'], $contact['info']);
                      $stmt->execute();
                  }
                  $stmt->close();
          }
        }
      }

      public static function getDeletedRecur() {
        $conn = self::getDB();
        $temparray = array();

        $fetchEventsQuery = "SELECT RECURING_EVENT_ID, NAME, VENUE, DATE_FORMAT(START_TIME, '%Y-%m-%dT%TZ') AS START_TIME,DATE_FORMAT(END_TIME, '%Y-%m-%dT%TZ') AS END_TIME, DESCRIPTION FROM tbl_recur_events WHERE APPROVAL_STATUS='delete'";
        $result = mysqli_query($conn,$fetchEventsQuery);

        if($result != NULL){
            while($row = mysqli_fetch_assoc($result))
            {
                $temparray[] = $row;
            }
        }

        return $temparray;
      }

      public static function getDeletedEvents() {
        $conn = self::getDB();
        $temparray = array();

        $fetchEventsQuery = "SELECT ID, NAME, VENUE, DATE_FORMAT(START_TIME, '%Y-%m-%dT%TZ') AS START_TIME,DATE_FORMAT(END_TIME, '%Y-%m-%dT%TZ') AS END_TIME, DESCRIPTION, RECURING_EVENT_ID FROM tbl_events WHERE APPROVAL_STATUS='delete'";
        $result = mysqli_query($conn,$fetchEventsQuery);

        if($result != NULL){
            while($row = mysqli_fetch_assoc($result))
            {
                $temparray[] = $row;
            }
        }

        return $temparray;
      }

      public static function undoDeleteEvent($eventId) {
        // connect to database
        $conn = self::getDB();

        // process and call query
        $eventId = $conn->real_escape_string($eventId);
        $eventInfo = "UPDATE tbl_events SET APPROVAL_STATUS='pending' WHERE ID='".$eventId."'";

        // get result
        mysqli_query($conn, $eventInfo);

        $stmt =$conn->prepare("INSERT INTO tbl_transaction
            (`APPLICATION`,
            `UBIT_NAME`,
            `ACTION`,
            `TIMESTAMP`)
            VALUES
            (?,?,?,?)");

        $date = date("Y-m-d H:i:s");

        session_start();
        if (isset($_SESSION['sessionUser'])) {
            $user = $_SESSION['sessionUser'];
        } else {
          $user = "user";
        }

        $delete = "Deleted Event: " . $eventId . " undeleted";
        $application = "Events Calendar";

        $stmt->bind_param("ssss", $application, $user, $delete, $date);

        $stmt->execute();
        $stmt->close();
      }

      public static function undoDeleteRecurringEvent($eventId) {
        // connect to database
        $conn = self::getDB();

        // process and call query
        $eventId = $conn->real_escape_string($eventId);
        $eventInfo = "UPDATE tbl_recur_events SET APPROVAL_STATUS='pending' WHERE RECURING_EVENT_ID='".$eventId."'";

        // get result
        mysqli_query($conn, $eventInfo);
      }

      public static function checkIfRecurringChild($eventId) {
        $conn = self::getDB();
        $temparray = array();

        $fetchEventsQuery = "SELECT RECURING_EVENT_ID FROM tbl_events WHERE ID='".$eventId."'";
        $result = mysqli_query($conn,$fetchEventsQuery);
        $row = mysqli_fetch_assoc($result);

        if ($result != NULL) {
          if ($row['RECURING_EVENT_ID'] != NULL) {
            return $row['RECURING_EVENT_ID'];
          } else {
            return "false";
          }
        } else {
          return "false";
        }

        $stmt =$conn->prepare("INSERT INTO tbl_transaction
            (`APPLICATION`,
            `UBIT_NAME`,
            `ACTION`,
            `TIMESTAMP`)
            VALUES
            (?,?,?,?)");

        $date = date("Y-m-d H:i:s");

        session_start();
        if (isset($_SESSION['sessionUser'])) {
            $user = $_SESSION['sessionUser'];
        } else {
          $user = "user";
        }

        $delete = "Deleted Recurring Event: " . $eventId . " undeleted";
        $application = "Events Calendar";

        $stmt->bind_param("ssss", $application, $user, $delete, $date);

        $stmt->execute();
        $stmt->close();
      }
    }
