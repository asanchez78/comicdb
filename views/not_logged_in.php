<?php
// show potential errors / feedback (from login object)
if (isset ( $login )) {
	if ($login->errors) {
		foreach ( $login->errors as $error ) {
			echo $error;
		}
	}
	if ($login->messages) {
		foreach ( $login->messages as $message ) {
			echo $message;
		}
	}
}
?>

<!-- login form box -->
<header class="headline">
	<h2>Log On To Continue</h2>
</header>
<form method="post" action="<?php echo $refering_page; ?>" name="loginform" class="form-inline login-form">
	<div class="form-group">
		<label for="login_input_username">Username</label>
		<input id="login_input_username" class="form-control" type="text" name="user_name" required />
	</div>
	<div class="form-group">
		<label for="login_input_password">Password</label>
		<input id="login_input_password" class="form-control" type="password" name="user_password" autocomplete="off" required /> 
	</div>
	<div class="form-group">
		<input type="submit" name="login" value="Log in" class="btn btn-default btn-lg btn-danger form-submit" />
	</div>
</form>