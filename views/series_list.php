<?php 
  $comics = new comicSearch ();
  $comics->seriesList ($_SESSION ['user_id']);
  if ($comics->series_list_result->num_rows > 0) { ?>
    <ul class="nolist row inventory-table">
    <?php while ( $row = $comics->series_list_result->fetch_assoc () ) {
      $series_id = $row ['series_id'];
      $series_name = $row ['series_name'];
      $series_vol = $row ['series_vol'];
      $comics->seriesInfo($series_id);
      $series_issue_count = $comics->series_issue_count;
      $series_cover = $comics->series_latest_cover;
      if ($series_cover == NULL) {
        $series_cover = 'assets/nocover.jpg';
      } ?>

      <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
        <a href="issues.php?series_id=<?php echo $series_id; ?>" class="series-info">
          <div class="comic-image">
            <img src="/<?php echo $series_cover; ?>" alt="<?php echo $series_name; ?>" class="img-responsive" />
            <div class="series-title"><h3><?php echo $series_name; ?></h3></div>
          </div>
        </a>
        <small><?php echo $series_issue_count; ?></small>
        <div class="volume-number">
          <span class="count">Vol <?php echo $series_vol; ?></span>
        </div>
        <a href="#" class="button add-button">[Add New]</a>
        <a href="#" class="button edit-button">[Edit]</a>
      </li>
    <?php } ?>
    </ul>
  <?php } else { ?>
    <li>No Comic Series in database. Perhaps you should <a href=\"/admin/addseries.php\">Add some.</a></li>
  <?php }
?>