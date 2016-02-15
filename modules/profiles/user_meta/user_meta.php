<?php
  $comic->collectionCount ($profileID);
  $comic->seriesCount ($profileID);
  $totalIssues = $comic->total_issue_count;

  if (isset($comic->user_avatar)) {
    $avatar = $comic->user_avatar;
  } else {
    $gravatar_hash = $profileEmail;
    $avatar = '//www.gravatar.com/avatar/' . $gravatar_hash . '?s=200&d=' . urlencode('http://comicmanager.com/assets/avatar-deadpool.png');
  }

  if (isset($comic->user_follows)) {
    $followBlock = '';
    $followList = explode(',', $comic->user_follows);
    $followCount = count($followList);
    foreach ($followList as $followUser) {
      $user = new comicSearch ();
      $user->userFollows($followUser);
      $user->userMeta($followUser);

      if (isset($user->user_first_name)) {
        $follow_first_name = $user->user_first_name;  
      } else {
        $follow_first_name = '';
      }

      if (isset($user->user_last_name)) {
        $follow_last_name = $user->user_last_name;
      } else {
        $follow_last_name = '';
      }

      $followBlock .= '<li><a href="/profile.php?user='. $user->follow_username . '">';
      if (isset($user->user_avatar)) {
        $followAvatar = $user->user_avatar;
        $followBlock .= '<img src="' . $user->user_avatar . '" alt="' . $follow_first_name . ' ' . $follow_last_name . '" class="img-circle img-responsive" />';
      } else {
        $gravatar_hash = $user->follow_email_hash;
        $followAvatar = '//www.gravatar.com/avatar/' . $gravatar_hash . '?s=60&d=' . urlencode('http://comicmanager.com/assets/avatar-deadpool.png');
        $followBlock .= '<img src="' . $followAvatar . '" alt="' . $follow_first_name . ' ' . $follow_last_name . '" class="img-circle img-responsive" />';
      }
      $followBlock .= '</a></li>';
    }
  } else {
    $follows = '';
  }

  if (isset($comic->facebook_url)) {
    $facebook_url = $comic->facebook_url;
  } else {
    $facebook_url = '';
  }

  if (isset($comic->twitter_url)) {
    $twitter_url = $comic->twitter_url;
  } else {
    $twitter_url = '';
  }

  if (isset($comic->instagram_url)) {
    $instagram_url = $comic->instagram_url;
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
            <div class="col-xs-6 col-md-2 text-center">
              <h3 class="big-red"><?php echo $totalIssues; ?></h3>
              comics
            </div>
            <div class="hidden-xs hidden-sm col-md-2 text-center">
              <h3 class="big-red"><?php echo $comic->total_series_count; ?></h3>
              series
            </div>
            <div class="col-xs-6 col-md-4 text-center">
              <?php if (isset($comic->user_follows) && $comic->user_follows != '') { ?>
              <h3 class="big-red hidden-md hidden-lg"><?php echo $followCount; ?></h3>
              following
              <div class="hidden-xs hidden-sm">
                <ul class="nolist follow-list">
                  <?php echo $followBlock; ?>
                </ul>
              </div>
              <?php } ?>
            </div>
            <div class="hidden-xs hidden-sm col-md-4 text-center">
              <?php if (isset($comic->user_follows) && $comic->user_follows != '') { ?>
              followers
              <div class="hidden-xs hidden-sm">
                <ul class="nolist follow-list">
                  <?php echo $followBlock; ?>
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