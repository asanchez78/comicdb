<?php
	require_once('../views/head.php');
	require_once(__ROOT__.'/classes/wikiFunctions.php');
	$ownerID = $_SESSION['user_id'];
	$filtered_issue_list = filter_input ( INPUT_POST, 'issue_list' );
	$issue_list = explode ( ",", $filtered_issue_list );
	$series_id = filter_input ( INPUT_POST, 'series_name' );
	$original_purchase = filter_input ( INPUT_POST, 'original_purchase' );

	$series_name_query = "SELECT series_name from series where series_id=$series_id";
	$series_name_result = mysqli_query ( $connection, $series_name_query );

	foreach ( $issue_list as $number ) {
		$insert_comics_query = "INSERT INTO comics (series_id, issue_number, original_purchase, ownerID) VALUES ('$series_id', '$number', '$original_purchase', '$ownerID')";
echo $series_id;
		if (mysqli_query ( $connection, $insert_comics_query )) {
			$message = "New Record created successfully. <br />";
			$new_comic_id = mysqli_insert_id ( $connection );
		} else {
			echo "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
		}

	// insert data in to series_comic_link table
	//$insert_series_link_query = "INSERT INTO series_link (comic_id, series_id)
    //VALUES ($new_comic_id, $series_id)";
	//if (mysqli_query ( $connection, $insert_series_link_query )) {
//		$message .= "New series/comic link created";
	//} else {
		// echo "Error: " . $insert_series_link_query . "<br>" . mysqli_error($connection);
	//}
	}
	//fill in missing wiki IDs
	$fillIn = new wikiQuery();
	$fillIn->addWikiID();
	//Update records that have a wiki id, but have not been updated with information from the marvel wikia
	$fillIn->addDetails();
?>
	<title></title>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<?php echo $message; echo $fillIn->newWikiIDs; ?>
			</div>
		</div>
	</div>
</body>
</html>
