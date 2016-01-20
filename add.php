<?php
	require_once('views/head.php');
	$filename = $_SERVER["PHP_SELF"];
	$submitted = filter_input ( INPUT_POST, 'submitted' );
	if ($submitted) { include('admin/formprocess.php'); }
?>
  <title>Add :: POW! Comic Book Manager</title>
</head>
<body>
	<?php include 'views/header.php';?>
	<div class="container content">
		<div class="row add-main">
			<ul class="nolist">
				<li class="col-xs-12 col-md-6">Add Series</li>
				<li class="col-xs-12 col-md-6">Add Issue</li>
				<li class="col-xs-12 col-md-6">Add Range of Issues</li>
				<li class="col-xs-12 col-md-6">Add List of Issues</li>
			</ul>
		</div>
		<div class="row add-block add-series">
			<?php if ($seriesSubmitted == true) { ?>
			<div class="add-success bg-success col-xs-12">
				<div class="success-message">
					<h3><?php echo $series_name; ?><br /><small>(Vol <?php echo $series_vol; ?>)</small></h2>
					<p>has been added to your collection.</p>
					<a href="#" class="btn btn-default">Add another?</a>
				</div>
			</div>
			<?php } ?>
			<div class="col-xs-12 form-series-add">
				<h2>Add Series</h2>
				<p>Use the form below to add a new series to your collection.</p>
				<form method="post" action="<?php echo $filename; ?>?type=series" class="form-inline" id="add-series">
					<div class="form-group">
						<label for="publisher">Publisher</label>
						<select class="form-control" name="publisher">
							<option value="">Choose a Publisher</option>
							<option value="marvel" selected>Marvel Comics</option>
							<option value="dc">DC Comics</option>
							<option value="image">Image Comics</option>
							<option value="darkhorse">Dark Horse Comics</option>
							<option value="valiant">Valiant Comics</option>
						</select>
					</div>
					<div class="form-group">
						<label for="series_name">Series Name</label>
						<input name="series_name" class="form-control" type="text" size="50" value="" required />
					</div>
					<div class="form-group">
						<label for="series_vol">Volume #</label>
						<input name="series_vol" class="form-control" type="text" size="3" maxlength="4" value="" required />
					</div>
					<input type="hidden" name="submitted" value="yes" />
					<input type="submit" name="submit" value="Submit" class="btn btn-primary form-submit" />
				</form>
			</div>
		</div>
	</div>
<?php include 'views/footer.php';?>
</body>
</html>