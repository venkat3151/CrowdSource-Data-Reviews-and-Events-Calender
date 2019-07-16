<?php
  require_once "Models/Event.php";
  require_once "../../../events/Models/EmailManager.php";

  $eventId = $_POST['eventId'];
  $type = $_POST['type'];
  $addedBy = $_POST['user'];
  $action = $_POST['action'];
  $reason = isset($_POST['reason']) ? $_POST['reason'] : ''; 
  
  if ($type == "recur") {
    Event::deleteRecurringEvent($eventId, $addedBy);
  } else {
    Event::deleteEvent($eventId, $addedBy);
  }

  if($action == 'decline'){
      $mail = new SpectrumEmail();
	    $userSubject = "Oh no! There is a problem with your event";
      $userMessage = "We are sorry, but we had to decline your event. See below to see why and feel free to resubmit. 
					Reason: $reason";
      $userHTMLMessage = "
        We are sorry, but we had to decline your event. See below to see why and feel free to resubmit. <br/></br>Reason: $reason
        <br/><br/>-UB Spectrum editors
		  ";

      $mail->sendMessage(array($addedBy), $userSubject,$userHTMLMessage,$userMessage);
  }

 ?>
