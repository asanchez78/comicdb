<?php
  require_once('views/head.php');

  $comic = new comicSearch ();
  $comic->userMeta($userID);
  $comic->collectionCount ($userID);
  $totalIssues = $comic->total_issue_count;
?>
  <title>Your Collection :: POW! Comic Book Manager</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <?php if ($login->isUserLoggedIn () == false) {
    include (__ROOT__."/views/not_logged_in.php");
    die ();
  } ?>
  <header class="row headline">
    <div class="col-xs-12 col-md-5 col-lg-6">
      <h2>Your Profile</h2>
    </div>
    <div class="col-xs-12 col-md-7 col-lg-6 series-meta">
      <ul class="nolist row">
        <li class="col-xs-4 col-md-4 col-lg-4"><span class="text-danger"><?php echo $totalIssues; ?></span> Total Issues</li>
        <li class="col-xs-3 col-md-3 col-lg-4"><span class="text-danger">XXX</span> Total Series</li>
        <li class="col-xs-5 col-md-5 col-lg-4 sort-control-container">
          <button class="btn-xs btn-default sort-control active" id="sort-thumb-lg"><i class="fa fa-th-large"></i></button>
          <button class="btn-xs btn-default sort-control" id="sort-thumb-sm"><i class="fa fa-th"></i></button>
          <button class="btn-xs btn-default sort-control" id="sort-list"><i class="fa fa-list"></i></button>
        </li>
      </ul>
    </div>
  </header>
  <div class="row">
    <div class="col-md-8">
      Hello <?php echo $first_name . ' ' . $last_name; ?><br />
      
    </div>
    <div class="col-md-4 sidebar">
      
    </div>
  </div>
<?php include 'views/footer.php';?>
</body>
</html>