<?php 
  $details = new comicSearch ();
  $details->issueLookup ( $comic_id );
  $details->seriesInfo ( $details->series_id );

  $series_name = $details->series_name;
  $series_vol = $details->series_vol;
  $issue_num = $details->issue_number;
?>
<div class="row">
  <div class="col-sm-12 headline">
    <h2><?php echo $series_name . " #" . $issue_num; ?></h2>
    <div class="series-meta">
      <ul class="nolist">
        <?php
          if ($details->release_date) {
        ?>
        <li><?php echo DateTime::createFromFormat('Y-m-d', $details->release_date)->format('M Y'); ?></li>
        <?php } ?>
        <li>Volume <?php echo $series_vol; ?></li>
      </ul>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-8">
    <div class="issue-story"><h4><?php echo $details->story_name; ?></h4></div>
    <div class="issue-description">
      <?php if ($details->plot != '') {
        echo $details->plot; 
      } else {
        echo 'Plot details have not been entered.';
      }
      ?>
    </div>
    <p>
      <?php
        if ($login->isUserLoggedIn () == true) {
          echo "<a href=\"/admin/wikiaedit.php?comic_id=" . $details->comic_id . "&wiki_id=" . $details->wiki_id . "\">Update Info</a>";
        }
      ?>
    </p>
  </div>
  <div class="col-md-4 issue-image">
    <img src="<?php echo $details->cover_image; ?>" alt="cover" />
  </div>
</div>