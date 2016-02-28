<?php
  $feedLength = 10;
  $followList = preg_replace('/}{/', ',', $userInfo->user_follows);
  $followList = substr($followList, 1, -1);
  $userInfo->showFeed ($followList, $feedLength);
?>
<div data-module="user_feed">
  <h3>Recent activity</h3>
  <ul class="nolist user-feed-container">
    <?php echo $userInfo->feed; ?>
  </ul>
  <a href="/feed.php">See more recent activity</a>
</div>