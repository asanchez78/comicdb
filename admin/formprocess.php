<?php
require_once '../views/head.php';
require_once(__ROOT__.'/classes/wikiFunctions.php');
$ownerID = $_SESSION['user_id'];
/* Filter POST Data Variables */

$series_name = filter_input ( INPUT_POST, 'series_name' );
$issue_number = filter_input ( INPUT_POST, 'issue_number' );
$filtered_story_name = filter_input ( INPUT_POST, 'story_name' );
$story_name = addslashes ( $filtered_story_name );
$released_date = filter_input ( INPUT_POST, 'released_date' );
$filtered_plot = filter_input ( INPUT_POST, 'plot' );
$plot = addslashes ( $filtered_plot );
$cover_image = filter_input ( INPUT_POST, 'cover_image' );
$cover_image_file = filter_input ( INPUT_POST, 'cover_image_file' );
$original_purchase = filter_input ( INPUT_POST, 'original_purchase' );
$update = filter_input ( INPUT_POST, 'update' );
$comic_id = filter_input ( INPUT_POST, 'comic_id' );
$series_id = filter_input ( INPUT_POST, 'series_id' );
$wiki_id = filter_input ( INPUT_POST, 'wiki_id' );


if ($released_date == 0000 - 00 - 00) {
  $release_date = "";
} else {
  $release_date = $released_date;
}

if ($cover_image) {
  $path = "../images/$cover_image_file";
  $downloader = new wikiQuery();
  $downloader->downloadFile ( $cover_image, $path );
}


// insert data in to comics table
if ($update == "yes") {
  $insert_comic_query = "UPDATE comics
  SET series_id='$series_id', issue_number='$issue_number', story_name='$story_name', release_date='$release_date', plot='$plot', cover_image='images/$cover_image_file', original_purchase='$original_purchase', wikiUpdated=1
  WHERE comic_id='$comic_id'";
  if (mysqli_query ( $connection, $insert_comic_query )) {
      echo '<META http-equiv="refresh" content="0;URL=/comic.php?comic_id=' . $comic_id . '&m=5">';
  } else {
      $message = "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
  }
} else {
  $insert_comic_query = "INSERT INTO comics (series_id, issue_number, ownerID, story_name, release_date, plot, cover_image, original_purchase, wiki_id, wikiUpdated)
  VALUES ('$series_id', '$issue_number', '$ownerID', '$story_name', '$release_date', '$plot', 'images/$cover_image_file', '$original_purchase', '$wiki_id', 1)";

  if (mysqli_query ( $connection, $insert_comic_query )) {
      $comic_id = mysqli_insert_id($connection);
      echo '<META http-equiv="refresh" content="0;URL=/comic.php?comic_id=' . $comic_id . '&m=1">';
  } else {
      $message = "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
  }
}
?>
  <title>Insert Record Confirmation :: POW! Comic Book Manager</title>
</head>
<body>
  <?php include '../views/header.php';?>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <strong><em><?php echo $message; ?></em></strong>:
      </div>
    </div>
  </div>
<?php include '../views/footer.php';?>
</body>
</html>