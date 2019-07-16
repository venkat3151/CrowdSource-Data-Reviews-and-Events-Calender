 
$(document).ready(function() {
  
  
    // $("#dialog").dialog({
    // autoOpen: false
    // });

     $("#datasetsAllbutton").click(function(e) {
      
    $('html,body').animate({
        scrollTop: $("#datasetsAll").offset().top},
        'slow');
}); 


    $("#registerModal").click(function(e) {
    $('#modalRegisterForm').modal('show');
});

$("#forgotPasswordSubmit").click(function(e) {    
    $('#forgotPasswordForm').modal('show');

    });

 $('#mailModal').on('hidden.bs.modal', function () {
  window.location.href="index.php";
}); 
});

    