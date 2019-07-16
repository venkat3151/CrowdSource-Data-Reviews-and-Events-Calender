<?php
  header('content-type: application/json; charset=utf-8');
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  require_once "Models/Event.php";

  // get the event id
  $id = $_POST['id'] or '';
  $result = Event::getEventInfo($id);
  echo $result;
 ?>
