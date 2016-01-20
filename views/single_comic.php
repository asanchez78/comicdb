<?php 
  $details = new comicSearch ();
  $details->issueLookup ( $comic_id );
  $details->seriesInfo ( $details->series_id );

  if (isset($details->publisher)) {
    $publisher = $details->publisher;
  } else {
    $publisher = 'Marvel Comics';
  }
  // Standardizes values for common variables for use in notifications
  if (isset($details->series_name) || isset($details->series_vol) || isset($details->issue_number)) {
    $series_name = $details->series_name;
    $series_vol = $details->series_vol;
    $issue_num = $details->issue_number;
  } else {
    $messageNum = 99;
  }

  // Creates a "shortname" for the publisher that can be used in a CSS class
  $publisherShort = strtolower(str_replace(' ', '', $publisher));
?>
<div class="row">
  <div class="col-sm-12 headline">
    <h2><?php echo $series_name . " #" . $issue_num; ?></h2>
    <div class="series-meta">
      <ul class="nolist">
        <?php if ($publisher) { echo '<li class="logo-' . $publisherShort .'">' . $publisher . '</li>'; } ?>
        <li>Volume <?php echo $series_vol; ?></li>
        <?php if ($details->release_date) { ?>
          <li><?php echo DateTime::createFromFormat('Y-m-d', $details->release_date)->format('M Y'); ?></li>
        <?php } ?>
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
        echo '<p>Plot details have not been entered.</p>';
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