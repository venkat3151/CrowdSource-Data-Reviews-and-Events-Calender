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
      }else{
        header("Location: /ubspectrum/admin/user/signin.php");
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
 
       
        <div class="heading">
        <h5>This is a page where you can see all the datasets that you have imported.You can publish,update,download or archive the datasets.Switch to the other tab to check the Archived Datasets.</h5><br>
        <a href="/ubspectrum/admin/crowdsource/importDataSetView.php" class="btn btn-primary" ><i class="fa fa-plus"></i> Add New Dataset</a>
      </div>
    <table class="table table-striped" id="currentDatasets">
      <thead class="thead-dark">
        <tr >
          <th scope="col">Dataset Name</th>
         
          <th scope="col">Description</th>
        
          <th scope="col">Split #</th>
          <th scope="col" class="center"><i class="fa fa-check-circle"></i></th>
          <th scope="col" class="center"><i class="fa fa-spinner"></i></th>
           <th scope="col" class="center"><i class="fa fa-edit"></i></th>
          <th scope="col" id="download" class="center"><i class="fa fa-download"></i> </th>
          <th scope="col" id="archive" class="center"><i class="fa fa-archive"></i></th>
          
        </tr>
      </thead>
      <tbody>
      <?php 
          for($i=0;$i<sizeof($result);$i++){
          ?>
          <tr >
            <td class = "datasetname"><?php echo $result[$i]['DATASET_NAME']; ?></td>
            <td class = "datasetdescription"><?php echo $result[$i]['DATASET_DESCRIPTION']; ?></td>
            <!-- <td class = "datasetfiletype"><?php echo $result[$i]['DATASET_FILETYPE']; ?></td> -->
            <td class = "datasetsplit"><?php echo $result[$i]['DATASET_SPLITNUM']; ?></td>

            <?php if($result[$i]['PUBLISH']==1){ ?> <td class="center"><button type="button" class="unpublish btn btn-outline-warning" id="<?php echo $result[$i]['DATASET_ID'];?>"> Unpublish</button></td><?php }else{?>
             <td class="center"><button type="button" class="publish btn btn-outline-success" id="<?php echo $result[$i]['DATASET_ID'];?>"> Publish </button></td>
           <?php }?>
            <td class="center" ><a class="btn btn-outline-info" href="<?php echo "/ubspectrum/admin/crowdsource/progressPageView.php?datasetid=".$result[$i]['DATASET_ID'] ?>"
              class=""> Progress</button></td>
                <td class="center"> <a class="btn btn-outline-primary" href="<?php echo "editPageView.php?datasetid=".$result[$i]['DATASET_ID'] ?>"
            class=""> Edit</button></td>
            <td class="center"><a class="btn btn-outline-secondary" href="<?php echo '/ubspectrum/admin/crowdsource/file.php?dataset_id='.$result[$i]['DATASET_ID'].'&selectCurrent=true'?>" target="blank" > Download   </button></td>
           <td class="center"> <button type="button" class="archive btn btn-outline-danger" id="<?php echo $result[$i]['DATASET_ID'];?>"> Archive</button></td>
                         
          </tr>
       
        <?php 
             }
        ?></tbody>
    </table>
  </div></div>
          </div>
          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
               <div class="panel">
                 <div class="panel-body">
                 <div class="heading">
        <h5>The following are the archived datasets. You need to unarchive them to be able to see their progress or download them.</h5><br>
        
      </div>
        
    <table class="table table-striped" id="archivedDatasets">
     <thead class="thead-dark">
    <tr>
     <!--  <th scope="col">#</th> -->
      <th scope="col">Name</th>
      <th scope="col">Description</th>     
      <th scope="col">Id</th>
      <th scope="col"  class="center" ><i class="fa fa-trash-restore"></i> </th>
    
    </tr>
  </thead>
  <tbody>
    <?php for($i=0;$i<sizeof($list);$i++) { ?>
    <tr>
      <!-- <th scope="row">1</th> -->
    
      <td><?php echo $list[$i]['DATASET_NAME']?></td>
       <td><?php echo $list[$i]['DATASET_DESCRIPTION']?></td>
        <td><?php echo $list[$i]['DATASET_ID']?></td>
        <td class="center"> <button type="button" class="unarchive btn btn-primary" id="<?php echo $list[$i]['DATASET_ID']?>"> Unarchive</button></td>
     

    </tr>
  <?php } ?>

  </tbody>
    </table>
  </div></div>
          </div>
        </div>
  </div></div></div>
</body>
<script>  
 $(document).ready(function(){   
      $('#archivedDatasets').DataTable({
        'columnDefs': [ {
        'targets': [3], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }]
      });  
      
           $('#currentDatasets').DataTable({
        'columnDefs': [ {
        'targets': [3,4,5,6,7], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }]
      });
 });   
 </script>
        

</html>         
