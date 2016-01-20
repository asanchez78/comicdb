<?php
  $type = $_GET['type'];
  if ($type) {
    $update = filter_input ( INPUT_POST, 'update' );
    $publisher = filter_input ( INPUT_POST, 'publisher' );
    $series_name = filter_input ( INPUT_POST, 'series_name' );
    $series_id = filter_input ( INPUT_POST, 'series_id' );
    $series_vol = filter_input ( INPUT_POST, 'series_vol' );
    $issue_number = filter_input ( INPUT_POST, 'issue_number' );
    
    if ($type == 'add_single' || $type == 'update_issue') {
      $comic_id = filter_input ( INPUT_POST, 'comic_id' );
      $wiki_id = filter_input ( INPUT_POST, 'wiki_id' );
      $filtered_story_name = filter_input ( INPUT_POST, 'story_name' );
      $story_name = addslashes ( $filtered_story_name );
      $released_date = filter_input ( INPUT_POST, 'released_date' );
      $filtered_plot = filter_input ( INPUT_POST, 'plot' );
      $plot = addslashes ( $filtered_plot );
      $cover_image = filter_input ( INPUT_POST, 'cover_image' );
      $cover_image_file = filter_input ( INPUT_POST, 'cover_image_file' );
      $original_purchase = filter_input ( INPUT_POST, 'original_purchase' );
    }
    
    switch ($type) {
      case 'series':
        $sql = "INSERT INTO series (series_name, series_vol) VALUES ('$series_name', '$series_vol')";
        if (mysqli_query ( $connection, $sql )) {
          $messageNum = 3;
          $seriesSubmitted = true;
          $sqlMessage = "<p>Success: " . $sql . '</p>';
        } else {
          $sqlMessage = "<p>Error: " . $sql . "<br>" . mysqli_error ( $connection ) . $connection->errno . '</p>';
          $seriesSubmitted = false;
          if ($connection->errno == 1062) {
            return $messageNum = 50;
          }
        }
        break;
      case 'add_single':

        break;
      case 'add_range':
        break;
      case 'add_csv':
        break;
      case 'update_issue':
        break;
    }
  } else {
    // If no type is detected, something is wrong. Display general error message.
    $submitted = 'no';
    $messageNum = 99;
  }
?>