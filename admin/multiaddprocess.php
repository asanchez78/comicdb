<?php
require_once '../config/db.php';
$first_issue = filter_input ( INPUT_POST, 'first_issue' );
$last_issue = filter_input ( INPUT_POST, 'last_issue' );
$series_id = filter_input ( INPUT_POST, 'series_name' );
$original_purchase = filter_input ( INPUT_POST, 'original_purchase' );

$series_name_query = "SELECT series_name from series where series_id=$series_id";
$series_name_result = mysqli_query ( $connection, $series_name_query );

foreach ( range ( $first_issue, $last_issue ) as $number ) {
	$insert_comics_query = "INSERT INTO comics (series_id, issue_number, original_purchase) VALUES ('$series_id', '$number', '$original_purchase')";
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
	//	$message .= "Series link created successfully.";
	//} else {
	//	echo "Error: " . $insert_series_link_query . "<br>" . mysqli_error ( $connection );
	//}
}
include '../views/head.php';
?>
	<script
	src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
	<title></title>
</head>
<body>

        <?php echo $message; ?>

    </body>
</html>
