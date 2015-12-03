<?php
require_once '../config/db.php';

	$last_series_id_query = "select series_id from series ORDER BY series_id DESC LIMIT 1";
	$series_query = "SELECT series_id, series_name FROM series";
	// $writer_query = "SELECT writer_name FROM writer_link LEFT JOIN writers ON (writers.writer_id = writer_link.writer_id) WHERE writer_link.comic_id = $_GET[comic_id]";
	$series_list = mysqli_query ( $connection, $series_query );
	$last_series_id = mysqli_query ( $connection, $last_series_id_query );
	// $writer = mysqli_query($connection,$writer_query);

	if (mysqli_num_rows ( $last_series_id ) > 0) {
		while ( $row = mysqli_fetch_assoc ( $last_series_id ) ) {
			$new_series_id = ++ $row ['series_id'];
		}
	} else {
		echo "0 results.";
	}

	if (mysqli_num_rows ( $series_list ) > 0) {
		$dropdown = "<select class=\"element select medium\" id=\"element_6\" name=\"series_name\">\n";
		$dropdown .= "<option value=\"\" selected=\"selected\"></option>\n";
		while ( $row = mysqli_fetch_assoc ( $series_list ) ) {
			$series_id = $row ['series_id'];
			$series_name = $row ['series_name'];
			$dropdown .= "<option value=\"$series_id\" >" . $series_name . "</option>\n";
		}
		$dropdown .= "</select>";
	} else {
		$dropdown = "<input id=\"element_6\" name=\"series_name\" class=\"element text medium\" type=\"text\" maxlength=\"255\" value=\"\"/>";
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
<form id="input_select" method="post" action="multiaddprocess.php">
	<label>Series</label>
        <?php echo $dropdown; ?>
        <label>First Issue</label> <input name="first_issue" type="text"
		maxlength="3" /> <label>Last Issue</label> <input name="last_issue"
		type="text" maxlength="3" /> <label>Purchased when released?</label> <select
		name="original_purchase">
		<option value="" selected="selected"></option>
		<option value="1">Yes</option>
		<option value="0">No</option>
	</select> <input type="submit" name="submit" value="Submit" />
</form>
<a href="multiadd.php?logout">Logout</a>
<?php include '../views/footer.php';?>
</html>
