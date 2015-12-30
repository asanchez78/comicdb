<?php
require_once '../views/head.php';

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
  $downloader = new grab_cover ();
  $downloader->downloadFile ( $cover_image, $path );
}


// insert data in to comics table
if ($update == "yes") {
  $insert_comic_query = "UPDATE comics
  SET series_id='$series_id', issue_number='$issue_number', story_name='$story_name', release_date='$release_date', plot='$plot', cover_image='images/$cover_image_file', original_purchase='$original_purchase', wikiUpdated=1
  WHERE comic_id='$comic_id'";
  if (mysqli_query ( $connection, $insert_comic_query )) {
      $message = "Record updated successfully with the following information";
  } else {
      $message = "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
  }
} else {
  $insert_comic_query = "INSERT INTO comics (series_id, issue_number, story_name, release_date, plot, cover_image, original_purchase, wiki_id)
  VALUES ('$series_id', '$issue_number', '$story_name', '$release_date', '$plot', 'images/$cover_image_file', '$original_purchase', '$wiki_id')";

  if (mysqli_query ( $connection, $insert_comic_query )) {
      $message = "New Record created successfully with the following information";
      $comic_id = mysqli_insert_id($connection);
  } else {
      $message = "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
  }
}
// insert data in to series_comic_link table
//$insert_series_link_query = "INSERT INTO series_link (comic_id, series_id)
//VALUES ($new_comic_id, $series_name)";
//if (mysqli_query ( $connection, $insert_series_link_query )) {
//  echo "New series/comic link created";
//} else {
//  echo "Error: " . $insert_series_link_query . "<br>" . mysqli_error ( $connection );
//}
?>
  <title>Insert Record Confirmation :: comicDB</title>
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
  <div class="container">
    <div class="row">
      <div class="col-sm-12 headline">
        <h2><?php echo $series_name . " #" . $issue_number; ?></h2>
        <a href="#">&lt; Back</a>
      </div>
      <div class="col-md-8">
        <div class="issue-details">
          <h3>Issue details</h3>
          <?php
            if ($details->writer) {
              echo "<div class=\"issue-writer\">";
              echo "Writer: " . $details->writer;
              echo "</div>";
            }

            if ($details->artist) {
              echo "<div class=\"issue-artist\">";
              echo "Artist: " . $details->artist;
              echo "</div>";
            }
          ?>
          <div>
            <strong>Issue: </strong><?php echo $issue_number; ?>
          </div>
          <div>
            <strong>Story Name: </strong><?php echo $story_name; ?>
          </div>
          <div>
            <strong>Published: </strong><?php echo $released_date; ?>
          </div>
          <a href="../comic.php?comic_id=<?php echo $comic_id; ?>">Go to record</a>
        </div>
        <div class="issue-description"><?php echo $plot; ?></div>
      </div>
      <div class="col-md-4 issue-image">
        <img src="../images/<?php echo $cover_image_file; ?>" />
      </div>
    </div>
  </div>
<?php include '../views/footer.php';?>
</body>
</html>