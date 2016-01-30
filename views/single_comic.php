<?php 
  $comic = new comicSearch ();
  $comic->issueLookup ( $comic_id );
  $comic->seriesInfo ( $comic->series_id );

  if (isset($comic->publisherName)) {
    $publisherName = $comic->publisherName;
    $publisherShort = $comic->publisherShort;
  } else {
    $messageNum = 60;
  }
  // Standardizes values for common variables for use in notifications
  if (isset($comic->series_name) || isset($comic->series_vol) || isset($comic->issue_number) || isset($comic->release_date)) {
    $series_name = $comic->series_name;
    $series_vol = $comic->series_vol;
    $issue_num = $comic->issue_number;
    $release_dateShort = DateTime::createFromFormat('Y-m-d', $comic->release_date)->format('M Y');
    $release_dateLong = DateTime::createFromFormat('Y-m-d', $comic->release_date)->format('M d, Y');
  } else {
    $messageNum = 99;
  }

?>
<header class="row headline">
  <div class="col-xs-12 col-md-7">
    <h2><?php echo $series_name . " #" . $issue_num; ?></h2>
  </div>
  <div class="col-xs-12 col-md-5 series-meta text-right">
    <ul class="nolist">
      <?php if ($publisherName) { echo '<li class="logo-' . $publisherShort .' sm-logo">' . $publisherName . '</li>'; } ?>
      <li>Volume <?php echo $series_vol; ?></li>
      <?php if ($comic->release_date) { ?>
        <li><?php echo $release_dateShort; ?></li>
      <?php } ?>
    </ul>
  </div>
</header>
<div class="row">
  <div class="col-md-8">
    <div class="issue-story"><h4><?php echo $comic->story_name; ?></h4></div>
    <div class="issue-description">
      <?php if ($comic->plot != '') {
        echo $comic->plot; 
      } else {
        echo '<p>Plot details have not been entered.</p>';
      }
      ?>
    </div>
    <div class="button-block text-center">
      <?php
        if ($login->isUserLoggedIn () == true) { ?>
          <a href="#" class="btn btn-danger"><i class="fa fa-trash"></i> Delete Comic</a>
          <a href="/comic.php?comic_id=<?php echo $comic->comic_id; ?>&wiki_id=<?php echo $comic->wiki_id; ?>&type=edit" class="btn btn-warning"><i class="fa fa-pencil-square-o"></i> Update Comic</a>
        <?php } 
      ?>
    </div>
  </div>
  <div class="col-md-4 sidebar">
    <div class="issue-image">
      <img src="<?php echo $comic->cover_image; ?>" alt="cover" />
    </div>
    <div class="issue-details">
      <h2>Issue Details</h2>
      <span class="logo-<?php echo $publisherShort; ?> pull-right"></span>
      <p>
        <big><strong><?php echo $series_name; ?></strong></big><br />
        <strong>Issue: #</strong><?php echo $issue_num; ?><br />
        <strong>Volume: </strong><?php echo $series_vol; ?><br />
        <strong>Cover Date: </strong><br />
      </p>
    </div>
    <div class="issue-credits text-center">
      <div class="row">
        <div class="col-md-6 credit-writer">
          <h3>Script</h3>
          <a href="#" title="View more comics written by AUTHOR NAME">Chris Claremont</a>, Len Wein, Roy Thomas, Arnold Drake, Linda Fite
        </div>
        <div class="col-md-6 credit-artist">
          <h3>Pencils</h3>
          <a href="#" title="View more comics drawn by ARTIST NAME">Gil Kane, Dave Cockrum, Werner Roth</a>
        </div>
      </div>      
      <div class="row">
        <div class="col-md-4 credit-inker">
          <h3>Inks/Colors</h3>
          Dave Cockrum, Peter Iro, John Verpoorten, Sam Grainger
        </div>
        <div class="col-md-4 credit-letters">
          <h3>Letters</h3>
          Tyler Durden
        </div>
        <div class="col-md-4 credit-editor">
          <h3>Editing</h3>
          Mike Marts
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 credit-cover">
          <h3>Cover</h3>
          <a href="#" title="View more comics drawn by ARTIST NAME">Alex Ross</a>
        </div>
      </div>
    </div>
  </div>
</div>