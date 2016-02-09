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
          $seriesDir = __ROOT__.'/images/' . preg_replace('/[^a-z0-9]+/i', '_', $series_name) . '-v' . $series_vol;
          if (!file_exists($seriesDir)) {
            mkdir($seriesDir, 0777, true);
          }
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
        $series_vol = $comic->series_vol;

        if (isset($comic->publisherName)) {
          $publisherName = $comic->publisherName;
          $publisherShort = $comic->publisherShort;
        } else {
          $messageNum = 60;
        }
        $wiki = new wikiQuery;
        $wiki->issueSearch($cvVolumeID, $issue_number, $series_vol);
        $searchResults = $wiki->searchResults;
        if ($searchResults != false) {
          $series_name = $comic->series_name;
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
          $creatorsList = $wiki->creatorsList;
        } else {
          $messageNum = 65;
        }
        break;
      // ADD SINGLE ISSUE: Part two of the single issue process. Checks the database for existing comics, and then adds all to the user's database. 
      case 'issue-submit':
        $issueSubmit = true;
        $ownerID = __userID__;
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
        $originalPurchase = filter_input ( INPUT_POST, 'singleOriginalPurchase' );
        $creatorsList = filter_input ( INPUT_POST, 'creatorsList' );

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
          $sql = "INSERT INTO users_comics (user_id, comic_id, originalPurchase, custPlot) VALUES ('$ownerID', '$comic->comic_id', '$originalPurchase', '$custPlot')";
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
            // Add creators to creators table
            $comic->insertCreators($comic_id, $creatorsList);
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
        $ownerID = __userID__;
        $series_id = filter_input ( INPUT_POST, 'series_id' );
        $first_issue = filter_input ( INPUT_POST, 'first_issue' );
        $last_issue = filter_input ( INPUT_POST, 'last_issue' );
        $originalPurchase = filter_input ( INPUT_POST, 'rangeOriginalPurchase' );
        $release_date = filter_input(INPUT_POST, 'release_date');
        $releaseDateArray = explode("-", $release_date);

        $comic = new comicSearch ();
        $comic->seriesInfo ($series_id, $ownerID);
        $series_name = $comic->series_name;
        $series_vol = $comic->series_vol;
        $cvVolumeID = $comic->cvVolumeID;
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
            $wiki->issueSearch($cvVolumeID, $issue_number, $series_vol);
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
            $creatorsList = $wiki->creatorsList;

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
              // Add creators to creators table
              $comic->insertCreators($comic_id, $creatorsList);
              // Add to user_comics table
              $sql = "INSERT INTO users_comics (user_id, comic_id, originalPurchase) VALUES ('$ownerID', '$comic_id', '$originalPurchase')";
              if (mysqli_query ( $connection, $sql )) {
                $sqlMessage = '<strong class="text-success">Success</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
                $rangeSearch = true;
                $messageNum = 4;
              } else {
                $rangeSearch = false;
                $messageNum = 51;
                $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
              }
            } else {
              $messageNum = 51;
              $rangeSearch = false;
              $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
            }
          }
          $addedList = '<h3>#' . $first_issue . ' - ' . $last_issue . '</h3>';
        }
        break;
      case 'csv':
        $ownerID = __userID__;
        $series_id = filter_input ( INPUT_POST, 'series_id' );
        $filtered_issue_list = filter_input ( INPUT_POST, 'issueList' );
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
        break;
      case 'edit':
        $ownerID = __userID__;
        $updatedSet = '';
        $comic_id = filter_input(INPUT_GET, 'comic_id');
        $comic = new comicSearch();
        $wiki = new wikiQuery ();
        $comic->issueLookup($comic_id);
        $series_id = $comic->series_id;
        $comic->seriesInfo ($series_id, $userID);
        $issue_number = $comic->issue_number;
        $cvVolumeID = $comic->cvVolumeID;
        $series_vol = $comic->series_vol;
        $series_name = $comic->series_name;
        $originalPurchase = $comic->originalPurchase;
        $quantity = $comic->issue_quantity;
        
        // Wiki updates automatically if field is blank
        $wiki->issueSearch($cvVolumeID, $issue_number);
        $creatorsList = $wiki->creatorsList;
        
        // Release Date
        if ($comic->release_date != '') {
          if ($wiki->releaseDate !== '' && $wiki->releaseDate == $comic->release_date) {
            $releaseDate = $comic->release_date;
          } else {
            $releaseDate = $wiki->releaseDate;
            $updatedSet .= 'Release Date; ';
          }
          
        } else {
          if ($wiki->releaseDate !== '') {
            $releaseDate = $wiki->releaseDate;
            $updatedSet .= 'Release Date; ';
          }
        }
        $release_dateShort = DateTime::createFromFormat('Y-m-d', $releaseDate)->format('M Y');
        $release_dateLong = DateTime::createFromFormat('Y-m-d', $releaseDate)->format('M d, Y');

        // Plot and Custom Plot
        if ($comic->plot !== '') {
          if ($wiki->synopsis !== '' && $wiki->synopsis == $comic->plot) {
            $plot = $comic->plot;
          } else {
            $plot = $wiki->synopsis;
            $updatedSet .= 'Plot; ';
          }
        } else {
          if ($wiki->synopsis !== '') {
            $plot = $wiki->synopsis;
            $updatedSet .= 'Plot; ';
          }
        }

        if ($comic->custPlot != '') {
          if ($comic->custPlot != $plot) {
            $custPlot = $comic->custPlot;
          }
        } else {
          $custPlot = '';
        }

        // Story Name and Custom Story Name
        if ($comic->story_name !== '') {
          if ($wiki->storyName !== '' && $wiki->storyName == $comic->story_name) {
            $story_name = $comic->story_name;
          } else {
            $story_name = $wiki->storyName;
            $updatedSet .= 'Story Name; ';
          }
        } else {
          if ($wiki->storyName !== '') {
            $story_name = $wiki->storyName;
            $updatedSet .= 'Story Name; ';
          }
        }

        if ($comic->custStoryName != '') {
          if ($comic->custStoryName != $story_name) {
            $custStoryName = $comic->custStoryName;
          }
        } else {
          $custStoryName = '';
        }

        // Creators
        if ($comic->script != '') {
          if ($wiki->script !== '' && $wiki->script == $comic->script) {
            $script = $comic->script;
          } else {
            $script = $wiki->script;
            $updatedSet .= 'Script; ';
          }
        } else {
          if ($wiki->script !== '') {
            $script = $wiki->script;
            $updatedSet .= 'Script; ';
          }
        }

        // Pencils
        if ($comic->pencils != '') {
          if ($wiki->pencils !== '' && $wiki->pencils == $comic->pencils) {
            $pencils = $comic->pencils;
          } else {
            $pencils = $wiki->pencils;
            $updatedSet .= 'Pencils; ';
          }
        } else {
          if ($wiki->pencils !== '') {
            $pencils = $wiki->pencils;
            $updatedSet .= 'Pencils; ';
          }
        }

        // Colors
        if ($comic->colors != '') {
          if ($wiki->colors !== '' && $wiki->colors == $comic->colors) {
            $colors = $comic->colors;
          } else {
            $colors = $wiki->colors;
            $updatedSet .= 'Colors; ';
          }
        } else {
          if ($wiki->colors !== '') {
            $colors = $wiki->colors;
            $updatedSet .= 'Colors; ';
          }
        }

        // Inks
        if ($comic->inks != '') {
          if ($wiki->inks !== '' && $wiki->inks == $comic->inks) {
            $inks = $comic->inks;
          } else {
            $inks = $wiki->inks;
            $updatedSet .= 'Inks; ';
          }
        } else {
          if ($wiki->inks !== '') {
            $inks = $wiki->inks;
            $updatedSet .= 'Inks; ';
          }
        }

        // Letters
        if ($comic->letters != '') {
          if ($wiki->letters !== '' && $wiki->letters == $comic->letters) {
            $letters = $comic->letters;
          } else {
            $letters = $wiki->letters;
            $updatedSet .= 'Letters; ';
          }
        } else {
          if ($wiki->letters !== '') {
            $letters = $wiki->letters;
            $updatedSet .= 'Letters; ';
          }
        }

        // Editing
        if ($comic->editing != '') {
          if ($wiki->editing !== '' && $wiki->editing == $comic->editing) {
            $editing = $comic->editing;
          } else {
            $editing = $wiki->editing;
            $updatedSet .= 'Editing; ';
          }
        } else {
          if ($wiki->editing !== '') {
            $editing = $wiki->editing;
            $updatedSet .= 'Editing; ';
          }
        }

        // CoverArtist
        if ($comic->coverArtist != '') {
          if ($wiki->coverArtist !== '' && $wiki->coverArtist == $comic->coverArtist) {
            $coverArtist = $comic->coverArtist;
          } else {
            $coverArtist = $wiki->coverArtist;
            $updatedSet .= 'Cover Artist; ';
          }
        } else {
          if ($wiki->coverArtist !== '') {
            $coverArtist = $wiki->coverArtist;
            $updatedSet .= 'Cover Artist; ';
          }
        }

        // Cover Image
        if ($comic->cover_image != '') {
          $coverURL = $comic->cover_image;
          $coverFile = $comic->cover_image;
        } else {
          $coverURL = $wiki->coverURL;
          $coverFile = $wiki->coverFile;
          $updatedSet .= 'Cover Artist; ';
        }
        
        // Publisher
        if (isset($comic->publisherName)) {
          $publisherName = $comic->publisherName;
          $publisherShort = $comic->publisherShort;
        } else {
          $messageNum = 60;
        }
        break;
      case 'edit-save':
        $issueEdit = true;
        $ownerID = __userID__;
        $comic_id = filter_input ( INPUT_POST, 'comic_id' );
        $released_date = filter_input ( INPUT_POST, 'released_date' );
        $story_name = addslashes ( filter_input ( INPUT_POST, 'story_name' ) );
        $custStoryName = addslashes ( filter_input ( INPUT_POST, 'custStoryName' ) );
        $quantity = addslashes ( filter_input ( INPUT_POST, 'quantity' ) );
        $plot = addslashes ( filter_input ( INPUT_POST, 'plot' ) );
        $custPlot = addslashes ( filter_input ( INPUT_POST, 'custPlot' ) );
        $cover_image = filter_input ( INPUT_POST, 'cover_image' );
        $cover_image_file = filter_input ( INPUT_POST, 'cover_image_file' );
        $originalPurchase = filter_input ( INPUT_POST, 'originalPurchase' );
        $creatorsList = filter_input ( INPUT_POST, 'creatorsList' );
        $updatedSet = filter_input ( INPUT_POST, 'updatedSet' );

        // Formats date
        if ($released_date == 0000 - 00 - 00) {
          $release_date = "";
        } else {
          $release_date = $released_date;
        }

        // Checks if the plot has been modified by the user
        if ($custStoryName == $story_name) {
          // If it's the same as the API data, then clear it out.
          $custStoryName = '';
        }

        // Checks if the plot has been modified by the user
        if ($custPlot == $plot) {
          // If it's the same as the API data, then clear it out.
          $custPlot = '';
        }

        $sql = "UPDATE comics SET story_name='$story_name', release_date='$release_date', plot='$plot', cover_image='$cover_image_file', wikiUpdated=1 WHERE comic_id='$comic_id'";
        if (mysqli_query ( $connection, $sql )) {
          $sqlMessage = '<strong class="text-success">Comic Database Update Success</strong>: Issue updated<br /><code>' . $sql . '</code><br /><br />';
          // Add creators to creators table
          $comic->insertCreators($comic_id, $creatorsList);
          $sql_user = "UPDATE users_comics SET quantity='$quantity', originalPurchase='$originalPurchase', custPlot='$custPlot', custStoryName='$custStoryName' WHERE user_id='$ownerID' AND comic_id='$comic_id'";
          if (mysqli_query ( $connection, $sql_user )) {
            $messageNum = 5;
            $sqlMessage .= '<strong class="text-success">User Database Update Success</strong>: Issue updated<br /><code>' . $sql_user . '</code>';
          } else {
            $sqlMessage = '<strong class="text-warning">Error</strong>: ' . $sql_user . '<br>' . mysqli_error ( $connection );
            $messageNum = 66;
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