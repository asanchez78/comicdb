<?php
	require_once('views/head.php');
	$issue_list = null;

	$series_id = filter_input ( INPUT_GET, 'series_id' );
	$issues = new comicSearch ();
	$issues->issuesList ( $series_id );
	$issues->seriesInfo ( $series_id );
?>
  <title>Comicdb</title>
</head>

<body>
<?php include 'views/header.php';?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 issues-list">
				<h2><?php echo $issues->series_name; ?></h2>
				<div class="issue-meta">
					<ul class="nolist">
						<li><?php echo $issues->series_issue_count; ?></li>
						<li>Volume <?php echo $issues->series_vol; ?></li>
					</ul>
				</div>
				<div class="list-heading">
					<div class="issue-cover">Issue</div>
					<div class="issue-number">#</div>
					<div class="issue-story">Story Name</div>
				</div>
				<ul class="nolist">
					<?php echo $issues->issue_list; ?>
				</ul>
			</div>
		</div>
	</div>
	
<?php include 'views/footer.php';?>
</body>
</html>
