<?php
require_once 'classes/Login.php';
require_once 'config/db.php';
?>

<html>
<head>
<link rel="stylesheet" href="material.min.css">
<script src="material.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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