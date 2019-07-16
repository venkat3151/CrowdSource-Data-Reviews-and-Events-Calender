<?php
    header('content-type: application/json; charset=utf-8');
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once "Models/Event.php";

      try {
        $result = Event::getDeletedEvents();
        $formattedResults = array();

        foreach ($result as $value) {
            $formattedResults[] = array('title' => $value['NAME'], 'start' => $value['START_TIME'], 'end' => $value['END_TIME'], 'recurId' => $value['RECURING_EVENT_ID'], 'description' => $value['DESCRIPTION'], 'id' => $value['ID']);
        }

        $recur = Event::getDeletedRecur();

        foreach ($recur as $value) {
          $formattedResults[] = array('title' => $value['NAME'], 'start' => $value['START_TIME'], 'end' => $value['END_TIME'], 'description' => $value['DESCRIPTION'], 'id_recur' => $value['RECURING_EVENT_ID']);
        }

        echo json_encode($formattedResults);
    } catch (\Throwable $th) {
        http_response_code(400);
        die();
    }

?>
