<?php
  require_once('views/head.php');
  $issue_list = null;

  $series_id = filter_input ( INPUT_GET, 'series_id' );
  $comic = new comicSearch ();

  if (isset($userSetID) && $validUser == 1) {
    $comic->seriesInfo ( $series_id, $userSetID );
    $comic->issuesList ( $series_id, $userSetID );
  } else {
    $comic->seriesInfo( $series_id, $userID );
    $comic->issuesList ( $series_id, $userID );
  }
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
  <div class="container issues-list content">
    <header class="row headline">
      <div class="col-xs-12 col-md-7">
        <h2><?php echo $series_name; ?></h2>
      </div>
      <div class="col-xs-12 col-md-5 series-meta text-right">
        <ul class="nolist">
          <?php if ($publisherName) { echo '<li class="logo-' . $publisherShort .' sm-logo"><a href="/publisher.php?pid=' . $publisherID . '">' . $publisherName . '</a></li>'; } ?>
          <li><?php echo $comic->series_issue_count; ?></li>
          <li>Volume <?php echo $series_vol; ?></li>
          <li>
            <button class="btn-xs btn-default sort-control active" id="sort-thumb-lg"><i class="fa fa-th-large"></i></button>
            <button class="btn-xs btn-default sort-control" id="sort-thumb-sm"><i class="fa fa-th"></i></button>
            <button class="btn-xs btn-default sort-control" id="sort-list"><i class="fa fa-list"></i></button>
          </li>
        </ul>
      </div>
    </header>
    <ul id="inventory-table" class="row layout-thumb-lg">
      <?php echo $comic->issue_list; ?>
    </ul>
  </div>
  
<?php include 'views/footer.php';?>
</body>
</html>
