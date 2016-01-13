<?php
  require_once('views/head.php');
  require_once 'classes/wikiFunctions.php';

  $wiki_id= filter_input(INPUT_GET, 'wiki_id');
  $comic = new wikiQuery();
  $comic->comicCover($wiki_id);
  $comic->comicDetails($wiki_id);
?>
  <title>Add Issue :: POW! Comic Book Manager</title>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <img src="<?php echo $comic->coverURL; ?>" alt="Cover" />
        <h3><?php echo $comic->wikiTitle; ?></h3>
        <p><?php echo $comic->storyName; ?></p>
        <p><?php echo $comic->synopsis; ?></p>
      </div>
    </div>
  </div>
<?php include 'views/footer.php';?>
</body>
</html>