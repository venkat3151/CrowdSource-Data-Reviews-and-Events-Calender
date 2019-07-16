<?php 

if(isset($_GET['EMAIL']) and $_GET['ROLE'] and $_GET['fullname']){
    $email=$_GET['EMAIL'];
    $role=$_GET['ROLE'];
    $fullname=$_GET['fullname'];
}
  

?>
<head>
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="css/header.css">
  <script src="../crowdsource/js/jquery.js"></script>
  <script src="../crowdsource/js/popper.js"></script>
  <script src="../../bootstrap/js/bootstrap.js"></script>
  <link rel="icon"  href="images/favicon.png" />

</head>
<body>
<?php include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/superHeader.php";?>

<div class="heading">
        
        <div class="container fluid">
          <h4> Update the details of admins </h4>
        <form method="POST" action="server/adminfile.php">
                    <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="text" class="form-control" name="updateEmail"value="<?php echo $email ?>">
                        </div>
                        <div class="form-group">
                        <label for="exampleInputPassword1">Full Name</label>
                        <input type="text"  class="form-control" name="updateFullname" value="<?php echo $fullname; ?>">
                      </div>
                      
                        <div class="form-group">
                        <label for="exampleInputPassword1">Role</label>
                          <div class="form-check">
                          <input type="radio" name="updateRole" <?php if($role=="super") {echo "checked";}?> value="super">
                            <label class="form-check-label" for="exampleRadios1">
                              super
                            </label>
                          </div>
                          <div class="form-check">
                          <input type="radio" name="updateRole" <?php if($role=="crowd") {echo "checked";}?> value="crowd">
                            <label class="form-check-label" for="exampleRadios2">
                              crowd
                            </label>
                          </div>
                          <div class="form-check">
                          <input type="radio" name="updateRole" <?php if($role=="event") {echo "checked";}?> value="event">
                            <label class="form-check-label" for="exampleRadios3">
                              event
                            </label>
                          </div>
  
                  </div>
                     
                     
                      <button class="btn btn-success" type="submit" value="submit" >Update</button>
                     
                      <a class="btn btn-secondary" href="/ubspectrum/admin/user/userManagement.php"  >Cancel</a>
                      
                    

                    
                </form>
                <?php
                    if (isset($_POST['updateRole'])){
                    echo $_POST['updateRole']; // Displays value of checked checkbox.
                    }
                    ?>
              </div>

</div>
</body>