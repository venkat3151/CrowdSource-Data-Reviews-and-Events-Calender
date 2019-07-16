<?php
    require_once "Models/Events.php";

    $eventId = $_GET['eventId'];
    $eventId = htmlentities($eventId);

    $flyerInfo = Events::getEventFlyer($eventId);

    if($flyerInfo == NULL){
        http_response_code(400);
        die();
    }

    $file = $flyerInfo["ADDITIONAL_FILE"];
    $size = $flyerInfo["ADDITIONAL_FILE_SIZE"];
    $type = $flyerInfo["ADDITIONAL_FILE_TYPE"];
    $eventName = urlencode($flyerInfo["NAME"]);
    header("Content-length: $size");
    header("Content-type: $type");
    header("Content-Disposition: attachment; filename=$filename");
    ob_clean();
    flush();
    echo $file;
    exit;
?>