<?php
  require_once('views/head.php');
  require_once('config/db.php');
?>
  <title>POW! Comic Book Manager</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <?php
  // If users is logged in, shows a random comic from their collection. Otherwise just shows a random comic.
  if ($login->isUserLoggedIn () == true) {
    $sql = "SELECT comics.comic_id, users_comics.comic_id FROM comics LEFT JOIN users_comics ON comics.comic_id=users_comics.comic_id WHERE users_comics.user_id=$userID ORDER BY RAND() LIMIT 1";
  } else {
    $sql = "SELECT comic_id FROM comics ORDER BY RAND() LIMIT 1";
  }
  $result = $connection->query ( $sql );
  while ($row = $result->fetch_assoc()) {
    $comic_id = $row['comic_id'];
  }
  include 'modules/single_comic/single_comic.php';
  ?>
<?php include 'views/footer.php';?>
</body>
</html>