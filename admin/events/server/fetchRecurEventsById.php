<?php
  require_once "Models/Event.php";
  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  try {
      $id = $_POST["id"];

      $result = Event::getEventsByRecurringId($id);
      $formattedResults = array();

      foreach ($result as $value) {
          $formattedResults[] = array('title' => $value['NAME'], 'start' => $value['START_TIME'], 'end' => $value['END_TIME'], 'description' => $value['DESCRIPTION'], 'id' => $value['ID'], "approved" => $value['APPROVAL_STATUS']);
      }

      echo json_encode($formattedResults);
  } catch (\Throwable $th) {
      http_response_code(400);
      die();
  }

 ?>
