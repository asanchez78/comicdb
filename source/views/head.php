<?php
  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/config/db.php');
  require_once(__ROOT__.'/classes/Login.php');
  require_once(__ROOT__.'/classes/functions.php');
  require_once(__ROOT__.'/classes/userFunctions.php');
  
  $login = new Login();
  if ($login->isUserLoggedIn () == true) {
    $userName = $_COOKIE ['user_name'];
    $userID = $_COOKIE ['user_id'];
    $apiKey = $_COOKIE ['apiKey'];
    $userEmail = $_COOKIE ['user_email'];
  }
  if (isset($_COOKIE['apiKey']) && isset($_COOKIE['user_id']) && isset($_COOKIE['user_name']) && isset($_COOKIE['user_email'])) {
    define('__apiKey__', $_COOKIE['apiKey']);
    define('__userID__', $_COOKIE['user_id']);
    define('__userName__', $_COOKIE['user_name']);
    define('__userEmail__', $_COOKIE['user_email']);
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
  <link rel="stylesheet" href="/styles.css">
  <link rel="apple-touch-icon-precomposed" href="/assets/logo.png">
  <script src="/scripts/jquery-2.2.0.min.js"></script>
  <meta name="description" content="POW! Comic Book Manager is your best place to organize and display your comic book collection!">

  <!-- Facebook Opengraph -->
  <meta property="og:site_name" content="POW! Comic Book Manager" />
  <meta property="og:url" content="http://comicmanager.com"/>
  <meta property="og:title" content="POW! Comic Book Manager" />
  <meta property="og:description" content="POW! Comic Book Manager is your best place to organize and display your comic book collection!" />
  <meta property="og:image" content="http://comicmanager.com/assets/logo.png" />
  <meta property="og:type" content="website" />