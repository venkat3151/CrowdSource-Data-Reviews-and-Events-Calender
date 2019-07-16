var id;
window.onload = function() {
  $('#events').DataTable({paging: false, scrollY: 400});

  const urlParams = new URLSearchParams(window.location.search);
  id = urlParams.get('eventid');
  id = id.replace("RECUR_", "");

  document.getElementById("heading").innerHTML = "Events for Recurring Event: " + id;

  $.ajax({
    type: "POST",
    url: "/ubspectrum/admin/events/server/fetchRecurEventsById.php",
    data: {id: id}
  }).then(function(data) {
    data = JSON.parse(data);
    data.map(function(dataObj) {
      if (dataObj.approved == "accepted") {
          addRecurringEvents(dataObj);
      }
    });
  });
}

function addRecurringEvents(recurringEvent) {
  var startDate = new Date(recurringEvent.start),
      endDate = new Date(recurringEvent.end),
      formatStartDate = startDate.toLocaleDateString(),
      table = $("#events").DataTable();

  var startTime = recurringEvent.start.split(/\D+/),
      endTime = recurringEvent.end.split(/\D+/),
      formatStartTime = startTime[3] + ":" + startTime[4],
      formatEndTime = endTime[3] + ":" + endTime[4];

      table.row.add([recurringEvent.id,
                      recurringEvent.title,
                      recurringEvent.description,
                      formatStartDate,
                      formatStartTime,
                      formatEndTime,
                      '<a href="#" id=info'+ recurringEvent.id +
                      ' onclick="window.location.href=\'moreinfo.php?type=accepted&eventid=' + recurringEvent.id +
                      '\'" class="btn btn-primary btn-sm pull-left">More Info</a>']).draw();

}

function openModalConfirm() {
  $('#continueConfirm').modal('show');
  //callMoreInfo();
}

function callMoreInfo() {
  window.location.href='moreinfo.php?type=accepted&eventid=RECUR_' + id;
}
