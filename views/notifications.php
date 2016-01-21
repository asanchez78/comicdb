<?php 
  if (isset($messageNum) || isset($_GET['m'])) {
    if (!isset($messageNum) && isset($_GET['m'])) {
      $messageNum = $_GET['m'];
    }
    if ($messageNum < 50) {
      $notifyClass = "bg-success";
    } else {
      $notifyClass = "bg-danger";
      $messageText = '';
      $messageText .= '<strong>ERROR</strong>: ';
      $sqlMessage .= '<strong class="text-danger">ERROR</strong>: ';
    }

    switch ($messageNum) {
    // SUCCESS MESSAGES
      case 1:
        $messageText = "Issue added successfully.";
        break;
      case 2:
        $messageText = "Issues added successfully.";
        break;
      case 3:
        $messageText = '<em>' . $series_name . ' (Vol ' . $series_vol . ')</em> added to your collection successfully.';
        break;
      case 4:
        $messageText = '<em>' . $series_name . ' #' . $first_issue . ' - ' . $last_issue . '</em> added to your collection successfully.';
        break;
      // Single Issue
      case 5:
        $messageText = 'Issue updated successfully.';
        break;
      case 6:
        $messageText = 'Cover image updated successfully';
        break;
      case 7:
        $messageText = $series_name . ' #N cover now set as the series image.';
        break;
      // Range addition
      case 8:
        $messageText = $series_name . ' #' . $series_csv_list . 'added to your collection successfully.';
        break;
      // Registration
      case 9:
        $messageText = 'Your account has been created successfully. You can now <a href="../login.php">log in.</a>';
        break;
      // Logout
      case 49:
        $messageText = "You have been successfully logged out.";
        break;
      // ERROR MESSAGES
      // Error messages are concatenated to append to ERROR: text above
      case 50:
        $messageText .= 'Cannot add <em>' . $series_name . ' (Vol ' . $series_vol . ')</em> to your collection. Series already exists.';
        break;
      case 51:
        $messageText .= 'This issue is already in your collection.';
        break;
      case 52:
        $messageText .= 'User "' . $user . '" not found. Here is a random comic instead.';
        break;
      // Registration
      case 53:
        $messageText .= 'Sorry, registration has been disabled. Please try again later.';
        break;
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
        $messageText .= 'The issues are already in your collection.';
        break;
      case 62:
        $messageText .= 'Could not update issue. An error occurred.';
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
<div class="dev-messages container">
  <div class="row">
    <div class="col-xs-12">
      <?php echo $sqlMessage; ?>
    </div>
  </div>
</div>
<?php } ?>
<?php if(isset($messageText)) { ?>
<div class="notifications <?php echo $notifyClass; ?>">
  <div class="container">
    <div class="row">
      <p class="col-xs-11 notification-text"><?php echo $messageText; ?></p>
      <button type="button" class="close col-xs-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
  </div>
</div>
<?php } ?>
