<?php
  $userInfo = new userInfo ();
  $userInfo->userMeta($userID);

  if (isset($userInfo->user_first_name)) {
    $first_name = $userInfo->user_first_name;  
  } else {
    $first_name = $userName;
  }

  if (isset($userInfo->user_avatar)) {
    $avatar = $userInfo->user_avatar;
  } else {
    $gravatar_hash = $userEmail;
    $avatar = '//www.gravatar.com/avatar/' . $gravatar_hash . '?s=200&d=' . urlencode('http://comicmanager.com/assets/avatar-deadpool.png');
  }
?>
<div data-module="user_bar">
  <div class="row">
    <div class="col-xs-6">
      <h2>Dashboard</h2>
    </div>
    <div class="hidden-xs hidden-sm col-md-3 col-lg-2 dropdown">
      <button id="addDropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
        <span class="hidden-xs hidden-sm">Add New</span>
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dLabel">
        <li><a href="/add.php#addSingle">Issue</a></li>
        <li><a href="/add.php#addRange">Range of Issues</a></li>
        <li><a href="/add.php#addList">List of Issues</a></li>
        <li><a href="/add.php#addSeries">Series</a></li>
      </ul>
    </div>
    <div class="col-xs-6 col-md-3 col-lg-3 user-profile-button text-right">
      <a href="/profile.php">
        <span class="profile-avatar"><img src="<?php echo $avatar;?>" alt="<?php echo $first_name; ?>" class="img-responsive img-circle" /></span>
        <span class="profile-name"><?php echo $first_name; ?></span>
      </a>
    </div>
  </div>
</div>