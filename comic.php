<?php
  require_once('views/head.php');
  $filename = $_SERVER["PHP_SELF"];
  $comic_id = filter_input ( INPUT_GET, 'comic_id' );
  $comic = new comicSearch ();
  $comic->issueLookup ( $comic_id );

  $editMode = filter_input ( INPUT_GET, 'type' );
  if ($editMode == 'edit' /*|| $editMode == 'edit-save'*/) { include('admin/formprocess.php'); }
?>
  <title><?php echo $comic->series_name . " #" . $comic->issue_number; ?> :: POW! Comic Book Manager</title>
</head>

<body>
  <?php include 'views/header.php';?>
  <div>
    <?php if ($editMode == 'edit') { include 'views/edit_comic.php'; } else { include 'views/single_comic.php'; } ?>
  </div>
  <?php include 'views/footer.php';?>
</body>
</html>