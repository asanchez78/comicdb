<?php
  if (isset($userInfo->user_follows)) {
    $followBlock = '';
    $followList = preg_split('/\D/', $userInfo->user_follows, NULL, PREG_SPLIT_NO_EMPTY);
    $followCount = count($followList);
    foreach ($followList as $followUser) {
      $followSearch = new comicSearch ();
      $followSearch->userFollows($followUser);
      $followSearch->userMeta($followUser);

      if (isset($followSearch->user_first_name)) {
        $follow_first_name = $followSearch->user_first_name;  
      } else {
        $follow_first_name = '';
      }

      if (isset($followSearch->user_last_name)) {
        $follow_last_name = $followSearch->user_last_name;
      } else {
        $follow_last_name = '';
      }

      $followBlock .= '<li><a href="/profile.php?user='. $followSearch->follow_username . '">';
      if (isset($followSearch->user_avatar)) {
        $followAvatar = $followSearch->user_avatar;
        $followBlock .= '<img src="' . $followSearch->user_avatar . '" alt="' . $follow_first_name . ' ' . $follow_last_name . '" class="img-circle img-responsive" />';
      } else {
        $gravatar_hash = $followSearch->follow_email_hash;
        $followAvatar = '//www.gravatar.com/avatar/' . $gravatar_hash . '?s=60&d=' . urlencode('http://comicmanager.com/assets/avatar-deadpool.png');
        $followBlock .= '<img src="' . $followAvatar . '" alt="' . $follow_first_name . ' ' . $follow_last_name . '" class="img-circle img-responsive" />';
      }
      $followBlock .= '</a></li>';
    }
  } else {
    $follows = '';
  }
?>
<div data-module="dashboard_following" class="follow-module">
  <h3>Following</h3>
  <ul class="nolist follow-list">
    <?php echo $followBlock; ?>
  </ul>
</div>