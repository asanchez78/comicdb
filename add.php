<?php
  require_once('views/head.php');
  $filename = $_SERVER["PHP_SELF"];
  
  // Reset all our form flags
  $seriesSearch = false;
  $seriesAdd = false;
  $seriesSubmit = false;
  $issueAdd = false;
  $issueSubmit = false;
  $listSearch = false;
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
    <ul class="add-menu nav nav-tabs" id="addTabs">
      <li role="presentation" class="active">
        <a href="#addSingle" id="form-add-issue" aria-controls="addSingle" role="tab" data-toggle="tab"><i class="fa fa-plus"></i> <span class="hidden-sm hidden-xs">Add Issue</span></a>
      </li>
      <li role="presentation">
        <a href="#addRange" id="form-add-range" aria-controls="addRange" role="tab" data-toggle="tab">
          <i class="fa fa-hashtag"></i> <span class="hidden-sm hidden-xs">Add Range of Issues</span>
        </a>
      </li>
      <li role="presentation">
        <a href="#addList" id="form-add-list" aria-controls="addList" role="tab" data-toggle="tab"><i class="fa fa-archive"></i> <span class="hidden-sm hidden-xs">Add List of Issues</span></a>
      </li>
      <li role="presentation">
        <a href="#addSeries" id="form-add-series" aria-controls="addSeries" role="tab" data-toggle="tab"><i class="fa fa-folder-open"></i> <span class="hidden-sm hidden-xs">Add Series</span></a>
      </li>
    </ul>
    <div class="tab-content">
      <?php // ADD SINGLE ISSUE ?>
      <div class="row add-block form-add-issue tab-pane fade in active" role="tabpanel" id="addSingle">
        <?php // ADD SINGLE ISSUE: Part 2/3: Displays final fields and allows user to change details before adding to collection.
          if ($issueAdd == true && $searchResults == true) { ?>
          <header class="col-xs-12 headline">
            <h2>Add Issue: <?php echo $series_name; ?> #<?php echo $issue_number; ?></h2>
          </header>
          <form method="post" action="<?php echo $filename; ?>?type=issue-submit#addissue" class="add-form">
            <div class="col-md-8 col-sm-12">
              <div class="form-group">
                <label for="story_name">Story Name: </label>
                <input class="form-control" name="story_name" type="text" maxlength="255" value="<?php echo $story_name; ?>" />
              </div>
              <div class="form-group">
                <label for="released_date">Cover Date:</label>
                <input class="form-control" name="released_date" size="10" maxlength="10" value="<?php echo $release_date; ?>" type="date" placeholder="YYYY-MM-DD" />
              </div>
              <div class="form-group form-radio">
                <label for="singleOriginalPurchase">Purchased When Released:</label>
                <fieldset>
                  <input name="singleOriginalPurchase" id="single-original-yes" value="1" type="radio" /> <label for="single-original-yes">Yes</label>
                  <input name="singleOriginalPurchase" id="single-original-no" value="0" type="radio" /> <label for="single-original-no">No</label>
                </fieldset>
              </div>
              <div class="plot form-group">
                <a class="btn btn-xs btn-link pull-right" id="editPlot">[edit]</a>
                <label for="plot">Plot:</label>
                <div class="plot-output">
                  <?php echo $plot; ?>
                </div>
                <div id="plotInput">
                  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
                  <script>
                    tinymce.init({ 
                      selector:'textarea',
                      height: 300,
                      toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
                      menubar: false
                    });
                  </script>
                  <textarea name="custPlot" class="form-control"><?php echo htmlspecialchars($plot); ?></textarea>
                  <input type="hidden" name="plot" value="<?php echo htmlspecialchars($plot); ?>" />
                </div>
              </div>
              <div class="text-center center-block button-block">
                <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
                <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-save"></i> Save</button>
              </div>
            </div>
            <div class="col-md-4 sidebar">
              <div class="issue-image">
                <img src="<?php echo $coverURL; ?>" alt="Cover" />
                <div class="form-group">
                  <label for="cover_image">Cover Image URL</label>
                  <input type="url" class="form-control" name="cover_image" value="<?php echo $coverURL; ?>" />
                  <small>Enter the URL of the image you wish to use. Default is the cover file from the ComicVine entry on this issue.</small>
                  <input type="hidden" name="cover_image_file" value="<?php echo $coverFile; ?>" />
                </div>
              </div>
              <div class="issue-details">
                <h2>Issue Details</h2>
                <span class="logo-<?php echo $publisherShort; ?> pull-right"></span>
                <p>
                  <big><strong><?php echo $series_name; ?></strong></big><br />
                  <strong>Issue: #</strong><?php echo $issue_number; ?><br />
                  <strong>First Published: </strong><?php echo $series_vol; ?><br />
                  <strong>Cover Date: </strong><?php echo $release_dateLong; ?><br />
                </p>
              </div>
              <?php if ($script || $pencils || $colors || $letters || $editing || $cover) { ?>
              <div class="issue-credits text-center">
                <div class="row">
                  <?php if ($script) { ?>
                  <div class="<?php if ($pencils) { ?>col-md-6<?php } else { ?>col-md-12<?php } ?> credit-writer">
                    <h3>Script</h3>
                    <?php echo $script; ?>
                  </div>
                  <?php } ?>
                  <?php if ($pencils) { ?>
                  <div class="<?php if ($script) { ?>col-md-6<?php } else { ?>col-md-12<?php } ?> credit-artist">
                    <h3>Pencils</h3>
                    <?php echo $pencils; ?>
                  </div>
                  <?php } ?>
                </div>
                <div class="row">
                  <?php if ($colors) { ?>
                  <div class="col-xs-12 <?php if ($letters && $editing) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-inker">
                    <h3>Inks/Colors</h3>
                    <?php echo $colors; ?>
                  </div>
                   <?php } ?>
                  <?php if ($letters) { ?>
                  <div class="col-xs-12 <?php if ($colors && $editing) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-letters">
                    <h3>Letters</h3>
                    <?php echo $letters; ?>
                  </div>
                   <?php } ?>
                  <?php if ($editing) { ?>
                  <div class="col-xs-12 <?php if ($letters && $colors) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-editor">
                    <h3>Editing</h3>
                    <?php echo $editing; ?>
                  </div>
                   <?php } ?>
                </div>
                <div class="row">
                  <?php if ($coverArtist) { ?>
                  <div class="col-xs-12 credit-cover">
                    <h3>Cover</h3>
                    <?php echo $coverArtist; ?>
                  </div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
            </div>
            <input type="hidden" name="series_name" value="<?php echo $series_name; ?>" />
            <input type="hidden" name="series_vol" value="<?php echo $series_vol; ?>" />
            <input type="hidden" name="issue_number" value="<?php echo $issue_number; ?>" />
            <input type="hidden" name="series_id" value="<?php echo $series_id; ?>" />
            <input type="hidden" name="creatorsList" value="<?php echo $creatorsList; ?>" />
            <input type="hidden" name="submitted" value="yes" />
          </form>  
        <?php // ADD SINGLE ISSUE: Part 3/3: Displays success message and allows user to view issue or add another issue.
          } elseif ($issueSubmit == true) { ?>
          <div class="add-success col-xs-12 <?php if ($messageNum != 51) { echo 'bg-success'; } else { echo 'bg-danger'; } ?>">
            <div class="success-message">
              <div class="row">
                <div class="col-md-3 col-xs-hidden">
                  <img src="<?php echo $cover_image_file; ?>" alt="<?php echo $series_name . '(' . $series_vol . ') #' . $issue_number; ?> Cover" class="" />
                </div>
                <div class="col-xs-12 col-md-9">
                  <h3><?php echo $series_name; ?> <small>(<?php echo $series_vol; ?>)</small> #<?php echo $issue_number; ?></h3>
                  <p><?php if ($messageNum != 51) { echo 'has been added to your collection.'; } else { echo 'already exists in your collection.'; } ?></p>
                </div>
              </div>
              <div class="text-center center-block">
                <a href="/comic.php?comic_id=<?php echo $comic_id; ?>" class="btn btn-lg btn-success">View Issue</a>
                <a href="/add.php#addissue" class="btn btn-lg btn-info">Add another?</a>
              </div>
            </div>
          </div>
        <?php // ADD SINGLE ISSUE: Part 1/3: Allows user to pick the series to add an issue and its issue #
          } else { ?>
          <header class="headline col-xs-12">
            <h2>Add Issue</h2>
          </header>
          <div class="col-xs-12">
            <form method="post" action="<?php echo $filename; ?>?type=issue-add#addissue" class="form-inline add-form" id="add-issue">
              <p>Add a single issue of a series. After submitting the series and issue, you will have a chance to edit the details before it's added to your collection.</p>
              <div class="form-group">
                <label>Series</label>
                <select class="form-control" name="series_id" required>
                  <option value="" disabled selected>Choose a series</option>
                  <?php
                    $listAllSeries=1;
                    $comic = new comicSearch ();
                    $comic->seriesList ($listAllSeries, NULL, $userID);
                    while ( $row = $comic->series_list_result->fetch_assoc () ) {
                      $list_series_name = $row ['series_name'];
                      $list_series_vol = $row ['series_vol'];
                      $list_series_id = $row ['series_id'];
                      echo '<option value="' . $list_series_id . '">' . $list_series_name . ' (' . $list_series_vol . ')</option>';
                    } 
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="issue_number">Issue #</label>
                <input name="issue_number" class="form-control" type="text" size="3" maxlength="4" value="" required aria-required="true" />
              </div>
              <input type="hidden" name="submitted" value="yes" />
              <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-plus"></i> Add</button>
            </form>
          </div>
        <?php } ?>
      </div>
      

      <?php // ADD RANGE ?>
      <div class="row add-block form-add-range tab-pane fade" role="tabpanel" id="addRange">
        <?php if ($rangeSearch != true) { // This shows the form if the user has not submitted yet. ?>
        <header class="headline col-xs-12"><h2>Add a range of issues</h2></header>
        <div class="col-xs-12">
          <form id="input_select" class="add-form" method="post" action="<?php echo $filename; ?>?type=range#addrange">
            <p>Use the form below to add several issues of one series in consecutive order.</p>
            <div class="row">
              <div class="col-xs-12 col-md-6">
                <div class="form-group">
                  <label for="series_name">Series</label>
                  <select class="form-control" name="series_id" required>
                    <option value="" disabled selected>Choose a series</option>
                    <?php 
                      $listAllSeries=1;
                      $comic = new comicSearch ();
                      $comic->seriesList ($listAllSeries, NULL, $userID);
                      while ( $row = $comic->series_list_result->fetch_assoc () ) {
                        $list_series_name = $row ['series_name'];
                        $list_series_vol = $row ['series_vol'];
                        $list_series_id = $row ['series_id'];
                        echo '<option value="' . $list_series_id . '">' . $list_series_name . ' (' . $list_series_vol . ')</option>';
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
                    <label for="rangeOriginalPurchase">Purchased When Released</label>
                    <fieldset>
                      <input name="rangeOriginalPurchase" id="range-original-yes" value="1" type="radio" /> <label for="range-original-yes">Yes</label>
                      <input name="rangeOriginalPurchase" id="range-original-no" value="0" type="radio" /> <label for="range-original-no">No</label>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="submitted" value="yes" />
            <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-paper-plane"></i> Submit</button>
          </form>
        </div>
        <?php } else { ?>
          <div class="add-success col-xs-12 bg-success">
            <div class="success-message">
              <div class="row">
                <div class="text-center">
                  <h2><?php echo $series_name; ?></h2>
                  <?php echo $addedList; ?>
                  <p>have been added to your collection.</p>
                </div>
              </div>
              <div class="text-center center-block">
                <a href="/issues.php?series_id=<?php echo $series_id; ?>" class="btn btn-lg btn-success">View Series</a>
                <a href="/add.php#addrange" class="btn btn-lg btn-info">Add More</a>
              </div>
            </div>
          </div>
          ?>
        <?php } ?>
      </div>
      

      <?php // ADD LIST ?>
      <div class="row add-block form-add-list tab-pane fade" role="tabpanel" id="addList">
        <header class="col-xs-12 headline">
          <h2>Add List of Issues</h2>
        </header>
        <div class="col-xs-12">
          <?php if ($listSearch != true) { ?>
          <form id="input_select" class="add-form" method="post" action="<?php echo $filename; ?>?type=csv#addlist">
            <p>Use the form below to add several issues of one series to your collection. Separate any issues with commas.</p>
            <div class="form-group">
              <label for="series_name">Series</label>
              <select class="form-control" name="series_id" required>
                <option value="" disabled selected>Choose a series</option>
                <?php 
                  $listAllSeries=1;
                  $comic = new comicSearch ();
                  $comic->seriesList ($listAllSeries, NULL, $userID);
                  while ( $row = $comic->series_list_result->fetch_assoc () ) {
                    $list_series_name = $row ['series_name'];
                    $list_series_vol = $row ['series_vol'];
                    $list_series_id = $row ['series_id'];
                    echo '<option value="' . $list_series_id . '">' . $list_series_name . ' (' . $list_series_vol . ')</option>';
                  }  
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="issueList">Comma separated list of issues</label>
              <input type="text" class="form-control" name="issueList" placeholder="5,29,156" />
            </div>
            <div class="form-group form-radio">
              <label for="listOriginalPurchase">Purchased When Released</label>
              <fieldset>
                <input name="listOriginalPurchase" id="list-original-yes" value="1" type="radio" /> <label for="list-original-yes">Yes</label>
                <input name="listOriginalPurchase" id="list-original-no" value="0" type="radio" /> <label for="list-original-no">No</label>
              </fieldset>
            </div>
            <input type="hidden" name="submitted" value="yes" />
            <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-paper-plane"></i> Submit</button>
          </form>
          <?php } else { ?>
            <div class="add-success col-xs-12 bg-success">
              <div class="success-message">
                <div class="row">
                  <div class="text-center">
                    <h2><?php echo $series_name; ?></h2>
                    #<?php echo $filtered_issue_list; ?>
                    <p>have been added to your collection.</p>
                  </div>
                </div>
                <div class="text-center center-block">
                  <a href="/issues.php?series_id=<?php echo $series_id; ?>" class="btn btn-lg btn-success">View Series</a>
                  <a href="/add.php#addrange" class="btn btn-lg btn-info">Add More</a>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>


      <?php // ADD SERIES ?>
      <div class="row add-block form-add-series tab-pane fade" role="tabpanel" id="addSeries">
        <?php if ($seriesSearch == true) { ?>
          <header class="headline col-xs-12">
            <h2>Your Search Results</h2>
          </header>
          <div class="col-xs-12">
            <form method="post" action="<?php echo $filename; ?>?type=series-submit#addseries" class="form-inline add-form" id="add-series-search">
              <p>We found the following series on ComicVine related to: <em><?php echo $series_name; ?></em></p>
              <p>Check if it's the correct series by clicking the thumbnail of each of the results from ComicVine to open it in a new tab.</p>
              <div class="form-group form-radio">
                <label for="add-series-search">Choose the result that matches your series:</label>
                <fieldset class="row">
                  <?php echo $seriesSearch->resultsList; ?>
                </fieldset>
              </div>
              <input type="hidden" name="publisherID" value="<?php echo $publisherID; ?>" />
              <input type="hidden" name="submitted" value="yes" />
              <div class="text-center center-block button-block">
                <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
                <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-paper-plane"></i> Submit</button>
              </div>
            </form>
          </div>
        <?php } elseif ($seriesSubmit == true) {
          if ($seriesSubmitted == true) { ?>
          <div class="add-success bg-success col-xs-12">
            <div class="success-message text-center">
              <h3><?php echo $series_name; ?><br /><small>(<?php echo $series_vol; ?>)</small></h2>
              <p>has been added to your collection.</p>
              <a class="btn btn-lg btn-success add-another" href="/add.php#addseries"><i class="fa fa-plus-square"></i> Add another?</a>
            </div>
          </div>
          <?php } else { ?>
          <div class="add-success bg-danger col-xs-12">
            <div class="success-message text-center">
              <h3><?php echo $series_name; ?><br /><small>(<?php echo $series_vol; ?>)</small></h2>
              <p>is already in your collection</p>
              <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
              <a class="btn btn-lg btn-success add-another" href="/add.php#addseries"><i class="fa fa-plus-square"></i> Add another?</a>
            </div>
          </div>
          <?php } ?>
        <?php } else {?>
        <header class="headline col-xs-12"><h2>Add Series</h2></header>
        <div class="col-xs-12" id="form-series-add">
          <form method="post" action="<?php echo $filename; ?>?type=series-search#addseries" class="form-inline add-form" id="form-add-series-1">
            <p>Use the form below to add a new series to your collection.</p>
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
            <input type="hidden" name="submitted" value="yes" />
            <div class="form-group">
              <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-search"></i> Search</button>
            </div>
          </form>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
<?php include 'views/footer.php';?>
</body>
</html>