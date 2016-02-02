<?php
  require_once('views/head.php');
  require_once('config/db.php');
?>
  <title>POW! Comic Book Manager</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <div class="container content">
    <?php if ($login->isUserLoggedIn () == true || $validUser == 1) {
      include ('views/series_list.php');
    } else {
      if (isset($userSetID) && $validUser != 1) {
        $messageNum = 52;
      }
      $sql = "SELECT comic_id FROM comics ORDER BY RAND() LIMIT 0,1";
      $result = $connection->query ( $sql );
      while ($row = $result->fetch_assoc()) {
        $comic_id = $row['comic_id'];
      }
      include 'views/single_comic.php';
    } ?>
  </div>
<?php include 'views/footer.php';?>
</body>
</html>