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
  <title>Add Issue :: POW! Comic Book Manager</title>
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
          <div class="form-group">
            <label for="series_name">Series: </label>
            <input class="form-control" name="series_name" type="text" maxlength="255" value="<?php echo $series; ?>" required />
          </div>
          <div class="form-group">
            <label for="issue_number">Issue Number: </label>
            <input class="form-control" name="issue_number" type="text" maxlength="255" value="<?php echo $issue; ?>" required />
          </div>
          <div class="form-group">
            <label for="story_name">Story Name: </label>
            <input class="form-control" name="story_name" type="text" maxlength="255" value="<?php echo $comic->storyName; ?>" />
          </div>
          <div class="form-group">
            <label for="released_date">Release Date:</label>
            <input class="form-control" name="released_date" size="10" maxlength="10" value="" type="date" placeholder="YYYY-MM-DD" />
          </div>
          <div class="form-group form-radio">
            <label for="original_purchase">Purchased When Released:</label>
            <fieldset>
              <input name="original_purchase" id="original-yes" value="1" type="radio" /> <label for="original-yes">Yes</label>
              <input name="original_purchase" id="original-no" value="0" type="radio" /> <label for="original-no">No</label>
            </fieldset>
          </div>
          <div class="plot form-group">
            <label for="plot">Plot:</label>
            <small><a href="#">[edit]</a></small>
            <?php echo $comic->synopsis; ?>
          </div>
          <input type="hidden" name="cover_image" value="<?php echo $comic->coverURL; ?>" />
          <input type="hidden" name="cover_image_file" value="<?php echo $comic->coverFile; ?>" />
          <input type="hidden" name="plot" value="<?php echo htmlspecialchars($comic->synopsis); ?>" />
          <input type="hidden" name="series_id" value="<?php echo $series_id; ?>" />
          <input type="hidden" name="wiki_id" value="<?php echo $wiki_id; ?>" />
          <input type="submit" name="submit" value="Submit" class="btn btn-default form-submit" />
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