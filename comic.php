<?php
	require_once('views/head.php');

	$comic_id = filter_input ( INPUT_GET, 'comic_id' );
	$details = new comicSearch ();
	$details->issueLookup ( $comic_id );
	$details->seriesInfo ( $details->series_id );
?>
	<title><?php echo $details->series_name . " #" . $details->issue_number; ?> :: POW! Comic Book Manager</title>
</head>

<body>
	<?php include 'views/header.php';?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 headline">
        <h2><?php echo $details->series_name . " #" . $details->issue_number; ?></h2>
        <div class="series-meta">
					<ul class="nolist">
						<li><?php echo DateTime::createFromFormat('Y-m-d', $details->release_date)->format('M Y'); ?></li>
						<li>Volume <?php echo $details->series_vol; ?></li>
					</ul>
				</div>
      </div>
			<div class="col-md-8">
				<div class="issue-story"><h4><?php echo $details->story_name; ?></h4></div>
				<div class="issue-description">
					<?php if ($details->plot != '') {
						echo $details->plot; 
					} else {
						echo 'Plot details have not been entered.';
					}
					?>
				</div>
				<p>
					<?php
						if ($login->isUserLoggedIn () == true) {
							echo "<a href=\"/admin/wikiaedit.php?comic_id=" . $details->comic_id . "&wiki_id=" . $details->wiki_id . "\">Update Info</a>";
						}
					?>
				</p>
			</div>
			<div class="col-md-4 issue-image">
				<img src="<?php echo $details->cover_image; ?>" alt="cover" />
			</div>
		</div>
	</div>
	<?php include 'views/footer.php';?>
</body>
</html>
