<?php
  session_start();
  if($_SESSION == array() || !isset($_SESSION['sessionID'])) {
    header("Location: /ubspectrum/admin/user/signin.php");
    exit();
  } else if ($_SESSION['userPermission'] != "event" && $_SESSION['userPermission'] != "super") {
    if ($_SESSION['userPermission'] == "crowd") {
        header("Location: /ubspectrum/admin/user/homepage.php");
    } else {
        header("Location: /ubspectrum/admin/user/signin.php");
    }
    exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/ubspectrum/bootstrap/css/header.css">
    <script src="/ubspectrum/bootstrap/js/popper.js"></script>
    <title>UB Spectrum Admin</title>
    <style>
      .h1 {
        font-family: 'Open Sans', serif;
        font-size: 40px;
        display: inline-block;
        margin: 0;
      }

    </style>
    <a href="https://www.ubspectrum.com/" target="blank">
      <div class="flip-card" >
        <div class="flip-card-inner">
          <div class="flip-card-front">
            <img src="/ubspectrum/admin/user/images/logo.png" alt="Avatar" style="width:200px;height:200px;">
          </div>
        <div class="flip-card-back">
          <p>THE INDEPENDENT STUDENT PUBLICATION OF THE UNIVERSITY AT BUFFALO, SINCE 1950</p>
        </div>
        </div>
       </div>
    </a>

    <div id="jumbo">
        <div id="textInJumbo">
          <h1>Events Management</h1>
          <p>Admin View to handle the Events Calendar</p>
        </div>
    </div>

    <?php
      if ($_SESSION['userPermission'] == "super") {
          include("super-navbar.php");
      } else if ($_SESSION['userPermission'] == "event") {
        include("events-navbar.php");
      }
    ?>
    <link rel="stylesheet" type="text/css" href="/ubspectrum/bootstrap/css/bootstrap.min.css">
</head>

<body>
  <br><br>

    <button type="button" onclick="" class="btn btn-primary btn" style="float: right;margin-right:3em;">Go To Calendar</button>
    <button type="button" onclick="window.location.replace('eventsAdmin.php')" class="btn btn-primary btn" style="float: right;margin-right:1em ">Back</button><br>

  <br><h3 align="center">Deleted Events</h3><br>
    <div style="margin-left: 5%; margin-right: 5%" class="panel">
      <table width=100% id="deletedEvents" class="table table-striped">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Id</th>
              <th scope="col">Name</th>
              <th scope="col">Description</th>
              <th scope="col">Date</th>
              <th scope="col">Start Time</th>
              <th scope="col">End Time</th>
              <th scope="col">Undo Deleted</th>
            </tr>
        </thead>
        <tbody id="deletedEventRows"></tbody>
      </table>
    </div>
  <br>

  <div class="modal" id="undoConfirm" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="undoTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you would like to undo this deleted event? Doing so will change the event to be a pending event. To get re-added to the calendar you will need to re-accepted</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="button" id="undoBtn" name="" onclick=undoDelete(this) class="btn btn-primary btn-sm">Undo</button>
      </div>
    </div>
  </div>
  </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="/ubspectrum/bootstrap/js/bootstrap.min.js"></script>
<script>
window.onload = function() {
  $('#deletedEvents').DataTable({paging: false, scrollY: 400});

    $.get("server/fetchDeletedEvents.php", function(data, status) {
      var date = new Date();
      data.map(function(dataObj, index) {
        if (date.toISOString() < dataObj.start) {
          if (dataObj.id_recur) {
            dataObj.id = "RECUR_" + dataObj.id_recur;
          }
          addEvents(dataObj);
        }
      });
    });
}


function showUndo(e) {
  document.getElementById("undoTitle").innerHTML = "Undo deleted event: " + e.id;
  document.getElementById("undoBtn").name = e.id;
  $('#undoConfirm').modal('show');
}

function undoDelete(e) {
  var table = table = $("#deletedEvents").DataTable(),
      data = table.rows().data(),
      index;

  var id = e.name,
      type;

  if (id.includes("RECUR_")) {
    type = "recur";
  } else {
    type = "single";
  }

  $.ajax({
    type: "POST",
    url: "/ubspectrum/admin/events/server/undoDelete.php",
    data: {id: id.replace("RECUR_", ""),
          type: type}
  }).then(function() {
    var filteredData = table.rows().indexes().filter( function ( value, index ) {
       return table.row(value).data()[0] == id;
    });
    table.rows( filteredData ).remove().draw();
  });

  $('#undoConfirm').modal('hide');
}

/**
  function to dynamically add delted events to the web page
*/
function addEvents(deletedEvent) {
  var startDate = new Date(deletedEvent.start),
      endDate = new Date(deletedEvent.end),
      formatStartDate = startDate.toLocaleDateString(),
      table = $("#deletedEvents").DataTable();


  var startTime = deletedEvent.start.split(/\D+/),
      endTime = deletedEvent.end.split(/\D+/),
      formatStartTime = startTime[3] + ":" + startTime[4],
      formatEndTime = endTime[3] + ":" + endTime[4];

      table.row.add([deletedEvent.id,
                     deletedEvent.title,
                     deletedEvent.description,
                      formatStartDate,
                      formatStartTime,
                      formatEndTime,
                      '<a href="#" class="btn btn-primary" id="' + deletedEvent.id +
                      '" onclick=showUndo(this) btn-sm pull-left">Undo Deleted</a>']).draw();
}


</script>
</html>
