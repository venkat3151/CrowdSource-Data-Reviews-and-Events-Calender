<?php
  require "../../../events/Models/EventCategories.php";
  require "../../../events/Models/EventContacts.php";
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  $id = $_POST['id'];
  $type = $_POST['type'];

  if ($type === 'category') {
      $result = EventCategories::getRecurringCategoriesForEvent($id);
  } else {
    $result = EventContacts::getRecurringAll($id);
  }

  echo json_encode($result);

 ?>
