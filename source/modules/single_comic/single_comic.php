<?php 
  $canonUrl = "http://comicmanager.com$_SERVER[REQUEST_URI]";
  $articleID = "comic" . $comic_id;
  $comic = new comicSearch ();
  $comic->issueLookup ( $comic_id );
  $series_id = $comic->series_id;
  
  if (!isset($userID)) {
    $userID=NULL;
  }
  $comic->seriesInfo ( $series_id, $userID );
  // Required values
  // Standardizes values for common variables
  if (isset($comic->series_name) && isset($comic->series_vol) && isset($comic->issue_number) && isset($comic->publisherName)) {
    $series_name = $comic->series_name;
    $series_vol = $comic->series_vol;
    $series_id = $comic->series_id;
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

  if (isset($comic->custStoryName) && $comic->custStoryName != '') {
    $story_name = $comic->custStoryName;
  } elseif ($comic->story_name != '') {
    $story_name = $comic->story_name;
  } else {
    $story_name = '';
  }
  
  $script = $comic->script;
  $pencils = $comic->pencils;
  $colors = $comic->colors;
  $inks = $comic->inks;
  $letters = $comic->letters;
  $editing = $comic->editing;
  $coverArtist = $comic->coverArtist;

?>
<section data-module="single_comic" data-series-id="<?php echo $series_id; ?>" data-comic-id="<?php echo $comic_id; ?>">
  <header class="row headline">
    <div class="col-xs-12 col-md-7 col-lg-8">
      <h2><a href="/issues.php?series_id=<?php echo $series_id; ?>"><?php echo $series_name . "</a> #" . $issue_num; ?></h2>
    </div>
    <div class="col-xs-12 col-md-5 col-lg-4 series-meta">
      <ul class="nolist row">
        <?php if ($publisherName) { echo '<li class="col-xs-6 issue-publisher"><a href="/publisher.php?pid=' . $publisherID . '" class="logo-' . $publisherShort .' sm-logo">' . $publisherName . '</a></li>'; } ?>
        <?php if ($comic->release_date) { ?>
          <li class="col-xs-6 release-date"><?php echo $release_dateShort; ?></li>
        <?php } ?>
      </ul>
    </div>
  </header>
  <div class="row">
    <div class="col-xs-12 hidden-md hidden-lg mobile-issue-image">
      <div class="issue-image">
        <img src="<?php echo $comic->cover_image; ?>" alt="cover" class="img-responsive" />
      </div>
    </div>
    <div class="col-md-8 issue-content">
      <?php if ($login->isUserLoggedIn () == true) { ?>
      <div class="manage-comic-container">
        <div class="text-center">
          <a href="/comic.php?comic_id=<?php echo $comic->comic_id; ?>&type=edit" class="btn btn-sm btn-warning" title="Edit the details of this issue"><i class="fa fa-fw fa-pencil-square-o"></i> <span class="hidden-xs hidden-sm">Edit</span></a>
          <button data-toggle="modal" data-target="#deleteModal" class="btn btn-sm btn-danger" title="Delete this issue from your collection"><i class="fa fa-fw fa-trash"></i> <span class="hidden-xs hidden-sm">Delete</span></button>
        </div>
      </div>
      <?php } ?>
      <div class="issue-story"><h4><?php echo $story_name; ?></h4></div>
      <div class="issue-description">
        <?php echo $plot; ?>
      </div>
      <div class="disqus-block hidden-xs hidden-sm">
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
      <div class="issue-image hidden-xs hidden-sm">
        <img src="<?php echo $comic->cover_image; ?>" alt="cover" class="img-responsive" />
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
          <div class="<?php if ($script) { ?>col-xs-6<?php } else { ?>col-xs-12<?php } ?> credit-writer">
            <h3>Script</h3>
            <?php echo $script; ?>
          </div>
          <?php } ?>
          <?php if ($pencils) { ?>
          <div class="<?php if ($script) { ?>col-xs-6<?php } else { ?>col-xs-12<?php } ?> credit-artist">
            <h3>Pencils</h3>
            <?php echo $pencils; ?>
          </div>
          <?php } ?> 
        </div> 
          
        <div class="row">
          <?php if ($colors) { ?>
          <div class="<?php if ($letters && $inks) { ?>col-xs-4<?php } else { ?>col-xs-6<?php } ?> credit-inker">
            <h3>Colors</h3>
            <?php echo $colors; ?>
          </div>
          <?php } ?>
          <?php if ($inks) { ?>
          <div class="<?php if ($colors && $letters) { ?>col-xs-4<?php } else { ?>col-xs-6<?php } ?> credit-inks">
            <h3>Inks</h3>
            <?php echo $inks; ?>
          </div>
          <?php } ?>
          <?php if ($letters) { ?>     
          <div class="<?php if ($colors && $inks) { ?>col-xs-4<?php } else { ?>col-xs-6<?php } ?> credit-letters">
            <h3>Letters</h3>
            <?php echo $letters; ?>
          </div>
          <?php } ?>
        </div>
        <div class="row">
          <?php if ($editing) { ?> 
          <div class="<?php if ($coverArtist) { ?>col-xs-6<?php } else { ?>col-xs-12<?php } ?> credit-editor">
            <h3>Editing</h3>
            <?php echo $editing; ?>
          </div>
          <?php } ?>
          <?php if ($coverArtist) { ?>
          <div class="<?php if ($editing) { ?>col-xs-6<?php } else { ?>col-xs-12<?php } ?> credit-cover">
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
</section>
<?php if ($login->isUserLoggedIn () == true) { ?>
<section data-module="delete_modal" class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="" name="deleteform" class="form-horizontal">
        <header class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-logo center-block text-center" id="loginFormModalLabel"><img src="../assets/logo.svg" alt="POW! Comic Book Manager" />Comic Book Manager</h4>
        </header>
        <div class="modal-body">
          <h5>Are you sure you want to remove this issue from your collection?</h5>
          <p>Any custom images and details will be removed.</p>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="comic_id" value="<?php echo $comic_id; ?>" />
          <input type="hidden" name="user_id" value="<?php echo $userID; ?>" />
          <input type="hidden" name="delete" value="true" />
          <button class="btn btn-lg btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
          <button type="submit" name="delete" class="btn btn-lg btn-success form-submit"><span class="icon-loading"><i class="fa fa-fw fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-fw fa-check"></i> Delete</span></button>
        </div>
      </form>
    </div>
  </div>
</section>
<?php } ?>