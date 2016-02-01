<?php
	require_once('views/head.php');
	require_once('config/db.php');
	if (isset($_SESSION ['user_name'])) {
		$user = $_SESSION ['user_name'];
		$validUser=1;
	} 
	$publisherSearchId = filter_input ( INPUT_GET, 'pid' );
	if ($publisherSearchId !== NULL) {
		$listAll = 2;
	}
	$comic = new comicSearch ();
	$comic->publisherInfo ( $publisherSearchId );
	$publisherName = $comic->publisherName;
?>
  <title><?php echo $publisherName; ?> :: POW! Comic Book Manager</title>
</head>
<body>
	<?php include 'views/header.php';?>
	<div class="container content">
		<?php if ($login->isUserLoggedIn () == true or isset($user) AND $validUser == 1) {
			if ($publisherSearchId !== NULL) { ?>
			<header class="row headline">
				<div class="col-xs-12 col-md-7">
					<h2><?php echo $publisherName; ?></h2>
				</div>
				<div class="col-xs-12 col-md-5 series-meta text-right">
					<ul class="nolist">
						<li>XXXX Total Issues</li>
						<li>XX Total Series</li>
						<li>
							<button class="btn-xs btn-default sort-control active" id="sort-thumb-lg"><i class="fa fa-th-large"></i></button>
							<button class="btn-xs btn-default sort-control" id="sort-thumb-sm"><i class="fa fa-th"></i></button>
							<button class="btn-xs btn-default sort-control" id="sort-list"><i class="fa fa-list"></i></button>
						</li>
					</ul>
				</div>
			</header>
			<?php include ('views/series_list.php');
			} else { ?>
				<p><span class="text-danger">ERROR:</span> No publisher was chosen. Please go back and try again.</p>
			<?php }
		} else { ?>
			<p>You are not signed in. Please <a href="/login.php">Login</a> to continue.
		<?php } ?>
	</div>
<?php include 'views/footer.php';?>
</body>
</html>