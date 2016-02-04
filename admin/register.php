<?php
	require_once('../views/head.php');
	require_once(__ROOT__.'/classes/Registration.php');
	$registration = new Registration();
	$allowRegistration = true;
	if ($allowRegistration != true) {
		$messageNum = 53;
	}
	// show potential errors / feedback (from registration object)
	if (isset ( $registration )) {
		if ($registration->errors) {
			foreach ( $registration->errors as $error ) {
				$errorMessage .= $error;
			}
			$isError = true;
		}
		if ($registration->messages) {
			foreach ( $registration->messages as $message ) {
				$successMessage .= $message;
			}
			$isRegistered = true;
		}
	}
?>
	<title>Register :: POW! Comic Book Manager</title>
</head>
<body>
	<?php include(__ROOT__.'/views/header.php'); ?>
	<div class="row">
		<div class="col-sm-12">
			<?php if ($isRegistered != true && $allowRegistration != false ) { ?>
				<header class="headline"><h2>Register for an Account</h2></header>
				<!-- register form -->
				<?php if ($isError == true) { ?>
					<p class="error-message"><?php echo $errorMessage; ?></p>
				<?php } ?>
				<form method="post" action="register.php" name="registerform" class="register-form">
					<!-- the user name input field uses a HTML5 pattern check -->
					<div class="form-group">
						<label for="login_input_username">Username </label> 
						<input id="login_input_username" class="login_input form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
						<p class="help-block">(only letters and numbers, 2 to 64 characters)</p>
					</div>
					<div class="form-group">
					<!-- the email input field uses a HTML5 email type check -->
						<label for="login_input_email">User's email</label> 
						<input id="login_input_email" class="login_input form-control" type="email" name="user_email" required /> 
					</div>
					<div class="form-group">
						<label for="login_input_password_new">Password (min. 6 characters)</label> 
						<input id="login_input_password_new" class="login_input form-control" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
					</div>
					<div class="form-group">
						<label for="login_input_password_repeat">Repeat password</label> 
						<input id="login_input_password_repeat" class="login_input form-control" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
					</div>
					<div class="text-center center-block button-block">
						<button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
						<button type="submit" name="register" class="btn btn-lg btn-success form-submit"><i class="fa fa-sign-in"></i> Register</button>
					</div>
				</form>
			<?php } else { ?>
				<?php if ($allowRegistration == false ) { ?>
					<header class="headline"><h2>Registration closed</h2></header>
					<p>Sorry, come back later.</p>
				<?php } else { ?>
					<header class="headline"><h2>Registration Success!</h2></header>
					<p><?php echo $successMessage; ?></p>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
<?php include(__ROOT__.'/views/footer.php'); ?>
</body>
</html>