<?php include '../views/head.php'; ?>
	<title>Admin</title>
</head>
<body>
<?php include '../views/header.php';
	if ($login->isUserLoggedIn () == false) {
	include ("../views/not_logged_in.php");
	die ();
}

?>
	<a href="new.php">Add a single comic with details.</a>
	<br />
	<a href="multiadd.php">Add a ranged comics without details.</a>
	<br />
	<a href="multiaddc.php">Add a comma separated list of comics without details.</a>
	<br />
	<a href="index.php?logout">Logout</a>
</body>
</html>
