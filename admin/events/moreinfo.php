<?php
  session_start();
  if($_SESSION == array() || !isset($_SESSION['sessionID'])) {
    $_SESSION['type'] = "user";
    if ($_SESSION['student'] == "false" || !isset($_SESSION['student'])) {
      header("Location: /ubspectrum/events/");
    } else {
      $_SESSION['student'] = "false";
    }
  } else {
    $_SESSION['type'] = "admin";
    $permission = $_SESSION['userPermission'];

    if ($permission != "event" && $permission != "super") {
      if ($permission == "crowd") {
        header("Location: /ubspectrum/admin/user/homepage.php");
      } else {
        header("Location: /ubspectrum/admin/user/signin.php");
      }
    }
  }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>UB Spectrum Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../../events/tagify.min.js"></script>
    <link rel="stylesheet" href="../../events/tagify.css">
    <script src="/ubspectrum/events/tagify.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
    <script src="/ubspectrum/bootstrap/js/popper.js"></script>
    <script src="/ubspectrum/javascript/pdfjs/build/pdf.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous">
    <script src="/ubspectrum/javascript/pdfThumbnails.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
    <link rel="stylesheet" href="site.css">
    <link rel="stylesheet" href="https://d35ppshcip65c3.cloudfront.net/58fcd72c5fb5d82391aebc3a0509a31a/dist/css/master.css">

</head>

<body>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script type="text/javascript" src="javascript/moreinfo.js"></script>
<script>
  <?php
      require_once "../../events/Models/EventCategories.php";

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
          $color = $value['COLOR'];
          echo "{label: '$label', icon: '$icon',description:'$description',value: '$categoryId',color:'$color', disabled: false },";
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

    function validateInput() {
            let input = $(this);
            let isRequired = input.attr('required') ? true : false;
            let type = input.data('type') || 'text';
            let isValid = false;
            if (isRequired && input.val() != '') {
                isValid = true;
            }

            if (!isValid) {
                input.removeClass('is-valid').addClass('is-invalid');
            }
            let value = input.val();

            switch (type) {
                case 'text':

                    break;
                case 'integer':
                    break;
                case 'money':

                    let moneyPatternMatches = value.match(/^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$/);
                    if (moneyPatternMatches != null) {
                        isValid = true;
                    } else {
                        isValid = false;
                    }
                    break;
                case 'date':
                    let datePatternMatches = value.match(/[0-9]{4}-[0-9]{2}-[0-9]{1,2}/);
                    if (datePatternMatches != null) {
                        isValid = true;
                    } else {
                        isValid = false;
                    }
                    break;
                case 'time':
                    break;
                case 'email':
                    break;
                case 'phone':
                    let phonePatternMatches = value.match('\\d{3}[\\-]?\\d{3}[\\-]?\\d{4}');
                    if (phonePatternMatches != null) {
                        isValid = true;
                    } else {
                        isValid = false;
                    }
                    break;
                case 'link':
                    if (!isRequired && value == '') {
                        isValid = true;
                    } else {
                        let urlPatternMatches = value.match(
                            /(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/
                            );
                        if (urlPatternMatches != null) {
                            isValid = true;
                        } else {
                            isValid = false;
                        }
                    }
                    break;
            }

            if (isValid) {
                input.removeClass('is-invalid').addClass('is-valid');
            } else {
                input.removeClass('is-valid').addClass('is-invalid');
            }
        }

        function handleTime(){
            let startTime = $('#start_time').val();
            let endTime = $('#end_time').val()

            let startVisibleSibling = $($('#start_time').siblings('input')[0]);
            let endVisibleSibling = $($('#end_time').siblings('input')[0]);

            if(endTime == '' || startTime == ''){
                return false;
            }

            let happensBeforeEnd = false;
            if(Date.parse('01/01/2011 ' + startTime) < Date.parse('01/01/2011 ' + endTime)){
                happensBeforeEnd = true;
            } else {
                happensBeforeEnd = false;
            }

            if (happensBeforeEnd) {

                startVisibleSibling.removeClass('is-invalid').addClass('is-valid');
                endVisibleSibling.removeClass('is-invalid').addClass('is-valid');
                return true;
            } else {
                startVisibleSibling.removeClass('is-valid').addClass('is-invalid');
                endVisibleSibling.removeClass('is-valid').addClass('is-invalid');

                return false;
            }

        }

        function handleLastDay() {
          console.log("here");
            let startTime = $('#date').val() || '';
            let endTime = $('#lastDay').val() || '';

            let visibleSibling = $($(this).siblings('input')[0]);
            let startVisibleSibling = $($('#date').siblings('input')[0]);
            let endVisibleSibling = $($('#lastDay').siblings('input')[0]);

            if(endTime == '' || startTime == ''){
                return;
            }

            let happensBeforeEnd = false;
            if(Date.parse(startTime) < Date.parse(endTime)) {
                happensBeforeEnd = true;
            } else {
                happensBeforeEnd = false;
            }

            if (happensBeforeEnd) {
                startVisibleSibling.removeClass('is-invalid').addClass('is-valid');
                endVisibleSibling.removeClass('is-invalid').addClass('is-valid');
            } else {
                startVisibleSibling.removeClass('is-valid').addClass('is-invalid');
                endVisibleSibling.removeClass('is-valid').addClass('is-invalid');
            }

        }

    $(document).ready(function(){
        var input = document.querySelector('input[name=tags-outside]');
        // init Tagify script on the above inputs
        tagify = new Tagify(input, {
            tagTemplate : function(v, tagData){
              return `<tag title='${tagData.label}'>
                      <x title='' style="background-color: ${tagData.color};color:white;"></x>
                      <div style="background-color: ${tagData.color};color:white;">
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

        const urlParams = new URLSearchParams(window.location.search);
        const type = urlParams.get('type');

        if (type == "pending") {
            var recurring = `<div id="newDiv" class="col-xs-12 col-md-4 text-md-right"></div>
                            <div class="col-xs-12 col-md-3 col-lg-2">
                              <button type="button" id="recurBtn" class="btn btn-info" onclick=makeRecurring()>Make Recurring</button>
                             </div>`
            $('#recurring').append(recurring);
        }

        addEventInfo();

        $('input').on('blur', validateInput);
           $('#contact-section').on('blur','input', validateInput);
           $('#start_time').on('change', handleTime);
           $('#end_time').on('change', handleTime);
           $('#lastDayDiv').on('change', '#lastDay', handleLastDay);
           $('#date').on('change', handleLastDay);
           $('textarea').on('blur', validateInput);

    })

    function makeRecurring() {
      var repeat = document.getElementById("rDiv"),
          lDay = document.getElementById("lDiv");

      if (repeat && lDay) {
        document.getElementById("recurBtn").innerHTML = "Make Recurring";
        repeat.remove();
        document.getElementById("r2Div").remove();
        lDay.remove();
        document.getElementById("l2Div").remove();
      } else {
        document.getElementById("recurBtn").innerHTML = "Cancel Recurring";
        var recurring = `<div id="rDiv" class="col-xs-12 col-md-4 text-md-right">
                            <label for="repeat">Repeat</label>
                        </div>
                        <div id="r2Div" class="col-xs-12 col-md-3 col-lg-2">
                          <select id="repeat" name="repeat" class="form-control" required>
                              <option value="daily">Daily</option>
                              <option value="weekly">Weekly</option>
                              <option value="monthly">Monthly</option>
                          </select>
                        </div>`;

            var lastDay= `<div id="lDiv" class="col-xs-12 col-md-4 text-md-right">
                            <label for="lastDay">Last Day<span class="required">*</span></label>
                        </div>
                        <div id="l2Div" class="col-xs-12 col-md-3 col-lg-2">
                            <input type="text" name="lastDay" id="lastDay" placeholder="Click to Select Time" class="form-control" maxlength="10" required data-type="time"/>
                        </div>`;

                    $('#repeatDiv').append(recurring);
                    $('#lastDayDiv').append(lastDay);
                    $('#lastDay').flatpickr({
                        // format: 'LT'
                        enableTime: false,
                        allowInput: false,
                        altInput: true,
                        minDate: "today"
                    });
                    $('input').on('blur', validateInput);
              }
    }

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
                        <input type="text" name="contact_${currentContactCount}_name" id="contact_${currentContactCount}_name" class="form-control" maxlength="64" required/>
                        <div class="invalid-feedback">
                            Please enter a name.
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-xs-12 col-md-4 text-md-right">
                        <label for="contact_${currentContactCount}_type">Contact Type<span class="required">*</span></label>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-3">
                        <select name="contact_${currentContactCount}_type" id="contact_${currentContactCount}_type" class="form-control" required>
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
                        <label>Email</label>&nbsp;<input type="radio" name="contact_${currentContactCount}_info_opt" id="contact_${currentContactCount}_info_opt_email" value="email" checked required onclick="handleContactInfoType(this);"/>&nbsp;&nbsp;
                        <label>Phone</label>&nbsp;<input type="radio" name="contact_${currentContactCount}_info_opt" id="contact_${currentContactCount}_info_opt_phone" value="phone" required onclick="handleContactInfoType(this);"/>
                        <input type="email" name="contact_${currentContactCount}_info" id="contact_${currentContactCount}_info" class="form-control" maxlength="64" required />
                        <div class="invalid-feedback" id="contact_${currentContactCount}_info_error">
                            Please enter a valid email.
                        </div>
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

        function checkCategories(){
            let hasAtLeastOne = false;
            let categories = $('#categories').val();

            if(categories != ''){
                categories = categories.split(',');

                if( categories.length >= 1){
                    hasAtLeastOne = true;
                }
            }

            if(hasAtLeastOne){
                $('#category-dropdown button').removeClass('btn-danger is-invalid').addClass('btn-secondary ');
                $('#category-dropdown-error').hide();
            } else {
                $('#category-dropdown button').removeClass('btn-secondary').addClass('btn-danger is-invalid');
                $('#category-dropdown-error').show();

            }

            return hasAtLeastOne;
        }

        function checkAllFields(){
            let isValid = true;
            console.log(isValid);
            $('#info input:visible').each(function(){
              $(this).trigger('blur');
            });

            $('#info textarea:visible').each(function(){
                $(this).trigger('blur');
            });

            isValid = isValid && handleTime.bind(document.getElementById('start_time')).call();
            console.log(isValid);
            isValid = isValid && handleTime.bind(document.getElementById('end_time')).call();
            console.log(isValid);

            let hasAtLeastOne = checkCategories();
            console.log(hasAtLeastOne);
            isValid = isValid && hasAtLeastOne;
            console.log(isValid);
            if(!isValid){
                $('html, body').animate({
                    scrollTop: $('#info .is-invalid').first().offset().top
            }, 500);
            }

            console.log(isValid);
            return isValid;

        }

        function checkFile(e){
            if(e.target.files.length ==0 ) return;

            var file = e.target.files[0];

            var ext = file.name.match(/\.([^\.]+)$/)[1];
            $('#flyer-section').empty();

            switch(ext)
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
                        document.getElementById("flyerImg").parentNode.removeChild(document.getElementById("flyerImg"));
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
                infoTargetElem.attr('type', 'email');
                infoTargetElem.data('type', 'email');
                infoTargetElem.removeAttr('pattern');
                break;
            }
        }
    </script>
    <?php
      if ($_SESSION['type'] == "admin") {
          include('header-info.php');
      } else if ($_SESSION['type'] == "user") {
        include('../../events/navbar-bootstrap.php');
      }
       ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-3" style="margin: 0 auto;">
                <h1 style="text-align:center;"></h1>
            </div>
        </div>
        <form id='info' class="form-group" method="post" action="server/saveEvent.php"
            enctype="multipart/form-data" onsubmit="return checkAllFields();">
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="addedBy">Your Email<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <input type="email" name="addedBy" id="addedBy" class="form-control" maxlength="64" required />
                    <div class="invalid-feedback" >
                            Please enter a valid email.
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="name">Name<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <input type="text" name="name" id="name" class="form-control" maxlength="64" required />
                    <div class="invalid-feedback">
                        Please enter a name.
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="venue">Venue<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <input type="text" name="venue" id="venue" class="form-control" maxlength="64" required />
                    <div class="invalid-feedback">
                        Please enter a venue.
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="link">Link</label>
                </div>
                <div class="col-xs-12 col-md-4">
                  <input type="text" name="link" id="link" class="form-control" maxlength="1024"
                      data-type="link" />
                    <div class="invalid-feedback">
                        Please enter a valid link, such as https://www.ubspectrum.com.
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="description">Description<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <textarea type="text" name="description" id="description" class="form-control" maxlength="1000"
                        required></textarea>
                    <h6 class="pull-right"><span  id="characters">1000</span> characters left</h6>
                    <div class="invalid-feedback">
                            Please enter a description.
                    </div>
                    <script>
                    $('#description').keyup(updateCount);
                    $('#description').keydown(updateCount);

                    function updateCount() {
                        var cs = $(this).val().length;
                        $('#characters').text(1000 - cs);
                    }
                    </script>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label>Categories<span class="required">*</span></label>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="input-group">
                        <input type="hidden" name="categories" id="categories" value="" />
                        <div class="dropdown" id="category-dropdown">
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
                            <div class="invalid-feedback" id="category-dropdown-error" >
                                Please choose at least one category.
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
                    <input type="number" id="eventCost"  data-type="money" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" name="cost" id="cost" class="form-control"/>
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
            <div class="row mb-3" id="recurring"></div>
            <div class="row mb-3" id="repeatDiv"></div>
            <div class="row mb-3" id="lastDayDiv"></div>

            <div class="row mb-3">
                <div class="col-xs-12 col-md-4 text-md-right">
                    <label for="flyer">Update Event Flyer</label>
                </div>
                <div class="col-xs-12 col-md-3">
                    <input type="file" name="flyer" id="flyer" accept=".pdf" onchange="checkFile(event);" />
                    <span>* PDF,PNG, or JPG smaller than 16MB Only</span>
                    <?php
                      require_once "../../events/Models/Events.php";
                      //require_once "/events/server/Models/Event.php";

                        $eventId = $_GET['eventid'];

                        if (strpos($eventId, 'RECUR_') !== false) {
                          $eventId = str_replace("RECUR_", "", $eventId);
                          $eventInfo = Events::getRecurringEventInfo($eventId);

                          $attachmentType = $eventInfo['ADDITIONAL_FILE_TYPE'];

                          if (strpos($attachmentType, 'pdf') !== false) {
                              echo '<img id="flyerImg" data-pdf-thumbnail-file="/ubspectrum/events/downloadRecurringEventFlyer.php?eventId='. $eventId . '">';
                          }
                          if (strpos($attachmentType, 'image') !== false) {
                              $imageData = $eventInfo['ADDITIONAL_FILE'];
                              echo '<img src="data:image/png;base64,'.base64_encode($imageData).'">';
                          }
                        } else {
                          $eventInfo = Events::getEventInfo($eventId);
                          $attachmentType = $eventInfo['ADDITIONAL_FILE_TYPE'];

                          if (strpos($attachmentType, 'pdf') !== false) {
                              echo '<img id="flyerImg" data-pdf-thumbnail-file="/ubspectrum/events/downloadEventFlyer.php?eventId='. $eventId . '">';
                          }
                          if (strpos($attachmentType, 'image') !== false) {
                              $imageData = $eventInfo['ADDITIONAL_FILE'];
                              echo '<img src="data:image/png;base64,'.base64_encode($imageData).'">';
                          }
                        }

                    ?>
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
                        <input type="text" name="contact_1_name" id="contact_1_name" class="form-control" maxlength="64" required />
                        <div class="invalid-feedback">
                            Please enter a name.
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-xs-12 col-md-4 text-md-right">
                        <label for="contact_1_type">Contact Type</label>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-3">
                        <select name="contact_1_type" id="contact_1_type" class="form-control" required>
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
                        <label>Email</label>&nbsp;<input type="radio" id="contact_1_info_opt_email" name="contact_1_info_opt" value="email" checked onclick="handleContactInfoType(this);"
                            required />&nbsp;&nbsp;
                        <label>Phone</label>&nbsp;<input type="radio" id="contact_1_info_opt_phone" name="contact_1_info_opt" value="phone"  onclick="handleContactInfoType(this);"/>
                        <input type="text" name="contact_1_info" id="contact_1_info" class="form-control" maxlength="64" required />
                        <div class="invalid-feedback" id="contact_1_info_error">
                            Please enter a valid email.
                        </div>
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
                    <input type="hidden" name="event_id" id="event_id" value="">
                    <input type="hidden" name="event_type" id="event_type" value="">
                    <input type="hidden" name="updateAllRecurring" id="updateAllRecurring" value="">
                    <input type="hidden" name="contact_count" id="contact_count" value="1">
                    <button type="button" class="btn btn-default" onclick="javascript:history.back();">Back</button>&nbsp
                    <button  class="btn btn-primary" id="saveBtn" onclick="javascript:checkAllFields();" type="submit">Save</button>&nbsp
                    <button  class="btn btn-danger" id="deleteBtn" onclick=onDeleteConfirm() type="button">Delete</button>
                    <?php
                      if ($_SESSION['type'] == 'admin') {
                          include('info-footer.php');
                      } else if ($_SESSION['type'] == 'user' && $_GET['type'] == 'pending') {
                        echo '<button  class="btn btn-danger" id="deleteBtn" onclick=onDeleteConfirm() type="button">Delete</button>';
                      }
                     ?>
            </div>
        </form>

    </div>


    <div class="modal" id="declineConfirm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="declineTitle">Decline Event</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to decline this event? Once declined it will not be added to the calendar and you will not be able to get it back.</p>
          <br/>
          <label>Reason*</label>
          <div>
            <textarea name="reason" id="reason" class="form-control" required></textarea>
            <div class="invalid-feedback">
                          Please enter a reason for declining.
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          <button type="button" onclick=declineEvent() class="btn btn-danger btn-sm">Decline</button>
        </div>
      </div>
    </div>
    </div>

    <div class="modal" id="deleteConfirm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteTitle">Delete Event</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this event? Once deleted you will not be able to get it back.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          <button type="button" onclick=deleteEvent() class="btn btn-danger btn-sm">Delete</button>
        </div>
      </div>
    </div>
  </div>

      <div class="modal" id="acceptConfirm" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="acceptTitle">Accept Event</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to accept this event? Accepting save all changes and will add the event to the calendar.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            <button type="button" onclick=acceptEvent() class="btn btn-success btn-sm">Save & Accept</button>
          </div>
        </div>
      </div>
    </div>

</body>
<?php
if ($_SESSION['type'] == "user") {
  include('../../events/footer-bootstrap.php');
}
 ?>

<script>
function deleteEvent() {
  const urlParams = new URLSearchParams(window.location.search);
  var id = urlParams.get('eventid'),
      addedBy = $("#addedBy").val() || "user";

  var type = "single";
  if (id.includes("RECUR_")) {
    type = "recur"
    id = id.replace("RECUR_", "");
  }

  $.ajax({
    type: "POST",
    url: "server/deleteEvent.php",
    data: {eventId: id, type: type, user: addedBy, action: 'delete'},
  });
  <?php
    if (!isset($_SESSION['sessionID'])) {
      echo "window.location = '../../events/'";
    }
   ?>

   if (type === "recur") {
     window.location.replace("eventsAdmin.php");
   } else {
      window.location.replace(document.referrer);
   }

}

function declineEvent() {
  const urlParams = new URLSearchParams(window.location.search);
  var reason = $('#reason').val();
  if(reason.length > 0){
    $('#reason').removeClass('is-invalid').addClass('is-valid');
  } else {
    $('#reason').removeClass('is-valid').addClass('is-invalid');
    return;

  }

  var id = urlParams.get('eventid'),
      addedBy = $("#addedBy").val() || "user";

  var type = "single";
  if (id.includes("RECUR_")) {
    type = "recur"
    id = id.replace("RECUR_", "");
  }

  $.ajax({
    type: "POST",
    url: "server/deleteEvent.php",
    data: {eventId: id, type: type, user: addedBy, action: 'decline', reason: reason},
  });
  <?php
    if (!isset($_SESSION['sessionID'])) {
      echo "window.location = '../../events/'";
    }
   ?>

   if (type === "recur") {
     window.location.replace("eventsAdmin.php");
   } else {
      window.location.replace(document.referrer);
   }

}
</script>
</html>
