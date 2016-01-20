<?php
  $type = $_GET['type'];
  if ($type) {
    $series_name = filter_input ( INPUT_POST, 'series_name' );
    if ($type == 'issue' || $type == 'issue-search' || $type == 'issue-add') {
      require_once(__ROOT__.'/classes/wikiFunctions.php');
      $series_id = filter_input ( INPUT_POST, 'series_id' );
      $issue_number = filter_input ( INPUT_POST, 'issue_number' );
    }
    switch ($type) {
      // Runs when the Add Series form has been submitted
      case 'series':
        $publisher = filter_input ( INPUT_POST, 'publisher' );
        $series_vol = filter_input ( INPUT_POST, 'series_vol' );
        $sql = "INSERT INTO series (series_name, series_vol) VALUES ('$series_name', '$series_vol')";
        if (mysqli_query ( $connection, $sql )) {
          $messageNum = 3;
          $seriesSubmitted = true;
          $sqlMessage = '<strong class="text-success">Success</strong>: <code>' . $sql . '</code>';
        } else {
          $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br /><code>' . mysqli_error ( $connection ) . $connection->errno . '</code>';
          $seriesSubmitted = false;
          if ($connection->errno == 1062) {
            return $messageNum = 50;
          }
        }
        break;
      // Part one of the single issue process. User chooses series and enters issue number.
      case 'issue':
        $series_vol = filter_input(INPUT_POST, 'series_vol');
        $query = $series_name . " " . $issue_number;
        $wiki = new wikiQuery();
        $wiki->wikiSearch($query, $series_name, $issue_number, 50);
        break;
      // Part two of the single issue process. Displays Wikia results.
      case 'issue-search':
        $issueSearch = true;
        $query = $series_name . " " . $issue_number;

        $wiki = new wikiQuery();
        $wiki->wikiSearch($query, $series_name, $issue_number, 50);
        break;
      // Part three of the single issue process. Displays final fields and allows user to change details before adding to collection.
      case 'issue-add':
        $issueSearch = false;
        $issueAdd = true;
        $wiki_id = filter_input (INPUT_POST, 'wiki_id');
        $comic_id = filter_input(INPUT_POST, 'comic_id');
        $comic = new wikiQuery ();
        $comic->comicCover ( $wiki_id );
        $comic->comicDetails ( $wiki_id );
        
        $sql = new comicSearch ();
        $sql->seriesFind ($series_name);
        if ($sql->series->num_rows > 0) {
          while ( $row = $sql->series->fetch_assoc () ) {
            $series_id = $row ['series_id'];
            $series_vol = $row ['series_vol'];
          }
        }
        break;
      case 'issue-submit':
        $comic_id = filter_input ( INPUT_POST, 'comic_id' );
        $wiki_id = filter_input ( INPUT_POST, 'wiki_id' );
        $released_date = filter_input ( INPUT_POST, 'released_date' );
        $story_name = addslashes ( filter_input ( INPUT_POST, 'story_name' ) );
        $plot = addslashes ( filter_input ( INPUT_POST, 'plot' ) );
        $cover_image = filter_input ( INPUT_POST, 'cover_image' );
        $cover_image_file = filter_input ( INPUT_POST, 'cover_image_file' );
        $original_purchase = filter_input ( INPUT_POST, 'original_purchase' );
        break;
      case 'range':
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