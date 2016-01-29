<?php
  $type = $_GET['type'];
  if ($type) {
    require_once(__ROOT__.'/classes/wikiFunctions.php');
    require_once(__ROOT__.'/classes/functions.php');
    switch ($type) {
      // ADD SERIES: Part one of the series process. Displays API search results.
      case 'series-search':
        $seriesSearch = true;
        $series_name = filter_input ( INPUT_POST, 'series_name' );
        $publisherID = filter_input ( INPUT_POST, 'publisherID' );
        $series_vol = filter_input ( INPUT_POST, 'series_vol' );
        $seriesSearch = new wikiQuery();
        $seriesSearch->seriesSearch("$series_name");

        break;
      // ADD SERIES: Part two of the series process. Checks the database for existing series, and then adds series to the database.
      case 'series-submit':
        $apiDetailURL = filter_input ( INPUT_POST, 'apiURL' );
        $seriesLookup = new wikiQuery();
        $seriesLookup->seriesLookup($apiDetailURL);
        $seriesSubmit = true;
        $series_name = $seriesLookup->seriesName;
        $series_vol = filter_input(INPUT_POST, 'series_vol');
        $publisherID = filter_input ( INPUT_POST, 'publisherID' );
        $sql = "INSERT INTO series (series_name, series_vol, publisherID, cvVolumeID, apiDetailURL, siteDetailURL)
                VALUES ('$series_name', '$series_vol', '$publisherID', '$seriesLookup->cvVolumeID', '$seriesLookup->apiDetailURL', '$seriesLookup->siteDetailURL')";
        if (mysqli_query ( $connection, $sql )) {
          $messageNum = 3;
          $seriesSubmitted = true;
          $sqlMessage = '<strong class="text-success">Success</strong>: <code>' . $sql . '</code>';
        } else {
          $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br /><code>' . mysqli_error ( $connection ) . $connection->errno . '</code>';
          $seriesSubmitted = false;
          if ($connection->errno == 1062) {
            $messageNum = 50;
          }
        }
        break;
      // ADD SINGLE ISSUE: Part one of the single issue process. Displays final fields and allows user to change details before adding to collection.
      case 'issue-add':
        $issueAdd = true;
        $series_id = filter_input ( INPUT_POST, 'series_id' );
        $issue_number = filter_input ( INPUT_POST, 'issue_number' );
        $seriesDetails = new comicSearch();
        $seriesDetails->seriesInfo($series_id);
        $cvVolumeID = $seriesDetails->cvVolumeID;
        $issueDetails = new wikiQuery;
        $issueDetails->issueSearch($cvVolumeID, $issue_number);
        $series_name = $seriesDetails->series_name;
        $series_vol = $seriesDetails->series_vol;
        $wiki_id = filter_input (INPUT_POST, 'wiki_id');
        $publisherAPI = filter_input( INPUT_POST, 'publisherAPI' );
        break;
      // ADD SINGLE ISSUE: Part two of the single issue process. Checks the database for existing comics, and then adds all to the user's database. 
      case 'issue-submit':
        $issueSubmit = true;
        $ownerID = $_SESSION['user_id'];
        $series_name = filter_input ( INPUT_POST, 'series_name' );
        $series_vol = filter_input(INPUT_POST, 'series_vol');
        $series_id = filter_input ( INPUT_POST, 'series_id' );
        $issue_number = filter_input ( INPUT_POST, 'issue_number' );
        $comic_id = filter_input ( INPUT_POST, 'comic_id' );
        $wiki_id = filter_input ( INPUT_POST, 'wiki_id' );
        $released_date = filter_input ( INPUT_POST, 'released_date' );
        $story_name = addslashes ( filter_input ( INPUT_POST, 'story_name' ) );
        $plot = addslashes ( filter_input ( INPUT_POST, 'plot' ) );
        $cover_image = filter_input ( INPUT_POST, 'cover_image' );
        $cover_image_file = filter_input ( INPUT_POST, 'cover_image_file' );
        $originalPurchase = filter_input ( INPUT_POST, 'originalPurchase' );

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

        $comic = new comicSearch();
        $comic->issueCheck($series_id, $issue_number);
        if ($comic->issueExists == 1) {
          // Checks if the new issues being added are already in the master database. If so, then just adds to the user table.
          $sql = "INSERT INTO users_comics (user_id, comic_id, originalPurchase) VALUES ('$ownerID', '$comic->comic_id', '$originalPurchase')";
          $comic_id = $comic->comic_id;
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
            // Add to user_comics table
            $sql_user = "INSERT INTO users_comics (user_id, comic_id, originalPurchase) VALUES ('$ownerID', '$comic_id', '$originalPurchase')";
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
        break;
      // ADD RANGE: Submit all issues in input range using the first Wikia API entry as the result.
      case 'range':
        $rangeSearch = true;
        $ownerID = $_SESSION['user_id'];
        $series_id = filter_input ( INPUT_POST, 'series_id' );
        $first_issue = filter_input ( INPUT_POST, 'first_issue' );
        $last_issue = filter_input ( INPUT_POST, 'last_issue' );
        $originalPurchase = filter_input ( INPUT_POST, 'originalPurchase' );
        $release_date = filter_input(INPUT_POST, 'release_date');
        $releaseDateArray = explode("-", $release_date);

        $comic = new comicSearch ();
        $comic->seriesInfo ($series_id);
        $series_name = $comic->series_name;
        $series_vol = $comic->series_vol;
        $cvVolumeID = $comic->cvVolumeID;
        $addedList = '';
        foreach ( range ( $first_issue, $last_issue ) as $issue_number ) {
          $comic->issueCheck($series_id, $issue_number);
          if ($comic->issueExists == 1) {
            $sql = "INSERT INTO users_comics (user_id, comic_id, originalPurchase) VALUES ('$ownerID', '$comic->comic_id', '$originalPurchase')";
            if (mysqli_query ( $connection, $sql )) {
              $messageNum = 4;
            } else {
              $messageNum = 61;
              $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
            }
          } else {
            $wiki = new wikiQuery();
            $wiki->issueSearch($cvVolumeID, $issue_number);
            $release_date = $wiki->releaseDate;
            $plot = addslashes( $wiki->synopsis );
            $story_name = addslashes( $wiki->storyName );
            $cover_image = $wiki->coverURL;

            if ($cover_image == 'assets/nocover.jpg') {
              $cover_image_file = 'assets/nocover.jpg';
            } else {
              $cover_image_file = $wiki->coverFile;
              $path = __ROOT__ . '/' . $cover_image_file;
              $wiki->downloadFile ( $cover_image, $path );
            }
            $sql = "INSERT INTO comics (series_id, issue_number, story_name, release_date, plot, cover_image, wikiUpdated) VALUES ('$series_id', '$issue_number', '$story_name', '$release_date', '$plot', '$cover_image_file', 1)";
            if (mysqli_query ( $connection, $sql )) {
              $comic_id = mysqli_insert_id ( $connection );
              $sql = "INSERT INTO users_comics (user_id, comic_id, originalPurchase) VALUES ('$ownerID', '$comic_id', '$originalPurchase')";
              if (mysqli_query ( $connection, $sql )) {
              } else {
                $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
              }
              $messageNum = 4;
            } else {
              $messageNum = 51;
              $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
            }
          }
          $addedList .= '<h3> ' . $series_name . '<small>(Vol' . $series_vol . ')</small> #' . $issue_number . '</h3>';
        }
        break;
      case 'csv':
        break;
      case 'edit':
        $comic_id = filter_input(INPUT_GET, 'comic_id');
        $wiki_id = filter_input ( INPUT_GET, 'wiki_id' );
        $wiki = new wikiQuery ();
        $comic = new comicSearch();
        $comic->issueLookup($comic_id);
        $series_id = $comic->series_id;
        $comic->seriesInfo ($series_id);
        $publisherAPI = $comic->publisherShort;
        $wiki->comicCover ($publisherAPI, $wiki_id );
        $wiki->comicDetails ($publisherAPI, $wiki_id );

        $series_id = $comic->series_id;
        $series_name = $comic->series_name;
        $series_vol = $comic->series_vol;
        $issue_number = $comic->issue_number;
        $originalPurchase = $comic->originalPurchase;
        $release_date = $comic->release_date;
        $story_name = $comic->story_name;
        break;
      case 'edit-save':
        $sql = "UPDATE comics SET series_id='$series_id', issue_number='$issue_number', story_name='$story_name', release_date='$release_date', plot='$plot', cover_image='images/$cover_image_file', wikiUpdated=1 WHERE comic_id='$comic_id'";
        if (mysqli_query ( $connection, $sql )) {
            $messageNum = 5;
            $sqlMessage = '<strong class="text-success">Success</strong>: ' . $sql;
        } else {
            $messageNum = 62;
            $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
        }
        break;
    }
  } else {
    // If no type is detected, something is wrong. Display general error message.
    $submitted = 'no';
    $messageNum = 99;
  }
?>