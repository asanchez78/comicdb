<?php
	require_once('views/head.php');
	$issue_list = null;

	$series_id = filter_input ( INPUT_GET, 'series_id' );
	$issues = new comicSearch ();
	$issues->issuesList ( $series_id );
	$issues->seriesInfo ( $series_id );

  $publisherName = $issues->publisherName;
  $publisherShort = $issues->publisherShort;
  $series_name = $issues->series_name;
  $series_vol = $issues->series_vol;
  $issue_num = $issues->issue_number;

?>
  <title><?php echo $series_name; ?> (Vol <?php echo $series_vol; ?>) :: POW! Comic Book Manager</title>
</head>

<body>
<?php include 'views/header.php';?>
	<div class="container issues-list content">
		<div class="row">
			<div class="col-sm-12">
				<h2><?php echo $series_name; ?></h2>
				<div class="series-meta">
					<ul class="nolist">
						<?php if ($publisherName) { echo '<li class="logo-' . $publisherShort .'">' . $publisherName . '</li>'; } ?>
						<li><?php echo $issues->series_issue_count; ?></li>
						<li>Volume <?php echo $series_vol; ?></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<ul class="nolist row inventory-table">
					<?php echo $issues->issue_list; ?>
				</ul>
			</div>
		</div>
	</div>
	
<?php include 'views/footer.php';?>
</body>
</html>
