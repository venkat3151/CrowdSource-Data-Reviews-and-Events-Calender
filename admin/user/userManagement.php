<html>
<head>

 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <script src="/ubspectrum/admin/user/javascript/jquery-3.3.1.js"></script>
  <script src="/ubspectrum/admin/user/javascript/jquery.dataTables.min.js"></script>
  <script src="/ubspectrum/admin/user/javascript/dataTables.bootstrap4.min.js"></script>
  <link rel="stylesheet" href="/ubspectrum/admin/user/css/dataTables.bootstrap4.css"/>
  
  <link rel="stylesheet" href="/ubspectrum/admin/user/css/header.css">
  <script src="/ubspectrum/admin/crowdsource/js/popper.js"></script>
  <script src="/ubspectrum/bootstrap/js/bootstrap.js"></script>
  <link rel="stylesheet" href="/ubspectrum/bootstrap/css/bootstrap.css">
  <script src='/ubspectrum/admin/crowdsource/js/bootbox.min.js'></script>
  <script src='/ubspectrum/admin/crowdsource/js/script.js' type='text/javascript'></script>

  <link rel="icon"  href="images/favicon.png" />
</head>
<?php
session_start();

include "server/adminViewModel.php";
$obj=new AdminModel();
$list=$obj->adminview();

?>
<body>
<?php 


if(isset($_SESSION['admin'])){

  $admin=$_SESSION['admin'];
 

  if($admin['ROLE']=='super'){?>
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
        <h1>User Management</h1> 
        <p>THE LESS YOU KNOW THE MORE YOU BELIEVE.</p> 
      </div>
  </div>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="/ubspectrum/admin/user/homepage.php">Home</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item ">
            <a class="nav-link" href="/ubspectrum/admin/user/userManagement.php">User Management<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../events/eventsAdmin.php">Events Management</a>
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
 <?php }
 
}
else{
  header("Location: /ubspectrum/admin/user/signin.php");
}
?>
	
	<div class="heading">
    <div class="container">
      




<div class="col-xs-12 ">
        <nav>
          <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Current Admins</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Pending Admins</a>
        
          </div>
        </nav>
        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
             <div class="panel heading"><div class="panel-body">
             <h4 class="center"> </h4>
        <h5>You can update or delete the admins from the system.</h5>
        <br>
	<table id="viewAll" class="display table table-striped">
      <thead class="thead-dark">
        <tr>
         <!--  <th scope="col">#</th> -->
          <th scope="col">EMAIL</th>
          <th scope="col">FULL NAME</th>     
          <th scope="col">ROLE</th>
          <th scope="col" ><i class="fa fa-edit"></th>
          <th scope="col"><i class="fa fa-trash-alt"></th>
         
        </tr>
      </thead>
      <tbody>
      	<?php for($i=0;$i<sizeof($list);$i++) { ?>
        <tr>
           <!-- <td scope="row"><?php echo ($i+1)?></td> -->
           <td><?php echo $list[$i]['EMAIL']?></td>
           <td><?php echo $list[$i]['FULL_NAME']?></td>
           <td><?php echo $list[$i]['ROLE']?></td>
           <td><a class = "btn btn-primary" href="<?php echo 'adminUpdate.php?EMAIL='.$list[$i]['EMAIL'].'&fullname='.$list[$i]['FULL_NAME'].'&ROLE='.$list[$i]['ROLE']?>" >Update </a></td>

           <td><button type="button" class="admindelete btn btn-danger" id="<?php echo $list[$i]['EMAIL']?>" >Delete </a></td>
        </tr>
          <?php } ?>
      </tbody>
  </table>
<?php
//include "adminViewModel.php";

$list=$obj->adminPendingList();
?>
    
  </div></div>
          </div>
          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
               <div class="panel heading">
                 <div class="panel-body">

                  
        <h5>You can approve or reject the admin to be requests.</h5>
        <br>
	<table id="viewPending" class="table table-striped">
  <thead class="thead-dark">
    <tr>
      <!-- <th scope="col">#</th> -->
      <th scope="col">EMAIL</th>
      <th scope="col">FULL NAME</th>     
      <th scope="col">ROLE</th>
     <!--  <th scope="col">STATUS</th> -->
      <th scope="col"><i class="fa fa-thumbs-up"></th>
      <th scope="col"><i class="fa fa-thumbs-down"></th>
    </tr>
  </thead>
  <tbody>
  	<?php for($i=0;$i<sizeof($list);$i++) { ?>
    <tr>
     <!--  <th scope="row"><?php echo ($i+1)?></th> -->
    
      <td><?php echo $list[$i]['EMAIL']?></td>
       <td><?php echo $list[$i]['FULL_NAME']?></td>
        <td><?php echo $list[$i]['ROLE']?></td>
     <!--    <td><?php echo $list[$i]['STATUS']?></td> -->
        <!-- <td><a href="<?php echo 'server/adminfile.php?EMAIL='.$list[$i]['EMAIL'].'&approve=true'?>" target="blank">Approve</a></td> -->
        <td><button type="button" class="adminapprove btn btn-success" id="<?php echo $list[$i]['EMAIL']?>" >Approve </a></td>
        <!-- <td><a href="<?php echo 'server/adminfile.php?EMAIL='.$list[$i]['EMAIL'].'&reject=true'?>" target="blank">Reject </a></td> -->
        <td><button type="button" class="adminreject btn btn-danger" id="<?php echo $list[$i]['EMAIL']?>" >Reject </a></td>
        <!-- <td><button type="button" class="admindelete btn btn-danger" id="<?php echo $list[$i]['EMAIL']?>" >Delete </a></td> -->
    </tr>
  <?php } ?>

  </tbody>
  </table>

          </div></div>
          </div>
        </div>
  </div>

  </div>
</div>
 
</body>
</html>

<script>  
  $(document).ready(function(){  
      $('#viewAll').DataTable({
        'columnDefs': [ {
        'targets': [3,4], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }]
      });  
 });    
  $(document).ready(function(){  
      $('#viewPending').DataTable({
        'columnDefs': [ {
        'targets': [3,4], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }]
      });  
 });   
 </script>  
