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
				$series_vol = $row ['series_vol'];
				$comics->seriesInfo($series_id);
				$series_issue_count = $comics->series_issue_count;
				$series_cover = $comics->series_latest_cover;
				if ($series_cover == NULL) {
					$series_cover = 'assets/nocover.jpg';
				}
				$series_list .= '<li class="col-xs-6 col-sm-3 col-md-2">';
				$series_list .= '<a href="issues.php?series_id=' . $series_id . '" class="series-info"><div class="comic-image">';
				$series_list .= '<img src="/' . $series_cover  . '" alt="' . $series_name .'" />';
				$series_list .= '<div class="series-title"><h3>' . $series_name . '</h3></div>';
				$series_list .= '</div></a>';
				$series_list .= '<small>' . $series_issue_count . '</small>';
				$series_list .= '<div class="volume-number"><span class="count">Vol ' . $series_vol . '</span></div><a href="#" class="button add-button">[Add New]</a><a href="#" class="button edit-button">[Edit]</a></li>';
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
					<ul class="inventory-table row">
						
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
