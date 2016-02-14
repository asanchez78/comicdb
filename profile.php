<?php
  require_once('views/head.php');

  $profile_name = filter_input(INPUT_GET, 'user');
  $comic = new comicSearch ();

  if (isset($profile_name) && $profile_name != '') {
    $comic->userLookup($profile_name);
    $profileID = $comic->browse_user_id;
  } else {
    $profileID = $userID;
  }
  // Grab all fields from users_meta for the user ID
  $comic->userMeta($profileID);

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
  if ($first_name == '' && $last_name == '') {
    $first_name = 'Username';
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
  <header class="row profile-header">
    <div class="profile-background-container">
      <div class="profile-background">
        <div class="row">
          <?php 
            // Grabs 36 random covers for the profile page header
            $comic->userCovers($profileID);
            echo $comic->cover_list; 
          ?>
        </div>
      </div>
    </div>
    <?php include ('modules/profiles/user_meta.php'); ?>
    <div class="profile-button-block">
      <button class="btn btn-link button-settings"><i class="fa fa-fw fa-gear"></i></button>
      <button class="btn btn-link button-share"><i class="fa fa-fw fa-share"></i></button>
    </div>
  </header>
  <?php include ('modules/series_list/series_list.php'); ?>
<?php include 'views/footer.php';?>
</body>
</html>