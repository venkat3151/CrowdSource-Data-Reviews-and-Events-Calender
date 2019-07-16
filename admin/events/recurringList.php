<?php
  session_start();
  if (isset($_SESSION['sessionID'])) {
    $_SESSION['type'] = "admin";
    $permission = $_SESSION['userPermission'];

    if ($permission != "event" && $permission != "super") {
      if ($permission == "crowd") {
        header("Location: /ubspectrum/admin/user/homepage.php");
      } else {
        header("Location: /ubspectrum/admin/user/signin.php");
      }
    }
  } else if (isset($_SESSION['student'])) {
    if ($_SESSION['student'] == "true") {
      $_SESSION['student'] == "false";
    } else {
        header("Location: /ubspectrum/events");
    }
  } else {
    header("Location: /ubspectrum/admin/user/signin.php");
  }

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/ubspectrum/bootstrap/css/header.css">
    <script src="/ubspectrum/events/tagify.min.js"></script>
    <script src="/ubspectrum/bootstrap/js/popper.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>

    <title>UB Spectrum Admin</title>
    <style>
      .panel {
        margin-right: 5%;
        margin-left: 5%;
      }
      .h1 {
        font-family: 'Open Sans', serif;
        font-size: 40px;
        display: inline-block;
        margin: 0;
      }

    </style>
    <link rel="stylesheet" type="text/css" href="/ubspectrum/bootstrap/css/bootstrap.min.css">
</head>
<?php
  if ($_SESSION['type'] == 'admin') {
    include('header-recur.php');
  } else {
    include('../../events/navbar-bootstrap.php');
  }
 ?>
<body>
  <br><br>
  <button type="button" onclick=openModalConfirm() class="btn btn-primary" style="float: right;margin-right:4em;">Edit All Events</button>
  <button type="button" class="btn btn-primary" onclick='window.location.href = "eventsAdmin.php";' style="float: right;margin-right:1em;">Back</button>

  <br><br><h3 id="heading" align="center"></h3><br>
  <div class="panel" style="margin-left: 5%; margin-right:5%">

    <table id="events" class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Name</th>
        <th scope="col">Description</th>
        <th scope="col">Date</th>
        <th scope="col">Start Time</th>
        <th scope="col">End Time</th>
        <th scope="col">More Info</th>
      </tr>
    </thead>
    <tbody id="eventsRows"></tbody>
    </table>
  </div>

  <div class="modal" id="continueConfirm" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="continueTitle">Edit All Events</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>This will bring you to a page that will update all the associated events. This will even change events you may have already updated. Are you sure you would like to continue?<br> NOTE: Date and Time cannot be bulk updated. Please update them with their individual events</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="button" onclick=callMoreInfo() class="btn btn-primary btn-sm">Continue</button>
      </div>
    </div>
  </div>
  </div>
<?php
  if ($_SESSION['type'] == 'user') {
    include('../../events/footer-bootstrap.php');
  }
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="/ubspectrum/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="javascript/recurring.js"></script>
</html>
