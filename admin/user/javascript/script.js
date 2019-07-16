$(document).ready(function(){
    

    // Delete 
    $('.delete').click(function(){
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
                    url: 'adminfile.php',
                    type: 'GET',
                    data: { EMAIL:deleteid,archive:true },
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

    $('.update').click(function(){
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
});