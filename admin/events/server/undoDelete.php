<?php

  require_once "Models/Event.php";

  $id = $_POST['id'];
  $type = $_POST['type'];

  if ($type == "recur") {
      Event::undoDeleteRecurringEvent($id);
  } else {
      Event::undoDeleteEvent($id);
  }


 ?>
