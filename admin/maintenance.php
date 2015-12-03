<?php
/**
 * fills in information for all records using information from marvel.wikia.com
 * records must have a wiki id
 */
require_once '../classes/wikiFunctions.php';
require_once '../classes/Login.php';
require_once '../classes/functions.php';
require_once '../config/db.php';

$login = new Login();
$wikiDetails = new wikiQuery();
$downloader = new grab_cover();
$returnedResults = new wikiQuery();

if ($login->isUserLoggedIn () == true) {
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
			$returnedResults->wikiSearch($query, $series_name, $issue_number, 1);
		}
	}
/**	
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
			$wikiDetails->comicCover($wiki_id);
			$wikiDetails->comicDetails($wiki_id);
			$url = $wikiDetails->coverURL;
			$path = "../images/$wikiDetails->coverFile";
//			$downloader->downloadFile($url, $path);
			$sqlInsert = "UPDATE comics
				SET story_name='$wikiDetails->storyName', plot='$wikiDetails->synopsis', cover_image='images/$wikiDetails->coverFile' wikiUpdated=1
				WHERE comic_id='$comic_id'";
			echo $sqlInsert . "<br/><br/>";
			set_time_limit(0);
//			if (mysqli_query ( $connection, $sqlInsert )) {
//				echo "Record updated successfully with the following information";
//			} else {
//				echo "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
//			}
		}
	} else {
		echo "0 results";
	}
**/
} else {
include '../views/not_logged_in.php';
}
