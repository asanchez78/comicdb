<?php
  require_once 'classes/Login.php';
  require_once 'config/db.php';
?>
<?php include 'views/head.php';?>
  <title>Comicdb</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <div class="mdl-layout">
    <div class="mdl-grid">
      <!-- login form box -->
      <form method="post" action="<?php echo filter_input ( INPUT_GET, 'return' ); ?>" name="loginform">
      	<label for="login_input_username">Username</label>
      	<br/>
      	<input id="login_input_username" class="login_input" type="text" name="user_name" required />
      	<br/>
      	<label for="login_input_password">Password</label>
      	<br/>
      	<input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required />
      	<br/>
      	<input type="submit" name="login" value="Log in" />
      </form>
    </div>
  </div>
  <?php include 'views/footer.php';?>

</body>
</html>