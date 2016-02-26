<?php
  $userInfo = new userInfo ();
  $userInfo->userMeta($userID);
  $followList = preg_replace('/}{/', ',', $userInfo->user_follows);
  $followList = substr($followList, 1, -1);
  $feedLength = 150;
  $userInfo->showFeed ($followList, $feedLength);
?>
<section data-module="feed">
  <ul class="nolist user-feed-container">
    <?php echo $userInfo->feed; ?>
  </ul>
</section>