<?php
  require_once(__ROOT__.'/classes/wikiFunctions.php');
  $ownerID = __userID__;
  $series_id = filter_input ( INPUT_POST, 'series_id' );
  $filtered_issue_list = filter_input ( INPUT_POST, 'issueList' );

  // Removes any spaces in the comma values
  $issue_list =  explode ( ",", strtr($filtered_issue_list, array(' ' => '')));
  $originalPurchase = filter_input ( INPUT_POST, 'listOriginalPurchase' );
  $comic = new comicSearch ();
  $comic->seriesInfo ($series_id, $ownerID);
  $series_name = $comic->series_name;
  $series_vol = $comic->series_vol;
  $cvVolumeID = $comic->cvVolumeID;

  foreach ( $issue_list as $issue_number ) {
    $comic->issueCheck($series_id, $issue_number);
    if ($comic->issueExists == 1) {
      $sql = "INSERT INTO users_comics (user_id, comic_id, originalPurchase)
              VALUES ('$ownerID', '$comic->comic_id', '$originalPurchase')";
      if (mysqli_query ( $connection, $sql )) {
        $listSearch = true;
      } else {
        $messageNum = 61;
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
        $sql = "INSERT INTO users_comics (user_id, comic_id, originalPurchase) VALUES ('$ownerID', '$comic_id', '$originalPurchase')";
        if (mysqli_query ( $connection, $sql )) {
          // Add creators to creators table
          $comic->insertCreators($comic_id, $creatorsList);
          $sqlMessage = '<strong class="text-success">Success</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
          $listSearch = true;
          $messageNum = 8;
        } else {
          $listSearch = false;
          $messageNum = 51;
          $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
        }
      } else {
        $messageNum = 51;
        $listSearch = false;
        $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
      }
    }
  }
?>