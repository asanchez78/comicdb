<?php


?>
<div data-module="follow_button">
  <form method="post" name="follow_form" action="">
    <input type="hidden" name="profile_id" value="<?php echo $profileID; ?>" />
    <input type="hidden" name="user_id" value="<?php echo $userID; ?>" />
    <input type="hidden" name="followed" value="true" />
    <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-fw fa-user-plus"></i> <span class="hidden-xs hidden-sm">Follow</span></button>
  </form>
</div>