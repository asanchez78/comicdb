<?php
  $user->collectionCount ($profileID);
  $user->seriesCount ($profileID);
  $user->userFollowedBy ($profileID);
  $totalIssues = $user->total_issue_count;

  // If the user has uploaded an avatar display it, otherwise show either Gravatar or default avatar.
  if (isset($user->user_avatar)) {
    $avatar = $user->user_avatar;
  } else {
    $gravatar_hash = $profileEmail;
    $avatar = '//www.gravatar.com/avatar/' . $gravatar_hash . '?s=200&d=' . urlencode('http://comicmanager.com/assets/avatar-deadpool.png');
  }

  if (isset($user->user_follows)) {
    $followBlock = '';
    $followList = preg_split('/\D/', $user->user_follows, NULL, PREG_SPLIT_NO_EMPTY);
    $followCount = count($followList);
    foreach ($followList as $followUser) {
      $followSearch = new userInfo ();
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

  if (isset($user->followerCount)) {
    $followerCount = $user->followerCount;
  }
  
  if (isset($user->facebook_url)) {
    $facebook_url = $user->facebook_url;
  } else {
    $facebook_url = '';
  }

  if (isset($user->twitter_url)) {
    $twitter_url = $user->twitter_url;
  } else {
    $twitter_url = '';
  }

  if (isset($user->instagram_url)) {
    $instagram_url = $user->instagram_url;
  } else {
    $instagram_url = '';
  }

?>

<div data-module="user-meta">
  <div class="row">
    <div class="col-xs-2 user-avatar">
      <img src="<?php echo $avatar;?>" alt="" class="img-circle img-responsive" />
    </div>
    <div class="col-xs-10 user-meta-block">
      <div class="row">
        <div class="col-xs-6 col-md-12 user-name">
          <h2><?php echo $first_name . ' ' . $last_name; ?></h2>
          <?php echo $user_location; ?>
          <div class="social-icons">
            <?php if ($facebook_url != '') { ?>
              <a href="<?php echo $facebook_url; ?>" title="View <?php echo $first_name; ?>'s Facebook Profile" target="_blank"><i class="fa fa-fw fa-facebook"></i><span class="sr-only">Facebook</span></a>
            <?php } ?>
            <?php if ($twitter_url != '') { ?>
              <a href="<?php echo $twitter_url; ?>" title="View <?php echo $first_name; ?>'s Twitter Profile" target="_blank"><i class="fa fa-fw fa-twitter"></i><span class="sr-only">Twitter</span></a>
            <?php } ?>
            <?php if ($instagram_url != '') { ?>
              <a href="<?php echo $instagram_url; ?>" title="View <?php echo $first_name; ?>'s Instagram Gallery" target="_blank"><i class="fa fa-fw fa-instagram"></i><span class="sr-only">Instagram</span></a>
            <?php } ?>
          </div>
          <?php if (isset($profile_name) && $profile_name != '' && $profile_name != $userName) { ?>
          <div class="hidden-xs hidden-sm">
            <?php include (__ROOT__.'/modules/profiles/follow_button/follow_button.php'); ?>
          </div>
          <?php } ?>
        </div>
        <div class="col-xs-6 col-md-12 user-count">
          <div class="row">
            <div class="col-xs-6 col-md-2 text-center meta-total-issues">
              <h3 class="big-red"><?php echo $totalIssues; ?></h3>
              comics
            </div>
            <div class="hidden-xs hidden-sm col-md-2 text-center meta-total-series">
              <h3 class="big-red"><?php echo $user->total_series_count; ?></h3>
              series
            </div>
            <div class="col-xs-6 col-md-4 text-center meta-following">
              <?php if (isset($user->user_follows) && $user->user_follows != '') { ?>
              <h3 class="big-red hidden-md hidden-lg"><?php echo $followCount; ?></h3>
              following
              <div class="hidden-xs hidden-sm">
                <ul class="nolist follow-list">
                  <?php echo $followBlock; ?>
                </ul>
              </div>
              <?php } ?>
            </div>
            <div class="hidden-xs hidden-sm col-md-4 text-center meta-followers">
              <?php if (isset($user->followerList) && $user->followerList != '') { ?>
              <h3 class="big-red"><?php echo $followerCount; ?></h3>
              followers
              <div class="hidden-xs hidden-sm">
                <ul class="nolist follow-list">
                  
                </ul>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>