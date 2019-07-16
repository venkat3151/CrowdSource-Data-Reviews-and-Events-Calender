var jsonTest, jsonTest2;
window.onload = function() {
  // add call to api to get admins from the database
  // test data based on how i expected it to look
  jsonTest = [{name: "Amanda", status: "Superadmin", email: "@email"},
                  {name: "maggie", status: "admin", email: "@email"},
                  {name: "sadie", status: "admin", email: "@email"},
                  {name: "ben", status: "admin", email: "@email"}];

  jsonTest2 = [{name: "Amanda", email: "@email"}, {name:"maggie", email: "@email"}];

  // adds the existing admins to the page
  addExistingAdmins(jsonTest);

  // adds the existing admins to the page
  addPendingAdmins(jsonTest2);
}

/**
  function to dynamically add pending admins to the web page
*/
function addPendingAdmins(pendingAdmins) {
  // loops through all of the pending admins
  pendingAdmins.map(function(adminObj, index) {
    // dynamically creates a card object for each admin
    var myCol = $('<div class="col-sm-3 col-md-3 pb-2"></div>'),
        adminString = JSON.stringify(adminObj),
        myPanel = $('<div class="card bg-dark text-white" style="width: 18rem;"><div class="card-body">' +
                  '<h5 class="card-title">' + adminObj.name + '</h5>' +
                  '<p class="card-text">Status: ' + adminObj.status + '<br>Email: ' + adminObj.email +
                  '</p><a href="#" id="approveBtn'+ index +
                  '" onclick=approveAdmin(this) class="btn btn-outline-success btn-sm pull-left">Approve</a>&nbsp&nbsp&nbsp' + '<a href="#" id="declineBtn'+ index +
                  '" onclick=declineAdmin(this) class="btn btn-outline-danger btn-sm pull-left">Decline</a></div></div>');

    // adds card to card list
    myPanel.appendTo(myCol);
    myCol.appendTo('#pendingAdmins');
  });
}

/**
  function to dynamically add the already existing admins to the page
*/
function addExistingAdmins(admins) {
  // loops through all admins and adds them as a single card
  admins.map(function(adminObj, index) {
    var myCol = $('<div class="col-sm-3 col-md-3 pb-2"></div>'),
        myPanel = $('<div class="card bg-dark text-white" style="width: 18rem;"><div class="card-body">' +
                  '<h5 class="card-title">' + adminObj.name + '</h5>' +
                  '<p class="card-text">Status: ' + adminObj.status + '<br>Email: ' + adminObj.email +
                  '</p><a href="#" id="updateBtn'+ index + '" onclick=updateAdmin(this) class="btn btn-outline-primary btn-sm pull-left">Edit</a>&nbsp&nbsp&nbsp' +
                  '<a href="#" id="deleteBtn'+ index + '" onclick=deleteAdmin(this) class="btn btn-outline-danger btn-sm pull-left">Delete</a></div></div>');

    // adds the card to the page
    myPanel.appendTo(myCol);
    myCol.appendTo('#existingAdmins');
  });
}

/**
  function to handle the approveBtn
*/
function approveAdmin(e) {
  var index = e.id.substr(-1);

  // json of the card in the selected button -- to finish in another story
  console.log(jsonTest2[index]);
}

/**
  function to handle the declineBtn
*/
function declineAdmin(e) {
  var index = e.id.substr(-1);

  // json of the card from the selected button -- to finish in another story
  console.log(jsonTest2[index]);
}

/**
  function to handle the deleteBtn
*/
function deleteAdmin(e) {
  var index = e.id.substr(-1);

  // json of the card in the selected button -- to finish in another story
  console.log(jsonTest[index]);
}

/**
  function to handle the updateBtn
*/
function updateAdmin(e) {
  var index = e.id.substr(-1);

  // json of the card in the selected button -- to finish in another story
  console.log(jsonTest[index]);
}
