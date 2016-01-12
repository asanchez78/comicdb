<?php
	require_once('views/head.php');
	require_once('config/db.php');
	if (isset($_SESSION ['user_name'])) {
		//$user = $_SESSION ['user_name'];
		$user = $_SESSION ['user_id'];
		echo $user;
		$invalidUser=0;
	} else {
		$user = filter_input(INPUT_GET, 'user');
		if ($connection->connect_errno) {
			die ( "Connection failed:" );
		}
		$sql = "SELECT user_id from users where user_name='$user'";
		$result = $connection->query ( $sql );
		if (mysqli_fetch_row($connection->query ( $sql )) > 0) {
			while ($row = $result->fetch_assoc ()) {
				$user = $row['user_id'];
			}
			$invalidUser=0;
		} else {
			$invalidUser=1;
		}
	}
?>
  <title>comicDB</title>
</head>
<body>
	<?php include 'views/header.php';?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<?php
					if ($invalidUser == 1) {
						$series_list = "$user not found.";
						echo "$user not found. Here is a random comic instead.";
						echo $invalidUser;
					}
					if ($login->isUserLoggedIn () == true or isset($user) AND $invalidUser == 0) { 
						$series_list = null;
						$comics = new comicSearch ();
						$comics->seriesList ($user);
						if ($comics->series_list_result->num_rows > 0) {
							while ( $row = $comics->series_list_result->fetch_assoc () ) {
								$series_id = $row ['series_id'];
								$series_name = $row ['series_name'];
								$series_vol = $row ['series_vol'];
								$comics->seriesInfo($series_id);
								$series_issue_count = $comics->series_issue_count;
								$series_cover = $comics->series_latest_cover;
								if ($series_cover == NULL) {
									$series_cover = 'assets/nocover.jpg';
								}
								$series_list .= '<li class="col-xs-6 col-sm-3 col-md-2">';
								$series_list .= '<a href="issues.php?series_id=' . $series_id . '" class="series-info"><div class="comic-image">';
								$series_list .= '<img src="/' . $series_cover  . '" alt="' . $series_name .'" />';
								$series_list .= '<div class="series-title"><h3>' . $series_name . '</h3></div>';
								$series_list .= '</div></a>';
								$series_list .= '<small>' . $series_issue_count . '</small>';
								$series_list .= '<div class="volume-number"><span class="count">Vol ' . $series_vol . '</span></div><a href="#" class="button add-button">[Add New]</a><a href="#" class="button edit-button">[Edit]</a></li>';
							}
						} else {
							$series_list = "<li>No Comic Series in database. Perhaps you should <a href=\"/admin/addseries.php\">Add some.</a></li>";
						}
					?>
				
					<h2>Your Comics</h2>
					<?php if (isset($series_list) and $series_list != null) { ?>
					<ul class="inventory-table row">
						
						<?php echo $series_list; ?>
					</ul>
					<?php } else { ?>
						<p>No comics have been entered into the database. Why not add one?</p>
					<?php } ?>
				<?php } else {
						$sql = "SELECT comic_id FROM comics ORDER BY RAND() LIMIT 0,1";
						$result = $connection->query ( $sql );
						while ($row = $result->fetch_assoc()) {
							$comic_id = $row['comic_id'];
						}
						$details = new comicSearch ();
						$details->issueLookup ( $comic_id );
						$details->seriesInfo ( $details->series_id );
					 ?>
<h2><?php echo $details->series_name . " #" . $details->issue_number; ?></h2>
        <div class="series-meta">
					<ul class="nolist">
						<li><?php 
						if ($details->release_date) {
							echo DateTime::createFromFormat('Y-m-d', $details->release_date)->format('M Y');
						}
						 ?>
						</li>
						<li>Volume <?php echo $details->series_vol; ?></li>
					</ul>
				</div>
      </div>
			<div class="col-md-8">
				<div class="issue-story"><h4><?php echo $details->story_name; ?></h4></div>
				<div class="issue-description">
					<?php if ($details->plot != '') {
						echo $details->plot; 
					} else {
						echo 'Plot details have not been entered.';
					}
					?>
				</div>
				<p>
					<?php
						if ($login->isUserLoggedIn () == true) {
							echo "<a href=\"/admin/wikiaedit.php?comic_id=" . $details->comic_id . "&wiki_id=" . $details->wiki_id . "\">Update Info</a>";
						}
					?>
				</p>
			</div>
			<div class="col-md-4 issue-image">
				<img src="<?php echo $details->cover_image; ?>" alt="cover" />
			</div>
			<?php } ?>
			</div>
		</div>
	</div>
<?php include 'views/footer.php';?>
</body>
</html>