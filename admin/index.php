<?php 
	define('__ROOT__', dirname(dirname(__FILE__)));
	include(__ROOT__.'/views/head.php');
?>
	<title>Admin :: comicDB</title>
</head>
<body>
<?php 
	include(__ROOT__.'/views/header.php');
	if ($login->isUserLoggedIn () == false) {
		include (__ROOT__."/views/not_logged_in.php");
		die ();
	}
?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<a href="new.php">Add a single comic with details.</a>
				<a href="multiadd.php">Add a ranged comics without details.</a>
				<a href="multiaddc.php">Add a comma separated list of comics without details.</a>
				<a href="index.php?logout">Logout</a>
			</div>
		</div>
	</div>
<?php include(__ROOT__.'/views/footer.php'); ?>	
</body>
</html>
