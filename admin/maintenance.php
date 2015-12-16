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
	$fillIn = new wikiQuery();
	$fillIn->addWikiID();
	$downloader = new grab_cover();


	//Update records that have a wiki id, but have not been updated with information from the marvel wikia
	$sql = "SELECT comics.comic_id, comics.wiki_id
			FROM comics
			WHERE comics.wiki_id IS NOT NULL
			AND comics.wikiUpdated=0 LIMIT 10";
	$result = $connection->query ( $sql );
	if ($result->num_rows > 0) {
		while ( $row = $result->fetch_assoc () ) {
			$comic_id = $row ['comic_id'];
			$wiki_id = $row ['wiki_id'];
			$fillIn->comicCover($wiki_id);
			$fillIn->comicDetails($wiki_id);
			$url = $fillIn->coverURL;
			$path = "../images/$fillIn->coverFile";
//			$downloader->downloadFile($url, $path);
            $synopsis = addslashes($fillIn->synopsis);
			$sql = "UPDATE comics
				SET story_name='$fillIn->storyName',  plot='$synopsis', cover_image='images/$fillIn->coverFile', wikiUpdated=1
				WHERE comic_id='$comic_id'";
			set_time_limit(0);
			echo "<br>" . $sql . "<br><br>";

//			if (mysqli_query ( $connection, $sql )) {
//				echo "Record updated successfully with the following information";
//			} else {
//				echo "Error: " . $sql . "<br>" . mysqli_error ( $connection );
//			}
			//resetting synopsis so that it doesn't concatinate with the next result
			$fillIn->synopsis = "";
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
							<th>Results: <?php echo $fillIn->wikiMsg; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php echo $fillIn->newWikiIDs; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php include(__ROOT__.'/views/footer.php'); ?>
</body>
</html>