<?php
  $followUser = function ($profile_id, $user_id, $toggle) {
    $connection = mysqli_connect ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    $fieldCheck = new comicSearch ();
    $fieldCheck->userMeta($user_id);
    if (isset($fieldCheck->user_follows)) {
      $followList = explode(',', $fieldCheck->user_follows);
      $followCount = count($followList);
    } else {
      $followCount = 0;
    }
    if ($toggle == 'true') {
      if (isset($fieldCheck->user_follows)) {
        if ($followCount >= 1) {
          $sql = "UPDATE users_meta
          SET meta_value = CONCAT_WS(',', meta_value, '$profile_id') 
          WHERE meta_key='user_follows' AND user_id='$user_id'";
        } else {
          $sql = "UPDATE users_meta
          SET meta_value = '$profile_id'
          WHERE meta_key='user_follows' AND user_id='$user_id'";
        }
      } else {
        $sql = "INSERT INTO users_meta (user_id, meta_key, meta_value) VALUES ('$user_id', 'user_follows', '$profile_id')";
      }
    } else {
      if ($followCount > 1) {
        $sql = "UPDATE users_meta SET meta_value=REPLACE(meta_value,',$profile_id','') WHERE meta_key='user_follows' AND user_id='$user_id'";
      } else {
        $sql = "DELETE FROM users_meta WHERE meta_key='user_follows' AND user_id='$user_id'";
      }
    }
    if (mysqli_query ( $connection, $sql )) {
      echo $sql;
      //$sqlMessage .= '<strong class="text-success">Success</strong>: User ' . $profile_id . ' follow status updated.<br />' . $sql . '<br />';
    } else {
      //$sqlMessage .= '<strong class="text-success">Failure</strong>: User ' . $profile_id . ' follow status not updated.<br />' . $sql . '<br />' . $sql . '<code>' . mysqli_error ( $connection ) . '</code><br />';
    }
  };

  $follow_profile_id = filter_input ( INPUT_POST, 'profile_id' );
  $follow_user_id = filter_input ( INPUT_POST, 'user_id' );
  
  $followUser($follow_profile_id, $follow_user_id, $followToggle);
?>