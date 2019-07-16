$(document).ready(function() {
  // test data -- will need to call db -- updated in a later story
  var i = 0, history = [];
  for (i; i < 30; i++) {
    history.push({app: "Events Calendar", user: "aepellec", data: "user alan", action: "delete", timestamp: "2017-07-04*13:23:55"});
    history.push({app: "Crowdsourced Data Review", user: "alan", data: "event *name* deleted", action: "delete", timestamp: "timestamp"});
    history.push({app: "User Management", user: "aepellec", data: "user alan", action: "update", timestamp: "timestamp"});
  }

  // appends the history rows to the table
  history.map(function(historyObj) {
    var app = historyObj.app,
        tr = '';

    // adds the row with a specific color
    if (app === "Events Calendar") {
      tr = '<tr class=table-info>';
    } else if (app === "Crowdsourced Data Review") {
      tr = '<tr class=table-active>';
    } else {
      tr = '<tr class=table-success>';
    }

    // appends the row to the history table
    $(tr).append(
      $('<td>').text(historyObj.app),
      $('<td>').text(historyObj.user),
      $('<td>').text(historyObj.data),
      $('<td>').text(historyObj.action),
      $('<td>').text(historyObj.timestamp))
      .appendTo($('#historyTableBody'));
  });

});

/**
  filters the rows in the history table
*/
function filterText(tr, filter, rowNum) {
  var id, td;

  // loops through the table
  for (i = 0; i < tr.length; i++) {
    // gets the cell
    td = tr[i].getElementsByTagName("td")[rowNum];
    if (td) {
      // checks if the filter matches the cell value
      if (td.innerText.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

/**
  calls the filter function for the application row
*/
$("#app").on("change", function() {
  var me = document,
      filter = me.getElementById("app").value.toUpperCase(),
      tr = me.getElementById("historyTable").getElementsByTagName("tr");

      filterText(tr, filter, 0);
});

/**
  calls the filter functino for the action row
*/
function filterAction() {
  var me = document,
      filter = me.getElementById("action").value.toUpperCase(),
      tr = me.getElementById("historyTable").getElementsByTagName("tr");

  filterText(tr, filter, 3);
}

/**
  calls the filter function for the timestamp row
*/
function filterTimestamp() {
  var me = document,
      filter = me.getElementById("timestamp").value.toUpperCase(),
      tr = me.getElementById("historyTable").getElementsByTagName("tr");

  filterText(tr, filter, 4);
}

/**
  calls the fiilter function for the data column
*/
function filterDataChanged() {
  var me = document,
      filter = me.getElementById("dataChanged").value.toUpperCase(),
      tr = me.getElementById("historyTable").getElementsByTagName("tr");

  filterText(tr, filter, 2);
}

/**
  calls the filter function based on the user row
*/
function filterUsers() {
  var me = document,
      filter = me.getElementById("user").value.toUpperCase(),
      tr = me.getElementById("historyTable").getElementsByTagName("tr");

  filterText(tr, filter, 1);

}

/**
  function that hides or shows the filter depending on the toggle
*/
function showFilters() {
  var hidden = document.getElementById("rowFilters");

  if (hidden.hidden) {
    hidden.hidden = false;
  } else {
    hidden.hidden = true;
  }
}
