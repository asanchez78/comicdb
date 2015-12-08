<?php
require_once 'classes/functions.php';
require_once 'config/db.php';
$issue_list = null;

$series_id = filter_input ( INPUT_GET, 'series_id' );
$issues = new comicSearch ();
$issues->issuesList ( $series_id );

?>

<?php include 'views/head.php';?>
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
