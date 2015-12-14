<?php
	require_once('views/head.php');
	$issue_list = null;

	$series_id = filter_input ( INPUT_GET, 'series_id' );
	$issues = new comicSearch ();
	$issues->issuesList ( $series_id );

?>
  <title>Comicdb</title>
</head>

<body>
<?php include 'views/header.php';?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<table class="comics-list">
					<thead>
						<tr>
							<th class="comics-heading-issue">Issue</th>
							<th>Story Name</th>
						</tr>
					</thead>
					<?php echo $issues->issue_list; ?>
				</table>
			</div>
		</div>
	</div>
	
<?php include 'views/footer.php';?>
</body>
</html>
