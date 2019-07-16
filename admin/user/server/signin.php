<?php
  header('content-type: application/json; charset=utf-8');
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  require_once "Login.php";

  // get the username and the password
  $username = $_POST['username'] or '';
  $password = $_POST['password'] or '';

  // attempt to login
  Login::checkCredentials($username, $password);
 ?>
