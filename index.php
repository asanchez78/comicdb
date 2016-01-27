<?php
	require_once('views/head.php');
	require_once('config/db.php');
	if (isset($_SESSION ['user_name'])) {
		$user = $_SESSION ['user_name'];
		$validUser=1;
	} else {
		$user = filter_input(INPUT_GET, 'user');
		if ($connection->connect_errno) {
			die ( "Connection failed:" );
		}
		$sql = "SELECT user_id from users where user_name='$user'";
		$result = $connection->query ( $sql );
		if (mysqli_fetch_row($connection->query ( $sql )) > 0) {
			while ($row = $result->fetch_assoc ()) {
				$_SESSION ['user_id'] = $row['user_id'];
			}
			$validUser=1;
		} else {
			$validUser=0;
		}
	}
?>
  <title>POW! Comic Book Manager</title>
</head>
<body>
	<?php include 'views/header.php';?>
	<div class="container content">
		<?php
			if ($login->isUserLoggedIn () == true or isset($user) AND $validUser == 1) {
				if ($login->isUserLoggedIn () == true) {
					echo '<h2>Your collection</h2>';
					include ('views/series_list.php');
				} elseif (isset($user) AND $validUser == 1) {
					echo '<h2>' .  $user . '&rsquo;s collection</h2>';
					include ('views/series_list.php');
				} ?>
				<div class="sort-controls">
					<button class="btn-xs btn-default sort-control active" id="sort-thumb-lg"><i class="fa fa-th-large"></i></button>
					<button class="btn-xs btn-default sort-control" id="sort-thumb-sm"><i class="fa fa-th"></i></button>
					<button class="btn-xs btn-default sort-control" id="sort-list"><i class="fa fa-list"></i></button>
				</div>
			<?php }
			if (isset($user) AND $validUser !=1 ) {
				$messageNum = 52;
				$sql = "SELECT comic_id FROM comics ORDER BY RAND() LIMIT 0,1";
			  $result = $connection->query ( $sql );
			  while ($row = $result->fetch_assoc()) {
			    $comic_id = $row['comic_id'];
			  }
				include 'views/single_comic.php';
			} elseif (!isset($user)) {
				$sql = "SELECT comic_id FROM comics ORDER BY RAND() LIMIT 0,1";
			  $result = $connection->query ( $sql );
			  while ($row = $result->fetch_assoc()) {
			    $comic_id = $row['comic_id'];
			  }
				include 'views/single_comic.php';
			}
		?>
	</div>
<?php include 'views/footer.php';?>
</body>
</html>