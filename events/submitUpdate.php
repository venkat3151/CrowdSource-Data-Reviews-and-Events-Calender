<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php include('head-tags.php') ?>
    <style>
      input {
        margin-bottom:10px;
      }
      h8 {
        color: red;
        display: none;
      }
    </style>
    <title>Submit an Update</title>
    <?php include('navbar-bootstrap.php')?>
    <br>
    <h1>Submit an Update</h1>
    <link rel="stylesheet" type="text/css" href="/ubspectrum/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/ubspectrum/events/tagify.css">
  </head>

  <body class="text-center"><br>
    <h6 align="center"> In order to submit an update to an event that you have posted you must<br>
    enter the email associated with your event and the update token. The update token is the token<br>
    that was sent to your email when you submitted the event.</h6><br><br>
    <form class="form-group" enctype="multipart/form-data" method="post" action="checkUpdate.php">

      <div class="row mb-3">
        <div class="col-xs-12 col-md-4 text-md-right">
          <label for="email">Email<span class="required">*</span></label>
        </div>
        <div class="col-xs-12 col-md-4">
          <input type="email" id="email" class="form-control" maxlength="64" name="email" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-xs-12 col-md-4 text-md-right">
          <label for="ubit">Update Token<span class="required">*</span></label>
        </div>
        <div class="col-xs-12 col-md-4">
          <input type="token" id="update" name="update" size=40 class="form-control" required></label>
        </div>
      </div>

      <button class="btn btn-primary btn-lg" type="submit">Update Event</button>
    </form>
    <?php include('footer-bootstrap.php') ?>
  </body>
  <script>
  $('input').on('blur', validateInput);
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
