<?php 
// $host="localhost";
// $username="root";
// $password="";
// $databasename="spectrum";
// $conn = new mysqli("localhost","root","","spectrum");


include "model/adminModel.php";
$obj= new AdminModel();

$_SESSION['id']=$_GET['datasetid'];
$id=$_SESSION['id'];
$result=$obj->getDatasetData($id);
$question=$obj->getDatasetQuestions($id);
$qcount = count($question);

?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
 <!--  <link rel="stylesheet" href="css/header.css"> -->
 <!--  <script src="js/jquery.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.js"></script> -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UB Spectrum Admin</title>
 <?php   session_start(); 

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
      }?>
</head>
<body>
  <script>
        var currentContactCount = 0;
        var flag = true;
        //alert(currentContactCount);
        function removeField(contactNumber){
            currentContactCount -= 1;
            $('#contact_count').val(currentContactCount);
            $(`#contact-${contactNumber}-group`).hide(300);
            
            setTimeout(() => {
                $(`#contact-${contactNumber}-group`).remove();
                $('.contact-count').each( function( index, value ) {
                $(this).text(index+2)
            })
            }, 301);
        }

        function addContactFields(){
            if(flag){
                currentContactCount = document.getElementById("question_count").value;
                currentContactCount = parseInt(currentContactCount);
                flag = false;
            } 
            //alert("before " + currentContactCount);
            if(currentContactCount > 10) return;
            currentContactCount +=1;
            //alert("after " + currentContactCount);
            $('#contact_count').val(currentContactCount);
            var fieldTemplate = `
            <div style="display: none;" id="contact-${currentContactCount}-group">
            <div class="row align-items-center mb-2">
                    <div class="col-xs-4 col-md-2 align-bottom">
                        <a class="btn btn-danger " href="javascript:removeField(${currentContactCount})"><i class="fa fa-times-circle"></i>&nbsp;
                            Remove</a>
                    </div>
                </div>
                 <div class="row mb-2">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="question[${currentContactCount}]">Question<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <textarea type="text" name="question[${currentContactCount}]" class="form-control" maxlength="1000"></textarea>
                </div>
            </div> 
            </div>
            `;
            $('#total').append(fieldTemplate);
            $(`#contact-${currentContactCount}-group`).show(300);
        }
         
  </script>
   <div class="container-fluid">
        <div class="row">
            <div class="col-8" style="margin: 0 auto;">
                <h1 style="text-align:center; margin-bottom: 16px;margin-top: 16px;">Edit Dataset Page</h1>
            </div>
        </div>
        <form class="form-group" method="post" action="model/editDataset.php" enctype="multipart/form-data">
            <input class='input' type='hidden' name='did' value="<?php echo $result[0]['DATASET_ID']?>" />
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="name">Name<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <input type="text" name="name" id="name" class="form-control" maxlength="64" 
                    value="<?php echo $result[0]['DATASET_NAME']?>"
                     placeholder="Name of the Dataset" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="description">Description<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <textarea type="text" name="description" id="description" class="form-control" 
                      placeholder="Description" maxlength="1000" required><?php echo htmlspecialchars($result[0]['DATASET_DESCRIPTION']); ?></textarea>
                </div>
            </div>
             <?php if($result[0]['PUBLISH']==0){ ?>
             <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="split">Split Range<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-2">
                    <input id="split-range" name="split" type="number"  value="<?php echo $result[0]['DATASET_SPLITNUM']?>" placeholder="Number of pages/rows you want to split the file" class="form-control"/>
                </div>
            </div> 
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="file">Choose a DataSet<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-2">
                    <input type="file" id="file-input" name="file" accept=".pdf" required />
                </div>
            </div> 
            <?php }?>
            <div id = "total">
            <?php foreach($question as $a => $b){ ?>
            <div id="contact-section">
            <div class="row mb-2">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="question_1">Question</label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <textarea type="text" name="question[]" class="form-control" maxlength="1000"><?php echo htmlspecialchars($question[$a][0]); ?></textarea>
                </div>
            </div> 
           </div>
           <?php } ?>
       </div>
            <div class="row mb-3">
                <br />
                <div class="col-md-4 d-none d-md-block"></div>
                <div class="col-xs-3">
                    <button type="button" style="margin-left: 16px;" class="btn btn-info" 
                    onclick="addContactFields();"><i class="fa fa-plus">
                    </i>&nbsp;Add Another Question</button>
                </div>
            </div>
            <div class="row  mb-3">
                <div class="col-md-4 d-none d-md-block"></div>
                <div class="col-xs-3">
                    <input type="hidden" name="contact_count" id="contact_count" value="1">
                    <input type="hidden" name="question_count" id="question_count" value = "<?php echo $qcount?>" >
                    <button class="btn btn-primary" type="submit" name="submit_row" style="margin-left: 16px;">Submit</button>
                    <a href="datasetsView.php" class="btn btn-danger" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body> 
</html>
