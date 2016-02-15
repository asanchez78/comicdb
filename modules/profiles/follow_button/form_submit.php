<?php
  // This local function will do the brunt of the work for us, so all we have to do is pass in a few variables to it.
  $followUser = function ($profile_id, $user_id, $toggle) {
    $connection = mysqli_connect ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    $fieldCheck = new comicSearch ();
    
    // Let's grab the users's current follow list to compare.
    $fieldCheck->userMeta($user_id);
    if (isset($fieldCheck->user_follows)) {
      // Split the list string into an array
      $followList = preg_split('/\D/', $fieldCheck->user_follows, NULL, PREG_SPLIT_NO_EMPTY);
      $followCount = count($followList);
    } else {
      $followCount = 0;
    }

    // Follow / Unfollow
    if ($toggle == 'true') {
      // Follow the user
      if (isset($fieldCheck->user_follows)) {
        $sql = "UPDATE users_meta
          SET meta_value = CONCAT_WS('', meta_value, '{{$profile_id}}') 
          WHERE meta_key='user_follows' AND user_id='$user_id'";
      } else {
        $sql = "INSERT INTO users_meta (user_id, meta_key, meta_value) 
          VALUES ('$user_id', 'user_follows', '{{$profile_id}}')";
      }
    } else {
      // Unfollow the user
      if ($followCount > 1) {
        $sql = "UPDATE users_meta 
          SET meta_value=REPLACE(meta_value,'{{$profile_id}}','') 
          WHERE meta_key='user_follows' 
          AND user_id='$user_id'";
      } else {
        // Is this the only user? Remove the key.
        $sql = "DELETE FROM users_meta 
          WHERE meta_key='user_follows' 
          AND user_id='$user_id'";
      }
    }
    if (mysqli_query ( $connection, $sql )) {
      echo $sql;
      //$sqlMessage .= '<strong class="text-success">Success</strong>: User ' . $profile_id . ' follow status updated.<br />' . $sql . '<br />';
    } else {
      //$sqlMessage .= '<strong class="text-success">Failure</strong>: User ' . $profile_id . ' follow status not updated.<br />' . $sql . '<br />' . $sql . '<code>' . mysqli_error ( $connection ) . '</code><br />';
    }
  };

  // Grab the field values
  $follow_profile_id = filter_input ( INPUT_POST, 'profile_id' );
  $follow_user_id = filter_input ( INPUT_POST, 'user_id' );
  
  // Call the function
  $followUser($follow_profile_id, $follow_user_id, $followToggle);
?>