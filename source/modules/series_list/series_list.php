<?php
  $comic->seriesList (0, '', $profileID);
?>
<section data-module="series_list">
<?php if (isset($comic->series_list_result->num_rows) && $comic->series_list_result->num_rows > 0) { ?>
  <header class="row headline">
    <div class="col-xs-7 col-md-8">
      <h2><?php if (isset($profile_name) && $profile_name != '') { echo $first_name . '&#8217;s'; } else { echo 'Your'; } ?>  Collection</h2>
    </div>
    <div class="col-xs-5 col-md-4 sort-control-container">
      <button class="btn-xs btn-default sort-control active" id="sort-thumb-lg"><i class="fa fa-th-large"></i></button>
      <button class="btn-xs btn-default sort-control" id="sort-thumb-sm"><i class="fa fa-th"></i></button>
      <button class="btn-xs btn-default sort-control" id="sort-list"><i class="fa fa-list"></i></button>
    </div>
  </header>
  <ul id="inventory-table" class="row layout-thumbLg">
  <?php while ( $row = $comic->series_list_result->fetch_assoc () ) {
    $series_id = $row ['series_id'];
    $series_name = $row ['series_name'];
    $series_vol = $row ['series_vol'];
    
    $comic->seriesInfo($series_id, $profileID);
    
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
        <a href="issues.php?series_id=<?php echo $series_id; if (isset($profile_name) && $profile_name != '') { echo '&user=' . $profile_name; } ?>" class="series-info">
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
  <?php if ($comic->hasPagination === true) { ?>
  <nav class="text-center">
    <ul class="pagination pagination-lg">
      <?php echo $comic->previousPage; ?>
      <?php echo $comic->pagination; ?>
      <?php echo $comic->nextPage; ?>
    </ul>
  </nav>
  <?php } ?>
<?php } else { ?>
  <p>No Comics in your collection. Perhaps you should <a href="/add.php">Add some!</a></p>
<?php } ?>
</section>