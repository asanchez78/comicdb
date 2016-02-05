<?php 
  $canonUrl = "http://comicmanager.com$_SERVER[REQUEST_URI]";
  $articleID = "comic" . $comic_id;
  $comic = new comicSearch ();
  $comic->issueLookup ( $comic_id );
  $series_id = $comic->series_id;
  
  if (isset($userSetID) && $validUser == 1) {
    $comic->seriesInfo ( $series_id, $userSetID );
  } else {
    if (!isset($userID)) {
      $userID=NULL;
    }
    $comic->seriesInfo ( $series_id, $userID );
  }
  // Required values
  // Standardizes values for common variables
  if (isset($comic->series_name) && isset($comic->series_vol) && isset($comic->issue_number) && isset($comic->publisherName)) {
    $series_name = $comic->series_name;
    $series_vol = $comic->series_vol;
    $issue_num = $comic->issue_number;
    $publisherID = $comic->publisherID;
    $publisherName = $comic->publisherName;
    $publisherShort = $comic->publisherShort;
  } else {
    $messageNum = 99;
  }

  // Optional values below
  // Making sure a release date exists or format function throws an error
  if (isset($comic->release_date)) {
    $release_dateShort = DateTime::createFromFormat('Y-m-d', $comic->release_date)->format('M Y');
    $release_dateLong = DateTime::createFromFormat('Y-m-d', $comic->release_date)->format('M d, Y');  
  } else {
    $release_dateShort = '';
    $release_dateLong = 'No Date Entered';
  }

  // Checks for user entered custom plot, otherwise displays the original value.
  if (isset($comic->custPlot) && $comic->custPlot != '') {
    $plot = $comic->custPlot;
  } elseif ($comic->plot != '') {
    $plot = $comic->plot;
  } else {
    $plot = '<p>Plot details have not been entered.</p>';
  }
  
  $script = $comic->script;
  $pencils = $comic->pencils;
  $colors = $comic->colors;
  $inks = $comic->inks;
  $letters = $comic->letters;
  $editing = $comic->editing;
  $coverArtist = $comic->coverArtist;

?>
<header class="row headline">
  <div class="col-xs-12 col-lg-8">
    <h2><?php echo $series_name . " #" . $issue_num; ?></h2>
  </div>
  <div class="col-xs-12 col-lg-4 series-meta">
    <ul class="nolist">
      <?php if ($publisherName) { echo '<li class="logo-' . $publisherShort .' sm-logo"><a href="/publisher.php?pid=' . $publisherID . '">' . $publisherName . '</a></li>'; } ?>
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
      <?php echo $plot; ?>
    </div>
    <div class="button-block text-center">
      <?php if ($login->isUserLoggedIn () == true) { ?>
        <a href="#" class="btn btn-danger"><i class="fa fa-trash"></i> Delete Comic</a>
        <a href="/comic.php?comic_id=<?php echo $comic->comic_id; ?>&type=edit" class="btn btn-warning"><i class="fa fa-pencil-square-o"></i> Update Comic</a>
      <?php } ?>
    </div>
    <div class="disqus-block">
      <div id="disqus_thread"></div>
      <script>
      var disqus_config = function () {
      this.page.url = '<?php echo $canonUrl; ?>'; // Replace PAGE_URL with your page's canonical URL variable
      this.page.identifier = '<?php echo $articleID; ?>'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
      };
      (function() { // DON'T EDIT BELOW THIS LINE
      var d = document, s = d.createElement('script');

      s.src = '//powcbm.disqus.com/embed.js';

      s.setAttribute('data-timestamp', +new Date());
      (d.head || d.body).appendChild(s);
      })();
      </script>
      <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
    </div>
  </div>
  <div class="col-md-4 sidebar">
    <div class="issue-image">
      <img src="<?php echo $comic->cover_image; ?>" alt="cover" />
    </div>
    <div class="issue-details">
      <h2>Issue Details</h2>
      <a href="/publisher.php?pid=<?php echo $publisherID; ?>" class="logo-<?php echo $publisherShort; ?> pull-right"></a>
      <p>
        <big><strong><?php if ($login->isUserLoggedIn () == true) { ?><a href="/issues.php?series_id=<?php echo $series_id; ?>" title="View more comics in this series"><?php echo $series_name; ?></a><?php } else { ?><?php echo $series_name; ?><?php } ?></strong></big><br />
        <strong>Issue: #</strong><?php echo $issue_num; ?><br />
        <strong>Series Published: </strong><?php echo $series_vol; ?><br />
        <strong>Cover Date: </strong><?php echo $release_dateLong; ?><br />
      </p>
    </div>
    <?php if ($script || $pencils || $colors || $letters || $editing || $coverArtist) { ?>
    <div class="issue-credits text-center">
      <div class="row">
        <?php if ($script) { ?>
        <div class="col-md-6 credit-writer">
          <h3>Script</h3>
          <?php echo $script; ?>
        </div>
        <?php } ?>
        <?php if ($pencils) { ?>
        <div class="col-md-6 credit-artist">
          <h3>Pencils</h3>
          <?php echo $pencils; ?>
        </div>
        <?php } ?> 
      </div> 
        
      <div class="row">
        <?php if ($colors) { ?>
        <div class="col-xs-12 <?php if ($letters && $inks) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-inker">
          <h3>Colors</h3>
          <?php echo $colors; ?>
        </div>
        <?php } ?>
        <?php if ($inks) { ?>
        <div class="col-xs-12 <?php if ($colors && $letters) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-inks">
          <h3>Inks</h3>
          <?php echo $inks; ?>
        </div>
        <?php } ?>
        <?php if ($letters) { ?>     
        <div class="col-xs-12 <?php if ($colors && $inks) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-letters">
          <h3>Letters</h3>
          <?php echo $letters; ?>
        </div>
        <?php } ?>
      </div>
      <div class="row">
        <?php if ($editing) { ?> 
        <div class="col-xs-12 <?php if ($coverArtist) { ?>col-md-6<?php } else { ?>col-md-12<?php } ?> credit-editor">
          <h3>Editing</h3>
          <?php echo $editing; ?>
        </div>
        <?php } ?>
        <?php if ($coverArtist) { ?>
        <div class="col-xs-12 <?php if ($editing) { ?>col-md-6<?php } else { ?>col-md-12<?php } ?> credit-cover">
          <h3>Cover</h3>
          <?php echo $coverArtist; ?>
        </div>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
<script id="dsq-count-scr" src="//powcbm.disqus.com/count.js" async></script>