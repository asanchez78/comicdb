<?php
  require_once(__ROOT__.'/classes/wikiFunctions.php');
  $ownerID = __userID__;
  $series_name = filter_input ( INPUT_POST, 'series_name' );
  $series_vol = filter_input(INPUT_POST, 'series_vol');
  $series_id = filter_input ( INPUT_POST, 'series_id' );
  $issue_number = filter_input ( INPUT_POST, 'issue_number' );
  $wiki_id = filter_input ( INPUT_POST, 'wiki_id' );
  $released_date = filter_input ( INPUT_POST, 'released_date' );
  $story_name = addslashes ( filter_input ( INPUT_POST, 'story_name' ) );
  $custStoryName = addslashes ( filter_input ( INPUT_POST, 'custStoryName' ) );
  $plot = addslashes ( filter_input ( INPUT_POST, 'plot' ) );
  $custPlot = addslashes ( filter_input ( INPUT_POST, 'custPlot' ) );
  $cover_image = filter_input ( INPUT_POST, 'cover_image' );
  $cover_image_file = filter_input ( INPUT_POST, 'cover_image_file' );
  $coverThumbURL = filter_input ( INPUT_POST, 'coverThumbURL' );
  $coverThumbFile = filter_input ( INPUT_POST, 'coverThumbFile' );
  $coverSmallURL = filter_input ( INPUT_POST, 'coverSmallURL' );
  $coverSmallFile = filter_input ( INPUT_POST, 'coverSmallFile' );
  $originalPurchase = filter_input ( INPUT_POST, 'singleOriginalPurchase' );
  $creatorsList = filter_input ( INPUT_POST, 'creatorsList' );
  $quantity = filter_input ( INPUT_POST, 'quantity' );
  $issueCondition = filter_input ( INPUT_POST, 'issueCondition' );

  // Formats date
  if ($released_date == 0000 - 00 - 00) {
    $release_date = "";
  } else {
    $release_date = $released_date;
  }

  // Downloads the cover from Wikia and stores is locally, otherwise show the nocover.jpg image.
  if ($cover_image == 'assets/nocover.jpg') {
    $cover_image_file = 'assets/nocover.jpg';
  } else {
    $path = __ROOT__ . '/' . $cover_image_file;
    $wiki = new wikiQuery();
    $wiki->downloadFile ( $cover_image, $path );
  }

  if ($coverThumbFile == 'assets/nocover.jpg') {
    $noThumb=1;
  } else {
    $path = __ROOT__ . '/' . $coverThumbFile;
    $wiki->downloadFile ( $coverThumbURL, $path );
  }

  if ($coverSmallFile == 'assets/nocover.jpg') {
    $noSmall=1;
  } else {
    $path = __ROOT__ . '/' . $coverSmallFile;
    $wiki->downloadFile ( $coverSmallURL, $path );
  }

  // Checks if the plot has been modified by the user
  if ($custPlot == $plot) {
    // If it's the same as the API data, then clear it out.
    $custPlot = '';
  }

  if (!isset($issueCondition) && $issueCondition !== '') {
    $issueCondition = 'Mint';
  }
  $comic = new comicSearch();
  $comic->issueCheck($series_id, $issue_number);

  if ($comic->issueExists == 1) {
    // Checks if the new issues being added are already in the master database. If so, then just adds to the user table.
    $comic_id = $comic->comic_id;
    $sql = "INSERT INTO users_comics (user_id, comic_id, quantity, originalPurchase, custPlot, custStoryName, issueCondition) VALUES ('$ownerID', '$comic_id', '$quantity', '$originalPurchase', '$custPlot', '$custStoryName', '$issueCondition')";
    if (mysqli_query ( $connection, $sql )) {
      $messageNum = 1;
    } else {
      $sqlMessage = '<strong class="text-warning">Error:</strong> ' . $sql . '<br>' . mysqli_error ( $connection );
      $messageNum = 51;
    }
  } else {
    // Comic does not exist in the master table. Add all details and then associate with the user table.
    $sql = "INSERT INTO comics (series_id, issue_number, story_name, release_date, plot, cover_image, wikiUpdated)
    VALUES ('$series_id', '$issue_number', '$story_name', '$release_date', '$plot', '$cover_image_file', 1)";
    if (mysqli_query ( $connection, $sql )) {
      $comic_id = mysqli_insert_id($connection);
      // Add creators to creators table
      $comic->insertCreators($comic_id, $creatorsList);
      // Add to user_comics table
      $sql_user = "INSERT INTO users_comics (user_id, comic_id, quantity, originalPurchase, custPlot, custStoryName, issueCondition) VALUES ('$ownerID', '$comic_id', '$quantity', '$originalPurchase', '$custPlot', '$custStoryName', '$issueCondition')";
      if (mysqli_query ( $connection, $sql_user )) {
        $messageNum = 1;
        $sqlMessage = '<strong class="text-success">Success</strong>: Issue did not exist in the current database. Issue added to database and users collection.';
      } else {
        $sqlMessage = '<strong class="text-warning">Error</strong>: ' . $sql_user . '<br>' . mysqli_error ( $connection );
      }
    } else {
      $sqlMessage = '<strong class="text-warning">Error</strong>: ' . $sql . '<br>' . mysqli_error ( $connection );
    }
  }
?>