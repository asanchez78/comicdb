<?php
	require_once('views/head.php');
	if ($login->isUserLoggedIn () == true) {
		$series_list = null;
		$comics = new comicSearch ();
		$comics->seriesList ();

		if ($comics->series_list_result->num_rows > 0) {
			while ( $row = $comics->series_list_result->fetch_assoc () ) {
				$series_id = $row ['series_id'];
				$series_name = $row ['series_name'];
				$series_list .= "<li><a href=\"issues.php?series_id=$series_id\">" . $series_name . "</a></li>\n";
			}
		} else {
			$series_list = "<li>No Comic Series in database. Perhaps you should <a href=\"/admin/addseries.php\">Add some.</a></li>";
		}
	}
?>
  <title>comicDB</title>
</head>
<body>
	<?php include 'views/header.php';?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<?php if ($login->isUserLoggedIn () == true) { ?>
					<h2>Your Comics</h2>
					<?php if ($series_list != null) { ?>
					<ul class="nolist inventory-table">
						<?php echo $series_list; ?>
					</ul>
					<?php } else { ?>
						<p>No comics have been entered into the database. Why not add one?</p>
					<?php } ?>
				<?php } else { ?>
					<p>You are not signed in. Please sign in using the Login button above.</p>
				<?php } ?>
			</div>
		</div>
	</div>
<?php include 'views/footer.php';?>
</body>
</html>
