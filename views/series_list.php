<?php 
  $comic = new comicSearch ();
  $comic->seriesList ($listAll, $publisherSearchId);
  if ($comic->series_list_result->num_rows > 0) { ?>
    <ul id="inventory-table" class="row layout-thumb-lg">
    <?php while ( $row = $comic->series_list_result->fetch_assoc () ) {
      $series_id = $row ['series_id'];
      $series_name = $row ['series_name'];
      $series_vol = $row ['series_vol'];
      $comic->seriesInfo($series_id);
      $series_issue_count = $comic->series_issue_count;
      $series_cover = $comic->series_latest_cover;
      if ($series_cover == NULL) {
        $series_cover = 'assets/nocover.jpg';
      } 
      $publisherName = $comic->publisherName;
      $publisherShort = $comic->publisherShort;
      ?>

      <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
        <a href="issues.php?series_id=<?php echo $series_id; ?>" class="series-info">
          <div class="comic-image">
            <img src="/<?php echo $series_cover; ?>" alt="<?php echo $series_name; ?>" class="img-responsive" />
          </div>
          <div class="series-title"><h3><?php echo $series_name; ?></h3></div>
        </a>
        <div class="volume-number">
          <span>Vol <?php echo $series_vol; ?></span>
        </div>
        <div class="series-extra">
          <div class="series-publisher hidden-xs hidden-sm hidden-md">
            <?php if ($publisherName) { echo '<div class="logo-' . $publisherShort .' sm-logo">' . $publisherName . '</div>'; } ?>
          </div>
          <div class="text-uppercase series-count">
            <?php echo $series_issue_count; ?>
          </div>
          <div class="hidden-xs hidden-sm text-right series-controls">
            <button class="btn btn-link btn-xs" title="Add New Issue"><i class="fa fa-plus-square"></i></button>
            <button class="btn btn-link btn-xs" title="Edit this Series"><i class="fa fa-cog"></i></button>
          </div>
        </div>
      </li>
    <?php } ?>
    </ul>
  <?php } else { ?>
    <p>No Comics in your collection. Perhaps you should <a href="/add.php">Add some!</a></p>
  <?php }
?>