<?php 
  require_once('views/head.php'); 
?>
  <title>Login to your Account :: POW! Comic Book Manager</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <div>
    <div class="row">
      <div class="col-sm-12">
        <!-- login form box -->
        <header class="headline"><h2>Log into your Account</h2></header>
        <?php if ($login->isUserLoggedIn () != true) { ?>
        <form method="post" action="<?php echo filter_input ( INPUT_GET, 'return' ); ?>" name="loginform" class="form-inline form-login">
        	<div class="form-group">
            <label for="login_input_username">Username</label>
          	<input id="login_input_username" class="login_input form-control" type="text" name="user_name" required />
          </div>
          <div class="form-group">
            <label for="login_input_password">Password</label>
            <input id="login_input_password" class="login_input form-control" type="password" name="user_password" autocomplete="off" required />
          </div>
          <button type="submit" name="login" class="btn btn-danger btn-lg form-submit"><i class="fa fa-sign-in"></i> Login</button>
        </form>
        <?php } else { ?>
          <p>You are already signed in!</p>
          <a href="/index.php" class="btn btn-default">View your collection</a>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php include 'views/footer.php';?>

</body>
</html>