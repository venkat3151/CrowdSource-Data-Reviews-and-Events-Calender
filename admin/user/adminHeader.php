
<!DOCTYPE html>

<head>
  <script src="javascript/jquery-3.3.1.js"></script>
  <script src="javascript/jquery.dataTables.min.js"></script>
  <script src="javascript/dataTables.bootstrap4.min.js"></script>
  <link rel="stylesheet" href="css/datatables.bootstrap4.css"/>
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="css/header.css">
  <script src="../crowdsource/js/popper.js"></script>
  <script src="../../bootstrap/js/bootstrap.js"></script>
  <link rel="icon"  href="images/favicon.png" />
</head>

<body>
  <a href="https://www.ubspectrum.com/" target="blank">
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
</a>
  <div id="jumbo">
      <div id="textInJumbo">
        <h1>CrowdSource Data Reviews</h1> 
        <p>THE LESS YOU KNOW THE MORE YOU BELIEVE.</p> 
      </div>
  </div>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="homepage.php">Home</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item ">
            <a class="nav-link" href="userManagement.php">User Management<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../events/eventsAdmin.php">Events Management</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../crowdsource/datasetsView.php">Crowdsourced Data Reviews Management</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="history.php" tabindex="-1">History Management</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="server/signout.php" tabindex="-1">Sign Out</a>
          </li>
        </ul>
      </div>
    </nav> 
</body>
</html>