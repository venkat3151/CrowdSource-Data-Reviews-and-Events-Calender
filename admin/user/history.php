<?php
   session_start(); 

     if(isset($_SESSION['admin'])){

      $admin=$_SESSION['admin'];
     

      if($admin['ROLE']=='super'){
        include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/superHeader.php";
      }
      else if($admin['ROLE']=='crowd'){
         include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/crowdHeader.php";
      }
     }
      else{
        header("Location: /ubspectrum/admin/user/signin.php");
      }
   
  include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/crowdsource/model/adminModel.php";
  $obj= new AdminModel();
  $result= $obj->getTransactions();
?>

<html>
<head>
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
      .container {
        height:450px;
      }
    </style>
 
</head>
<body><br><br>

<div class="container">
  <table class="table table-striped" id="historyTable">
    <thead class="thead-dark">
      <tr>
        <th scope="col">No</th>
        <th scope="col">Application</th>
        <th scope="col">User Email</th>
        <th scope="col">Action</th>
        <th scope="col">Timestamp</th>
      </tr>
    </thead>
    <tbody>
    <?php for($i=0;$i<sizeof($result);$i++){ ?>
          <tr>
            <td><?php echo $i ?></td>
            <td><?php echo $result[$i]['APPLICATION']; ?></td>
            <td><?php echo $result[$i]['UBIT_NAME']; ?></td>
            <td><?php echo $result[$i]['ACTION']; ?></td>
            <td><?php echo $result[$i]['TIMESTAMP']; ?></td>                  
          </tr>
        <?php  }?>
      </tbody>
</table>
</div>
</body>
<script>  
 $(document).ready(function(){  
      $('#historyTable').DataTable();  
 });   
 </script>
</html>
