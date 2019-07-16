<?php
    header('content-type: application/json; charset=utf-8');
    header("access-control-allow-origin: *");
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    require_once "Models/Event.php";
    require_once "../../../events/Models/Events.php";
    require_once "../../../events/Models/EmailManager.php";

    $name = $_POST['name'] or '';
    $venue = $_POST['venue'] or '';
    $description = $_POST['description'] or '';
    $link = $_POST['link'] or '';
    $ub_campus = $_POST['ub_campus'] or '';
    $cost = $_POST['cost'] or '';
    $date = $_POST['date'] or '';
    $start_time = $_POST['start_time'] or '';
    $end_time = $_POST['end_time'] or '';
    $posted_by = $_POST['addedBy'] or '';
    $type = $_POST['event_type'] or '';
    $flyer="";
    $flyerSize="";
    $flyerType="";

    $eventId = $_POST['event_id'] or '';

    if(isset($_FILES['flyer'] ) && $_FILES['flyer']['tmp_name'] != null){
        $flyer = file_get_contents($_FILES['flyer']['tmp_name']);
        $flyerSize = $_FILES['flyer']['size'];
        $file_info = new finfo(FILEINFO_MIME);
        $mime_type = $file_info->buffer($flyer);
        $flyerType = $mime_type;
        if (isset($_POST['repeat']) && isset($_POST['lastDay'])) {
            Event::updateRecurringFlyer($eventId, $flyer, $flyerSize, $flyerType);
        } else {
            Event::updateFlyer($eventId, $flyer, $flyerSize,$flyerType);
        }

    }

    $contact_count = $_POST['contact_count'] or 1;
    $ub_campus = $_POST['ub_campus'] or '';
    $categories =  $_POST['categories'] or '';

   $posted_by = htmlentities(   $posted_by);
   $name = htmlentities(   $name);
   $venue = htmlentities(   $venue);
   $description = htmlentities(   $description);
   $link = htmlentities(   $link);
   $ub_campus = htmlentities(   $ub_campus);
   $cost = htmlentities(   $cost);
   $date = htmlentities(   $date);
   $start_time = htmlentities(   $start_time);
   $end_time = htmlentities(   $end_time);
   $contact_count = htmlentities($contact_count);
   $eventId = htmlentities($eventId);
   $ub_campus = htmlentities($ub_campus);
   $type = htmlentities($type);
   $categories = htmlentities($categories);

    $contacts = array();
    for ($i=1; $i <= $contact_count; $i++) {
        $contactName = $_POST['contact_'.$i.'_name'] or '';
        $contactType = $_POST['contact_'.$i.'_type'] or '';
        $contactInfo = $_POST['contact_'.$i.'_info'] or '';

        $contactName = htmlentities($contactName);
        $contactType = htmlentities($contactType);
        $contactInfo = htmlentities($contactInfo);

        $contacts[] = array('name'=> $contactName, 'type' => $contactType, 'info' => $contactInfo);
    }

    if ($type == "existing") {
        $type = "accepted";
    }

    if (isset($_POST['repeat']) && isset($_POST['lastDay'])) {
      $repeat = $_POST['repeat'];
      $lastDay = $_POST['lastDay'];

      $repeat = htmlentities($repeat);
      $lastDay = htmlentities($lastDay);

      $lastDay = "$lastDay $start_time";
      $start_time = "$date $start_time";
      $end_time = "$date $end_time";

      Event::updateRecurringEvent($repeat, $lastDay, $eventId, $name, $posted_by, $venue, $start_time, $end_time, $description, $link, $cost, "", "", $ub_campus, $type, $categories, $contacts );

      if ($_POST['updateAllRecurring'] == "make") {
        Events::makeRecurring($eventId, $repeat, $lastDay, $name, $posted_by, $venue, $start_time, $end_time, $description, $link, $cost, "", "",$ub_campus, $flyer, $flyerSize, $flyerType, $type, $categories, $contacts, "");
      }

    } else {
      $start_time = "$date $start_time";
      $end_time = "$date $end_time";

      if (isset($_POST['updateAllRecurring'])) {
        if ($_POST['updateAllRecurring'] == "true") {
            Event::updateAll($eventId, $name, $posted_by, $venue, $start_time, $end_time, $description, $link, $cost, "", "", $ub_campus, $type, $categories, $contacts );
        } else {
          Event::updateEvent($eventId, $name, $posted_by, $venue, $start_time, $end_time, $description, $link, $cost, "", "", $ub_campus, $type, $categories, $contacts );
        }
      }
    }



    $isRecurring = Event::checkIfRecurringChild($eventId);

//    $referer = $_SERVER['HTTP_REFERER'];
    //echo $isRecurring;
    //header("Location: " . $referer);

    if($type =='accepted'){
      $eventInfo = Event::getEventToken($eventId);
      $token = $eventInfo == NULL ? '' : $eventInfo["UPDATE_TOKEN"];

      $mail = new SpectrumEmail();


      $userSubject = "$name has been approved. Hooray! $name is on The Spectrum calendar";
      $userMessage = "We have approved $name and put it on The Spectrum calendar.
        If you need to change or update details about your event, please use this token.
        $token
        Put this code into the box labeled “update code” and change the details of your event.
        If you are having trouble, please contact the calendar editor at xxx@ubspectrum.com ";
      $userHTMLMessage = "
        We have approved $name and put it on The Spectrum calendar.
        If you need to change or update details about your event, please use this token.
        <br/>
        <br/>
        <b>$token</b>
        <br/>
        <br/>
        Put this code into the box labeled “update code” and change the details of your event.
        If you are having trouble, please contact the calendar editor at <a href='mailto:xxx@ubspectrum.com'>xxx@ubspectrum.com</a>.
        <br/>
        <br/>
        - UB Spectrum editors
          ";

      $mail->sendMessage(array($posted_by), $userSubject,$userHTMLMessage,$userMessage);
    }

    if (!isset($_SESSION['sessionID'])) {
      header("Location: ../../../events/");
    } else if(isset($_POST['updateAllRecurring']) && $_POST['updateAllRecurring'] == "true"){
          header("Location: ../recurringList.php?eventid=RECUR_" . $eventId);
    } else {
      if ($isRecurring == "false") {
          header("Location: ../eventsAdmin.php");
      } else {
        header("Location: ../recurringList.php?eventid=RECUR_" . $isRecurring);
      }
    }
?>
