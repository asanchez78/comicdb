<?php
	include 'views/head.php';
	$series_list = null;
	$comics = new comicSearch ();
	$comics->seriesList ();

	if ($comics->series_list_result->num_rows > 0) {
		while ( $row = $comics->series_list_result->fetch_assoc () ) {
			$series_id = $row ['series_id'];
			$series_name = $row ['series_name'];
			$series_list = "<li><a href=\"issues.php?series_id=$series_id\">" . $series_name . "</a></li>\n";
		}
	} else {
		$series_list = "<li>No Comic Series in database. Perhaps you should <a href=\"/admin/addseries.php\">Add some.</a></li>";
	}
?>
  <title>ComicDB</title>
</head>
<body>
	<?php include 'views/header.php';?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h2>Your Comics</h2>
				<ul class="nolist inventory-table">
					<?php echo $series_list; ?>
				</ul>
			</div>
		</div>
	</div>
<?php include 'views/footer.php';?>
</body>
</html>
