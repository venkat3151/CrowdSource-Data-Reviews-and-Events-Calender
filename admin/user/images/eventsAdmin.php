<?php
  session_start();
  echo $_SESSION['sessionID'];
  if($_SESSION == array() || !isset($_SESSION['sessionID'])) {
    echo $_SESSION['userPermission'];
    //header("Location: /ubspectrum/admin/user/signin.php");
    exit();
  } else if ($_SESSION['userPermission'] != "events" || $_SESSION['userPermission'] != "super") {
    echo $_SESSION['userPermission'];
    //header("Location: /ubspectrum/admin/user/signin.php");
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
    <script src="/ubspectrum/events/tagify.min.js"></script>
    <script src="/ubspectrum/bootstrap/js/popper.js"></script>
    <title>UB Spectrum Admin</title>
    <style>
      .h1 {
        font-family: 'Open Sans', serif;
        font-size: 40px;
        display: inline-block;
        margin: 0;
      }
      .table {
        width: 80%;
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

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="/ubspectrum/admin/user/homepage.php">Home</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="/ubspectrum/admin/user/userManagement.php">User Management</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="/ubspectrum/admin/events/eventsAdmin.php">Events Management<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/ubspectrum/admin/crowdsource/datasetsView.php">Crowdsourced Data Reviews Management</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/ubspectrum/admin/user/history.php" tabindex="-1">History Management</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/ubspectrum/admin/user/server/signout.php" tabindex="-1">Sign Out</a>
          </li>
        </ul>
      </div>
    </nav>
    <link rel="stylesheet" type="text/css" href="/ubspectrum/bootstrap/css/bootstrap.min.css">
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="/ubspectrum/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="javascript/eventsAdmin.js"></script>

<body>
  <br><br>

    <button type="button" onclick="window.location.href='/ubspectrum/events/'" class="btn btn-primary btn" style="float: right;margin-right:3em;">Go To Calendar</button>
    <button type="button" onclick="window.location.href='/ubspectrum/admin/events/deletedEvents.php'" class="btn btn-primary btn" style="float: right;margin-right:1em ">Undo Deleted Events</button>
    <button type="button" onclick="window.location.href='/ubspectrum/events/AddEvent.php'" class="btn btn-primary btn" style="float: right;margin-right:1em ">Add New Event +</button><br>
    <br><br>
    <nav>
      <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-pending-tab" data-toggle="tab" href="#nav-pending" role="tab" aria-controls="nav-pending" aria-selected="true">Pending Events</a>
        <a class="nav-item nav-link" id="nav-accepted-tab" data-toggle="tab" href="#nav-accepted" role="tab" aria-controls="nav-accepted" aria-selected="false">Accepted Events</a>
    </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">

      <div style="margin-left:5%; margin-right: 5%;" class="tab-pane fade show active" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab">
        <br><h3 align="center">Pending Events</h3><br>
          <table id="pendingEvents" class="table table-striped">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Id</th>
              <th scope="col">Name</th>
              <th scope="col">Description</th>
              <th scope="col">Date</th>
              <th scope="col">Start Time</th>
              <th scope="col">End Time</th>
              <th scope="col">Accept/Decline</th>
            </tr>
          </thead>
          <tbody id="pendingEventRows"></tbody>
          </table>
      </div>

      <div style="margin-left:5%; margin-right: 5%;" class="tab-pane fade active" id="nav-accepted" role="tabpanel" aria-labelledby="nav-accepted-tab">
        <br><h3 align="center">Accepted Events</h3><br>
        <!--<div class="container">-->
          <table id="acceptedEvents" class="table table-striped">
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
          <tbody id=""></tbody>
          </table>
      </div>

      </div>
</body>
</html>
