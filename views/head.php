<?php
  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/classes/functions.php');
  require_once(__ROOT__.'/config/db.php');
  require_once(__ROOT__.'/classes/Login.php');
  $login = new Login();
  // This will get the current URL the user is on
  $current_page = htmlspecialchars(urlencode($_SERVER['REQUEST_URI']));
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta http-equiv="Content-Language" content="en">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="/styles.css">
  <link rel="apple-touch-icon-precomposed" href="/assets/pow.png">