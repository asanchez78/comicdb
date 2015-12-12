<?php include 'views/head.php'; ?>
  <title>Login to your Account :: comicDB</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <!-- login form box -->
        <h2>Log into your Account</h2>
        <form method="post" action="<?php echo filter_input ( INPUT_GET, 'return' ); ?>" name="loginform">
        	<div>
            <label for="login_input_username">Username</label>
          	<input id="login_input_username" class="login_input" type="text" name="user_name" required />
          </div>
          <div>
            <label for="login_input_password">Password</label>
            <input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required />
          </div>
        	<input type="submit" name="login" value="Log in" />
        </form>
      </div>
    </div>
  </div>
  <?php include 'views/footer.php';?>

</body>
</html>