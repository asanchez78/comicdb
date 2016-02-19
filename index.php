<?php
  require_once('views/head.php');
  require_once('config/db.php');
?>
  <title>POW! Comic Book Manager</title>
</head>
<body>
  <?php
  // If users is logged in, shows a random comic from their collection. Otherwise just shows a random comic.
  if ($login->isUserLoggedIn () == true) {
    include 'views/header.php';
    include 'modules/user_bar/user_bar.php';
    include 'views/dashboard.php';
  } else {
    include 'views/home.php';
  }
  ?>
<?php include 'views/footer.php';?>
</body>
</html>