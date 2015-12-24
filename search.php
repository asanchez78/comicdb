<?php
  require_once('views/head.php');
  $comics = new comicSearch ();
  $comics->seriesList ();
  $dropdown = "<select name=\"series_name\">\n";
  $dropdown .= "<option value=\"\" selected=\"selected\"></option>\n";
  while ( $row = $comics->series_list_result->fetch_assoc () ) {
  	$series_id = $row ['series_id'];
  	$series_name = $row ['series_name'];
  	$dropdown .= "<option value=\"$series_name\" >" . $series_name . "</option>\n";
  }

  $dropdown .= "</select>";

  $last_series_id_query = "select series_id from series ORDER BY series_id DESC LIMIT 1";

  // $writer_query = "SELECT writer_name FROM writer_link LEFT JOIN writers ON (writers.writer_id = writer_link.writer_id) WHERE writer_link.comic_id = $_GET[comic_id]";

  $last_series_id = mysqli_query ( $connection, $last_series_id_query );
  // $writer = mysqli_query($connection,$writer_query);

  if (mysqli_num_rows ( $last_series_id ) > 0) {
  	while ( $row = mysqli_fetch_assoc ( $last_series_id ) ) {
  		$new_series_id = ++ $row ['series_id'];
  	}
  } else {
  	echo "0 results.";
  }

  mysqli_close ( $connection );
?>
  <title>Comic Search :: comicDB</title>
</head>
<body>
<?php include 'views/header.php';?>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h2>Add an Issue</h2>
        <form method="post" action="results.php">
          <label>Series</label>
          <?php echo $dropdown?>
          <label for="issue_number">Issue Number</label>
          <input name="issue_number" type="text" size="3" maxlength="4" value="" required aria-required="true" />
          <input class="form-submit" type="submit" name="submit" value="Search" />
        </form>
      </div>
    </div>
  </div>
<?php include 'views/footer.php';?>
</body>
</html>