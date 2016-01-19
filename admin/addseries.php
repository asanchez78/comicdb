<?php
	require_once('../views/head.php');
	$submitted = filter_input ( INPUT_POST, 'submitted' );
	$filename = $_SERVER["PHP_SELF"];
	if ($submitted) {
		$publisher = filter_input ( INPUT_POST, 'publisher' );
		$series_name = filter_input ( INPUT_POST, 'series_name' );
		$volume_number = filter_input ( INPUT_POST, 'volume_number' );
		$sql = "INSERT INTO series (series_name, series_vol) VALUES ('$series_name', '$volume_number')";

		if (mysqli_query ( $connection, $sql )) {
			$messageNum = 3;
		} else {
			if ($connection->errno == 1062) {
				$messageNum = 50;
				$error = $series_name . ' (Vol ' . $volume_number . ')';
			}
			// Uncomment below if you want more verbose error reporting.
			//$messageTemp = "<p>Error: " . $sql . "<br>" . mysqli_error ( $connection ) . $connection->errno . '</p>';
		}
	}
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
					if (!$submitted) { ?>
						<p>Use the form below to add a new series to your database.</p>
					<?php } else { ?>
						<p><?php if (isset($messageTemp)) {
							echo $messageTemp;
						} ?>
						<p>Add another new series to your database.</p>
				<?php } ?>
				<form method="post" action="<?php echo $filename; ?>" class="form-inline">
					<div class="form-group">
						<label for="publisher">Publisher</label>
						<select class="form-control" name="publisher">
							<option value="default" selected>Choose a Publisher</option>
							<option value="marvel">Marvel Comics</option>
							<option value="dc">DC Comics</option>
							<option value="image">Image Comics</option>
							<option value="darkhorse">Dark Horse Comics</option>
							<option value="darkhorse">Valiant Comics</option>
						</select>
					</div>
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
			</div>
		</div>
	</div>
<?php include(__ROOT__.'/views/footer.php'); ?>
</body>
</html>
