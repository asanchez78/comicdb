<?php
  // User meta is set in header. Grab collection and counts already set by userMeta query.
  $userInfo->collectionCount ($userID);
  $userInfo->seriesCount ($userID);
  $userInfo->userFollowedBy ($userID);
  $totalIssues = $userInfo->total_issue_count;
  $totalSeries = $userInfo->total_series_count;

  if (isset($userInfo->user_follows)) {
    $followList = preg_split('/\D/', $userInfo->user_follows, NULL, PREG_SPLIT_NO_EMPTY);
    $followCount = count($followList);
  } else {
    $followList = '';
    $followCount = 0;
  }

  if (isset($userInfo->followerCount)) {
    $followerCount = $userInfo->followerCount;
  } else {
    $followerCount = 0;
  }
?>
<div data-module="count_bar">
  <div class="row">
    <div class="col-xs-6 col-lg-3">
      <strong><i class="fa fa-fw fa-hashtag"></i> Total Comics</strong>
      <span class="big-red"><?php echo $totalIssues; ?></span>
    </div>
    <div class="col-xs-6 col-lg-3">
      <strong><i class="fa fa-fw fa-archive"></i> Total Series</strong>
      <span class="big-red"><?php echo $totalSeries; ?></span>
    </div>
    <div class="col-xs-6 col-lg-3">
      <strong><i class="fa fa-fw fa-users"></i> Following</strong>
      <span class="big-red"><?php if (isset($userInfo->user_follows) && $userInfo->user_follows != '') { echo $followCount; } ?></span>
    </div>
    <div class="col-xs-6 col-lg-3">
      <strong><i class="fa fa-fw fa-users"></i> Followers</strong>
      <span class="big-red"><?php if (isset($userInfo->followerCount)) { echo $followerCount; } ?></span>
    </div>
    <div class="col-xs-12">
      <a href="/profile.php"><i class="fa fa-fw fa-eye"></i> View your collection</a>
    </div>
  </div>
</div>