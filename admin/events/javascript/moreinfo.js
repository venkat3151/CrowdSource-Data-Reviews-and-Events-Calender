function addEventInfo() {
  const urlParams = new URLSearchParams(window.location.search);
  const type = urlParams.get('type');
  var id = urlParams.get('eventid');

  document.getElementById("updateAllRecurring").value = "false";

  if (id.includes("RECUR_")) {
    if (type == "accepted") {
        document.getElementById("updateAllRecurring").value = "true";
        document.getElementById("start_time").readOnly = true;
        document.getElementById("end_time").readOnly = true;
        document.getElementById("date").readOnly = true;
    }

    id = id.replace("RECUR_", "");

    $.ajax({
      type: "POST",
      url: "server/fetchRecurEvents.php",
      data: {id: id}
    }).then(function(data) {
      addDataToWindow(JSON.parse(data));
    });
  } else {
    $.ajax({
      type: "POST",
      url: "/ubspectrum/admin/events/server/fetchEventInfo.php",
      data: {id: id}
    }).then(function(data) {
      addDataToWindow(data);
    });
  }


  document.getElementById("event_id").value = id;
  document.getElementById("event_type").value = type;

  if (type === "accepted") {
    $("#acceptBtn").hide();
    $("#declineBtn").hide();
  } else if(type === "pending") {
    $("#deleteBtn").hide();
  }

}

function addDataToWindow(event) {
  $('#declineTitle').text("Decline Event: " + event.NAME);
  $('#deleteTitle').text("Delete Event: " + event.NAME);
  $('#acceptTitle').text("Accept Event: " + event.NAME);

  document.getElementById("addedBy").value = event.ADDED_BY;

  document.getElementById("name").value = event.NAME;

  document.getElementById("venue").value = event.VENUE;

  document.getElementById("link").value = event.LINK;

  document.getElementById("description").value = event.DESCRIPTION;
  $('#characters').text(1000 - event.DESCRIPTION.length);

  const urlParams = new URLSearchParams(window.location.search);
  const type = urlParams.get('type');
  var id = urlParams.get('eventid');

  if (id.includes("RECUR_")) {
    if (type == "pending") {
        makeRecurring();
        document.getElementById("repeat").value = event.REPEAT_BY;
        $('#lastDay').flatpickr({
          enableTime: false,
          altInput: true,
          minDate: new Date(),
          defaultDate: event.LAST_DATE
        });
    }

    $.ajax({
      type: "POST",
      url: "server/fetchRecurringEventC.php",
      data: {
              id: event.RECURING_EVENT_ID,
              type: "category"
            }
    }).then(function(data) {
      JSON.parse(data).map(function(cat) {
        onSelectTagFromDropdown(cat.CATEGORY_ID.toString());
      });
    });
  } else {
      $.ajax({
        type: "POST",
        url: "server/fetchEventC.php",
        data: {
                id: event.ID,
                type: "category"
              }
      }).then(function(data) {
        JSON.parse(data).map(function(cat) {
          onSelectTagFromDropdown(cat.CATEGORY_ID.toString());
        });
      });
}

  document.getElementById("ub_campus").value = event.UB_CAMPUS_LOCATION;

  document.getElementById("eventCost").value = event.COST;

  var startDate = new Date(event.START_TIME),
      endDate = new Date(event.END_TIME);

  if (!id.includes("RECUR_") || type !== "accepted") {
      $('#date').flatpickr({
        enableTime: false,
        altInput: true,
        minDate: new Date(),
        defaultDate: startDate
      });


      $('#start_time').flatpickr({
          enableTime: true,
          noCalendar: true,
          altFormat: "h:i K",
          altInput: true,
          minDate: new Date(),
          dateFormat: "H:i",
          defaultDate: startDate
      });

      $('#end_time').flatpickr({
          enableTime: true,
          noCalendar: true,
          altFormat: "h:i K",
          minDate: new Date(),
          altInput: true,
          dateFormat: "H:i",
          defaultDate: endDate
      });
    } else {
      var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

      // start time
      var hr = startDate.getHours();
      var min = startDate.getMinutes();
      if (min < 10) {
          min = "0" + min;
      }
      var ampm = "AM";
      if( hr > 12 ) {
          hr -= 12;
          ampm = "PM";
      }
      var date = startDate.getDate();
      var month = months[startDate.getMonth()];
      var year = startDate.getFullYear();

      // end time
      var ehr = endDate.getHours();
      var emin = endDate.getMinutes();
      if (emin < 10) {
          emin = "0" + emin;
      }
      var eampm = "AM";
      if( ehr > 12 ) {
          ehr -= 12;
          eampm = "PM";
      }

      document.getElementById("date").value = month + " " + date + ", " + year;
      document.getElementById("start_time").value = hr + ":" + min + " " + ampm;
      document.getElementById("end_time").value = ehr + ":" + emin + " " + eampm;
    }

  if (id.includes("RECUR_")) {
    $.ajax({
      type: "POST",
      url: "server/fetchRecurringEventC.php",
      data: {
              id: event.RECURING_EVENT_ID,
              type: "contact"
            }
    }).then(function(data) {
      var jsonData = JSON.parse(data);
      jsonData.map(function(con, index) {
        var i = index + 1;

        document.getElementById("contact_" + i + "_name").value = con.PERSON_NAME;
        document.getElementById("contact_" + i + "_type").value = con.CONTACT_TYPE;
        if (con.ADDITIONAL_INFO.includes("@")) {
          $("#contact_" + i + "_info_opt_email").prop("checked", true);

        } else {
          $("#contact_" + i + "_info_opt_phone").prop("checked", true);
        }
        document.getElementById("contact_" + i + "_info").value = con.ADDITIONAL_INFO;

        if (index < jsonData.length - 1) {
          addContactFields();
        }
      });
    });

  } else {
    console.log(id);
    console.log(event.ID);
    $.ajax({
      type: "POST",
      url: "server/fetchEventC.php",
      data: {
              id: event.ID,
              type: "contact"
            }
    }).then(function(data) {
      var jsonData = JSON.parse(data);
      jsonData.map(function(con, index) {
        console.log(con);
        var i = index + 1;

        document.getElementById("contact_" + i + "_name").value = con.PERSON_NAME;
        document.getElementById("contact_" + i + "_type").value = con.CONTACT_TYPE;
        if (con.ADDITIONAL_INFO.includes("@")) {
          $("#contact_" + i + "_info_opt_email").prop("checked", true);

        } else {
          $("#contact_" + i + "_info_opt_phone").prop("checked", true);
        }
        document.getElementById("contact_" + i + "_info").value = con.ADDITIONAL_INFO;

        if (index < jsonData.length - 1) {
          addContactFields();
        }
      });
    });
  }
}

function onDeleteConfirm() {
    $("#deleteConfirm").modal('show');
}

function onDeclineConfirm() {
  $("#declineConfirm").modal('show');
}

function onAcceptConfirm() {
  $("#acceptConfirm").modal('show');
}

function acceptEvent() {
  document.getElementById("updateAllRecurring").value = "make";
  document.getElementById("event_type").value = "accepted";

  if (checkAllFields()) {
      document.forms["info"].submit();
  } else {
    $("#acceptConfirm").modal('hide');
  }
}
