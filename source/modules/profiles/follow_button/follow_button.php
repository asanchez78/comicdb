<?php
  $followers = new userInfo ();
  $followers->userMeta($userID);

  // Checks to see if the user is already following the profile
  if (isset($followers->user_follows)) {
    $followList = preg_split('/\D/', $followers->user_follows, NULL, PREG_SPLIT_NO_EMPTY);
    foreach ($followList as $followUser) {
      if ($followUser == $profileID) {
        $userFollowing = true;
      }
    }
  }
?>
<div data-module="follow_button">
  <form method="post" name="follow_form" action="">
    <input type="hidden" name="profile_id" value="<?php echo $profileID; ?>" />
    <input type="hidden" name="user_id" value="<?php echo $userID; ?>" />
    <?php if (isset($userFollowing)) {
      if ($userFollowing == true) { ?>
        <input type="hidden" name="followed" value="false" />
        <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-fw fa-user-times"></i> <span class="hidden-xs hidden-sm">Unfollow</span></button>
      <?php } else { ?>
        <input type="hidden" name="followed" value="true" />
        <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-fw fa-user-plus"></i> <span class="hidden-xs hidden-sm">Follow</span></button>
      <?php }
    } else { ?>
      <input type="hidden" name="followed" value="true" />
      <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-fw fa-user-plus"></i> <span class="hidden-xs hidden-sm">Follow</span></button>
    <?php }?>
  </form>
</div>