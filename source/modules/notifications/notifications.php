<?php 
  if (isset($messageNum) || isset($_GET['m']) || isset ($_POST ["messageNum"])) {
    if (!isset($messageNum) && isset($_GET['m'])) {
      $messageNum = $_GET['m'];
    }
    if (!isset($messageNum) && isset($_POST ["messageNum"])) {
      $messageNum = $_POST ["messageNum"];
    }
    if ($messageNum < 50) {
      $notifyClass = "alert-success";
    } else {
      $notifyClass = "alert-danger";
      $messageText = '';
      $messageText .= '<strong>ERROR</strong>: ';
    }

    switch ($messageNum) {
    // SUCCESS MESSAGES
      case 1:
        $messageText = 'Issue added successfully. <a href="/comic.php?comic_id="' . $comic_id . '">View issue.</a>';
        break;
      case 2:
        $messageText = 'Issues added successfully.';
        break;
      case 3:
        $messageText = '<em>' . $series_name . ' (' . $series_vol . ')</em> added to your collection successfully.';
        break;
      // Add Range Success
      case 4:
        $messageText = '<em>' . $series_name . ' #' . $first_issue . ' - ' . $last_issue . '</em> added to your collection successfully.';
        break;
      // Edit Issue
      case 5:
        $messageText = 'Issue updated successfully. The following fields were updated: ' . $updatedSet;
        break;
      // Cover Image Update        
      case 6:
        $messageText = 'Cover image updated successfully';
        break;
      // Series Cover Set  
      case 7:
        $messageText = $series_name . ' #N cover now set as the series image.';
        break;
      // CSV addition Success
      case 8:
        $messageText = $series_name . ' #' . $filtered_issue_list . ' added to your collection successfully.';
        break;
      // Registration
      case 9:
        $messageText = 'Your account has been created successfully. You can now <a href="../login.php">log in.</a>';
        break;
      // User Profiles
      case 10:
        $messageText = 'Your profile has been updated.';
        break;
      // Logout
      case 49:
        $messageText = "You have been successfully logged out.";
        break;
      // ERROR MESSAGES
      // Error messages are concatenated to append to ERROR: text above
      case 50:
        $messageText .= 'Cannot add <em>' . $series_name . ' (' . $series_vol . ')</em> to your collection. Series already exists.';
        break;
      // Single Issue Add duplicate
      case 51:
        $messageText .= 'This issue is already in your collection.';
        break;
      // User search fail
      case 52:
        $messageText .= 'User "' . $userSetName . '" not found. Here is a random comic instead.';
        break;
      // Registration
      case 53:
        $messageText .= 'Sorry, registration has been disabled. Please try again later.';
        break;
      // Registration account exist
      case 54:
        $messageText .= 'Sorry, that USERNAME/EMAIL exists. Try to login(link) or create a new account.';
        break;
      case 55:
        $messageText .= 'Sorry, your registration failed. Please go back and try again.';
        break;
      case 56:
        $messageText .= 'That account does not exist. Please try again or <a href="/admin/register.php">register</a> for an account.';
        break;
      case 57:
        $messageText .= 'That password is incorrect, please try again.';
        break;
      case 58:
        $messageText .= 'Username field was empty.';
        break;
      case 59:
        $messageText .= 'Password field was empty.';
        break;
      case 60:
        $messageText .= 'Publisher data missing.';
        break;
      case 61:
        // Duplicate range/list addition issue.
        $messageText .= 'The issues are already in your collection.';
        break;
      case 62:
        // Update Issue: Could not connect to database to update the issue.
        $messageText .= 'Could not update issue. An error occurred.';
        break;
      case 63:
        // Update Issue: User tried to update an issue that is either not in their collection or they are not logged in.
        $messageText .= 'Could not update issue. An error occurred: You are not logged in.';
        break;
      case 64:
        // User search, logged in but invalid user name
        $messageText .= 'User "' . $userSetName . '" not found.';
        break;
      case 65:
        // Add Single Comic: Cannot find issue on ComicVine
        $messageText .= 'Cannot find this issue on ComicVine. Please try again or check the issue number.';
        break;
      case 66:
        // SQL Error: Syntax
        $messageText .= 'A problem occurred with the server. Please try again later.';
        break;
      case 67:
        // Update profile error
        $messageText .= 'Could not update the following fields ' . $errorSet;
        break;
      case 90:
        $messageText .= 'Sorry, no database connection.';
        break;
      case 99:
        $messageText .= 'An unknown error occurred. Please try again later.';
        break;
    }
  } else {
    $notifyClass = "notifications-hide";
  }
?>
<?php if(isset($sqlMessage)) { ?>
<div class="dev-messages">
  <div class="row">
    <div class="col-xs-12">
      <?php echo $sqlMessage; ?>
    </div>
  </div>
</div>
<?php } ?>
<?php if(isset($messageText)) { ?>
<div class="alert alert-dismissible notifications <?php echo $notifyClass; ?>" role="alert">
  <div class="row">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <p class="col-xs-11 notification-text"><?php echo $messageText; ?></p>
  </div>
</div>
<?php } ?>
