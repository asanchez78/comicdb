<?php
  require_once('views/head.php');

  $comic_id = filter_input ( INPUT_GET, 'comic_id' );
  $comic = new comicSearch ();
  $comic->issueLookup ( $comic_id );
?>
  <title><?php echo $comic->series_name . " #" . $comic->issue_number; ?> :: POW! Comic Book Manager</title>
</head>

<body>
  <?php include 'views/header.php';?>
  <div class="container content">
    <?php include 'views/single_comic.php'; ?>
  </div>
  <?php include 'views/footer.php';?>
</body>
</html>