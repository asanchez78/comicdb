<?php
require_once 'classes/functions.php';
require_once 'config/db.php';
$issue_list = null;

$series_id = filter_input ( INPUT_GET, 'series_id' );
$issues = new comicSearch ();
$issues->issuesList ( $series_id );

?>

<html>
<head>
<link rel="stylesheet" href="material.min.css">
<script src="material.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comicdb</title>
</head>

<body>
<?php include 'views/header.php';?>
	<table class="table mdl-data-table mdl-js-data-table mdl-shadow--2dp centered half-width">
		<thead>
			<tr>
				<th class="mdl-data-table__cell--non-numeric">Issue</th>
				<th class="mdl-data-table__cell--non-numeric">Story Name</th>
			</tr>
		</thead>
			<?php echo $issues->issue_list; ?>
		</table>
<?php include 'views/footer.php';?>
</body>
</html>
