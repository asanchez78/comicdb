<?php
	require_once('views/head.php');

	$comic_id = filter_input ( INPUT_GET, 'comic_id' );
	$details = new comicSearch ();
	$details->issueLookup ( $comic_id );
	$details->artistLookup ( $comic_id );
	$details->writerLookup ( $comic_id );
?>
	<title><?php echo $details->series_name . " #" . $details->issue_number; ?> :: comicDB</title>
</head>

<body>
	<?php include 'views/header.php';?>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h2><?php echo $details->series_name . " #" . $details->issue_number; ?></h2>
				<div class="issue-description"><?php echo $details->plot; ?></div>
				<div class="issue-details">
					<h3>Issue details</h3>
					<?php
						if ($details->writer) {
							echo "<div class=\"issue-writer\">";
							echo "Writer: " . $details->writer;
							echo "</div>";
						}

					 	if ($details->artist) {
							echo "<div class=\"issue-artist\">";
							echo "Artist: " . $details->artist;
							echo "</div>";
						}
					?>
					<div>
						<strong>Volume: </strong><?php echo $details->volume_number; ?>
					</div>
					<div>
						<strong>Issue: </strong><?php echo $details->issue_number; ?>
					</div>
					<div>
						<strong>Story Name: </strong><?php echo $details->story_name; ?>
					</div>
					<div>
						<strong>Published: </strong><?php echo $details->release_date; ?>
					</div>
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
