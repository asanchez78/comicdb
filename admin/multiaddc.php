<?php
	require_once('../views/head.php');

	$last_series_id_query = "select series_id from series ORDER BY series_id DESC LIMIT 1";
	$series_query = "SELECT * FROM series";
	// $writer_query = "SELECT writer_name FROM writer_link LEFT JOIN writers ON (writers.writer_id = writer_link.writer_id) WHERE writer_link.comic_id = $_GET[comic_id]";
	$series_list = mysqli_query ( $connection, $series_query );
	$last_series_id = mysqli_query ( $connection, $last_series_id_query );
	// $writer = mysqli_query($connection,$writer_query);

	if (mysqli_num_rows ( $last_series_id ) > 0) {
		while ( $row = mysqli_fetch_assoc ( $last_series_id ) ) {
			$new_series_id = ++ $row ['series_id'];
		}
	} else {
		echo "0 results.";
	}

	if (mysqli_num_rows ( $series_list ) > 0) {
		$dropdown = '<select class="form-control" name="series_name">';
		$dropdown .= '<option value="" selected>Choose a series</option>';
		while ( $row = mysqli_fetch_assoc ( $series_list ) ) {
			$series_id = $row ['series_id'];
			$series_name = $row ['series_name'];
			$series_vol = $row ['series_vol'];
			$dropdown .= '<option value="' . $series_id .'">' . $series_name . ' Vol ' . $series_vol . '</option>';
		}
		$dropdown .= "</select>";
	} else {
		$dropdown = "<input name=\"series_name\" class=\"element text medium\" type=\"text\" maxlength=\"255\" value=\"\"/>";
	}
	mysqli_close ( $connection );
?>
	<title>Add Comma Separated List :: POW! Comic Book Manager</title>
</head>
<body>
<?php
	include(__ROOT__.'/views/header.php');
	if ($login->isUserLoggedIn () == false) {
		include(__ROOT__."/views/not_logged_in.php");
		die ();
	}
?>
	<div class="container content">
		<div class="row">
			<div class="col-sm-12">
				<form id="input_select" method="post" action="multiaddcprocess.php">
					<div class="form-group">
						<label>Series</label>
			      <?php echo $dropdown; ?>
			    </div>

					<div class="form-group">
						<label for="issue_list">Comma separated list of issues</label>
						<textarea  class="form-control" name="issue_list" rows="4" cols="30" form="input_select"></textarea>
					</div>

					<div class="form-group form-radio">
						<label for="original_purchase">Purchased When Released</label>
						<fieldset>
							<input name="original_purchase" id="original-yes" value="1" type="radio" /> <label for="original-yes">Yes</label>
							<input name="original_purchase" id="original-no" value="0" type="radio" /> <label for="original-no">No</label>
          	</fieldset>
          </div>
					<input type="submit" name="submit" value="Submit" class="btn btn-default form-submit" />
				</form>
			</div>
		</div>
	</div>
	<?php include(__ROOT__.'/views/footer.php'); ?>
</html>
