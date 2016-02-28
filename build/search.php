<?php
  require_once('views/head.php');
  $listAllSeries=1;
  $comics = new comicSearch ();
  $comics->seriesList ($listAllSeries);
  $dropdown = '<select class="form-control" name="series_name">';
  $dropdown .= '<option value="default" selected>Choose a series</option>';
  while ( $row = $comics->series_list_result->fetch_assoc () ) {
  	$series_id = $row ['series_id'];
  	$series_name = $row ['series_name'];
    $series_vol = $row ['series_vol'];
    $dropdown .= '<option value="' . $series_name . '">' . $series_name . ' Vol ' . $series_vol . '</option>';
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
  <title>Comic Search :: POW! Comic Book Manager</title>
</head>
<body>
<?php include 'views/header.php';?>
  <div class="container content">
    <div class="row">
      <div class="col-sm-12">
        <h2>Add an Issue</h2>
        <form method="post" action="results.php" class="form-inline">
          <div class="form-group">
            <label>Series</label>
            <?php echo $dropdown; ?>
          </div>
          <div class="form-group">
            <label for="issue_number">Issue #</label>
            <input name="issue_number" class="form-control" type="text" size="3" maxlength="4" value="" required aria-required="true" />
          </div>
          <input class="btn btn-default form-submit" type="submit" name="submit" value="Search" />
        </form>
      </div>
    </div>
  </div>
<?php include 'views/footer.php';?>
</body>
</html>