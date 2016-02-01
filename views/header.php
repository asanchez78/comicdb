<!-- Site wrapper begins -->
<div id="wrapper">
	<header class="global-header">
		<div class="container">
			<div class="row">
				<!-- Title -->
				<div class="col-md-9 col-xs-8 logo">
					<h1><a href="/index.php"><img src="../assets/logo.svg" alt="POW! Comic Book Manager" />Comic Book Manager</a></h1>
				</div>
				<!-- Navigation -->
				<nav class="col-md-3 col-xs-4 sitenav">
					<ul class="nolist">
					<?php if ($login->isUserLoggedIn () == true) { ?>
						<li><a href="/add.php" id="button-add-comics">Add Comics</a>
						</li>
						<li><a href="/index.php?logout&m=49" class="logout"><i class="fa fa-sign-out"></i> Logout</a></li>
					<?php } else { ?>
						<li><a href="/login.php?return=<?php echo $current_page; ?>" class="login">Login <i class="fa fa-sign-in"></i></a></li>
					<?php } ?>
					</ul>
				</nav>
			</div>
		</div>
	</header>
	<!-- Site content begins -->
	<main>