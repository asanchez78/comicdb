<?php
require_once '../config/db.php';
require_once '../classes/functions.php';

/* Filter POST Data Variables */

$series_name = filter_input ( INPUT_POST, 'series_name' );
$issue_number = filter_input ( INPUT_POST, 'issue_number' );
$filtered_story_name = filter_input ( INPUT_POST, 'story_name' );
$story_name = addslashes ( $filtered_story_name );
$released_date = filter_input ( INPUT_POST, 'released_date' );
$filtered_plot = filter_input ( INPUT_POST, 'plot' );
$plot = addslashes ( $filtered_plot );
$cover_image = filter_input ( INPUT_POST, 'cover_image' );
$cover_image_file = filter_input ( INPUT_POST, 'cover_image_file' );
$original_purchase = filter_input ( INPUT_POST, 'original_purchase' );
$update = filter_input ( INPUT_POST, 'update' );
$comic_id = filter_input ( INPUT_POST, 'comic_id' );
$series_id = filter_input ( INPUT_POST, 'series_id' );
$wiki_id = filter_input ( INPUT_POST, 'wiki_id' );


if ($released_date == 0000 - 00 - 00) {
	$release_date = "";
} else {
	$release_date = $released_date;
}

if ($cover_image) {
	$path = "../images/$cover_image_file";
	$downloader = new grab_cover ();
	$downloader->downloadFile ( $cover_image, $path );
}


// insert data in to comics table
if ($update == "yes") {
	$insert_comic_query = "UPDATE comics
	SET series_id='$series_id', issue_number='$issue_number', story_name='$story_name', release_date='$release_date', plot='$plot', cover_image='images/$cover_image_file', original_purchase='$original_purchase', wikiUpdated=1
	WHERE comic_id='$comic_id'";
	if (mysqli_query ( $connection, $insert_comic_query )) {
		echo "Record updated successfully with the following information";
	} else {
		echo "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
	}
} else {
	$insert_comic_query = "INSERT INTO comics (series_id, issue_number, story_name, release_date, plot, cover_image, original_purchase, wiki_id)
	VALUES ('$series_name', '$issue_number', '$story_name', '$release_date', '$plot', 'images/$cover_image_file', '$original_purchase', '$wiki_id')";

	if (mysqli_query ( $connection, $insert_comic_query )) {
		echo "New Record created successfully with the following information";
	} else {
		echo "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
	}
}
// insert data in to series_comic_link table
//$insert_series_link_query = "INSERT INTO series_link (comic_id, series_id)
//VALUES ($new_comic_id, $series_name)";
//if (mysqli_query ( $connection, $insert_series_link_query )) {
//	echo "New series/comic link created";
//} else {
//	echo "Error: " . $insert_series_link_query . "<br>" . mysqli_error ( $connection );
//}

/* Results rendered as HTML */
$theResults = <<<EOD
<html>
        <head>
                <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.green-blue.min.css">
<script src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

                <title>Comicdb</title>
        </head>

        <body>
<div>
        <img src="../images/$cover_image_file" />
</div>

<div>
        <em>$plot</em>
</div>
                <div>
                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                        <thead>
                        <tr>
                                <th>Issue Number</th>
                                <td>$issue_number</td>
                        </tr>
                        <tr>
                                <th>Series Name</th>
                                <td>$series_name</td>
                        </tr>
                        <tr>
                                <th>Story Name</th>
                                <td>$story_name</td>
                        </tr>
                        <tr>
                                <th>Cover Date</th>
                                <td>$release_date</td>
                        </tr>
                        </thead>
                </table>
                </div>
                <a href="../comic.php?comic_id=$comic_id">Go to record</a>
        </body>
</html>

EOD;

echo "$theResults";

?>
