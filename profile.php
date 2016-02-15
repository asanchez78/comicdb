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

  if (isset($comic->user_location)) {
    $user_location = $comic->user_location;
  } else {
    $user_location = '';
  }

  $editMode = filter_input ( INPUT_POST, 'edit_submit' );

  if (isset($editMode) && $editMode === 'true') {
    include ('modules/profiles/edit_profile/form_submit.php');
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
    <?php include ('modules/profiles/user_meta/user_meta.php'); ?>
    <div class="profile-button-block">
      <?php if ($login->isUserLoggedIn () == true && !isset($profile_name)) { ?>
        <button class="btn btn-link button-settings" data-toggle="modal" data-target="#editProfileModal"><i class="fa fa-fw fa-gear"></i> <span class="sr-only">Edit Profile</span></button>
      <?php } ?>
      <button class="btn btn-link button-share"><i class="fa fa-fw fa-share"></i></button>
    </div>
  </header>
  <?php if ($login->isUserLoggedIn () == true && !isset($profile_name)) { ?>
    <?php include ('modules/profiles/edit_profile/edit_profile_modal.php'); ?>
  <?php } ?>
  <?php include ('modules/series_list/series_list.php'); ?>

<?php include 'views/footer.php';?>
</body>
</html>