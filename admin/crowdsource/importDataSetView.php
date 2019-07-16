
<html>
<head>
     <script src="/ubspectrum/javascript/pdfjs/build/pdf.js"></script>
    
    <title>UB Spectrum Admin</title>
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
      } ?>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
     <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
     <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
     <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
</head>
<body>
  <script>

        var currentContactCount = 0;
        var pages = 0;
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
        };

        function addContactFields(){
            if(currentContactCount > 10) return;
            currentContactCount +=1;
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
            $('#contact-section').append(fieldTemplate);
            $(`#contact-${currentContactCount}-group`).show(300);
        };

         function checkFile(e) {
            $( "#dialog" ).dialog({
                     autoOpen: false
                });
            var splitNumber = document.getElementById("split-range").value;
            // if(splitNumber <= 0){
            //    //alert("The split should be greater than 0");
            //     $("#blog").text("The split number should be greater than 0");
            //    $( "#dialog" ).dialog('open');
            // }
            if(splitNumber==null || splitNumber==undefined ){
                //alert("Please Enter the Split Number first");
                 $("#blog").text("Please Enter the Split Number first");
               $( "#dialog" ).dialog('open');
            }
            var file = e.target.files[0];
            var filePath = URL.createObjectURL(file);
            pdfjsLib.getDocument(filePath).promise.then(function (doc) {
                pages = doc.numPages;
               //   $("#blog").text("The number of pages in the file is"+pages);
               // $( "#dialog" ).dialog('open');
                    if(pages < splitNumber){
                        //alert("The number of pages in the file is less than the number of splits requested");
                          $("#blog").text("The number of pages in the file is " + pages + " which is less than the number of splits requested (" + splitNumber +")");
                          $( "#dialog" ).dialog('open');
                    }
                }).catch(console.error);  
        };

         function checkSplit(e) {
            var splitNumber = document.getElementById("split-range").value;
            if(splitNumber <= 0){
                $( "#dialog" ).dialog({
                     autoOpen: false
                });
               //alert("The split should be greater than 0");
               $("#blog").text("The split number should be greater than 0");
               $( "#dialog" ).dialog('open');
            }
            if(splitNumber==null || splitNumber==undefined ){
                //alert("Please Enter the Split Number first");
                 $("#blog").text("Please Enter the Split Number first");
               $( "#dialog" ).dialog('open');
            }
            if(pages != 0 && pages < splitNumber){
                 $("#blog").text("The number of pages in the file is " + pages + " which is less than the number of splits requested (" + splitNumber +")");
               $( "#dialog" ).dialog('open');
                //alert("The number of pages in the file is less than the number of splits requested");
            }
        };
         
  </script>
  <div class="heading">
   <div class="container-fluid">
        <div class="row">
            <div class="col-8" style="margin: 0 auto;">
                <h1 style="text-align:center; margin-bottom: 16px;margin-top: 16px;">Import a Dataset</h1>
            </div>
        </div>
        <form class="form-group" method="post" action="/ubspectrum/admin/crowdsource/model/currentdataset.php" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="name">Name<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <input type="text" name="name" id="name" class="form-control" maxlength="64" placeholder="Name of the Dataset" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="description">Description<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <textarea type="text" name="description" id="description" class="form-control" 
                      placeholder="Description" maxlength="1000" required></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="split">Split Range<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-2">
                    <input id="split-range" name="split" type="number" placeholder="Number of pages/rows you want to split the file" class="form-control" onchange="checkSplit(event);"/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="file">Choose a DataSet<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-2">
                    <input type="file" id="file-input" name="file" accept=".pdf,.csv"  required  onchange="checkFile(event);" />
                </div>
            </div>
            <div id="contact-section">
            <div class="row mb-2">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="question_1">Question</label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <textarea type="text" name="question[]" class="form-control" maxlength="1000"></textarea>
                </div>
            </div> 
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
                <div class="col-xs-3" id ="contact_submit">
                    <input type="hidden" name="contact_count" id="contact_count" value="1">
                    <button class="btn btn-primary" type="submit" name="submit_row" style="margin-left: 16px;">Submit</button>
                </div>
            </div>
        </form>
        <div id="dialog" title="Error!!!!!">
          <div id = "blog" ></div>
        </div>
    </div>
</div>
<script>

    $("#contact_submit button").click(function(event){
    var error_free=true;
     var splitNumber = document.getElementById("split-range").value;
            if(splitNumber <= 0){
               //alert("Form will not be submitted as The split should be greater than 0");
                $("#blog").text("Form will not be submitted as The split should be greater than 0");
               $( "#dialog" ).dialog('open');
               error_free = false;
            }
            if(splitNumber==null || splitNumber==undefined ){
                //alert("Form will not be submitted as Please Enter the Split Number first");
                 $("#blog").text("Form will not be submitted as Please Enter the Split Number first");
               $( "#dialog" ).dialog('open');
                error_free = false;
            }
            if(pages != 0 && pages < splitNumber){
                //alert("Form will not be submitted as The number of pages in the file is less than the number of splits requested");
                 $("#blog").text("Form will not be submitted as The number of pages in the file is less than the number of splits requested");
               $( "#dialog" ).dialog('open');
                error_free = false;
            }
    if (!error_free){
        event.preventDefault(); 
    }
});

</script>

 
</body> 
</html>

