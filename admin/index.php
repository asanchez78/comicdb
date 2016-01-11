<?php
	require_once('../views/head.php');
?>
	<title>Admin :: comicDB</title>
</head>
<body>
	<?php include(__ROOT__.'/views/header.php'); ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<?php
					if ($login->isUserLoggedIn () == false) {
						include (__ROOT__."/views/not_logged_in.php");
						die ();
					}
				?>
				<ul class="nolist">
					<li><a href="new.php">Add a single comic with details.</a></li>
					<li><a href="multiadd.php">Add a ranged comics without details.</a></li>
					<li><a href="multiaddc.php">Add a comma separated list of comics without details.</a></li>
					<li><a href="index.php?logout">Logout</a></li>
				</ul>
			</div>
		</div>
	</div>
<?php include(__ROOT__.'/views/footer.php'); ?>
</body>
</html>
