<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/ubspectrum/bootstrap/css/header.css">
    <title>UB Spectrum Admin</title>
    <style>
      .panel {
        margin-right: 5%;
        margin-left: 5%;
      }
      .h1 {
        font-family: 'Open Sans', serif;
        font-size: 40px;
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
            <h1>More Info</h1>
            <p>This page shows you more info about an event</p>
          </div>
      </div>
      <?php 
        if ($_SESSION['userPermission'] == "super") {
            include("super-navbar.php");
        } else if ($_SESSION['userPermission'] == "event") {
          include("events-navbar.php");
        }
      ?>
</head>
</html>
