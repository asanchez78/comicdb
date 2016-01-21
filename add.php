<?php
  require_once('views/head.php');
  $filename = $_SERVER["PHP_SELF"];
  
  // Reset all our form flags
  $seriesSubmitted = false;
  $issueSearch = false;
  $issueAdd = false;
  $issueSubmit = false;
  $submitted = filter_input ( INPUT_POST, 'submitted' );
  if ($submitted) { include('admin/formprocess.php'); }
?>
  <title>Add :: POW! Comic Book Manager</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <div class="container content add-container">
    <?php if ($login->isUserLoggedIn () == false) {
      include (__ROOT__."/views/not_logged_in.php");
      die ();
    } ?>
    <ul class="add-menu list-inline">
      <li>
        <a href="#addseries" class="active" id="form-add-series">Add Series</a>
      </li>
      <li>
        <a href="#addissue" id="form-add-issue">Add Issue</a>
      </li>
      <li>
        <a href="#addrange" id="form-add-range">Add Range of Issues</a>
      </li>
      <li>
        <a href="#addlist" id="form-add-list">Add List of Issues</a>
      </li>
    </ul>
    <?php // ADD SERIES ?>
    <div class="row add-block form-add-series active">
      <div class="col-xs-12" id="form-series-add">
        <h2>Add Series</h2>
        <p>Use the form below to add a new series to your collection.</p>
        <form method="post" action="<?php echo $filename; ?>?type=series" class="form-inline" id="add-series">
          <div class="form-group">
            <label for="publisher">Publisher</label>
            <select class="form-control" name="publisher">
              <option value="">Choose a Publisher</option>
              <option value="marvel" selected>Marvel Comics</option>
              <option value="dc">DC Comics</option>
              <option value="image">Image Comics</option>
              <option value="darkhorse">Dark Horse Comics</option>
              <option value="valiant">Valiant Comics</option>
            </select>
          </div>
          <div class="form-group">
            <label for="series_name">Series Name</label>
            <input name="series_name" class="form-control" type="text" size="50" value="" required />
          </div>
          <div class="form-group">
            <label for="series_vol">Volume #</label>
            <input name="series_vol" class="form-control" type="text" size="3" maxlength="4" value="" required />
          </div>
          <input type="hidden" name="submitted" value="yes" />
          <input type="submit" name="submit" value="Submit" class="btn btn-primary form-submit" />
        </form>
      </div>
      <?php if ($seriesSubmitted == true) { ?>
        <div class="add-success bg-success col-xs-12">
          <div class="success-message text-center">
            <h3><?php echo $series_name; ?><br /><small>(Vol <?php echo $series_vol; ?>)</small></h2>
            <p>has been added to your collection.</p>
            <a href="#" class="btn btn-default">Add another?</a>
          </div>
        </div>
      <?php } ?>
    </div>
    <?php // ADD SINGLE ISSUE ?>
    <div class="row add-block form-add-issue">
      <?php if ($issueSearch == true) { ?>
        <div class="col-xs-12">
          <h2>Your Search Results</h2>
          <p>We found the following issues on the Marvel Wikia related to <em><?php echo $series_name; ?> (Vol <?php echo $series_vol; ?>) #<?php echo $issue_number; ?>:</em></p>
          <form method="post" action="<?php echo $filename; ?>?type=issue-add#addissue" class="form-inline" id="add-issue-search">
            <div class="form-group form-radio">
              <label for="wiki_id">Choose the result that matches your issue:</label>
              <fieldset class="row">
                <?php echo $wiki->resultsList; ?>
              </fieldset>
            </div>
            <input type="hidden" name="series_name" value="<?php echo $series_name; ?>" />
            <input type="hidden" name="series_vol" value="<?php echo $series_vol; ?>" />
            <input type="hidden" name="issue_number" value="<?php echo $issue_number; ?>" />
            <input type="hidden" name="submitted" value="yes" />
            <div class="text-center center-block">
              <a href="#" class="btn btn-default form-back">&lt; Back</a>
              <input type="submit" name="submit" value="Submit" class="btn btn-primary form-submit" />
            </div>
          </form>
        </div>
      <?php } elseif ($issueAdd == true) { ?>
        <div class="col-sm-12 headline">
          <h2>Add Issue</h2>
        </div>
        <div class="col-md-8 col-sm-12">
          <form method="post" action="<?php echo $filename; ?>?type=issue-submit#addissue">
            <div class="form-group">
              <h4><?php echo $series_name; ?> (Vol <?php echo $series_vol; ?>) #<?php echo $issue_number; ?></h4>
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
            <input type="hidden" name="series_name" value="<?php echo $series_name; ?>" />
            <input type="hidden" name="series_vol" value="<?php echo $series_vol; ?>" />
            <input type="hidden" name="issue_number" value="<?php echo $issue_number; ?>" />
            <input type="hidden" name="cover_image" value="<?php echo $comic->coverURL; ?>" />
            <input type="hidden" name="cover_image_file" value="<?php echo $comic->coverFile; ?>" />
            <input type="hidden" name="plot" value="<?php echo htmlspecialchars($comic->synopsis); ?>" />
            <input type="hidden" name="series_id" value="<?php echo $series_id; ?>" />
            <input type="hidden" name="wiki_id" value="<?php echo $wiki_id; ?>" />
            <input type="hidden" name="submitted" value="yes" />
            <div class="text-center center-block">
              <a href="#" class="btn btn-default form-back">&lt; Back</a>
              <input type="submit" name="submit" value="Submit" class="btn btn-primary form-submit" />
            </div>
          </form>
        </div>
        <div class="col-md-4 issue-image">
          <img src="<?php echo $comic->coverURL; ?>" alt="Cover" />
        </div>
      <?php } elseif ($issueSubmit == true) { ?>
        <div class="add-success col-xs-12 <?php if ($messageNum != 51) { echo 'bg-success'; } else { echo 'bg-danger'; } ?>">
          <div class="success-message">
            <div class="row">
              <div class="col-md-3 col-xs-hidden">
                <img src="/images/<?php echo $cover_image_file; ?>" alt="<?php echo $series_name . '(Vol ' . $series_vol . ') #' . $issue_number; ?> Cover" class="" />
              </div>
              <div class="col-xs-12 col-md-9">
                <h3><?php echo $series_name; ?> <small>(Vol <?php echo $series_vol; ?>)</small> #<?php echo $issue_number; ?></h2>
                <p><?php if ($messageNum != 51) { echo 'has been added to your collection.'; } else { echo 'already exists in your collection.'; } ?></p>
              </div>
            </div>
            <div class="text-center center-block">
              <a href="/comic.php?comic_id=<?php echo $comic_id; ?>" class="btn btn-default">View Issue</a>
              <a href="/add.php#addissue" class="btn btn-default">Add another?</a>
            </div>
          </div>
        </div>
      <?php } else {
        $listAllSeries=1;
        $comics = new comicSearch ();
        $comics->seriesList ($listAllSeries);
      ?>
        <div class="col-xs-12">
          <h2>Add Issue</h2>
          <form method="post" action="<?php echo $filename; ?>?type=issue-search#addissue" class="form-inline" id="add-issue">
            <div class="form-group">
              <label>Series</label>
              <select class="form-control" name="series_name">
                <option value="" disabled selected>Choose a series</option>
                <?php 
                  while ( $row = $comics->series_list_result->fetch_assoc () ) {
                    $series_name = $row ['series_name'];
                    $series_vol = $row ['series_vol'];
                    echo '<option value="' . $series_name . '">' . $series_name . ' (Vol ' . $series_vol . ')</option>';
                  } 
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="issue_number">Issue #</label>
              <input name="issue_number" class="form-control" type="text" size="3" maxlength="4" value="" required aria-required="true" />
            </div>
            <input type="hidden" name="series_vol" value="<?php echo $series_vol; ?>" />
            <input type="hidden" name="submitted" value="yes" />
            <input class="btn btn-default form-submit" type="submit" name="submit" value="Search" />
          </form>
        </div>
      <?php } ?>
    </div>
    <?php // ADD RANGE ?>
  </div>
<?php include 'views/footer.php';?>
</body>
</html>