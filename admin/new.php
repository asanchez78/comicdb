<?php
require_once '../config/db.php';
require_once '../classes/Login.php';
require_once '../classes/functions.php';
require_once '../classes/wikiFunctions.php';
$issue_number = filter_input(INPUT_GET, 'issue_number');
$wiki_id= filter_input(INPUT_GET, 'wiki_id');
$comic = new wikiQuery();
$comic->comicCover($wiki_id);
$comic->comicDetails($wiki_id);

$comics = new comicSearch ();
$comics->seriesList ();
$dropdown = "<select class=\"element select medium\" id=\"element_6\" name=\"series_name\">\n";
$dropdown .= "<option value=\"\" selected=\"selected\"></option>\n";
while ( $row = $comics->series_list_result->fetch_assoc () ) {
	$series_id = $row ['series_id'];
	$series_name = $row ['series_name'];
	$dropdown .= "<option value=\"$series_id\" >" . $series_name . "</option>\n";
}

$dropdown .= "</select>";

$last_series_id_query = "select series_id from series ORDER BY series_id DESC LIMIT 1";

// $writer_query = "SELECT writer_name FROM writer_link LEFT JOIN writers ON (writers.writer_id = writer_link.writer_id) WHERE writer_link.comic_id = $_GET[comic_id]";

$last_series_id = mysqli_query ( $connection, $last_series_id_query );
// $writer = mysqli_query($connection,$writer_query);

if (mysqli_num_rows ( $last_series_id ) > 0) {
	while ( $row = mysqli_fetch_assoc ( $last_series_id ) ) {
		$new_series_id = ++ $row ['series_id'];
	}
} else {
	echo "0 results.";
}

mysqli_close ( $connection );

?>


<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
<head>
<link rel="stylesheet"
	href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.green-blue.min.css">
<script
	src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
<link rel="stylesheet"
	href="https://fonts.googleapis.com/icon?family=Material+Icons">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<title>Admin</title>
</head>
<body>
<?php include '../views/header.php';
	if ($login->isUserLoggedIn () == false) {
	include ("../views/not_logged_in.php");
	die ();
}

?>
<div class="mdl-layout">
<div class="mdl-grid">
	<div class="mdl-cell mdl-cell--6-col">
		<img src="<?php echo $comic->coverURL; ?>"/>
		<form method="post" action="formprocess.php">
		<div>
			<h2>Add Issue</h2>
			<p></p>
		</div>
		
		<ul>
			<li>
				<label>Series </label>
				<div>
					<?php echo $dropdown?>
				</div>
			</li>
			<li>
				<label>Issue Number </label>
				<div>
					<input name="issue_number" type="text" maxlength="255" value="<?php echo $issue_number; ?>" />
				</div>
			</li>
			<li>
				<label>Story Name </label>
				<div>
					<input name="story_name" type="text" maxlength="255" value="<?php echo $comic->storyName; ?>" />
				</div>
			</li>
			<li>
				<label>Release Date </label>
				<div>
					<input name="released_date" size="10" maxlength="10" value="" type="text" />
					<label>YYYY-MM-DD</label>
				</div>
			</li>
			<li>
				<label>Plot </label>
				<div>
					<textarea name="plot"><?php echo $comic->synopsis; ?></textarea>
				</div>
			</li>
			<li>
				<label>Cover Image URL</label>
				<div>
					<input name="cover_image" type="text" maxlength="255" value="<?php echo $comic->coverURL; ?>" />
					<label >Destination Filename</label>
					<input name="cover_image_file" type="text" maxlength="255" value="<?php echo $comic->coverFile?>" />
				</div>
			</li>
			<li>
				<label>Purchased when released?</label>
				<div>
					<select name="original_purchase">
						<option value="" selected="selected"></option>
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select>
				</div>
			</li>
			<li>
				<input type="hidden" name="new_series_id" value="<?php echo $new_series_id; ?>" />
				<input type="hidden" name="wiki_id" value="<?php echo $wiki_id; ?>" />
				<input type="submit" name="submit" value="Submit" />
			</li>
		</ul>
		</form>
	</div>
</div>
</div>

	<a href="comic.php?logout">Logout</a>
<?php include '../views/footer.php';?>
</body>
</html>
