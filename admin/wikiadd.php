<?php
  require_once('../views/head.php');

  if ($login->isUserLoggedIn () == false) {
  	include (__ROOT__."/views/not_logged_in.php");
  	die ();
  }

  require_once(__ROOT__.'/classes/wikiFunctions.php');
  $wiki_id = filter_input ( INPUT_GET, 'wiki_id' );
  $comic_id = filter_input(INPUT_GET, 'comic_id');
  $series = filter_input(INPUT_GET, 'series_name');
  $issue = filter_input(INPUT_GET, 'issue_number');
  
  $comic = new wikiQuery ();
  $comic->comicCover ( $wiki_id );
  $comic->comicDetails ( $wiki_id );

  $db = new comicSearch ();
  $db->seriesFind ($series);
  if ($db->series->num_rows > 0) {
    while ( $row = $db->series->fetch_assoc () ) {
      $series_id = $row ['series_id'];
    }
  }
?>
  <title>Add Issue :: comicDB</title>
</head>
<body>
<?php include(__ROOT__.'/views/header.php'); ?>
  <div class="container edit-form">
    <div class="row">
      <div class="col-sm-12 headline">
        <h2>Add Issue</h2>
        <a href="#">&lt; Back</a>
      </div>
      <div class="col-md-8 col-sm-12">
        <form method="post" action="formprocess.php">
          <div>
            <label for="series_name">Series: </label>
            <input name="series_name" type="text" maxlength="255" value="<?php echo $series; ?>" required />
          </div>
          <div>
            <label for="issue_number">Issue Number: </label>
            <input name="issue_number" type="text" maxlength="255" value="<?php echo $issue; ?>" required />
          </div>
          <div>
            <label for="story_name">Story Name: </label>
              <input name="story_name" type="text" maxlength="255" value="<?php echo $comic->storyName; ?>" />
          </div>
          <div>
            <label for="released_date">Release Date:</label>
            <input name="released_date" size="10" maxlength="10" value="" type="text" placeholder="YYYY-MM-DD" />
          </div>
          <div class="plot">
            <label for="plot">Plot:</label>
            <small><a href="#">[edit]</a></small>
            <?php echo $comic->synopsis; ?>
          </div>
          <div>
            <label for="cover_image">Cover Image URL:</label>
            <input name="cover_image" type="text" maxlength="255" value="<?php echo $comic->coverURL; ?>" />
          </div>
          <div>
            <label for="cover_image_file">Destination Filename:</label>
            <input name="cover_image_file" type="text" maxlength="255" value="<?php echo $comic->coverFile; ?>" />
          </div>
          <input type="hidden" name="plot" value="<?php echo htmlspecialchars($comic->synopsis); ?>" />
          <input type="hidden" name="original_purchase" value="" />
          <input type="hidden" name="series_id" value="<?php echo $series_id; ?>" />

          <input type="submit" name="submit" value="Submit" class="form-submit" />
        </form>
      </div>
      <div class="col-md-4 issue-image">
        <img src="<?php echo $comic->coverURL; ?>" alt="Cover" />
      </div>
    </div>
  </div>
<?php include(__ROOT__.'/views/footer.php'); ?>
</body>
</html>