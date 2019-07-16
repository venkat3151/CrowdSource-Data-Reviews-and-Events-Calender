<?php
    header('content-type: application/json; charset=utf-8');
    header("access-control-allow-origin: *");

    require_once "Models/Events.php";
    require_once "Models/EmailManager.php";

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
    $flyer="";
    $flyerSize="";
    $flyerType="";
    if(isset($_FILES['flyer'] ) && $_FILES['flyer']['tmp_name'] != null){
        $flyer = file_get_contents($_FILES['flyer']['tmp_name']);
        $flyerSize = $_FILES['flyer']['size'];
        $file_info = new finfo(FILEINFO_MIME);
        $mime_type = $file_info->buffer($flyer);
        $flyerType = $mime_type;
    }

    $contact_count = $_POST['contact_count'] or 1;
    $eventId = $_POST['event_id'] or '';
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
   $categories = htmlentities($categories);
   $start = $start_time;
    $start_time = "$date $start_time";
    $end_time = "$date $end_time";

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

    $updateToken = uniqid();
    if (isset($_POST['repeat']) && isset($_POST['lastDay'])) {
      $repeat = $_POST['repeat'];
      $lastDay = $_POST['lastDay'];

      $repeat = htmlentities($repeat);
      $lastDay = htmlentities($lastDay);
      $lastDay = "$lastDay $start";

      Events::addRecurringEvent($repeat, $lastDay, $name, $posted_by, $venue, $start_time, $end_time, $description, $link, $cost, "", "", $ub_campus, $flyer, $flyerSize,$flyerType, "pending", $categories, $contacts, $updateToken );
    } else {
      Events::addEvent($name, $posted_by, $venue, $start_time, $end_time, $description, $link, $cost, "", "", $ub_campus, $flyer, $flyerSize,$flyerType, "pending", $categories, $contacts, $updateToken );
    }


    if ($_SESSION == array() || !isset($_SESSION['sessionID'])) {

      $mail = new SpectrumEmail();
      $userSubject = "Your event $name was submitted for review   Thanks for submitting your event to The Spectrum!";
      $userMessage = "Thanks! We’ve submitted your event for review. Our editors will get back to you soon.  If we approve the event, we will post it on the calendar for you.  If there is a problem with the event, you will hear from us, too. From, UB Spectrum editors";
      $userHTMLMessage = "
        Thanks! We’ve submitted your event for review. Our editors will get back to you soon.  If we approve the event, we will post it on the calendar for you.  If there is a problem with the event, you will hear from us, too. 
<br/><br/>
- UB Spectrum editors
      ";

      $mail->sendMessage(array($posted_by), $userSubject,$userHTMLMessage,$userMessage);

    }
    if ($_SESSION == array() || !isset($_SESSION['sessionID'])) {
      $referer = dirname($_SERVER["HTTP_REFERER"]);
      header("Location: $referer");
    } else {
      header("Location: /ubspectrum/admin/events/eventsAdmin.php");
    }

?>
