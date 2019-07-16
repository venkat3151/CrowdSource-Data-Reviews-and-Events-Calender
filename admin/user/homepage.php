<?php
  session_start();
  if($_SESSION == array() || !isset($_SESSION['sessionID'])) {
    header("Location: signin.php");
    exit();
  }
  else{
    include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/superHeader.php";
  }
?>

<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UB Spectrum Admin</title>
   <!--  <link rel="stylesheet" type="text/css" href="../../bootstrap/css/header.css">
     <link rel="stylesheet" type="text/css" href="../../bootstrap/css/bootstrap.min.css"> -->
   <!--  <style>
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
      .container {
        height:300px;
        margin: 20px;
      }

    </style> -->

   <!--  <a href="https://www.ubspectrum.com/" target="blank">
   <div class="flip-card" >
       <div class="flip-card-inner">
           <div class="flip-card-front">
                <img src="images/logo.png" alt="Avatar" style="width:200px;height:200px;">
           </div>
           <div class="flip-card-back">
                <p>THE INDEPENDENT STUDENT PUBLICATION OF THE UNIVERSITY AT BUFFALO, SINCE 1950</p>
           </div>
       </div>
   </div>
</a> -->
<!-- 
  <div id="jumbo">
      <div id="textInJumbo">
        <h1>Admin Homepage</h1>
        <p>Navigate to any app from here</p>
        <button type="button" onclick="window.location.href='server/signout.php'" class="btn btn-primary btn" style="float: right;margin-right:1em ">Sign Out</button><br>
      </div>
  </div> -->
   
  </head>
  <body>
    <div class=" heading container">
      <div class="card-deck">
      <div class="card bg-dark text-white">
        <!-- <img class="card-img-top" src="images/userMng.PNG" alt="Card image cap"> -->
        <div class="card-body">
          <h4 class="card-title">User Management</h4>
          <p class="card-text">
            This page is for managing all admins in the system. Here you can add a new admin, edit admins, and add new admins.
          </p>
        </div>
        <div class="card-footer">
          <a href="userManagement.php" class="btn btn-outline-primary btn-sm">Go Here</a>
        </div>
      </div>

      <div class="card bg-dark text-white">
        <!-- <img class="card-img-top" src="images/eventsMng.PNG" alt="Card image cap"> -->
        <div class="card-body">
          <h4 class="card-title">Events Management</h4>
          <p class="card-text">
              This page is for managing the Events Calendar. Here, you can approve events, edit events, and add new events.
          </p>
        </div>
        <div class="card-footer">
          <a href="/ubspectrum/admin/events/eventsAdmin.php" class="btn btn-outline-primary btn-sm">Go Here</a>
        </div>
      </div>

      <div class="card bg-dark text-white">
        <!-- <img class="card-img-top" src="images/crowdMng.PNG" alt="Card image cap"> -->
        <div class="card-body">
          <h4 class="card-title">Crowdsourced Data Reviews Management</h4>
          <p class="card-text">
            This page is for managing Crowdsourced Data Reviews. Here, you can add new datasets, archive datasets and view results for the datasets.
          </p>
        </div>
        <div class="card-footer">
          <a href="../crowdsource/datasetsView.php" class="btn btn-outline-primary btn-sm">Go Here</a>
        </div>
      </div>

      <div class="card bg-dark text-white">
        <!-- <img class="card-img-top" src="images/history.PNG" alt="Card image cap"> -->
        <div class="card-body">
          <h4 class="card-title">History</h4>
          <p class="card-text">
            This page shows the history of every action that had happend within the application for reference.
          </p>
        </div>
        <div class="card-footer">
          <a href="history.php" class="btn btn-outline-primary btn-sm">Go Here</a>
        </div>
      </div>
    </div>

    </div>
  </body>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<script src="../../bootstrap/js/popper.js"></script> -->
</html>
