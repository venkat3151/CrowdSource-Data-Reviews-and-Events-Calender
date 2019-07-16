<?php
    require_once "DatabaseConnector.php";

    class Events extends DatabaseConnector {

        public static function getAll($after = '', $before='', $categories='', $cost = '',$start='',$end='', $onlyApproved=FALSE, $campus=''){
            $temparray = array();
            $conn = self::getDB();

            $fetchEventsQuery = "SELECT ID, NAME, VENUE, APPROVAL_STATUS, RECURING_EVENT_ID, DATE_FORMAT(START_TIME, '%Y-%m-%dT%TZ') AS START_TIME,DATE_FORMAT(END_TIME, '%Y-%m-%dT%TZ') AS END_TIME, CATEGORY, DESCRIPTION, LINK, PHONE, EMAIL  FROM tbl_events ";

            if($onlyApproved){
                $fetchEventsQuery .= " WHERE APPROVAL_STATUS = 'accepted' ";
            } else {
                $fetchEventsQuery .= " WHERE APPROVAL_STATUS <>'delete' ";
            }

            if($campus != ''){
                $campus = $conn->real_escape_string($campus);
                $fetchEventsQuery .= " AND UB_CAMPUS_LOCATION = '$campus' ";
            }

            if($start != '' ){
                $start = $conn->real_escape_string($start);
                $fetchEventsQuery .= " AND START_TIME >= '$start' ";
            }

            if($end != '' ){
                $end = $conn->real_escape_string($end);
                $fetchEventsQuery .= " AND END_TIME <= '$end' ";
            }

            if($categories != NULL && $categories != ''){
            // $categoryIdList = $conn->real_escape_string($categories);
            $fetchEventsQuery = "SELECT ID, NAME, VENUE, APPROVAL_STATUS, DATE_FORMAT(START_TIME, '%Y-%m-%dT%TZ') AS START_TIME,DATE_FORMAT(END_TIME, '%Y-%m-%dT%TZ') AS END_TIME, CATEGORY, DESCRIPTION, LINK, PHONE, EMAIL  FROM tbl_events
                                WHERE APPROVAL_STATUS <>'delete' AND ID IN ( SELECT EVENT_ID FROM tbl_event_categories WHERE CATEGORY_ID IN ($categories))
                                ";

            }

            if($after !== ''){
                $after = $conn->real_escape_string($after);
                $fetchEventsQuery .= " AND TIME(START_TIME) >= CAST('$after' AS time) ";
            }

            if($before !== ''){
                $before = $conn->real_escape_string($before);
                $fetchEventsQuery .= " AND TIME(END_TIME) <= CAST('$before' AS time) ";
            }

            if($cost != ''){
                $cost = $conn->real_escape_string($cost);
                switch ($cost) {
                    case 'lt10':
                        $fetchEventsQuery .= " AND COST < 10 ";
                        break;
                    case 'lt20':
                        $fetchEventsQuery .= " AND COST BETWEEN 10 AND 20 ";
                        break;
                    case 'lt50':
                        $fetchEventsQuery .= " AND COST BETWEEN 20 AND 50 ";
                        break;
                    case 'lt100':
                        $fetchEventsQuery .= " AND COST BETWEEN 50 AND 100 ";
                        $costAmount = 100;
                        break;
                    case 'gt100':
                        $fetchEventsQuery .= " AND COST > 100 ";
                        break;
                    default:
                        break;
                }

            }

            $result = mysqli_query($conn,$fetchEventsQuery);
            if($result != NULL){
                while($row = mysqli_fetch_assoc($result))
                {
                    $temparray[] = $row;
                }
            }

            return $temparray;
        }

        public static function getEventFlyer($eventId){
            $temparray = array();
            $conn = self::getDB();
            $eventId = $conn->real_escape_string($eventId);
            $fetchFlyerQuery = "SELECT ID,NAME, ADDITIONAL_FILE, ADDITIONAL_FILE_SIZE, ADDITIONAL_FILE_TYPE FROM tbl_events WHERE ID=$eventId;";
            $result = mysqli_query($conn,$fetchFlyerQuery);

            if($result != NULL){
                while($row = mysqli_fetch_assoc($result))
                {
                    $temparray[] = $row;
                }
            }

            if(sizeof($temparray) > 0){
                return $temparray[0];
            } else {
                return NULL;
            }
        }

        public static function getRecurringEventFlyer($eventId){
            $temparray = array();
            $conn = self::getDB();
            $eventId = $conn->real_escape_string($eventId);
            $fetchFlyerQuery = "SELECT RECURING_EVENT_ID, NAME, ADDITIONAL_FILE, ADDITIONAL_FILE_SIZE, ADDITIONAL_FILE_TYPE FROM tbl_recur_events WHERE RECURING_EVENT_ID=$eventId;";
            $result = mysqli_query($conn,$fetchFlyerQuery);

            if($result != NULL){
                while($row = mysqli_fetch_assoc($result))
                {
                    $temparray[] = $row;
                }
            }

            if(sizeof($temparray) > 0){
                return $temparray[0];
            } else {
                return NULL;
            }
        }

        public static function addEvent($name, $addedBy, $venue, $startTime, $endTime, $description, $link, $cost, $phone, $email,$ubCampusLocation="", $additionalFile="",$additionalFileSize="",$additionalFileType="", $approvalStatus = "pending", $categories="", $contacts=array(), $updateToken){
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
            $additionalFile= $conn->real_escape_string($additionalFile);
            $additionalFileSize = $conn->real_escape_string($additionalFileSize);
            $additionalFileType = $conn->real_escape_string($additionalFileType);
            $categories = $conn->real_escape_string($categories);
            $approvalStatus = $conn->real_escape_string($approvalStatus);
            $addedBy = $conn->real_escape_string($addedBy);
            $categories = $conn->real_escape_string($categories);

            session_start();
            if ($_SESSION == array() || !isset($_SESSION['sessionID'])) {
              $approvalStatus = "pending";
            } else {
              $approvalStatus = "accepted";
            }

            $stmt = $conn->prepare("INSERT INTO tbl_events(
                NAME,
                VENUE,
                START_TIME,
                END_TIME,
                DESCRIPTION,
                LINK,
                COST,
                PHONE,
                EMAIL,
                UB_CAMPUS_LOCATION,
                ADDITIONAL_FILE,
                ADDITIONAL_FILE_SIZE,
                ADDITIONAL_FILE_TYPE,
                APPROVAL_STATUS,
                ADDED_BY,
                UPDATE_TOKEN) VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                '$additionalFile',
                ?,
                ?,
                ?,
                ?,
                ?)");

            $stmt->bind_param("ssssssdsssissss",$name,
                $venue,
                $startTime,
                $endTime,
                $description,
                $link,
                 $cost,
                $phone,
                $email,
                $ubCampusLocation,
                // $additionalFile,
                 $additionalFileSize,
                $additionalFileType,
                $approvalStatus,
                $addedBy,
                $updateToken);

            $stmt->execute();
            $last_id = $conn->insert_id;
            $stmt->close();

            if(strlen($categories) > 0){
                $categoryIdList = explode(",",$categories);
                $stmt =$conn->prepare("INSERT INTO tbl_event_categories
                    (`EVENT_ID`,
                    `CATEGORY_ID`)
                    VALUES
                    (?,?);
                ");
                foreach ($categoryIdList as $categoryId) {

                    $stmt->bind_param("ii", $last_id, $categoryId);
                    $stmt->execute();
                }
                $stmt->close();
            }

            if(sizeof($contacts) > 0){
                $stmt =$conn->prepare("INSERT INTO tbl_event_contacts
                    (`EVENT_ID`,
                     `CONTACT_TYPE`,
                     `PERSON_NAME`,
                     `ADDITIONAL_INFO`)
                    VALUES
                    (?,?,?,?);
                ");
                foreach ($contacts as $contact) {
                    $stmt->bind_param("isss", $last_id, $contact['type'], $contact['name'], $contact['info']);
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

            $delete = "Event: " . $eventId . " added";
            $application = "Events Calendar";

            $stmt->bind_param("ssss", $application, $user, $delete, $date);

            $stmt->execute();
            $stmt->close();

        }

        public static function getEventInfo($eventId){
            $fetchedEvent = array("ID"=>'', "NAME"=>'', "VENUE"=>'',"DATE"=> '', "START_TIME" => '', "END_TIME"=>'',"CATEGORY"=>'',"DESCRIPTION"=>'',"LINK"=>'',"PHONE"=>'',"EMAIL"=>'',"COST"=>'',"UB_CAMPUS_LOCATION"=>'', "APPROVAL_STATUS"=>'');
            $conn = self::getDB();
            $eventId = $conn->real_escape_string($eventId);
            $fetchFlyerQuery = "SELECT ID,NAME, ADDITIONAL_FILE, ADDITIONAL_FILE_SIZE, ADDITIONAL_FILE_TYPE,
            VENUE, DATE_FORMAT(START_TIME, '%W %M %D, %Y') AS DATE, DATE_FORMAT(START_TIME, '%l:%i %p') AS START_TIME,DATE_FORMAT(END_TIME, '%l:%i %p') AS END_TIME, CATEGORY, DESCRIPTION, LINK, PHONE, EMAIL,COST, UB_CAMPUS_LOCATION, APPROVAL_STATUS  FROM tbl_events WHERE ID=$eventId;";
            $result = mysqli_query($conn,$fetchFlyerQuery);

            if($result != NULL){
                while($row = mysqli_fetch_assoc($result))
                {
                   $fetchedEvent  = $row;
                }
            }

            return $fetchedEvent;
        }

        public static function getRecurringEventInfo($eventId){
            $fetchedEvent = array("ID"=>'', "NAME"=>'', "VENUE"=>'',"DATE"=> '', "START_TIME" => '', "END_TIME"=>'',"CATEGORY"=>'',"DESCRIPTION"=>'',"LINK"=>'',"PHONE"=>'',"EMAIL"=>'',"COST"=>'',"UB_CAMPUS_LOCATION"=>'', "APPROVAL_STATUS"=>'');
            $conn = self::getDB();
            $eventId = $conn->real_escape_string($eventId);
            $fetchFlyerQuery = "SELECT RECURING_EVENT_ID, NAME, ADDITIONAL_FILE, ADDITIONAL_FILE_SIZE, ADDITIONAL_FILE_TYPE,
            VENUE, DATE_FORMAT(START_TIME, '%W %M %D, %Y') AS DATE, DATE_FORMAT(START_TIME, '%l:%i %p') AS START_TIME,DATE_FORMAT(END_TIME, '%l:%i %p') AS END_TIME, CATEGORY, DESCRIPTION, LINK, PHONE, EMAIL,COST, UB_CAMPUS_LOCATION, APPROVAL_STATUS  FROM tbl_recur_events WHERE RECURING_EVENT_ID=$eventId;";
            $result = mysqli_query($conn,$fetchFlyerQuery);

            if($result != NULL){
                while($row = mysqli_fetch_assoc($result))
                {
                   $fetchedEvent  = $row;
                }
            }

            return $fetchedEvent;
        }

        public static function addRecurringEvent($repeat, $lastDay, $name, $addedBy, $venue, $startTime, $endTime, $description, $link, $cost, $phone, $email,$ubCampusLocation="", $additionalFile="",$additionalFileSize="",$additionalFileType="", $approvalStatus = "pending", $categories="", $contacts=array(), $updateToken){
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
            $additionalFile= $conn->real_escape_string($additionalFile);
            $additionalFileSize = $conn->real_escape_string($additionalFileSize);
            $additionalFileType = $conn->real_escape_string($additionalFileType);
            $categories = $conn->real_escape_string($categories);
            $approvalStatus = $conn->real_escape_string($approvalStatus);
            $addedBy = $conn->real_escape_string($addedBy);
            $categories = $conn->real_escape_string($categories);
            $repeat = $conn->real_escape_string($repeat);
            $lastDay = $conn->real_escape_string($lastDay);

            session_start();
            if ($_SESSION == array() || !isset($_SESSION['sessionID'])) {
              $approvalStatus = "pending";
            } else {
              $approvalStatus = "accepted";
            }

            $stmt = $conn->prepare("INSERT INTO tbl_recur_events(
                NAME,
                VENUE,
                START_TIME,
                END_TIME,
                DESCRIPTION,
                LINK,
                COST,
                PHONE,
                EMAIL,
                UB_CAMPUS_LOCATION,
                ADDITIONAL_FILE,
                ADDITIONAL_FILE_SIZE,
                ADDITIONAL_FILE_TYPE,
                APPROVAL_STATUS,
                ADDED_BY,
                LAST_DATE,
                REPEAT_BY,
                UPDATE_TOKEN) VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                '$additionalFile',
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?)");

            $stmt->bind_param("ssssssdsssissssss",
                $name,
                $venue,
                $startTime,
                $endTime,
                $description,
                $link,
                $cost,
                $phone,
                $email,
                $ubCampusLocation,
                $additionalFileSize,
                $additionalFileType,
                $approvalStatus,
                $addedBy,
                $lastDay,
                $repeat,
                $updateToken);

            $stmt->execute();
            $last_id = $conn->insert_id;
            $stmt->close();

            if(strlen($categories) > 0){
                $categoryIdList = explode(",",$categories);
                $stmt =$conn->prepare("INSERT INTO tbl_recur_event_categories
                    (`EVENT_ID`,
                    `CATEGORY_ID`)
                    VALUES
                    (?,?);
                ");
                foreach ($categoryIdList as $categoryId) {

                    $stmt->bind_param("ii", $last_id, $categoryId);
                    $stmt->execute();
                }
                $stmt->close();
            }

            if(sizeof($contacts) > 0){
                $stmt =$conn->prepare("INSERT INTO tbl_recur_event_contacts
                    (`EVENT_ID`,
                     `CONTACT_TYPE`,
                     `PERSON_NAME`,
                     `ADDITIONAL_INFO`)
                    VALUES
                    (?,?,?,?);
                ");
                foreach ($contacts as $contact) {
                    $stmt->bind_param("isss", $last_id, $contact['type'], $contact['name'], $contact['info']);
                    $stmt->execute();
                }
                $stmt->close();
            }

            if ($approvalStatus == "accepted") {
              Events::makeRecurring($last_id, $repeat, $lastDay, $name, $addedBy, $venue, $startTime, $endTime, $description, $link, $cost, $phone, $email,$ubCampusLocation, $additionalFile, $additionalFileSize, $additionalFileType, $approvalStatus, $categories, $contacts, $updateToken);
            }

            session_start();
            if (isset($_SESSION['sessionUser'])) {
                $user = $_SESSION['sessionUser'];
            } else {
              $user = $addedBy;
            }

            $delete = "Recurring Event: " . $eventId . " added";
            $application = "Events Calendar";

            $stmt->bind_param("ssss", $application, $user, $delete, $date);

            $stmt->execute();
            $stmt->close();

        }

        public static function addSingleRecurring($recurId, $name, $addedBy, $venue, $startTime, $endTime, $description, $link, $cost, $phone, $email,$ubCampusLocation="", $additionalFile="",$additionalFileSize="",$additionalFileType="",
                  $approvalStatus = "accepted", $categories="", $contacts=array(), $updateToken) {

          $conn = self::getDB();

          $approvalStatus = "accepted";

          $stmt = $conn->prepare("INSERT INTO tbl_events(
              NAME,
              VENUE,
              START_TIME,
              END_TIME,
              DESCRIPTION,
              LINK,
              COST,
              PHONE,
              EMAIL,
              UB_CAMPUS_LOCATION,
              ADDITIONAL_FILE,
              ADDITIONAL_FILE_SIZE,
              ADDITIONAL_FILE_TYPE,
              APPROVAL_STATUS,
              ADDED_BY,
              RECURING_EVENT_ID,
              UPDATE_TOKEN) VALUES (
              ?,
              ?,
              ?,
              ?,
              ?,
              ?,
              ?,
              ?,
              ?,
              ?,
              '$additionalFile',
              ?,
              ?,
              ?,
              ?,
              ?,
              ?)");

          $stmt->bind_param("ssssssdsssisssis",
              $name,
              $venue,
              $startTime,
              $endTime,
              $description,
              $link,
               $cost,
              $phone,
              $email,
              $ubCampusLocation,
              // $additionalFile,
               $additionalFileSize,
              $additionalFileType,
              $approvalStatus,
              $addedBy,
              $recurId,
              $updateToken);

          $stmt->execute();
          $last_id = $conn->insert_id;
          $stmt->close();

          if(strlen($categories) > 0){
              $categoryIdList = explode(",",$categories);
              $stmt =$conn->prepare("INSERT INTO tbl_event_categories
                  (`EVENT_ID`,
                  `CATEGORY_ID`)
                  VALUES
                  (?,?);
              ");
              foreach ($categoryIdList as $categoryId) {

                  $stmt->bind_param("ii", $last_id, $categoryId);
                  $stmt->execute();
              }
              $stmt->close();
          }

          if(sizeof($contacts) > 0){
              $stmt =$conn->prepare("INSERT INTO tbl_event_contacts
                  (`EVENT_ID`,
                   `CONTACT_TYPE`,
                   `PERSON_NAME`,
                   `ADDITIONAL_INFO`)
                  VALUES
                  (?,?,?,?);
              ");
              foreach ($contacts as $contact) {
                  $stmt->bind_param("isss", $last_id, $contact['type'], $contact['name'], $contact['info']);
                  $stmt->execute();
              }
              $stmt->close();

          }

        }

        public static function makeRecurring($recurId, $repeat, $lastDay, $name, $addedBy, $venue, $startTime, $endTime, $description, $link, $cost, $phone, $email,$ubCampusLocation="", $additionalFile="",$additionalFileSize="",$additionalFileType="",
            $approvalStatus = "accepted", $categories="", $contacts=array(), $updateToken) {

          if ($updateToken == "") {
            $conn = self::getDB();

            $fetchEventsQuery = "SELECT UPDATE_TOKEN FROM tbl_recur_events WHERE RECURING_EVENT_ID='".$recurId."'";
            $result = mysqli_query($conn,$fetchEventsQuery);

            if($result != NULL){
                $row = mysqli_fetch_assoc($result);
                $updateToken = $row['UPDATE_TOKEN'];
            }
          }

          if ($repeat == "monthly") {
            $lastDay = date("Y-m-d",strtotime($lastDay));
            $startDay = strtotime(date("Y-m-d", strtotime($startTime)));

            $startTimeSplit = explode(" ", $startTime);
            $startTime = $startTimeSplit[1];

            $endTimeSplit = explode(" ", $endTime);
            $endTime = $endTimeSplit[1];

            while (strtotime($lastDay) >= $startDay) {
              $formatDay = date("Y-m-d", $startDay);
              $newDay = "$formatDay $startTime";
              $newEnd = "$formatDay $endTime";

              Events::addSingleRecurring($recurId, $name, $addedBy, $venue, $newDay, $newEnd, $description, $link, $cost, $phone, $email,$ubCampusLocation, $additionalFile, $additionalFileSize, $additionalFileType, $approvalStatus, $categories, $contacts, $updateToken);
              $startDay = strtotime("+1 month", $startDay);
            }
          } else if ($repeat == "weekly") {
            $lastDay = date("Y-m-d",strtotime($lastDay));
            $startDay = strtotime(date("Y-m-d", strtotime($startTime)));

            $startTimeSplit = explode(" ", $startTime);
            $startTime = $startTimeSplit[1];

            $endTimeSplit = explode(" ", $endTime);
            $endTime = $endTimeSplit[1];

            while (strtotime($lastDay) >= $startDay) {
              $formatDay = date("Y-m-d", $startDay);
              $newDay = "$formatDay $startTime";
              $newEnd = "$formatDay $endTime";

              Events::addSingleRecurring($recurId, $name, $addedBy, $venue, $newDay, $newEnd, $description, $link, $cost, $phone, $email,$ubCampusLocation, $additionalFile, $additionalFileSize, $additionalFileType, $approvalStatus, $categories, $contacts, $updateToken);
              $startDay = strtotime("+1 week", $startDay);
            }

          } else if ($repeat == "daily") {
            $lastDay = date("Y-m-d",strtotime($lastDay));
            $startDay = strtotime(date("Y-m-d", strtotime($startTime)));

            $startTimeSplit = explode(" ", $startTime);
            $startTime = $startTimeSplit[1];

            $endTimeSplit = explode(" ", $endTime);
            $endTime = $endTimeSplit[1];

            while (strtotime($lastDay) >= $startDay) {
              $formatDay = date("Y-m-d", $startDay);
              $newDay = "$formatDay $startTime";
              $newEnd = "$formatDay $endTime";

              Events::addSingleRecurring($recurId, $name, $addedBy, $venue, $newDay, $newEnd, $description, $link, $cost, $phone, $email,$ubCampusLocation, $additionalFile, $additionalFileSize, $additionalFileType, $approvalStatus, $categories, $contacts, $updateToken);
              $startDay = strtotime("+1 day", $startDay);
            }
          } else {
            echo "Not a valid repeat type";
          }

        }
    }

?>
