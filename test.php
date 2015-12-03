<?
$series_list = null;
try {
	$connection = new PDO ( "mysql:host=localhost;dbname=comicdb", "comicdb", "comicdb" );
	$connection->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$series_query = $connection->query ( "SELECT series_id, series_name FROM series" );
	$series_query->setFetchMode ( PDO::FETCH_ASSOC );
	while ( $row = $series_query->fetch () ) {
		$series_id = $row ['series_id'];
		$series_name = $row ['series_name'];
		$series_list .= "<tr>\n";
		$series_list .= "<td class=\"mdl-data-table__cell--non-numeric\">" . "<a href=\"issues.php?series_id=$series_id\">" . $series_name . "</a>" . "</td>\n";
		$series_list .= "</tr>\n";
	}
} 

catch ( PDOException $e ) {
	echo $e->getMessage ();
}
$connection = null;
?>
<html>
<head>
<link rel="stylesheet"
	href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.green-blue.min.css">
<script
	src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
<link rel="stylesheet"
	href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet"
	href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700"
	type="text/css">
<title>Comicdb</title>
</head>
<body>
	<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
		<thead>
			<tr>
				<th class="mdl-data-table__cell--non-numeric">Series</th>
			</tr>
		</thead>
			<? echo $series_list; ?>
		</table>
</body>
</html>
