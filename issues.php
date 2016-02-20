<?php
  require_once('views/head.php');
  $issue_list = null;

  $series_id = filter_input ( INPUT_GET, 'series_id' );
  $profile_name = filter_input(INPUT_GET, 'user');

  $comic = new comicSearch ();
  $user = new userInfo ();

  if (isset($profile_name) && $profile_name != '') {
    $user->userLookup($profile_name);
    $profileID = $user->browse_user_id;
  } else {
    $profileID = $userID;
  }

  $comic->seriesInfo( $series_id, $profileID );
  $comic->issuesList ( $series_id, $profileID );
  $publisherID = $comic->publisherID;
  $publisherName = $comic->publisherName;
  $publisherShort = $comic->publisherShort;
  $series_name = $comic->series_name;
  $series_vol = $comic->series_vol;
  $issue_num = $comic->issue_number;

?>
  <title><?php echo $series_name; ?> (Vol <?php echo $series_vol; ?>) :: POW! Comic Book Manager</title>
</head>

<body>
<?php include 'views/header.php';?>
  <div class="issues-list">
    <header class="row headline">
      <div class="col-xs-12 col-md-8 col-lg-7">
        <h2><?php echo $series_name; ?> (<?php echo $series_vol; ?>)</h2>
      </div>
      <div class="col-xs-12 col-md-4 col-lg-5 series-meta">
        <ul class="nolist row">
          <li class="col-xs-4 hidden-md col-lg-4 issue-publisher">
            <?php if ($publisherName) { echo '<a href="/publisher.php?pid=' . $publisherID . '" class="logo-' . $publisherShort .' sm-logo ">' . $publisherName . '</a>'; } ?>
          </li>
          <li class="col-xs-3 col-md-5 col-lg-3 issue-count"><?php echo $comic->series_issue_count; ?></li>
          <li class="col-xs-5 col-md-7 col-lg-5 sort-control-container">
            <button class="btn-xs btn-default sort-control active" id="sort-thumb-lg"><i class="fa fa-th-large"></i></button>
            <button class="btn-xs btn-default sort-control" id="sort-thumb-sm"><i class="fa fa-th"></i></button>
            <button class="btn-xs btn-default sort-control" id="sort-list"><i class="fa fa-list"></i></button>
          </li>
        </ul>
      </div>
    </header>
    <ul id="inventory-table" class="row layout-thumbLg">
      <?php echo $comic->issue_list; ?>
    </ul>
  </div>
  
<?php include 'views/footer.php';?>
</body>
</html>
