<?php
/**
 * fills in information for all records using information from marvel.wikia.com
 * records must have a wiki id
 */
require_once '../classes/wikiFunctions.php';
require_once '../classes/Login.php';
require_once '../classes/functions.php';
require_once '../config/db.php';
?>

<html>
<head>
<link rel="stylesheet" href="../material.min.css">
<script src="../material.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Maintenance</title>

</head>

<body>

<?php include '../views/header.php';
if ($login->isUserLoggedIn () == true) {
	$wikiDetails = new wikiQuery();
	$downloader = new grab_cover();
	$linkList = "";
//	turning off NOTICE reporting. bulleted lists in synopsis are burried one level deeper in the json data
//	this currently causes an index error
	error_reporting(E_ALL & ~E_NOTICE);

	//Get wiki ids for records that do not have them by searching the marvel wikia api
	$sql = "SELECT comics.comic_id, series.series_name, comics.issue_number
			FROM comics
			LEFT JOIN series ON comics.series_id=series.series_id
			WHERE comics.wiki_id IS NULL";
	$result = $connection->query ($sql);
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$comic_id = $row ['comic_id'];
			$series_name = $row ['series_name'];
			$issue_number = $row['issue_number'];
			$query = $series_name . " " . $issue_number;
			$wikiDetails->wikiSearch($query, $series_name, $issue_number, 1);
			$sql = "UPDATE comics
			SET wiki_id=$wikiDetails->wikiSearchResultID
			WHERE comic_id='$comic_id'";
			echo $sql . "<br/><br/>";
			set_time_limit(0);
			if (mysqli_query ( $connection, $sql )) {
				$wikiMsg = "wiki IDs entered";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error ( $connection );
			}
		}
	} else {
		$wikiMsg = "All entries have a wiki id.";
	}

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
			$downloader->downloadFile($url, $path);
            $synopsis = addslashes($wikiDetails->synopsis);
			$sql = "UPDATE comics
				SET story_name='$wikiDetails->storyName',  plot='$synopsis', cover_image='images/$wikiDetails->coverFile', wikiUpdated=1
				WHERE comic_id='$comic_id'";
			$linkList .= "<tr>\n";
			$linkList .= "<td class=\"mdl-data-table__cell--non-numeric\"><a href=\"../comic.php?comic_id=" . $comic_id . "\">". $wikiDetails->wikiTitle ."</a></td>\n";
			$linkList .= "</tr>\n";
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



<div class="mdl-grid">
	<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp centered half-width table">
		<thead>
			<tr>
				<th class="mdl-data-table__cell--non-numeric full-width">Results: <?php echo $wikiMsg; ?></th>
			</tr>
		</thead>
		<tbody>
<?php echo $linkList; ?>
</tbody>
</table>
</div>


<?php include '../views/footer.php';?>

</body>
</html>
