<?php
    header('content-type: application/json; charset=utf-8');
    // header("access-control-allow-origin: *");

    require_once "Models/Events.php";
    require_once "Models/EventCategories.php";

    $start = (isset($_POST['start']) && !empty($_POST['start'])) ? $_POST['start'] : '';
    $end = (isset($_POST['end']) && !empty($_POST['end'])) ? $_POST['end'] : '';

    $after = (isset($_POST['after']) && !empty($_POST['after'])) ? $_POST['after'] : '';
    $before = (isset($_POST['before']) && !empty($_POST['before'])) ? $_POST['before'] : '';
    $categories = (isset($_POST['categories']) && !empty($_POST['categories'])) ? $_POST['categories'] : '';
    $cost = (isset($_POST['cost']) && !empty($_POST['cost'])) ? $_POST['cost'] : '';
    $campus = (isset($_POST['campus']) && !empty($_POST['campus'])) ? $_POST['campus'] : '';
    $categories = htmlentities($categories);
    $campus = htmlentities($campus);
    $after = htmlentities($after);
    $before = htmlentities($before);
    $cost = htmlentities($cost);
    $start = htmlentities($start);
    $end = htmlentities($end);

    try {
        $onlyApproved = TRUE;
        $result = Events::getAll($after, $before, $categories, $cost,$start, $end, $onlyApproved, $campus);
        $formattedResults = array();


        

        foreach ($result as $value) {
            $categories = EventCategories::getCategoriesForEvent($value['ID']);
            $eventColor = NULL;
            if(count($categories) == 1){
                $eventColor = $categories[0]["COLOR"];
            }else if ( count($categories) > 1 ){
                $eventColor = 'purple';
            }


            $formattedResults[] = array('title' => $value['NAME'], 'start' => $value['START_TIME'], 'end' => $value['END_TIME'], 'approve' => $value['APPROVAL_STATUS'], 'description' => $value['DESCRIPTION'], 'id' => $value['ID'],
            'categories'=> $categories, 'recurring' => $value['RECURING_EVENT_ID'], 'color' => $eventColor);
        }

        echo json_encode($formattedResults);
    } catch (\Throwable $th) {
        http_response_code(400);
        die();
    }

?>
