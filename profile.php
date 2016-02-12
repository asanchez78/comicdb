<?php
  require_once('views/head.php');

  $profile_name = filter_input(INPUT_GET, 'user');
  $comic = new comicSearch ();
  if (isset($profile_name) && $profile_name != '') {
    $comic->userLookup($profile_name);
    $comic->userMeta($comic->browse_user_id);
    $comic->collectionCount ($comic->browse_user_id);
  } else {
    $comic->userMeta($userID);
    $comic->collectionCount ($userID);
  }
  $totalIssues = $comic->total_issue_count;
  if (isset($comic->user_first_name)) {
    $first_name = $comic->user_first_name;  
  } else {
    $first_name = '';
  }
  if (isset($comic->user_last_name)) {
    $last_name = $comic->user_last_name;
  } else {
    $last_name = '';
  }
  if (isset($comic->user_location)) {
    $location = $comic->user_location;
  } else {
    $location = '';
  }
  if (isset($comic->user_avatar)) {
    $avatar = $comic->user_avatar;
  } else {
    $avatar = '';
  }
  if (isset($comic->user_follows)) {
    $follows = $comic->user_follows;
    $user = new comicSearch ();
    $user->userMeta($follows);
    if (isset($user->user_avatar)) {
      $followAvatar = '<a href="/profiles.php?user='. $profile_name . '"><img src="' . $user->user_avatar . '" alt="" class="img-circle img-responsive" /></a>';
    } else {
      $followAvatar = '';
    }
  } else {
    $follows = '';
  }
?>
  <title><?php if (isset($profile_name) && $profile_name != '') { echo $first_name . ' ' . $last_name; } else { echo 'Your Profile'; } ?> :: POW! Comic Book Manager</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <?php if ($login->isUserLoggedIn () == false) {
    include (__ROOT__."/views/not_logged_in.php");
    die ();
  } ?>
  <header class="row headline">
    <div class="col-xs-12 col-md-5 col-lg-6">
      <div class="user-avatar pull-left" style="width: 80px;"><img src="<?php echo $avatar;?>" alt="" class="img-circle img-responsive" /></div>
      <h2><?php if (isset($profile_name) && $profile_name != '') { echo $first_name . ' ' . $last_name; } else { echo 'Your Profile'; } ?></h2>
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
    Hello <?php echo $first_name . ' ' . $last_name . ' in ' . $location; ?><br />
    </div>
    <div class="col-md-4 sidebar">
      Following: 
      <ul class="list-inline follow-list">
        <li class="col-xs-2"><?php echo $followAvatar; ?></li>
        <li class="col-xs-2"><?php echo $followAvatar; ?></li>
      </ul>
    </div>
  </div>
<?php include 'views/footer.php';?>
</body>
</html>