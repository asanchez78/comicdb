<?php
  $user_id = filter_input ( INPUT_POST, 'user_id' );
  $input_first_name = filter_input ( INPUT_POST, 'first_name' );
  $input_last_name = filter_input ( INPUT_POST, 'last_name' );
  $input_location = filter_input ( INPUT_POST, 'user_location' );
  $input_facebook = filter_input ( INPUT_POST, 'user_facebook' );
  $input_twitter = filter_input ( INPUT_POST, 'user_twitter' );
  $input_instagram = filter_input ( INPUT_POST, 'user_instagram' );

  // Initialize our meta arrays
  $meta_key[] = '';
  $meta_value[] = '';
  $meta_update[] = '';

  // Look through inputs, push them to the meta arrays
  if (isset($input_first_name)) {
    array_push($meta_key, 'first_name');
    array_push($meta_value, $input_first_name);
  }
  if (isset($input_last_name)) {
    array_push($meta_key, 'last_name');
    array_push($meta_value, $input_last_name);
  }
  if (isset($input_location)) {
    array_push($meta_key, 'location');
    array_push($meta_value, $input_location);
  }
  if (isset($input_facebook)) {
    array_push($meta_key, 'facebook_url');
    array_push($meta_value, $input_facebook);
  }
  if (isset($input_twitter)) {
    array_push($meta_key, 'twitter_url');
    array_push($meta_value, $input_twitter);
  }
  if (isset($input_instagram)) {
    array_push($meta_key, 'instagram_url');
    array_push($meta_value, $input_instagram);
  }

  // Grab the size of the arrays and offset by 1 for [0] array pos
  $array_size = sizeof($meta_key) -1;
  // Loop through the array and push each key into the dB
  $errorSet = '';
  for ($i = 0; $i <= $array_size; $i++) {
    $sql = "INSERT INTO users_meta (user_id, meta_key, meta_value) VALUES ('$user_id', '$meta_key[$i]', '$meta_value[$i]') ON DUPLICATE KEY UPDATE meta_value='$meta_value[$i]'";

    $sqlMessage = '';
    if (mysqli_query ( $connection, $sql )) {
      $editProfileSubmit = true;
      $sqlMessage .= '<strong class="text-success">Success</strong>: Key <code>' . $meta_key[$i] . '</code> updated.<br />' . $sql . '<br />';
    } else {
      $editProfileSubmit = false;
      $sqlMessage .= '<strong class="text-danger">Error</strong>: Key <code>' . $meta_key[$i] . '</code> failed to save.<br />' . $sql . '<code>' . mysqli_error ( $connection ) . '</code><br />';
      $errorSet .= $meta_key[$i] . ', ';
    }
  }
  if ($editProfileSubmit === true) {
    $messageNum = 10;
  } else {
    $messageNum = 67;
  }
?>