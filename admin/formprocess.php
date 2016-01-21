<?php
  $type = $_GET['type'];
  if ($type) {
    require_once(__ROOT__.'/classes/wikiFunctions.php');
    switch ($type) {
      // Runs when the Add Series form has been submitted
      case 'series':
        $series_name = filter_input ( INPUT_POST, 'series_name' );
        $series_vol = filter_input(INPUT_POST, 'series_vol');
        $publisherID = filter_input ( INPUT_POST, 'publisherID' );
        $sql = "INSERT INTO series (series_name, series_vol, publisherID) VALUES ('$series_name', '$series_vol', '$publisherID')";
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
      // Part one of the single issue process. Displays Wikia results.
      case 'issue-search':
        $issueSearch = true;
        $series_id = filter_input ( INPUT_POST, 'series_id' );
        $comic = new comicSearch ();
        $comic->seriesInfo ($series_id);
        $series_name = $comic->series_name;
        $series_vol = $comic->series_vol;
        $issue_number = filter_input ( INPUT_POST, 'issue_number' );
        $query = $series_name . ' Vol ' . $series_vol . ' ' . $issue_number;

        $wiki = new wikiQuery();
        $wiki->wikiSearch($query, 50);
        break;
      // Part two of the single issue process. Displays final fields and allows user to change details before adding to collection.
      case 'issue-add':
        $issueAdd = true;
        $series_name = filter_input ( INPUT_POST, 'series_name' );
        $series_vol = filter_input(INPUT_POST, 'series_vol');
        $series_id = filter_input ( INPUT_POST, 'series_id' );
        $issue_number = filter_input ( INPUT_POST, 'issue_number' );
        $wiki_id = filter_input (INPUT_POST, 'wiki_id');

        $wiki = new wikiQuery ();
        $wiki->comicCover ( $wiki_id );
        $wiki->comicDetails ( $wiki_id );
        break;
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
        $original_purchase = filter_input ( INPUT_POST, 'original_purchase' );

        if ($released_date == 0000 - 00 - 00) {
          $release_date = "";
        } else {
          $release_date = $released_date;
        }

        if ($cover_image) {
          $path = __ROOT__ . '/images/' . $cover_image_file;
          $wiki = new wikiQuery();
          $wiki->downloadFile ( $cover_image, $path );
        }

        $comic = new comicSearch();
        $comic->issueCheck($series_id, $issue_number);
        if ($comic->issueExists == 1) {
          $sql = "INSERT INTO users_comics (user_id, comic_id) VALUES ('$ownerID', '$comic->comic_id')";
          $comic_id = $comic->comic_id;
          if (mysqli_query ( $connection, $sql )) {
            $messageNum = 1;
          } else {
            $sqlMessage = '<strong class="text-warning">Error:</strong> ' . $sql . '<br>' . mysqli_error ( $connection );
            $messageNum = 51;
          }
        } else {
          $sql = "INSERT INTO comics (series_id, issue_number, story_name, release_date, plot, cover_image, original_purchase, wiki_id, wikiUpdated)
          VALUES ('$series_id', '$issue_number', '$story_name', '$release_date', '$plot', 'images/$cover_image_file', '$original_purchase', '$wiki_id', 1)";
          if (mysqli_query ( $connection, $sql )) {
            $comic_id = mysqli_insert_id($connection);
            // Add to user_comics table
            $sql_user = "INSERT INTO users_comics (user_id, comic_id) VALUES ('$ownerID', '$comic_id')";
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
      case 'range':
        $rangeSearch = true;
        $ownerID = $_SESSION['user_id'];
        $series_id = filter_input ( INPUT_POST, 'series_id' );
        $first_issue = filter_input ( INPUT_POST, 'first_issue' );
        $last_issue = filter_input ( INPUT_POST, 'last_issue' );
        $original_purchase = filter_input ( INPUT_POST, 'original_purchase' );
        $release_date = filter_input(INPUT_POST, 'release_date');
        $releaseDateArray = explode("-", $release_date);

        $comic = new comicSearch ();
        $comic->seriesInfo ($series_id);
        $series_name = $comic->series_name;
        $series_vol = $comic->series_vol;

        foreach ( range ( $first_issue, $last_issue ) as $issue_number ) {
          $comic->issueCheck($series_id, $issue_number);
          if ($comic->issueExists == 1) {
            $sql = "INSERT INTO users_comics (user_id, comic_id) VALUES ('$ownerID', '$comic->comic_id')";
            if (mysqli_query ( $connection, $sql )) {
              $messageNum = 4;
            } else {
              $messageNum = 61;
              $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
            }
          } else {
            $query = $series_name . ' Vol ' . $series_vol . ' ' . $issue_number;
            $wiki = new wikiQuery();
            $wiki->wikiSearch($query, 1);
            $wiki_id = $wiki->wiki_id;
            
            $wiki->comicDetails ( $wiki_id );
            $release_date = $releaseDateArray[0] . "-" . $releaseDateArray[1] . "-" . $releaseDateArray[2];
            $plot = htmlspecialchars($wiki->synopsis);
            $story_name = $wiki->storyName;
            $cover_image_file = $wiki->comicCover( $wiki_id );
            $sql = "INSERT INTO comics (series_id, issue_number, story_name, release_date, plot, cover_image, original_purchase, wiki_id, wikiUpdated) VALUES ('$series_id', '$issue_number', '$story_name', '$release_date', '$plot', 'images/$cover_image_file', '$original_purchase', '$wiki_id', 1)";
            if (mysqli_query ( $connection, $sql )) {
              $comic_id = mysqli_insert_id ( $connection );
              $sql = "INSERT INTO users_comics (user_id, comic_id) VALUES ('$ownerID', '$comic_id')";
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
          ++$releaseDateArray[1];
          if ($releaseDateArray[1] > 12) {
            ++$releaseDateArray[0];
            $releaseDateArray[1] = 01;
          }
        }
        break;
      case 'csv':
        break;
      case 'update':
        break;
    }
  } else {
    // If no type is detected, something is wrong. Display general error message.
    $submitted = 'no';
    $messageNum = 99;
  }
?>