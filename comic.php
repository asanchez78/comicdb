<?php
  require_once('views/head.php');

  $comic_id = filter_input ( INPUT_GET, 'comic_id' );
  $details = new comicSearch ();
  $details->issueLookup ( $comic_id );
?>
  <title><?php echo $details->series_name . " #" . $details->issue_number; ?> :: POW! Comic Book Manager</title>
</head>

<body>
  <?php include 'views/header.php';?>
  <div class="container content">
    <?php include 'views/single_comic.php'; ?>
  </div>
  <?php include 'views/footer.php';?>
</body>
</html>