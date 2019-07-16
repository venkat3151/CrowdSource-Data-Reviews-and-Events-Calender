
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include('head-tags.php') ?>

    <title>Add an Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="/ubspectrum/javascript/pdfjs/build/pdf.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="tagify.min.js"></script>
    <link rel="stylesheet" href="tagify.css">
    
</head>

<body>
<script>
    <?php
          require_once "Models/EventCategories.php";

          $categories = EventCategories::getAll();
    ?>

    var categories;
    categories = [
        <?php 
        foreach ($categories as $value) {
            $label = $value['NAME'];
            $icon = $value['ICON'];
            $description = $value['DESCRIPTION'];
            $categoryId = $value['CATEGORY_ID'];
            echo "{label: '$label', icon: '$icon',description:'$description',value: '$categoryId', disabled: false },";
        }    
        ?>
    ];

    function makeCategoryOptions() {
	let categoryMenu = categories.map(
		(cat) =>
			`<a class="dropdown-item ${cat.disabled === true
				? 'disabled'
				: ''}" href="javascript:onSelectTagFromDropdown('${cat.value}')" >${cat.icon ? `<i class="${cat.icon}"></i>` : '' } ${cat.label}</a>`
	);
    $('#category-menu').html(categoryMenu);
    }

    function onSelectTagFromDropdown(id) {
            let cat = getCategoryById(id);

            if (cat.disabled === true) {
                return;
            }

            tagify.addTags([cat]);
            cat.disabled = true;
            makeCategoryOptions();
            let oldVal = $('#categories').val();
            if(oldVal == ''){
                $('#categories').val(id);

            } else {
                $('#categories').val(`${oldVal},${id}`);

            }
        }

    function getCategoryByLabel(label) {
        for (let i = 0; i < categories.length; i++) {
            let cat = categories[i];
            if (cat.label === label) {
                return cat;
            }
        }

        return null;
    }

    function getCategoryById(id) {
        for (let i = 0; i < categories.length; i++) {
            let cat = categories[i];
            if (cat.value === id) {
                return cat;
            }
        }

        return null;
    }
    $(document).ready(function(){
        var input = document.querySelector('input[name=tags-outside]');
        // init Tagify script on the above inputs
        tagify = new Tagify(input, {
            tagTemplate : function(v, tagData){
            return `<tag title='${tagData.label}'>
                            <x title=''></x>
                            <div>
                                <i class="${tagData.icon}"></i>&nbsp;
                                <span class='tagify__tag-text'>${tagData.label}</span>
                            </div>
                        </tag>`;
            },
        });
        tagify.on('remove', function(e) {
            let id = e.detail.data.value;
            let cat = getCategoryById(id);
            cat.disabled = false;

            let oldVal = $('#categories').val().split(',');
            let newVal = oldVal.filter(v => v != id || v == null || v == '');
            $('#categories').val(newVal.join(','))
            
            makeCategoryOptions();
        });
        // add a class to Tagify's input element
        tagify.DOM.input.classList.add('tagify__input--outside');

        // re-place Tagify's input element outside of the  element (tagify.DOM.scope), just before it
        tagify.DOM.scope.parentNode.insertBefore(tagify.DOM.input, tagify.DOM.scope);
        makeCategoryOptions();
    })
</script>

    <script>
        var currentContactCount = 1;
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
            if(currentContactCount > 10) return;

            currentContactCount +=1;
            $('#contact_count').val(currentContactCount);

            var fieldTemplate = `
            <div style="display: none;" id="contact-${currentContactCount}-group">
            <div class="row align-items-center mb-2">
                    <div class="col-md-3 d-none d-md-block"></div>
                    <div class="col-xs-8 col-md-7">
                        <h3>Contact <span class="contact-count">${currentContactCount}</span> </h3>
                    </div>
                    <div class="col-xs-4 col-md-2 align-bottom">
                        <a class="btn btn-danger " href="javascript:removeField(${currentContactCount})"><i class="fa fa-times-circle"></i>&nbsp;
                            Remove</a>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-xs-12 col-md-4 text-md-right">
                        <label for="contact_${currentContactCount}_name">Name<span class="required">*</span></label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <input type="text" name="contact_${currentContactCount}_name" class="form-control" maxlength="64" required/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-xs-12 col-md-4 text-md-right">
                        <label for="contact_${currentContactCount}_type">Contact Type<span class="required">*</span></label>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-3">
                        <select name="contact_${currentContactCount}_type" class="form-control" required>
                            <option value="primary">Primary Event Contact</option>
                            <option value="secondary" selected>Secondary Event Contact</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-xs-12 col-md-4 text-md-right">
                        <label for="contact_${currentContactCount}_info">Information<span class="required">*</span></label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>Email</label>&nbsp;<input type="radio" name="contact_${currentContactCount}_info_opt" value="email" checked required onclick="handleContactInfoType(this);"/>&nbsp;&nbsp;
                        <label>Phone</label>&nbsp;<input type="radio" name="contact_${currentContactCount}_info_opt" value="phone" required onclick="handleContactInfoType(this);"/>
                        <input type="email" name="contact_${currentContactCount}_info" id="contact_${currentContactCount}_info" class="form-control" maxlength="64" required />
                    </div>
                </div>
            </div>
            `;

            $('#contact-section').append(fieldTemplate);
            $(`#contact-${currentContactCount}-group`).show(300);
        }

        function makeThumb(page) {
            var vp = page.getViewport(1);
            var canvas = document.createElement("canvas");
            canvas.width = document.getElementById('flyer-section').clientWidth;
            canvas.height = document.body.clientHeight / 2;
            
            var scale = Math.min(canvas.width / vp.width, canvas.height / vp.height);
            return page.render({canvasContext: canvas.getContext("2d"), viewport: page.getViewport(scale)}).promise.then(function () {
                return canvas;
            });
        }

        
        ;
        function checkFile(e){
            if(e.target.files.length ==0 ) return;

            var file = e.target.files[0]; 

            var ext = file.name.match(/\.([^\.]+)$/)[1];
            $('#flyer-section').empty();

            switch(ext.toLowerCase())
            {
                case 'pdf':
                // case 'jpg':
                // case 'png':
                    var filePath = URL.createObjectURL(file);
                    pdfjsLib.getDocument(filePath).promise.then(function (doc) {
                    // var pages = []; while (pages.length < doc.numPages) pages.push(pages.length + 1);
                    var div = document.createElement("div");
                    $('#flyer-section').append(div);
                    return doc.getPage(1).then(makeThumb)
                    .then(function (canvas) {
                        div.appendChild(canvas);
                    })
                })
                .catch(console.error);
                    break;
                default:
                    alert('Sorry, only PDF files are allowed as the flyer');
                    this.value='';
            }
        };

        function handleContactInfoType(radio){
            let infoType = radio.value;
            let infoTargetElem = $('#' + radio.name.replace('_opt', ''));
            switch(infoType){
                case 'phone':
                    infoTargetElem.attr('type', 'text' );
                    infoTargetElem.attr('pattern', '\\d{3}[\\-]?\\d{3}[\\-]?\\d{4}' );
                    
                    break;
                case 'email':
                    infoTargetElem.attr('type', 'email' );
                    infoTargetElem.removeAttr('pattern');

                break;
            } 
        }
    </script>
    <?php include('navbar-bootstrap.php')?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-3" style="margin: 0 auto;">
                <h1 style="text-align:center;">Add an Event</h1>
            </div>
        </div>
        <form class="form-group" method="post" action="/ubspectrum/events/insertEvent.php" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="name">Name<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <input type="text" name="name" id="name" class="form-control" maxlength="64" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="venue">Venue<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <input type="text" name="venue" id="venue" class="form-control" maxlength="64" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="link">Link<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <input type="text" name="link" id="link" class="form-control" maxlength="1024" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="description">Description<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <textarea type="text" name="description" id="description" class="form-control" maxlength="1000"
                        required></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label>Categories</label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <input type="hidden" name="categories" id="categories" value=""/>
                    <div class="dropdown">
                        <div class="row">
                            <div class="col-xs-6 col-md-4">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Categories
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="category-menu">

                                </div>
                            </div>
                            <div class="col">
                                <input name='tags-outside' class='tagify--outside' style="display: none;">
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="ub_campus">On UB Campus?</label>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-2">
                    <select id="ub_campus" name="ub_campus" class="form-control" required>
                        <option value="north">North Campus</option>
                        <option value="south">South Campus</option>
                        <option value="medical">Medical Campus</option>
                        <option value="off">Off Campus</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="cost">Cost<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-2">
                <div class="input-group"> 
                    <span class="input-group-addon">$</span>
                    <input type="text" name="cost" id="cost" class="form-control"/>
                </div>
                   
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="date">Date<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-2">
                    <input type="text" name="date" id="date" class="form-control" maxlength="12" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="start_time">Start From<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-2">
                    <input type="text" name="start_time" id="start_time" class="form-control" maxlength="10" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="end_time">End At<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-2">
                    <input type="text" name="end_time" id="end_time" class="form-control" maxlength="10" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="flyer">Event Flyer</label>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-2">
                    <input type="file" name="flyer" id="flyer" accept=".pdf" onchange="checkFile(event);" />
                    <span>* PDF smaller than 16MB Only</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 d-none d-md-block"></div>

                <div class="col-xs-12 col-md-6">
                    <div class="col-md-3 d-none d-md-block"></div>

                    <div id="flyer-section"></div>
                </div>
            </div>
            <div id="contact-section">
                <div class="row align-items-center mb-2">
                    <div class="col-md-3 d-none d-md-block"></div>
                    <div class="col-xs-8 col-md-7">
                        <h3>Contact 1 </h3>
                    </div>
                    <div class="col-xs-4 col-md-2 align-bottom">

                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-xs-12 col-md-4 text-md-right">
                        <label for="contact_1_name">Name<span class="required">*</span></label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <input type="text" name="contact_1_name" class="form-control" maxlength="64" required />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-xs-12 col-md-4 text-md-right">
                        <label for="contact_1_type">Contact Type</label>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-3">
                        <select name="contact_1_type" class="form-control" required>
                            <option value="primary" selected>Primary Event Contact</option>
                            <option value="secondary">Secondary Event Contact</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-xs-12 col-md-4 text-md-right">
                        <label for="contact_1_info">Information<span class="required">*</span></label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>Email</label>&nbsp;<input type="radio" name="contact_1_info_opt" value="email" checked onclick="handleContactInfoType(this);"
                            required />&nbsp;&nbsp;
                        <label>Phone</label>&nbsp;<input type="radio" name="contact_1_info_opt" value="phone"  onclick="handleContactInfoType(this);"/>
                        <input type="email" name="contact_1_info" id="contact_1_info" class="form-control" maxlength="64" required />
                    </div>
                </div>



            </div>

            <div class="row mb-3">
                <br />
                <div class="col-md-4 d-none d-md-block"></div>
                <div class="col-xs-3">
                    <button type="button" class="btn btn-info" onclick="addContactFields();"><i class="fa fa-plus"></i>&nbsp;Add
                        Another Contact</button>
                </div>
            </div>
            <br />
            <div class="row  mb-3">
                <div class="col-md-4 d-none d-md-block"></div>
                <div class="col-xs-3">
                    <input type="hidden" name="event_id" value="">
                    <input type="hidden" name="contact_count" id="contact_count" value="1">
                    <button type="button" class="btn btn-default" onclick="javascript:history.back();">Back</button>
                    <button  class="btn btn-primary" onclick="javascript:void(0);" type="submit">Submit</button>
                </div>
            </div>
        </form>

    </div>
    <?php include('footer-bootstrap.php') ?>
    <script>
        $('#date').flatpickr({
            // format: 'LT'
            enableTime: false,
            allowInput: true,
            altInput: true

        });
        $('#start_time').flatpickr({
            enableTime: true,
            noCalendar: true,
            altFormat: "h:i K",
            allowInput: true,
            altInput: true,
            dateFormat: "H:i"



        });
        $('#end_time').flatpickr({
            enableTime: true,
            noCalendar: true,
            altFormat: "h:i K",
            allowInput: true,
            altInput: true,
            dateFormat: "H:i"



        });

       
    </script>
</body>

</html>