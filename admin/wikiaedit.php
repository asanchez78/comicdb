<?php
  require_once('../views/head.php');

  if ($login->isUserLoggedIn () == false) {
  	include (__ROOT__."/views/not_logged_in.php");
  	die ();
  }

  require_once(__ROOT__.'/classes/wikiFunctions.php');
  $wiki_id = filter_input ( INPUT_GET, 'wiki_id' );
  $comic_id = filter_input(INPUT_GET, 'comic_id');
  $comic = new wikiQuery ();
  $comic->comicCover ( $wiki_id );
  $comic->comicDetails ( $wiki_id );
  $dbValues = new comicSearch();
  $dbValues->issueLookup($comic_id);

?>
  <title>Edit Issue :: POW! Comic Book Manager</title>
</head>
<body>
<?php include(__ROOT__.'/views/header.php'); ?>
  <div class="container edit-form content">
    <div class="row">
      <div class="col-sm-12 headline">
        <h2>Update Issue</h2>
        <a href="#">&lt; Back</a>
      </div>
      <div class="col-md-8 col-sm-12">
        <form method="post" action="formprocess.php">
          <div class="form-group">
            <label for="series_name">Series: </label>
            <input name="series_name" class="form-control" type="text" maxlength="255" value="<?php echo $dbValues->series_name; ?>" required />
          </div>
          <div class="form-group">
            <label for="issue_number">Issue Number: </label>
            <input name="issue_number" class="form-control" type="text" maxlength="255" value="<?php echo $dbValues->issue_number; ?>" required />
          </div>
          <div class="form-group">
            <label for="story_name">Story Name: </label>
              <input name="story_name" class="form-control" type="text" maxlength="255" value="<?php echo $comic->storyName; ?>" />
          </div>
          <div class="form-group">
            <label for="released_date">Release Date:</label>
            <input class="form-control" name="released_date" size="10" maxlength="10" value="<?php echo $dbValues->release_date; ?>" type="date" placeholder="YYYY-MM-DD" />
          </div>
          <div class="form-group textarea">
            <label for="plot">Plot:</label>
            <textarea class="form-control" name="plot"><?php echo $comic->synopsis; ?></textarea>
          </div>
          <div class="form-group">
            <label for="cover_image">Cover Image URL:</label>
            <input class="form-control" name="cover_image" type="text" maxlength="255" value="<?php echo $comic->coverURL; ?>" />
          </div>
          <div class="form-group">
            <label for="cover_image_file">Destination Filename:</label>
            <input class="form-control" name="cover_image_file" type="text" maxlength="255" value="<?php echo $comic->coverFile; ?>" />
          </div>
          <input type="hidden" name="original_purchase" value="<?php echo $dbValues->original_purchase; ?>" />
          <input type="hidden" name="update" value="yes" />
          <input type="hidden" name="comic_id" value="<?php echo $comic_id; ?>" />
          <input type="hidden" name="series_id" value="<?php echo $dbValues->series_id; ?>" />

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