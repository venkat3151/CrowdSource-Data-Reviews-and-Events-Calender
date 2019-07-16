<html>
<head>
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
      <h1>Recurring Events List View</h1>
      <p>More info list for recurring events</p>
    </div>
</div>

<?php
  if ($_SESSION['userPermission'] == "super") {
      include("super-navbar.php");
  } else if ($_SESSION['userPermission'] == "event") {
    include("events-navbar.php");
  }
?>

</head></html>
