<?php
require_once("Models/StudentUpdate.php");

// get the username and the password
$email = $_POST['email'] or '';
$token = $_POST['update'] or '';

$result = StudentUpdate::studentUpdateRecurCheck($email, $token);

if ($result != null) {
  $status = $result['APPROVAL_STATUS'];
  $id = $result['RECURING_EVENT_ID'];

  if ($result['APPROVAL_STATUS'] == "accepted") {
    session_start();
    $_SESSION['student'] = "true";
    header("Location: /ubspectrum/admin/events/recurringList.php?type=" . $status . "&eventid=" . $id);
  } else {
    session_start();
    $_SESSION['student'] = "true";
    header("Location: /ubspectrum/admin/events/moreinfo.php?type=" . $status . "&eventid=RECUR_" .$id);
  }
} else {
    $result = StudentUpdate::studentUpdateCheck($email, $token);

    if ($result != null) {
      session_start();
      $_SESSION['student'] = "true";
      $status = $result['APPROVAL_STATUS'];
      $id = $result['ID'];
      header("Location: /ubspectrum/admin/events/moreinfo.php?type=" . $status . "&eventid=" .$id);
    }
}

 ?>
