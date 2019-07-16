<?php
session_start();
include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/crowdsource/model/adminModel.php";
$obj= new AdminModel();

$_SESSION['id']=$_GET['datasetid'];
$id=$_SESSION['id'];
$result=$obj->getallData($id);

$ubit=$obj->getUbitName($id);
$studentsReviewed=$obj->getStudentsReviewed($id);
$data = $obj->getDatasetData($id);
$numofsplits=$obj->getNumOfSplits($id);
$splitnum=$numofsplits['num'];
$stunum= $studentsReviewed['num'];

$percent=($stunum/$splitnum)*100;
?>
<html>
<head>
    <title>Progress | Crowdsource Data Reviews</title>


    <?php  // print_r($_SESSION['admin']);

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
      }  ?>

</head>
<body>
  <div class="heading "> 
    <div class="container">
  <h3>Progress of <?php echo $data[0]['DATASET_NAME'] ?> </h3> 

  <div class=" heading row">
  <div class="col-sm-12 col-md-12 col-lg-8">
  <?php if(sizeof($ubit[0]['UBIT_NAME'])!=0){?>
  <div class="panel"><div class="panel-body">
    <table class="table table-striped" id="progessDataset">
      <thead class="thead-dark">
        <tr>
         
          <th scope="col">UBIT Name</th>
           <th scope="col">Assigned Time</th>
          <th scope="col">Operation</th>
        </tr>
      </thead>

      <tbody>
      <?php 
         for($i=0;$i<sizeof($ubit);$i++){
          ?>
          <tr>
             
            <td ><?php echo $ubit[$i]['UBIT_NAME']; ?></td>
           
           <td><?php echo $result[$i]['FILE_ASSIGNED_TIME']?>
            <?php if(is_null($result[$i]['FILE_SUBMITTED_TIME'])){ ?> <td>No Answers to View</td><?php }else{?>
              <td><a  href=<?php echo "/ubspectrum/admin/crowdsource/answerViewPage.php?datasetid=".$id."&ubit=".$ubit[$i]['UBIT_NAME'] ?>>View Answers</a></td>
           <?php }?> 
          </tr>
        <?php 
          }
        ?></tbody>
    </table>
        </div></div><?php }?></div>
        <div class="col-sm-12 col-md-12 col-lg-4" id="outerdetails">
          <div class="col-lg-12 col-md-6 col-sm-6 alert-primary" id="details">
          <a href="/ubspectrum/admin/crowdsource/datasetsView.php"> Back to previous page</a>
        <p>Total number of splits:<?php echo $splitnum ?> </p>
        <p>Number of students reviewed :<?php echo $stunum ?> </p>
        <p>Number of students Signed Up :<?php echo sizeof($ubit) ?> </p>
        
        <div class="progress ">
            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="40"
            aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percent?>%">
       </div>
        </div>
      </div>
    </div>
  </div> 
      </div>
</div>
</body>
<script>  
 $(document).ready(function(){  
      $('#progessDataset').DataTable({
        'columnDefs': [ {
        'targets': [2], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }]
      });    
 });   
 </script>

</html>