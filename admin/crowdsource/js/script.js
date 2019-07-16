$(document).ready(function(){
    

    // Delete 
    $('.archive').click(function(){
        var el = this;
        var id = this.id;
        // var splitid = id.split("_");
        // Delete id
        var deleteid = id;
        
        // Confirm box
        bootbox.confirm("Are you sure want to archive?", function(result) {
           
            if(result){
                
              
                $.ajax({
                    url: 'file.php',
                    type: 'GET',
                    data: { dataset_id:deleteid,archive:true },
                    success: function(response){
                       
                        var res=response;
                       if(res==0){
                       
                        bootbox.alert('You can not archive a dataset until all of its splits are reviewed.');

                       }
                       else{
                        $(el).closest('tr').css('background','tomato');
                        $(el).closest('tr').fadeOut(800, function(){      
                            $(this).remove();
                        });
                        window.location.reload()
                    }

                    },
                    // error: function() { 
                    //     bootbox.alert('You can not archive until all the reviews for dataset are submitted');
                    // }
                    
                    error: function (xhr, status, errorThrown) {
                        //Here the status code can be retrieved like;
                        xhr.status;
                
                        //The message added to Response object in Controller can be retrieved as following.
                        xhr.responseText;
                        
                    }
                });
            }
            
        });
        
    });

    $('.unarchive').click(function(){
        // document.write("sowmith");
        var el = this;
        var id = this.id;
        // Delete id
        var unarchiveId = id;
        
        // Confirm box
        bootbox.confirm("Are you sure want to Unarchive?", function(result) {
            
            if(result){
                // AJAX Request
                $.ajax({
                    url: 'file.php',
                    type: 'GET',
                    data: { dataset_id:unarchiveId,undo:true },
                    success: function(response){

                        // Removing row from HTML Table
                        $(el).closest('tr').css('background','tomato');
                        $(el).closest('tr').fadeOut(800, function(){      
                            $(this).remove();
                        });
                        window.location.reload()

                    }
                });
            }
            
        });
        
    });

    $('.publish').click(function(){
       // document.write("sowmith");
        var el = this;
        var id = this.id;
        // var splitid = id.split("_");

        // Delete id
        var publishId = id;
        
        // Confirm box
        bootbox.confirm("Are you sure want to publish?", function(result) {
            
            if(result){
                // AJAX Request
                $.ajax({
                    url: 'file.php',
                    type: 'GET',
                    data: { dataset_id:publishId,publish:true },
                    success: function(response){

                        //Removing row from HTML Table
                        $(el).closest('tr').css('background','#F0F8FF');
                        window.location.reload()
                        //$(el).closest('tr').fadeOut(800, function(){      
                            
                        ;

                    }
                });
            }
            
        });
        
    });


    $('.unpublish').click(function(){
        // document.write("sowmith");
         var el = this;
         var id = this.id;
         // var splitid = id.split("_");
 
         // Delete id
         var unpublishId = id;
         
         // Confirm box
         bootbox.confirm("Are you sure want to Unpublish?", function(result) {
             
             if(result){
                 // AJAX Request
                 $.ajax({
                     url: 'file.php',
                     type: 'GET',
                     data: { dataset_id:unpublishId,unpublish:true },
                     success: function(response){
 
                         //Removing row from HTML Table
                         $(el).closest('tr').css('background','blue');
                         window.location.reload()
 
                     }
                 });
             }
             
         });
         
     });

     $('.admindelete').click(function(){
        var el = this;
        var id = this.id;
        // var splitid = id.split("_");
        // Delete id
        var deleteid = id;
        
        
        // Confirm box
        bootbox.confirm("Are you sure want to delete?", function(result) {
            
            if(result){
                // AJAX Request
                $.ajax({
                    url: '../user/server/adminfile.php',
                    type: 'GET',
                    data: { EMAIL:deleteid,delete:true },
                    success: function(response){

                        // Removing row from HTML Table
                        $(el).closest('tr').css('background','tomato');
                        $(el).closest('tr').fadeOut(800, function(){      
                            $(this).remove();
                        });
                        window.location.reload()

                    }
                });
            }
            
        });
        
    });


    $('.adminreject').click(function(){
        var el = this;
        var id = this.id;
        // var splitid = id.split("_");
        // Delete id
        var deleteid = id;
       
        
        // Confirm box
        bootbox.confirm("Are you sure want to reject?", function(result) {
            
            if(result){
                // AJAX Request
                $.ajax({
                    url: '../user/server/adminfile.php',
                    type: 'GET',
                    data: { email:deleteid,reject:true },
                    success: function(response){

                        // Removing row from HTML Table
                        $(el).closest('tr').css('background','tomato');
                        $(el).closest('tr').fadeOut(800, function(){      
                            $(this).remove();
                        });
                        window.location.reload()

                    }
                });
            }
            
        });
        
    });

    $('.adminapprove').click(function(){
        
        var el = this;
        var id = this.id;
        // var splitid = id.split("_");
        // Delete id
        var deleteid = id;
 
        
        // Confirm box
        bootbox.confirm("Are you sure want to approve?", function(result) {
            
            if(result){
                // AJAX Request
                $.ajax({
                    url: '../user/server/adminfile.php',
                    type: 'GET',
                    data: { email:deleteid,approve:true },
                    success: function(response){

                        // Removing row from HTML Table
                        $(el).closest('tr').css('background','#AEEBC6');
                        $(el).closest('tr').fadeOut(800, function(){      
                            $(this).remove();
                        });
                        window.location.reload()

                    }
                });
            }
            
        });
        
    });


   
    

      

    });
