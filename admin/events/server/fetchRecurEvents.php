<?php
    header('content-type: application/json; charset=utf-8');
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once "Models/Event.php";

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $result = Event::getRecurringEvent($id);
        echo json_encode($result);
    } else {
    try {
        $result = Event::getAll();
        $formattedResults = array();

        foreach ($result as $value) {
            $formattedResults[] = array('title' => $value['NAME'], 'start' => $value['START_TIME'], 'end' => $value['END_TIME'], 'description' => $value['DESCRIPTION'], 'id' => $value['RECURING_EVENT_ID'], "approved" => $value['APPROVAL_STATUS']);
        }

        echo json_encode($formattedResults);
    } catch (\Throwable $th) {
        http_response_code(400);
        die();
    }
  }

?>
