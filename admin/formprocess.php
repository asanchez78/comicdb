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
        $series_vol = $seriesLookup->seriesStartYear;
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
      // ADD SINGLE ISSUE: Part one of the single issue process. Passes basic information from user dropdown to begin search.
      case 'issue-add':
        $issueAdd = true;
        $series_id = filter_input ( INPUT_POST, 'series_id' );
        $issue_number = filter_input ( INPUT_POST, 'issue_number' );
        $comic = new comicSearch();
        $comic->seriesInfo($series_id, $userID);
        $cvVolumeID = $comic->cvVolumeID;

        if (isset($comic->publisherName)) {
          $publisherName = $comic->publisherName;
          $publisherShort = $comic->publisherShort;
        } else {
          $messageNum = 60;
        }
        $wiki = new wikiQuery;
        $wiki->issueSearch($cvVolumeID, $issue_number);
        $searchResults = $wiki->searchResults;
        if ($searchResults != false) {
          $series_name = $comic->series_name;
          $series_vol = $comic->series_vol;
          $story_name = $wiki->storyName;
          $plot = $wiki->synopsis;
          $release_date = $wiki->releaseDate;
          $release_dateShort = DateTime::createFromFormat('Y-m-d', $wiki->releaseDate)->format('M Y');
          $release_dateLong = DateTime::createFromFormat('Y-m-d', $wiki->releaseDate)->format('M d, Y');
          $script = $wiki->script;
          $pencils = $wiki->pencils;
          $colors = $wiki->colors;
          $letters = $wiki->letters;
          $editing = $wiki->editing;
          $coverArtist = $wiki->coverArtist;
          $coverURL = $wiki->coverURL;
          $coverFile = $wiki->coverFile;
        } else {
          $messageNum = 65;
        }
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
        $custPlot = addslashes ( filter_input ( INPUT_POST, 'custPlot' ) );
        $cover_image = filter_input ( INPUT_POST, 'cover_image' );
        $cover_image_file = filter_input ( INPUT_POST, 'cover_image_file' );
        $originalPurchase = filter_input ( INPUT_POST, 'originalPurchase' );
        $art = filter_input ( INPUT_POST, 'art' );
        $script = filter_input ( INPUT_POST, 'script' );
        $colors = filter_input ( INPUT_POST, 'colors' );
        $letters = filter_input ( INPUT_POST, 'letters' );
        $editor = filter_input ( INPUT_POST, 'editor' );
        $coverArtist = filter_input ( INPUT_POST, 'coverArtist' );

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

        // Checks if the plot has been modified by the user
        if ($custPlot == $plot) {
          // If it's the same as the API data, then clear it out.
          $custPlot = '';
        }

        $comic = new comicSearch();
        $comic->issueCheck($series_id, $issue_number);

        if ($comic->issueExists == 1) {
          // Checks if the new issues being added are already in the master database. If so, then just adds to the user table.
          $sql = "INSERT INTO users_comics (user_id, comic_id, originalPurchase, custPlot) VALUES ('$ownerID', '$comic->comic_id', '$originalPurchase', $custPlot)";
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
            $sql_user = "INSERT INTO users_comics (user_id, comic_id, originalPurchase, custPlot) VALUES ('$ownerID', '$comic_id', '$originalPurchase', '$custPlot')";
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
      // ADD RANGE: Submit all issues in input range using the first ComicVine API entry as the result.
      case 'range':
        $ownerID = $_SESSION['user_id'];
        $series_id = filter_input ( INPUT_POST, 'series_id' );
        $first_issue = filter_input ( INPUT_POST, 'first_issue' );
        $last_issue = filter_input ( INPUT_POST, 'last_issue' );
        $originalPurchase = filter_input ( INPUT_POST, 'originalPurchase' );
        $release_date = filter_input(INPUT_POST, 'release_date');
        $releaseDateArray = explode("-", $release_date);

        $comic = new comicSearch ();
        $comic->seriesInfo ($series_id, $userID);
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
              $rangeSearch = true;
            } else {
              $messageNum = 61;
              $rangeSearch = false;
              $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
            }
          } else {
            $wiki = new wikiQuery();
            $wiki->issueSearch($cvVolumeID, $issue_number);
            $release_date = $wiki->releaseDate;
            $plot = addslashes( $wiki->synopsis );
            $story_name = addslashes( $wiki->storyName );
            $cover_image = $wiki->coverURL;
            $script = $wiki->script;
            $pencils = $wiki->pencils;
            $colors = $wiki->colors;
            $letters = $wiki->letters;
            $editing = $wiki->editing;
            $coverArtist = $wiki->coverArtist;

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
                $sqlMessage = '<strong class="text-success">Success</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
              } else {
                $rangeSearch == false;
                $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
              }
              $messageNum = 4;
            } else {
              $messageNum = 51;
              $rangeSearch == false;
              $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
            }
          }
          $addedList = '<h3>#' . $first_issue . ' - ' . $last_issue . '</h3>';
        }
        break;
      case 'csv':
        break;
      case 'edit':
        $ownerID = $_SESSION['user_id'];
        $comic_id = filter_input(INPUT_GET, 'comic_id');
        $comic = new comicSearch();
        $comic->issueLookup($comic_id);
        $series_id = $comic->series_id;
        $comic->seriesInfo ($series_id);
        $issue_number = $comic->issue_number;
        $cvVolumeID = $comic->cvVolumeID;

        $wiki = new wikiQuery ();
        $wiki->issueSearch($cvVolumeID, $issue_number);

        $series_name = $comic->series_name;
        $series_vol = $comic->series_vol;
        
        $originalPurchase = $comic->originalPurchase;
        $release_date = $wiki->releaseDate;
        $release_dateShort = DateTime::createFromFormat('Y-m-d', $release_date)->format('M Y');
        $release_dateLong = DateTime::createFromFormat('Y-m-d', $release_date)->format('M d, Y');

        $story_name = $wiki->storyName;
        $plot = $wiki->synopsis;
        $custPlot = $comic->custPlot;
        $coverURL = $comic->cover_image;
        if (isset($comic->publisherName)) {
          $publisherName = $comic->publisherName;
          $publisherShort = $comic->publisherShort;
        } else {
          $messageNum = 60;
        }
        $script = $wiki->script;
        $pencils = $wiki->pencils;
        $colors = $wiki->colors;
        $letters = $wiki->letters;
        $editing = $wiki->editing;
        $coverArtist = $wiki->coverArtist;
        $coverFile = $wiki->coverFile;
        break;
      case 'edit-save':
        $issueEdit = true;
        $ownerID = $_SESSION['user_id'];
        $comic_id = filter_input ( INPUT_POST, 'comic_id' );
        $released_date = filter_input ( INPUT_POST, 'released_date' );
        $story_name = addslashes ( filter_input ( INPUT_POST, 'story_name' ) );
        $plot = addslashes ( filter_input ( INPUT_POST, 'plot' ) );
        $custPlot = addslashes ( filter_input ( INPUT_POST, 'custPlot' ) );
        $cover_image = filter_input ( INPUT_POST, 'cover_image' );
        $cover_image_file = filter_input ( INPUT_POST, 'cover_image_file' );
        $originalPurchase = filter_input ( INPUT_POST, 'originalPurchase' );
        $art = filter_input ( INPUT_POST, 'art' );
        $script = filter_input ( INPUT_POST, 'script' );
        $colors = filter_input ( INPUT_POST, 'colors' );
        $letters = filter_input ( INPUT_POST, 'letters' );
        $editor = filter_input ( INPUT_POST, 'editor' );
        $coverArtist = filter_input ( INPUT_POST, 'coverArtist' );

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

        // Checks if the plot has been modified by the user
        if ($custPlot == $plot) {
          // If it's the same as the API data, then clear it out.
          $custPlot = '';
        }

        $sql = "UPDATE comics SET story_name='$story_name', release_date='$release_date', plot='$plot', cover_image='images/$cover_image_file', wikiUpdated=1 WHERE comic_id='$comic_id'";
        if (mysqli_query ( $connection, $sql )) {
          $sql_user = "UPDATE users_comics SET originalPurchase='$originalPurchase', custPlot='$custPlot' WHERE comic_id='$comic_id', user_id='$ownerID'";
          if (mysqli_query ( $connection, $sql_user )) {
            $messageNum = 5;
            $sqlMessage = '<strong class="text-success">Success</strong>: Issue updated ' . $sql_user;
          } else {
            $sqlMessage = '<strong class="text-warning">Error</strong>: ' . $sql_user . '<br>' . mysqli_error ( $connection );
            $messageNum = 63;
          }
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