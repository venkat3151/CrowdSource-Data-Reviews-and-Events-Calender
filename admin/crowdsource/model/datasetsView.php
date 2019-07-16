<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CrowdSource | Datasets</title>

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
$result= $obj->getCurrentDatasets();
$list= $obj->archivedDatasetList();

?>


<style type="text/css">
	.bs-example{
		margin: 20px;
	}
</style>


</head>
<body>

<div class="heading">
<div class="container">
  <div class="col-xs-12 ">
        <nav>
          <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Current Datasets</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Archived Datasets</a>
        
          </div>
        </nav>
        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
             <div class="panel"><div class="panel-body">
  <h4 class="center">List of current datasets</h4>
        <h6>You can update,download or achive the dataset.</h6>
        <div class="heading">
        <a href="/ubspectrum/admin/crowdsource/importDataSetView.php" class="btn btn-primary" ><i class="fa fa-plus"></i> Add New Dataset</a>
      </div>
    <table class="table table-striped" id="currentDatasets">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Dataset Name</th>
          <!-- <th scope="col">Posted By</th> -->
          <th scope="col">Description</th>
          <th scope="col">File Type</th>
          <th scope="col">Split #</th>
          <th scope="col"><i class="fa fa-check-circle"></i></th>
          <th scope="col"><i class="fa fa-spinner"></i></th>
           <th scope="col"><i class="fa fa-edit"></i></th>
          <th scope="col" id="download"><i class="fa fa-download"></i> </th>
          <th scope="col" id="archive"><i class="fa fa-archive"></i></th>
          
        </tr>
      </thead>
      <tbody>
      <?php 
          for($i=0;$i<sizeof($result);$i++){
          ?>
          <tr>
            <td class = "datasetname"><?php echo $result[$i]['DATASET_NAME']; ?></td>
            <td class = "datasetdescription"><?php echo $result[$i]['DATASET_DESCRIPTION']; ?></td>
            <td class = "datasetfiletype"><?php echo $result[$i]['DATASET_FILETYPE']; ?></td>
            <td class = "datasetsplit"><?php echo $result[$i]['DATASET_SPLITNUM']; ?></td>
             <td><a class="btn btn-primary" href="<?php echo '/ubspectrum/admin/crowdsource/file.php?dataset_id='.$result[$i]['DATASET_ID'].'&publish=true'?>" target="blank"> Publish</a></td>
            <td><a  class="btn btn-primary" href="<?php echo "/ubspectrum/admin/crowdsource/progressPageView.php?datasetid=".$result[$i]['DATASET_ID'] ?>"
              class=""> Progress</a></td>
                <td> <a class="btn btn-primary" href="<?php echo "editPageView.php?datasetid=".$result[$i]['DATASET_ID'] ?>"
            class=""> Edit</a></td>
            <td><a class="btn btn-primary" href="<?php echo '/ubspectrum/admin/crowdsource/file.php?dataset_id='.$result[$i]['DATASET_ID'].'&selectCurrent=true'?>" target="blank"> Download   </a></td>
           <td> <a class="btn btn-primary" href="<?php echo "/ubspectrum/admin/crowdsource/model/archiveDataset.php?datasetid=".$result[$i]['DATASET_ID'] ?>"
            class=""> Archive</a></td>
          

                           
                          
                          
                           
          </tr>
       
        <?php 
             }
        ?></tbody>
    </table>
  </div></div>
          </div>
          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
               <div class="panel"><div class="panel-body">
  <h4 class="center">List of Archived datasets</h4>
        <h6>You can download or delete the dataset.</h6>
    <table class="table table-striped" id="archivedDatasets">
     <thead class="thead-dark">
    <tr>
     <!--  <th scope="col">#</th> -->
      <th scope="col">Name</th>
      <th scope="col">Description</th>     
      <th scope="col">Id</th>
      <th scope="col" ><i class="fa fa-trash-restore"></i> </th>
     <th scope="col" id="download"><i class="fa fa-download"></i> </th>
          <th scope="col" id="archive"><i class="fa fa-trash"></i></th>
    </tr>
  </thead>
  <tbody>
    <?php for($i=0;$i<sizeof($list);$i++) { ?>
    <tr>
      <!-- <th scope="row">1</th> -->
    
      <td><?php echo $list[$i]['DATASET_NAME']?></td>
       <td><?php echo $list[$i]['DATASET_DESCRIPTION']?></td>
        <td><?php echo $list[$i]['DATASET_ID']?></td>
        <td><a href="<?php echo '/ubspectrum/admin/crowdsource/file.php?dataset_id='.$list[$i]['DATASET_ID'].'&undo=true'?>" target="blank">Unarchive </a></td>
        <td><a href="<?php echo '/ubspectrum/admin/crowdsource/file.php?dataset_id='.$list[$i]['DATASET_ID']?>" target="blank">Download </a></td>
        <td><a href="<?php echo '/ubspectrum/admin/crowdsource/file.php?dataset_id='.$list[$i]['DATASET_ID'].'&delete=true'?>" target="blank">Delete </a></td>

    </tr>
  <?php } ?>

  </tbody>
    </table>
  </div></div>
  
</body>
<script>  
 $(document).ready(function(){  
      $('#archivedDatasets').DataTable();  
       $('#currentDatasets').DataTable();  
 });   
 </script>
          </div>
      
        </div>
      
      </div>

</div></div>


</body>
<<!-- script>  
 $(document).ready(function(){  
      $('#currentDatasets').DataTable({
        'columnDefs': [ {
        'targets': [1,4,5,6,7], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }]
      });  

     
 });   
 </script>  -->
<!-- <script>
   $(document).ready(function(){  
   $('#archivedDatasets').DataTable({
        'columnDefs': [ {
        'targets': [2,3,4], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }]
       }); 
    });  
     </script> -->
</html>         
