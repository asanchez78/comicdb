<?php
	require_once('views/head.php');
	if (isset($_COOKIE ['user_name'])) {
		$userName = $_COOKIE ['user_name'];
		$validUser=1;
	} 
	$publisherSearchId = filter_input ( INPUT_GET, 'pid' );
	if ($publisherSearchId !== NULL) {
		$listAll = 2;
	}
	if (isset($profile_name) && $profile_name != '') {
   		$user->userLookup($profile_name);
   		$profileID = $user->browse_user_id;
   	} else {
   		$profileID = $userID;
   	}

	$comic = new comicSearch ();
  $comic->publisherList ( $publisherSearchId );
  $publisherName = $comic->publisherName;
?>
  <title><?php echo $publisherName; ?> :: POW! Comic Book Manager</title>
</head>
<body>
	<?php include 'views/header.php';?>
	<div class="container content">
		<?php if ($login->isUserLoggedIn () == true) {
			if ($publisherSearchId !== NULL) {
				include ('modules/series_list/series_list.php');
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