<?php
  $followerList = $userInfo->followerList;
  $followerCount = count($followerList);

  if ($followerCount > 0) {
    $followerBlock = '';
    
    foreach ($followerList as $followerUser) {
      $followerSearch = new userInfo ();
      $followerSearch->userMeta($followerUser);

      if (isset($followerSearch->user_first_name)) {
        $follower_first_name = $followerSearch->user_first_name;
      } else {
        $follower_first_name = '';
      }

      if (isset($followerSearch->user_last_name)) {
        $follower_last_name = $followerSearch->user_last_name;
      } else {
        $follower_last_name = '';
      }

      $followerBlock .= '<li><a href="/profile.php?user='. $followerSearch->user_account_name . '">';
      if (isset($followerSearch->user_avatar)) {
        $followerAvatar = $followerSearch->user_avatar;
        $followerBlock .= '<img src="' . $followerSearch->user_avatar . '" alt="' . $follower_first_name . ' ' . $follower_last_name . '" class="img-circle img-responsive" />';
      } else {
        $gravatar_hash = $followerSearch->follow_email_hash;
        $followerAvatar = '//www.gravatar.com/avatar/' . $gravatar_hash . '?s=60&d=' . urlencode('http://comicmanager.com/assets/avatar-deadpool.png');
        $followerBlock .= '<img src="' . $followerAvatar . '" alt="' . $follower_first_name . ' ' . $follower_last_name . '" class="img-circle img-responsive" />';
      }
      $followerBlock .= '</a></li>';
    }
  } else {
    $follows = '';
  }
?>

<div data-module="dashboard_followers" class="follow-module">
  <h3>Followers</h3>
  <ul class="nolist follow-list">
    <?php echo $followerBlock; ?>
  </ul>
</div>