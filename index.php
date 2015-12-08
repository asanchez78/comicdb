<?php
include 'views/head.php';
$series_list = null;
$comics = new comicSearch ();
$comics->seriesList ();

if ($comics->series_list_result->num_rows > 0) {
	while ( $row = $comics->series_list_result->fetch_assoc () ) {
		$series_id = $row ['series_id'];
		$series_name = $row ['series_name'];
		$series_list .= "<tr>\n";
		$series_list .= "<td class=\"mdl-data-table__cell--non-numeric\"><a href=\"issues.php?series_id=$series_id\">" . $series_name . "</a></td>\n";
		$series_list .= "</tr>\n";
	}
} else {
	$series_list = "<tr>\n";
	$series_list .= "<td class=\"mdl-data-table__cell--non-numeric\">No Comic Series in database. Perhaps you should <a href=\"admin/addseries.php\">Add some.</a></td>\n";
	$series_list .= "</tr>\n";
}
?>
  <title>Comicdb</title>
</head>
<body>
	<?php include 'views/header.php';?>
	<div class="mdl-grid">
		<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp centered half-width table">
			<thead>
				<tr>
					<th class="mdl-data-table__cell--non-numeric full-width">Series</th>
				</tr>
			</thead>
			<tbody>
				<?php echo $series_list; ?>
			</tbody>
		</table>
	</div>
<?php include 'views/footer.php';?>

</body>
</html>
