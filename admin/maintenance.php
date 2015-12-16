<?php
	require_once('../views/head.php');
	require_once(__ROOT__.'/classes/wikiFunctions.php');
?>
	<title>Maintenance :: comicDB</title>
</head>
<body>

<?php include '../views/header.php';
if ($login->isUserLoggedIn () == true) {
	//fill in missing wiki IDs
	$wikiID = new wikiQuery();
	$wikiID->addWikiID();
	$wikiDetails = new wikiQuery();
	$downloader = new grab_cover();
	$linkList = "";
//	turning off NOTICE reporting. bulleted lists in synopsis are burried one level deeper in the json data
//	this currently causes an index error
//	error_reporting(E_ALL & ~E_NOTICE);

	//Update records that have a wiki id, but have not been updated with information from the marvel wikia
	$sql = "SELECT comics.comic_id, comics.wiki_id
			FROM comics
			WHERE comics.wiki_id IS NOT NULL
			AND comics.wikiUpdated=0 LIMIT 2";
	$result = $connection->query ( $sql );
	if ($result->num_rows > 0) {
		while ( $row = $result->fetch_assoc () ) {
			$comic_id = $row ['comic_id'];
			$wiki_id = $row ['wiki_id'];
			$wikiDetails->comicCover($wiki_id);
			$wikiDetails->comicDetails($wiki_id);
			$url = $wikiDetails->coverURL;
			$path = "../images/$wikiDetails->coverFile";
//			$downloader->downloadFile($url, $path);
            $synopsis = addslashes($wikiDetails->synopsis);
			$sql = "UPDATE comics
				SET story_name='$wikiDetails->storyName',  plot='$synopsis', cover_image='images/$wikiDetails->coverFile', wikiUpdated=1
				WHERE comic_id='$comic_id'";
			set_time_limit(0);

            echo "<br><br><br>";
//			if (mysqli_query ( $connection, $sql )) {
//				echo "Record updated successfully with the following information";
//			} else {
//				echo "Error: " . $sql . "<br>" . mysqli_error ( $connection );
//			}
			//resetting synopsis so that it doesn't concatinate with the next result
			$wikiDetails->synopsis = "";
		}
	} else {
		echo "0 results";
	}
} else {
	include '../views/not_logged_in.php';
}
?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<table>
					<thead>
						<tr>
							<th>Results: <?php echo $wikiID->wikiMsg; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php echo $wikiID->newWikiIDs; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php include(__ROOT__.'/views/footer.php'); ?>
</body>
</html>