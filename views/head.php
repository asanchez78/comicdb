<?php
  define('__ROOT__', dirname(dirname(__FILE__)));
  if (isset($_COOKIE['apiKey']) && isset($_COOKIE['user_id']) && isset($_COOKIE['user_name']) && isset($_COOKIE['user_email'])) {
    define('__apiKey__', $_COOKIE['apiKey']);
    define('__userID__', $_COOKIE['user_id']);
    define('__userName__', $_COOKIE['user_name']);
    define('__userEmail__', $_COOKIE['user_email']);
  }
  require_once(__ROOT__.'/classes/functions.php');
  require_once(__ROOT__.'/classes/userFunctions.php');
  require_once(__ROOT__.'/config/db.php');
  require_once(__ROOT__.'/classes/Login.php');
  
  $login = new Login();
  if ($login->isUserLoggedIn () == true) {
    $userName = $_COOKIE ['user_name'];
    $userID = $_COOKIE ['user_id'];
    $apiKey = $_COOKIE ['apiKey'];
    $userEmail = $_COOKIE ['user_email'];
  }
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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/styles.css">
  <link rel="apple-touch-icon-precomposed" href="/assets/pow.png">
  <script src="/scripts/jquery-2.2.0.min.js"></script>