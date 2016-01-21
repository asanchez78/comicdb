<?php
	require_once('../views/head.php');
	require_once(__ROOT__.'/classes/wikiFunctions.php');
$ownerID = $_SESSION['user_id'];
$first_issue = filter_input ( INPUT_POST, 'first_issue' );
$last_issue = filter_input ( INPUT_POST, 'last_issue' );
$series_id = filter_input ( INPUT_POST, 'series_name' );
$original_purchase = filter_input ( INPUT_POST, 'original_purchase' );
$release_date = filter_input(INPUT_POST, 'release_date');
$releaseDateArray = explode("-", $release_date);

$series_name_query = "SELECT series_name from series where series_id=$series_id";
$series_name_result = mysqli_query ( $connection, $series_name_query );

foreach ( range ( $first_issue, $last_issue ) as $number ) {
	$issueExists = new comicSearch();
	$issueExists->issueCheck($series_id, $number);
	if ($issueExists->issueExists == 1) {
  	$sql = "INSERT INTO users_comics (user_id, comic_id) VALUES ('$ownerID', '$issueExists->comic_id')";
    if (mysqli_query ( $connection, $sql )) {
      	
    } else {
      $message = "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
  	}
	} else {
		$release_date = $releaseDateArray[0] . "-" . $releaseDateArray[1] . "-" . $releaseDateArray[2];
		$sql = "INSERT INTO comics (series_id, issue_number, release_date, original_purchase) VALUES ('$series_id', '$number', '$release_date', '$original_purchase')";
		if (mysqli_query ( $connection, $sql )) {
			$comic_id = mysqli_insert_id ( $connection );
			$messageNum = 4;
			$sql = "INSERT INTO users_comics (user_id, comic_id) VALUES ('$ownerID', '$comic_id')";
      if (mysqli_query ( $connection, $sql )) {
	    } else {
        $message = "Error: " . $insert_comic_query . "<br>" . mysqli_error ( $connection );
    	}
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error ( $connection );
			$messageNum = 51;
		}
		++$releaseDateArray[1];
		if ($releaseDateArray[1] > 12) {
			++$releaseDateArray[0];
			$releaseDateArray[1] = 01;
		}
	}
}
//fill in missing wiki IDs
$fillIn = new wikiQuery();
$fillIn->addWikiID();
//Update records that have a wiki id, but have not been updated with information from the marvel wikia
$fillIn->addDetails();
?>
	<title>Multi Issue Addition :: POW! Comic Book Manager</title>
</head>
<body>
<?php include(__ROOT__.'/views/header.php'); ?>
	<div class="container content">
		<div class="row">
			<div class="col-sm-12">
				<?php echo $message; echo $fillIn->newWikiIDs; ?>
			</div>
		</div>
	</div>
</body>
</html>
