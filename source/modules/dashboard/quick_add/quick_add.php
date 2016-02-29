<?php
  $quickAddIssue = filter_input ( INPUT_POST, 'quickAddIssue' );

  if ($quickAddIssue) {
    require_once(__ROOT__.'/classes/wikiFunctions.php');
    $series_id = filter_input ( INPUT_POST, 'series_id' );
    $issue_number = filter_input ( INPUT_POST, 'issue_number' );
    $release_date = filter_input(INPUT_POST, 'release_date');
    $releaseDateArray = explode("-", $release_date);

    $comic = new comicSearch ();
    $comic->seriesInfo ($series_id, $userID);
    $series_name = $comic->series_name;
    $series_vol = $comic->series_vol;
    $cvVolumeID = $comic->cvVolumeID;
    $comic->issueCheck($series_id, $issue_number);
    
    if ($comic->issueExists == 1) {
      $comic_id = $comic->comic_id;
      $sql = "INSERT INTO users_comics (user_id, comic_id, originalPurchase) VALUES ('$userID', '$comic_id', '0')";
      if (mysqli_query ( $connection, $sql )) {
        $messageNum = 1;
        $quickAddSuccess = true;
      } else {
        $messageNum = 51;
        $quickAddSuccess = false;
        $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
      }
    } else {
      $wiki = new wikiQuery();
      $wiki->issueSearch($cvVolumeID, $issue_number, $series_vol);
      $release_date = $wiki->releaseDate;
      $plot = addslashes( $wiki->synopsis );
      $story_name = addslashes( $wiki->storyName );
      $cover_image = $wiki->coverURL;
      $coverThumbURL = $wiki->coverThumbURL;
      $coverSmallURL = $wiki->coverSmallURL;
      $script = $wiki->script;
      $pencils = $wiki->pencils;
      $colors = $wiki->colors;
      $letters = $wiki->letters;
      $editing = $wiki->editing;
      $coverArtist = $wiki->coverArtist;
      $creatorsList = $wiki->creatorsList;

      if ($cover_image == 'assets/nocover.jpg') {
        $cover_image_file = 'assets/nocover.jpg';
      } else {
        $cover_image_file = $wiki->coverFile;
        $path = __ROOT__ . '/' . $cover_image_file;
        $wiki->downloadFile ( $cover_image, $path );
      }

      if ($coverSmallURL == 'assets/nocover.jpg') {
        $noSmall=1;
      } else {
        $path = __ROOT__ . '/' . $wiki->coverSmallFile;
        $wiki->downloadFile ( $coverSmallURL, $path );
      }

      if ($coverThumbURL == 'assets/nocover.jpg') {
        $noThumb=1;
      } else {
        $path = __ROOT__ . '/' . $wiki->coverThumbFile;
        $wiki->downloadFile ( $coverThumbURL, $path );
      }

      $sql = "INSERT INTO comics (series_id, issue_number, story_name, release_date, plot, cover_image, wikiUpdated) VALUES ('$series_id', '$issue_number', '$story_name', '$release_date', '$plot', '$cover_image_file', 1)";
      if (mysqli_query ( $connection, $sql )) {
        $comic_id = mysqli_insert_id ( $connection );
        // Add creators to creators table
        $comic->insertCreators($comic_id, $creatorsList);
        // Add to user_comics table
        $sql = "INSERT INTO users_comics (user_id, comic_id, originalPurchase) VALUES ('$userID', '$comic_id', '0')";
        if (mysqli_query ( $connection, $sql )) {
          $sqlMessage = '<strong class="text-success">Success</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
          $quickAddSuccess = true;
          $messageNum = 1;
        } else {
          $quickAddSuccess = false;
          $messageNum = 51;
          $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
        }
      }
    }
  }
?>

<div data-module="quick_add">
  <form method="post" action="" class="quick-add-form">
    <h3>Quick Add</h3>
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
      <input name="issue_number" class="form-control" type="number" pattern="[0-9]*" inputmode="numeric" autocomplete="off" size="3" maxlength="4" value="" required aria-required="true" />
    </div>
    <input type="hidden" name="quickAddIssue" value="true" />
    <button type="submit" name="submit" class="btn btn-danger form-submit"><span class="icon-loading"><i class="fa fa-fw fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-plus"></i> Add</span></button>
  </form>
</div>