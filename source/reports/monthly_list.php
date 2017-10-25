<?php
  require_once('../views/head.php');
?>
  <title>POW! Month Results</title>
</head>
<body>
  <?php
  // If users is logged in, shows a random comic from their collection. Otherwise just shows a random comic.
  if ($login->isUserLoggedIn () == true) {
    include '../views/header.php';
    //include 'modules/user_bar/user_bar.php';
    //include 'views/dashboard.php';
    $db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($db_connection->connect_errno) {
      die ( "Connection failed: " );
    }
    $month = filter_input ( INPUT_GET, 'month' );
    $year = filter_input ( INPUT_GET, 'year' );
    $sort = filter_input ( INPUT_GET, 'sort' );
    $sql = "SELECT series.series_name, comics.issue_number, comics.release_date, comics.comic_id, comics.cover_image
    		FROM comics
			left join series
			on comics.series_id=series.series_id
			left join users_comics
			on users_comics.comic_id=comics.comic_id
			left join users
			on users.user_id=users_comics.user_id
			where (YEAR(release_date) = '$year' and MONTH(release_date) = '$month') and users.user_name='$userName'
			order by comics.issue_number";
    $result = $db_connection->query ( $sql );
    $myArray = array();
    $issue_list = "";
    if ($result->num_rows > 0) {
      while ( $row = $result->fetch_assoc () ) {
        $comic_id = $row ['comic_id'];
        $issue_number = $row ['issue_number'];
        $release_date = $row ['release_date'];
        $cover_image = $row ['cover_image'];
        $series_name = $row ['series_name'];
        //print $series_name . " " . $issue_number . " " . $release_date . "<br>";

        $coverMed = $row ['cover_image'];
        $coverSmall = str_replace('-medium.', '-small.', $coverMed);
        $coverThumb = str_replace('-medium.', '-thumb.', $coverMed);
        $issue_list .= '<li class="col-xs-6 col-sm-4 col-md-3 col-lg-2" id="issue-' . $issue_number . '"><div class="series-list-row">';
        $issue_list .= '<a href="comic.php?comic_id=' . $comic_id . '" class="issue-info"><div class="series-list-row">';
        //$issue_list .= '<div class="comic-image"><img src="' . $coverSmall . '" alt="" class="img-responsive" /></div>';
        //$issue_list .= '<div class="story-name"><h3>' . $story_name . '</h3></div>';
        $issue_list .= '<div class="story-name"><h3>' . $series_name . '</h3></div>';
        $issue_list .= '</div></a>';
        $issue_list .= '<div class="issue-number text-uppercase">#' . $issue_number . '</div>';
        $issue_list .= '<div class="release-date text-uppercase">' . $release_date . '</div>';
        $issue_list .= '</div></li>';
        //print "<hr>";
      }
      // while($row = $result->fetch_assoc()) {
      // 	$myArray[]=$row;
      // }
      // $json = json_encode($myArray, JSON_PRETTY_PRINT);
      // echo "<pre>";
      // echo $json;
      // echo "</pre>";
    }
  } else {
    include '../views/splash.php';
  }
  ?>
  <div class="issues-list">
    <header class="row headline">
      <div class="col-xs-12 col-md-8 col-lg-7">
        <h2> <?php echo $month; ?>&nbsp 1993</h2>
      </div>
      <div class="col-xs-12 col-md-4 col-lg-5 series-meta">
        <ul class="nolist row">
           <!-- <li class="col-xs-4 hidden-md col-lg-4 issue-publisher">
            <?php if ($publisherName) { echo '<a href="/publisher.php?pid=' . $publisherID . '" class="logo-' . $publisherShort .' sm-logo ">' . $publisherName . '</a>'; } ?>
          </li> -->
          <li class="col-xs-5 col-md-7 col-lg-5 sort-control-container">
            <button class="btn-xs btn-default sort-control" id="sort-thumb-lg"><i class="fa fa-th-large"></i></button>
            <button class="btn-xs btn-default sort-control" id="sort-thumb-sm"><i class="fa fa-th"></i></button>
            <button class="btn-xs btn-default sort-control active" id="sort-list"><i class="fa fa-list"></i></button>
          </li>
        </ul>
      </div>
    </header>
    <ul id="inventory-table" class="row layout-thumbLg">
      <?php echo $issue_list; ?>
    </ul>
  </div>
<?php include '../views/footer.php';?>
</body>
</html>