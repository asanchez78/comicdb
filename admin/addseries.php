<?php
	require_once('../views/head.php');
	$submitted = filter_input ( INPUT_POST, 'submitted' );
?>
	<title>Add Series :: POW! Comic Book Manager</title>
</head>

<body>
	<?php include(__ROOT__.'/views/header.php'); ?>
	<div class="container content">
		<div class="row">
			<div class="col-sm-12">
				<h2>Add Series</h2>
				<?php
					if ($login->isUserLoggedIn() == false) {
						include '../views/not_logged_in.php';
						die();
					}
					if (!$submitted) {
						$filename=$_SERVER["PHP_SELF"];
						?>
						<p>Use the form below to add a new series to your database.</p>
						<form method="post" action="<?php echo $filename; ?>" class="form-inline">
							<div class="form-group">
								<label for="series_name">Series Name</label>
								<input name="series_name" class="form-control" type="text" size="50" value="" required />
							</div>
							<div class="form-group">
								<label for="volume_number">Volume #</label>
								<input name="volume_number" class="form-control" type="text" size="3" maxlength="4" value="" required />
							</div>
							<input type="hidden" name="submitted" value="yes" />
							<input type="submit" name="submit" value="Submit" class="btn btn-primary form-submit" />
						</form>
						<?php
					} else {
						$series_name = filter_input ( INPUT_POST, 'series_name' );
						$volume_number = filter_input ( INPUT_POST, 'volume_number' );
						$sql = "INSERT INTO series (series_name, series_vol)
						VALUES ('$series_name', '$volume_number')";

						if (mysqli_query ( $connection, $sql )) {
							echo '<p>' . $series_name . ' Volume '. $volume_number . ' series created in database.</p>';
						} else {
							echo "<p>Error: " . $sql . "<br>" . mysqli_error ( $connection ) . '</p>';
						} ?>
						<p>Use the form below to add another new series to your database.</p>
						<form method="post" action="<?php echo $filename; ?>" class="form-inline">
							<div class="form-group">
								<label for="series_name">Series Name</label>
								<input name="series_name" class="form-control" type="text" size="50" value="" required />
							</div>
							<div class="form-group">
								<label for="volume_number">Volume #</label>
	          		<input name="volume_number" class="form-control" type="text" size="3" maxlength="4" value="" required />
          		</div>
							<input type="hidden" name="submitted" value="yes" />
							<input type="submit" name="submit" value="Submit" class="btn btn-primary form-submit" />
						</form>
					<?php }
				?>
			</div>
		</div>
	</div>
<?php include(__ROOT__.'/views/footer.php'); ?>
</body>
</html>
