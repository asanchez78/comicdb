<?php
  require_once(__ROOT__.'/classes/wikiFunctions.php');
  $issueEdit = true;
  $ownerID = __userID__;
  $comic_id = filter_input ( INPUT_POST, 'comic_id' );
  $released_date = filter_input ( INPUT_POST, 'released_date' );
  $story_name = addslashes ( filter_input ( INPUT_POST, 'story_name' ) );
  $custStoryName = addslashes ( filter_input ( INPUT_POST, 'custStoryName' ) );
  $quantity = addslashes ( filter_input ( INPUT_POST, 'quantity' ) );
  $plot = addslashes ( filter_input ( INPUT_POST, 'plot' ) );
  $custPlot = addslashes ( filter_input ( INPUT_POST, 'custPlot' ) );
  $condition = addslashes ( filter_input ( INPUT_POST, 'condition' ) );
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
?>