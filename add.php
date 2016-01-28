<?php
  require_once('views/head.php');
  $filename = $_SERVER["PHP_SELF"];
  
  // Reset all our form flags
  $seriesSearch = false;
  $seriesAdd = false;
  $seriesSubmit = false;
  $issueSearch = false;
  $issueAdd = false;
  $issueSubmit = false;
  $rangeSearch = false;
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
        <a href="#addissue" class="active" id="form-add-issue"><i class="fa fa-plus"></i> Add Issue</a>
      </li>
      <li>
        <a href="#addrange" id="form-add-range"><i class="fa fa-hashtag"></i> Add Range of Issues</a>
      </li>
      <li>
        <a href="#addlist" id="form-add-list"><i class="fa fa-archive"></i> Add List of Issues</a>
      </li>
      <li>
        <a href="#addseries" id="form-add-series"><i class="fa fa-folder-open"></i> Add Series</a>
      </li>
    </ul>
    <?php // ADD SINGLE ISSUE ?>
    <div class="row add-block form-add-issue active">
      <?php // ADD SINGLE ISSUE: Part 2/4: Displays Wikia results.
        if ($issueSearch == true) { ?>
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
            <input type="hidden" name="series_id" value="<?php echo $series_id; ?>" />
            <input type="hidden" name="issue_number" value="<?php echo $issue_number; ?>" />
            <input type="hidden" name="publisherAPI" value="<?php echo $publisherAPI; ?>" />
            <input type="hidden" name="submitted" value="yes" />
            <div class="text-center center-block">
              <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
              <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-paper-plane"></i> Next</button>
            </div>
          </form>
        </div>
      <?php // ADD SINGLE ISSUE: Part 3/4: Displays final fields and allows user to change details before adding to collection.
        } elseif ($issueAdd == true) { ?>
        <form method="post" action="<?php echo $filename; ?>?type=issue-submit#addissue">
          <div class="col-sm-12 headline">
            <h2>Add Issue: <?php echo $series_name; ?> (Vol <?php echo $series_vol; ?>) #<?php echo $issue_number; ?></h2>
          </div>
          <div class="col-md-8 col-sm-12">
            <div class="form-group">
              <label for="story_name">Story Name: </label>
              <input class="form-control" name="story_name" type="text" maxlength="255" value="<?php echo $wiki->storyName; ?>" />
            </div>
            <div class="form-group">
              <label for="released_date">Release Date:</label>
              <input class="form-control" name="released_date" size="10" maxlength="10" value="" type="date" placeholder="YYYY-MM-DD" />
            </div>
            <div class="form-group form-radio">
              <label for="originalPurchase">Purchased When Released:</label>
              <fieldset>
                <input name="originalPurchase" id="original-yes" value="1" type="radio" /> <label for="original-yes">Yes</label>
                <input name="originalPurchase" id="original-no" value="0" type="radio" /> <label for="original-no">No</label>
              </fieldset>
            </div>
            <div class="plot form-group">
              <label for="plot">Plot:</label>
              <small><a href="#">[edit]</a></small>
              <?php echo $wiki->synopsis; ?>
            </div>
          </div>
          <div class="col-md-4 issue-image">
            <img src="<?php echo $wiki->coverURL; ?>" alt="Cover" />
            <div class="form-group">
              <label for="cover_image">Cover Image URL</label>
              <input type="url" class="form-control" name="cover_image" value="<?php echo $wiki->coverURL; ?>" />
              <small>Enter the URL of the image you wish to use. Default is the cover file from the Wikia entry on this issue.</small>
              <input type="hidden" name="cover_image_file" value="<?php echo $wiki->coverFile; ?>" />
            </div>
          </div>
          <input type="hidden" name="series_name" value="<?php echo $series_name; ?>" />
          <input type="hidden" name="series_vol" value="<?php echo $series_vol; ?>" />
          <input type="hidden" name="issue_number" value="<?php echo $issue_number; ?>" />
          <input type="hidden" name="plot" value="<?php echo htmlspecialchars($wiki->synopsis); ?>" />
          <input type="hidden" name="series_id" value="<?php echo $series_id; ?>" />
          <input type="hidden" name="wiki_id" value="<?php echo $wiki_id; ?>" />
          <input type="hidden" name="submitted" value="yes" />
          <div class="text-center center-block">
            <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
            <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-paper-plane"></i> Submit</button>
          </div>
        </form>  
      <?php // ADD SINGLE ISSUE: Part 4/4: Displays success message and allows user to view issue or add another issue.
        } elseif ($issueSubmit == true) { ?>
        <div class="add-success col-xs-12 <?php if ($messageNum != 51) { echo 'bg-success'; } else { echo 'bg-danger'; } ?>">
          <div class="success-message">
            <div class="row">
              <div class="col-md-3 col-xs-hidden">
                <img src="<?php echo $cover_image_file; ?>" alt="<?php echo $series_name . '(Vol ' . $series_vol . ') #' . $issue_number; ?> Cover" class="" />
              </div>
              <div class="col-xs-12 col-md-9">
                <h3><?php echo $series_name; ?> <small>(Vol <?php echo $series_vol; ?>)</small> #<?php echo $issue_number; ?></h3>
                <p><?php if ($messageNum != 51) { echo 'has been added to your collection.'; } else { echo 'already exists in your collection.'; } ?></p>
              </div>
            </div>
            <div class="text-center center-block">
              <a href="/comic.php?comic_id=<?php echo $comic_id; ?>" class="btn btn-lg btn-success">View Issue</a>
              <a href="/add.php#addissue" class="btn btn-lg btn-info">Add another?</a>
            </div>
          </div>
        </div>
      <?php // ADD SINGLE ISSUE: Part 1/4: Allows user to pick the series to add an issue and its issue #
        } else { ?>
        <div class="col-xs-12">
          <h2>Add Issue</h2>
          <form method="post" action="<?php echo $filename; ?>?type=issue-search#addissue" class="form-inline" id="add-issue">
            <div class="form-group">
              <label>Series</label>
              <select class="form-control" name="series_id">
                <option value="" disabled selected>Choose a series</option>
                <?php
                  $listAllSeries=1;
                  $comic = new comicSearch ();
                  $comic->seriesList ($listAllSeries);
                  while ( $row = $comic->series_list_result->fetch_assoc () ) {
                    $list_series_name = $row ['series_name'];
                    $list_series_vol = $row ['series_vol'];
                    $list_series_id = $row ['series_id'];
                    echo '<option value="' . $list_series_id . '">' . $list_series_name . ' (Vol ' . $list_series_vol . ')</option>';
                  } 
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="issue_number">Issue #</label>
              <input name="issue_number" class="form-control" type="text" size="3" maxlength="4" value="" required aria-required="true" />
            </div>
            <input type="hidden" name="submitted" value="yes" />
            <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-search"></i> Search</button>
          </form>
        </div>
      <?php } ?>
    </div>
    <?php // ADD RANGE ?>
    <div class="row add-block form-add-range">
      <?php if ($rangeSearch != true) { // This shows the form if the user has not submitted yet. ?>
      <div class="col-xs-12">
        <h2>Add a range of issues</h2>
        <p>If the series had a regular monthly release, you may enter the published date of the first issue.</p>
        <p>Each entry will automatically have the month incremented.</p>
        <form id="input_select" method="post" action="<?php echo $filename; ?>?type=range#addrange">
          <div class="row">
            <div class="col-xs-12 col-md-6">
              <div class="form-group">
                <label for="series_name">Series</label>
                <select class="form-control" name="series_id">
                  <option value="" disabled selected>Choose a series</option>
                  <?php 
                    $listAllSeries=1;
                    $comic = new comicSearch ();
                    $comic->seriesList ($listAllSeries);
                    while ( $row = $comic->series_list_result->fetch_assoc () ) {
                      $list_series_name = $row ['series_name'];
                      $list_series_vol = $row ['series_vol'];
                      $list_series_id = $row ['series_id'];
                      echo '<option value="' . $list_series_id . '">' . $list_series_name . ' (Vol ' . $list_series_vol . ')</option>';
                    }  
                  ?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-md-6">
              <div class="form-inline">
                <div class="form-group">
                  <label for="first_issue">First Issue</label>
                  <input name="first_issue" type="text" class="form-control" maxlength="3" size="3" />
                </div>
                <div class="form-group">
                  <label for="last_issue">Last Issue</label>
                  <input name="last_issue" type="text" class="form-control" maxlength="3" size="3" />
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-xs-12">
              <div class="form-inline">
                <div class="form-group form-radio">
                  <label for="originalPurchase">Purchased When Released</label>
                  <fieldset>
                    <input name="originalPurchase" id="original-yes" value="1" type="radio" /> <label for="original-yes">Yes</label>
                    <input name="originalPurchase" id="original-no" value="0" type="radio" /> <label for="original-no">No</label>
                  </fieldset>
                </div>
                <div class="form-group">
                  <label for="release_date">First Comic's Published Date</label>
                  <input name="release_date" type="date" class="form-control" maxlength="10" placeholder="YYYY-MM-DD"/>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="submitted" value="yes" />
          <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-paper-plane"></i> Submit</button>
        </form>
      </div>
      <?php } else {
        $wiki = new wikiQuery();
        $wiki->addWikiID();
        $wiki->addDetails();
        echo $wiki->newWikiIDs; ?>
      <?php } ?>
    </div>
    <?php // ADD LIST ?>
    <div class="row add-block form-add-list">
      <div class="col-xs-12">
        <h2>Add Series</h2>
      </div>
    </div>
    <?php // ADD SERIES ?>
    <div class="row add-block form-add-series">
      <?php if ($seriesSearch == true) { ?>
        <div class="col-xs-12">
          <h2>Your Search Results</h2>
          <p>We found the following series on ComicVine related to: <em><?php echo $series_name; ?></em></p>
          <p>Check the ComicVine link below the result to make sure it is the series you are looking for. Links open in a new tab.</p>
          <form method="post" action="<?php echo $filename; ?>?type=series-submit#addseries" class="form-inline" id="add-series-search">
            <div class="form-group form-radio">
              <label for="wiki_id">Choose the result that matches your series:</label>
              <fieldset class="row">
                <?php echo $seriesSearch->resultsList; ?>
              </fieldset>
            </div>
            <input type="hidden" name="series_vol" value="<?php echo $series_vol; ?>" />
            <input type="hidden" name="publisherID" value="<?php echo $publisherID; ?>" />
            <input type="hidden" name="submitted" value="yes" />
            <div class="text-center center-block">
              <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
              <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-paper-plane"></i> Submit</button>
            </div>
          </form>
        </div>
      <?php } elseif ($seriesSubmit == true) { ?>
        <div class="add-success bg-success col-xs-12">
          <div class="success-message text-center">
            <h3><?php echo $series_name; ?><br /><small>(Vol <?php echo $series_vol; ?>)</small></h2>
            <p>has been added to your collection.</p>
            <button class="btn btn-lg btn-success add-another"><i class="fa fa-plus-square"></i> Add another?</button>
          </div>
        </div>
      <?php } else {?>
      <div class="col-xs-12" id="form-series-add">
        <h2>Add Series</h2>
        <p>Use the form below to add a new series to your collection.</p>
        <form method="post" action="<?php echo $filename; ?>?type=series-search#addseries" class="form-inline" id="add-series">
          <div class="form-group">
            <label for="publisherID">Publisher</label>
            <select class="form-control" name="publisherID" required>
              <option value="">Choose a Publisher</option>
              <?php
                $comic = new comicSearch ();
                $comic->publisherList ();
                while ( $row = $comic->publisher_list_result->fetch_assoc () ) {
                  $list_publisher_name = $row ['publisherName'];
                  $list_publisherID = $row ['publisherID'];
                  echo '<option value="' . $list_publisherID . '">' . $list_publisher_name . '</option>';
                } 
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="series_name">Series Name</label>
            <input name="series_name" class="form-control" type="text" size="50" value="" required />
          </div>
          <div class="form-group">
            <label for="series_vol">Volume #</label>
            <select class="form-control" name="series_vol">
              <option value="1" selected>1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
            </select>
          </div>
          <input type="hidden" name="submitted" value="yes" />
          <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-search"></i> Search</button>
        </form>
      </div>
      <?php } ?>
    </div>
  </div>
<?php include 'views/footer.php';?>
</body>
</html>