<?php
    require_once "Models/EventCategories.php";

    $categories = EventCategories::getAll();

?>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php include('head-tags.php') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css">
    <link rel="stylesheet" href="eventCalendar.css">

    <script src="tagify.min.js"></script>
    <link rel="stylesheet" href="tagify.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="eventCalendar.js"></script>
    <title>Events Calendar</title>
</head>

<body>
<?php include('navbar-bootstrap.php')?>
<script>
    var categories;
    categories = [
        <?php
        foreach ($categories as $value) {
            $label = $value['NAME'];
            $icon = $value['ICON'];
            $description = $value['DESCRIPTION'];
            $categoryId = $value['CATEGORY_ID'];
            $color = $value['COLOR'];
            echo "{label: '$label', icon: '$icon',description:'$description',value: '$categoryId', color:'$color', disabled: false },";
        }
        ?>
    ];

    var categoryIconMapping = {
        <?php
        foreach ($categories as $value) {
            $label = $value['NAME'];
            $icon = $value['ICON'];
            $description = $value['DESCRIPTION'];
            $categoryId = $value['CATEGORY_ID'];
            echo " '$categoryId':'$icon',";
        }
        ?>
    };
</script>
    <div class="container-fluid" style="padding:10px">
    <div class="row">
        <div class="col-12" id="notification-section">

        </div>
    </div>
        <div class="row">
            <div class="col">

                <div class="row mb-3">
                    <div class="col">
                        <button class="btn btn-secondary" id="toggleFiltersButton">Show Filters</button>
                        &nbsp;
                        <a class="btn btn-primary" href="/ubspectrum/events/submitUpdate.php" >Submit an Update</a>
                        <a class="btn btn-primary" href="/ubspectrum/events/AddEvent.php">Add an Event</a>
                    </div>
                    <div class="col">
                        <tags class="tagify  tagify--outside">
                            <?php
                                foreach ($categories as $value) {
                                    $label = $value['NAME'];
                                    $icon = $value['ICON'];
                                    $description = $value['DESCRIPTION'];
                                    $categoryId = $value['CATEGORY_ID'];
                                    $color = $value['COLOR'];
                                    echo "<tag title='$label' >
                        <div style='background-color: $color;color:white;''>
                            <i class='$icon'></i>&nbsp;
                            <span class='tagify__tag-text'>$label</span>
                        </div>
                    </tag>";
                                }
                                ?>
                        </tags>
                    </div>
                </div>
                <div class="row mb-3" id="filterSection" style="display: none;">
                    <div class="col-xs-12 col-md-3">
                        <h5>By Category</h5>
                        <br/>

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
                                    <input name='tags-outside' class='tagify--outside' style="display: none;" >
                                    <input type="hidden" name="categories" id="categories"/>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-xs-12 col-md-3">
                        <h5>By Time</h5>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <label>From</label>
                                <div class="input-group date" data-target-input="nearest">
                                    <input type="text" class="form-control" id="filterAfter" style="width: 80%" />
                                    <div class="input-group-append" data-target="#filterAfter" data-toggle="datetimepicker">
                                            <div class="input-group-text clear-button" onclick="clearSiblingInput(this);"  ><i class="fa fa-times"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <label>To</label>
                                <div class="input-group date"  data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"id="filterBefore" style="width: 80%" />
                                    <div class="input-group-append" data-target="#filterBefore" data-toggle="datetimepicker">
                                            <div class="input-group-text clear-button" onclick="clearSiblingInput(this);"><i class="fa fa-times"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <h5>By Cost</h5>
                        <br/>
                        <input type="hidden" name="cost" id="cost"/>

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Cost
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="cost-menu">


                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <h5>By Campus Location</h5>
                        <br/>
                        <input type="hidden" name="campus" id="campus"/>

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Campus
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="campus-menu">


                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col">
                <div id='calendar' style="height: 90vh;"></div>
            </div>
        </div>
    </div>
    <script>


        (function(){
            let costOptions = [{label:'All',value:''},{label:'Less than $10',value:'lt10'}, {label:'$10 - $20 ',value:'lt20'}, {label:'$20 - $50 ',value:'lt50'}, {label:'$50 - $100 ',value:'lt100'}, {label:'More than $100 ',value:'gt100'}];
            let costOptionElems = costOptions.map(op => {
                return `<a class="dropdown-item" onclick="javascript:handleCost(this);" data-val="${op.value}">
                                    ${op.label}
                        </a>`;
            }).join('');
            $('#cost-menu').html(costOptionElems);

            let campusOptions = [
                {label:'All',value:''},
                {label:'North Campus',value:'north'},
                {label:'South Campus',value:'south'},
                {label:'Medical Campus',value:'medical'},
                {label:'Off Campus',value:'off'}
            ];
            let campusOptionElems = campusOptions.map(op => {
                return `<a class="dropdown-item" onclick="javascript:handleCampus(this);" data-val="${op.value}">
                                    ${op.label}
                        </a>`;
            }).join('');
            $('#campus-menu').html(campusOptionElems);
        })()
    </script>
    <?php include('footer-bootstrap.php') ?>
</body>

</html>
