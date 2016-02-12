<?php 
$publisherSearchId = filter_input ( INPUT_GET, 'pid' );
$comic = new comicSearch ();

if ($publisherSearchId !== NULL) {
  $listAll = 2;
}

if (isset($userSetID) && $validUser == 1) {
  $comic->collectionCount ($userSetID);
  $totalIssues = $comic->total_issue_count;
  $listAll = 0;
  $comic->seriesList ($listAll, $publisherSearchId, $userSetID);
} else {
  if (isset($userSetID) && $validUser != 1) {
    $messageNum = 64;
  }
  if ($publisherSearchId !== NULL) {
    $listAll = 2;
  } else {
    $listAll = 0;
  }
  $comic->collectionCount ($userID);
  $totalIssues = $comic->total_issue_count;
  $comic->seriesList ($listAll, $publisherSearchId, $userID);
} ?>
<section data-module="series_list">
<?php if (isset($comic->series_list_result->num_rows) && $comic->series_list_result->num_rows > 0) { ?>
  <header class="row headline">
    <div class="col-xs-12 col-md-5 col-lg-6">
      <h2>
        <?php if (isset($userSetName) && $validUser == 1) {
          echo $userSetName . '&rsquo;s collection';
        } else if (isset($publisherSearchId)) {
          echo $publisherName;
        } else {
          echo 'Your collection';
        } ?>
      </h2>
    </div>
    <div class="col-xs-12 col-md-7 col-lg-6 series-meta">
      <ul class="nolist row">
        <li class="col-xs-4 col-md-4 col-lg-4"><span class="text-danger"><?php echo $totalIssues; ?></span> Total Issues</li>
        <li class="col-xs-3 col-md-3 col-lg-4"><span class="text-danger">XXX</span> Total Series</li>
        <li class="col-xs-5 col-md-5 col-lg-4 sort-control-container">
          <button class="btn-xs btn-default sort-control active" id="sort-thumb-lg"><i class="fa fa-th-large"></i></button>
          <button class="btn-xs btn-default sort-control" id="sort-thumb-sm"><i class="fa fa-th"></i></button>
          <button class="btn-xs btn-default sort-control" id="sort-list"><i class="fa fa-list"></i></button>
        </li>
      </ul>
    </div>
  </header>
  <ul id="inventory-table" class="row layout-thumbLg">
  <?php while ( $row = $comic->series_list_result->fetch_assoc () ) {
    $series_id = $row ['series_id'];
    $series_name = $row ['series_name'];
    $series_vol = $row ['series_vol'];
    if (isset($userSetID) && $validUser == 1) {
      $comic->seriesInfo($series_id, $userSetID);
    } else {
      $comic->seriesInfo($series_id, $userID);
    }
    $series_issue_count = $comic->series_issue_count;
    $latestCoverMed = $comic->latestCoverMed;
    $latestCoverSmall = $comic->latestCoverSmall;
    $latestCoverThumb = $comic->latestCoverThumb;
    if ($latestCoverMed == NULL) {
      $latestCoverMed = 'assets/nocover.jpg';
    } 
    $publisherName = $comic->publisherName;
    $publisherShort = $comic->publisherShort;
    ?>

    <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
      <div class="series-list-row">
        <a href="issues.php?series_id=<?php echo $series_id; ?>" class="series-info">
          <div class="series-list-row">
            <div class="comic-image">
              <img src="/<?php echo $latestCoverMed; ?>" alt="<?php echo $series_name; ?>" class="img-responsive" />
            </div>
            <div class="series-title"><h3><?php echo $series_name; ?></h3></div>
          </div>
        </a>
        <div class="volume-number">
          <span><?php echo $series_vol; ?></span>
        </div>
        <div class="series-extra">
          <div class="series-list-row">
            <div class="series-publisher hidden-xs hidden-sm hidden-md">
              <?php if ($publisherName) { echo '<div class="logo-' . $publisherShort .' sm-logo">' . $publisherName . '</div>'; } ?>
            </div>
            <div class="series-count text-uppercase text-center">
              <?php echo $series_issue_count; ?>
            </div>
          </div>
        </div>
      </div>
    </li>
  <?php } ?>
  </ul>
<?php } else { ?>
  <p>No Comics in your collection. Perhaps you should <a href="/add.php">Add some!</a></p>
<?php } ?>
</section>