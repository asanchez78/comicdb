<?php
	require_once('../views/head.php');

	$last_series_id_query = "select series_id from series ORDER BY series_id DESC LIMIT 1";
	$series_query = "SELECT series_id, series_name FROM series";
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
		$dropdown = '<select class="form-control" id="element_6" name="series_name">';
		$dropdown .= '<option value="" selected>Series Name</option>';
		while ( $row = mysqli_fetch_assoc ( $series_list ) ) {
			$series_id = $row ['series_id'];
			$series_name = $row ['series_name'];
			$dropdown .= '<option value="' . $series_id .'">' . $series_name . '</option>';
		}
		$dropdown .= '</select>';
	} else {
		$dropdown = '<input id="element_6" name="series_name" class="form-control" type="text" maxlength="255" value=""/>';
	}
	mysqli_close ( $connection );
?>
	<title>Add Range :: POW! Comic Book Manager</title>
</head>
<body>
<?php
	include(__ROOT__.'/views/header.php');
?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<?php
					if ($login->isUserLoggedIn () == false) {
						include(__ROOT__."/views/not_logged_in.php");
						die ();
					}
				?>
				<h2>Add a range of issues</h2>
				<p>If the series had a regular monthly release, you may enter the published date of the first issue.</p>
				<p>Each entry will automatically have the month incremented.</p>
				<form id="input_select" method="post" action="multiaddprocess.php" class="container">
					<div class="row">
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<label for="series_name">Series</label>
						  	<?php echo $dropdown; ?>
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="form-inline">
								<div class="form-group">
							  	<label for="first_issue">First Issue</label>
							  	<input name="first_issue" type="text" class="form-control" maxlength="3" size="3" />
							  </div>
								<div class="form-group">
									<label for="last_issue">Last Issue</label>
									<input name="last_issue" type="text" class="form-control" maxlength="3" size="3" />
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<div class="form-inline">
								<div class="form-group form-radio">
									<label for="original_purchase">Purchased When Released</label>
									<fieldset>
										<input name="original_purchase" id="original-yes" value="1" type="radio" /> <label for="original-yes">Yes</label>
										<input name="original_purchase" id="original-no" value="0" type="radio" /> <label for="original-no">No</label>
			          	</fieldset>
			          </div>
			          <div class="form-group">
			            <label for="release_date">First Comic's Published Date</label>
							  	<input name="release_date" type="date" class="form-control" maxlength="10" placeholder="YYYY-MM-DD"/>
							  </div>
							</div>
						</div>
					</div>
					<input type="submit" name="submit" value="Submit" class="form-submit" />
				</form>
			</div>
		</div>
	</div>
<?php include(__ROOT__.'/views/footer.php'); ?>
</html>