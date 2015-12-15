<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/views/head.php');

$first_issue = filter_input ( INPUT_POST, 'first_issue' );
$last_issue = filter_input ( INPUT_POST, 'last_issue' );
$series_id = filter_input ( INPUT_POST, 'series_name' );
$original_purchase = filter_input ( INPUT_POST, 'original_purchase' );

$series_name_query = "SELECT series_name from series where series_id=$series_id";
$series_name_result = mysqli_query ( $connection, $series_name_query );

foreach ( range ( $first_issue, $last_issue ) as $number ) {
	$insert_comics_query = "INSERT INTO comics (issue_number, original_purchase) VALUES ('$number', '$original_purchase')";
	if (mysqli_query ( $connection, $insert_comics_query )) {
		echo "New Record created successfully with the following information";
		echo $insert_comics_query;
		echo "<br>";
		$new_comic_id = mysqli_insert_id ( $connection );
	} else {
		echo "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
	}

	// insert data in to series_comic_link table
	$insert_series_link_query = "INSERT INTO series_link (comic_id, series_id)
    VALUES ($new_comic_id, $series_id)";
	if (mysqli_query ( $connection, $insert_series_link_query )) {
		echo "New series/comic link created";
		echo $insert_series_link_query;
		echo "<br>";
	} else {
		echo "Error: " . $insert_series_link_query . "<br>" . mysqli_error ( $connection );
	}
}
?>
	<title></title>
</head>
<body>



</body>
</html>
