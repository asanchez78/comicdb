<?php
	require_once('views/head.php');
	require_once('config/db.php');
	if (isset($_COOKIE ['user_name'])) {
		$user = $_COOKIE ['user_name'];
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
		<?php if ($login->isUserLoggedIn () == true) {
			if ($publisherSearchId !== NULL) {
				include ('views/series_list.php');
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