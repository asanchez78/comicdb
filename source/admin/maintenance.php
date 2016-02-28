<?php
	require_once('../views/head.php');
	require_once(__ROOT__.'/classes/wikiFunctions.php');
?>
	<title>Maintenance :: POW! Comic Book Manager</title>
</head>
<body>

<?php include '../views/header.php';
if ($login->isUserLoggedIn () == true) {
	//fill in missing wiki IDs
	$fillIn = new wikiQuery();
	$fillIn->addWikiID();
	//Update records that have a wiki id, but have not been updated with information from the marvel wikia
	$fillIn->addDetails();
	$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
} else {
	include '../views/not_logged_in.php';
}
?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<table>
					<thead>
						<tr>
							<th>Results: <?php echo $fillIn->AddWikiIDMsg . " "; echo $fillIn->addDetailsMsg; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php echo $fillIn->newWikiIDs; echo $fillIn->updatedList; echo $fillIn->coverSearchErr?>
					</tbody>
				</table>
					<?php echo "Process Time: {$time} </br>"; ?>
			</div>
		</div>
	</div>
<?php include(__ROOT__.'/views/footer.php'); ?>
</body>
</html>