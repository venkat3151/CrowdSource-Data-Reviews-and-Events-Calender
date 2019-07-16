<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php include('../../events/head-tags.php') ?>
    <style>
      input {
        margin-bottom:10px;
      }
      h8 {
        color: red;
        display: none;
      }
    </style>
    <title>UB Spectrum Admin</title>
    <?php include('../../events/navbar-bootstrap.php')?>
    <br>
    <h1>Apply to be an Admin</h1>
    <link rel="stylesheet" type="text/css" href="/ubspectrum/bootstrap/css/bootstrap.min.css">
    <script src="/ubspectrum/pdfjs/build/pdf.js"></script>
    <script src="/ubspectrum/pdfThumbnails.js"></script>
    <script src="/ubspectrum/events/tagify.min.js"></script>
    <link rel="stylesheet" href="/ubspectrum/events/tagify.css">
  </head>

  <body class="text-center"><br>
    <h6 align="center"> This page is meant for employees working on UB Spectrum to apply to be an admin.<br>
      An admin will need to approve your request to access the website</h6><br><br>
    <form class="form-group" enctype="multipart/form-data" method="post" action="server/AddAdminRequest.php">

      <div class="row mb-3">
        <div class="col-xs-12 col-md-4 text-md-right">
          <label for="firstName">First Name</label>
        </div>
        <div class="col-xs-12 col-md-4">
          <input type="text" name="firstName" id="firstName" class="form-control" maxlength="64" required autofocus/>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-xs-12 col-md-4 text-md-right">
          <label for="lastName">Last Name</label>
        </div>
        <div class="col-xs-12 col-md-4">
          <input type="text" name="lastName" id="lastName" class="form-control" maxlength="64" required />
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-xs-12 col-md-4 text-md-right">
          <label for="email">Email</label>
        </div>
        <div class="col-xs-12 col-md-4">
          <input type="email" id="email" class="form-control" maxlength="64" name="email" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-xs-12 col-md-4 text-md-right">
          <label for="ubit">Password</label>
        </div>
        <div class="col-xs-12 col-md-4">
          <input type="password" id="password" name="password" size=40 class="form-control" required></label>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-xs-12 col-md-4 text-md-right">
          <label for="role">Role</label>
        </div>
        <div class="col-xs-12 col-md-4">
          <select name="role">
              <option value="crowd">crowd</option>
              <option value="event">event</option>
              <option value="super">super</option>
          </select>
        </div>
      </div>
      <br>

      <button class="btn btn-primary btn-lg" type="submit">Submit Admin Request</button>
    </form>
    <?php include('../../events/footer-bootstrap.php') ?>
  </body>
  <script>
  $('input').on('blur', validateInput);
  $('#email').on('blur','input', validateInput);
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
                  let urlPatternMatches = value.match(
                      /(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/
                      );
                  if (urlPatternMatches != null) {
                      isValid = true;
                  } else {
                      isValid = false;
                  }
                  break;
          }

          if (isValid) {
              input.removeClass('is-invalid').addClass('is-valid');
          } else {
              input.removeClass('is-valid').addClass('is-invalid');
          }
      }
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
<script src="/ubspectrum/bootstrap/js/bootstrap.min.js"></script>
</html>
