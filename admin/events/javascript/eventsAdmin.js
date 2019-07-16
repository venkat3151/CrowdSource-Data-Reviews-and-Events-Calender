window.onload = function() {
  $('#pendingEvents').DataTable({paging: false, scrollY: 400});
  $('#acceptedEvents').DataTable({paging: false, scrollY: 400});

  $.get("/ubspectrum/events/fetchEvents.php", function(data, status){
    var date = new Date();
    data.map(function(dataObj, index) {
      // get only events for the current day and beyond
      date.setHours(0,0,0,0);
      if (date.toISOString() < dataObj.start) {
        if (dataObj.approve === "accepted" && dataObj.recurring == null) {
          var onclick = "window.location.href=\'moreinfo.php?type=accepted&eventid=" + dataObj.id + "\'";
          // adds the existing events to the page
          addAcceptedEvents(dataObj, onclick);
        }
      }
    });
  });


      $.get("/ubspectrum/admin/events/server/fetchPendingEvents.php", function(data, status){
        var date = new Date();
        data.map(function(dataObj, index) {
          date.setHours(0,0,0,0);
          if (date.toISOString() < dataObj.start) {
            if (dataObj.approve === "pending") {
              // adds the pending events to the page
              addPendingEvents(dataObj);
            }
          }
        });
      });

    $.get("server/fetchRecurEvents.php", function(data, status) {
      var date = new Date();
      data.map(function(dataObj, index) {
        date.setHours(0,0,0,0);
        console.log(dataObj);
        if (date.toISOString() < dataObj.start) {
          if (dataObj.approved === "pending") {
            dataObj.id = "RECUR_" + dataObj.id;
            addPendingEvents(dataObj);
          } else if (dataObj.approved === "accepted") {
            dataObj.id = "RECUR_" + dataObj.id;
            var onclick = "window.location.href=\'recurringList.php?eventid=" + dataObj.id + "\'";
            addAcceptedEvents(dataObj, onclick);
          }
        }
      });
    });
}

/**
  function to dynamically add pending admins to the web page
*/
function addPendingEvents(pendingEvent) {
  var startDate = new Date(pendingEvent.start),
      endDate = new Date(pendingEvent.end),
      formatStartDate = startDate.toLocaleDateString(),
      table = $("#pendingEvents").DataTable();


  var startTime = pendingEvent.start.split(/\D+/),
      endTime = pendingEvent.end.split(/\D+/),
      formatStartTime = startTime[3] + ":" + startTime[4],
      formatEndTime = endTime[3] + ":" + endTime[4];

      table.row.add([pendingEvent.id,
                      pendingEvent.title,
                      pendingEvent.description,
                      formatStartDate,
                      formatStartTime,
                      formatEndTime,
                      '<a href="#" id=info'+ event.id +
                      ' onclick="window.location.href=\'moreinfo.php?type=pending&eventid=' + pendingEvent.id +
                      '\'" class="btn btn-primary btn-sm pull-left">Accept/Decline</a>']).draw();
}

function addAcceptedEvents(acceptedEvent, onclick) {
  var startDate = new Date(acceptedEvent.start),
      formatStartDate = startDate.toLocaleDateString(),
      table = $("#acceptedEvents").DataTable();

  var startTime = acceptedEvent.start.split(/\D+/),
      endTime = acceptedEvent.end.split(/\D+/),
      formatStartTime = startTime[3] + ":" + startTime[4],
      formatEndTime = endTime[3] + ":" + endTime[4];

       table.row.add([acceptedEvent.id,
                      acceptedEvent.title,
                      acceptedEvent.description,
                      formatStartDate,
                      formatStartTime,
                      formatEndTime,
                      '<a href="#" id=info'+ acceptedEvent.id +
                      ' onclick=' + onclick +
                      ' class="btn btn-primary btn-sm pull-left">More Info</a>']).draw();
}
