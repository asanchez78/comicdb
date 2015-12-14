<!-- Site wrapper begins -->
<div id="wrapper">
	<header>
		<div class="container">
			<div class="row">
				<!-- Spacer -->
				<div class="col-md-4 hidden-sm"></div>
				<!-- Title -->
				<div class="col-md-4 col-sm-12 logo">
					<h1><a href="/index.php">comicDB</a></h1>
				</div>
				<!-- Navigation -->
				<nav class="col-md-4 col-sm-12 sitenav">
					<ul class="nolist">
					<?php if ($login->isUserLoggedIn () == true) { ?>
						<li><a href="#" id="button-add-comics">Add Comics</a>
							<ul id="comics-submenu" class="nolist">
								<li><a href="/admin/addseries.php">Add Series</a></li>
								<li><a href="/search.php">Add Single Issue</a></li>
								<li><a href="/admin/multiadd.php">Add Range</a></li>
								<li><a href="/admin/multiaddc.php">Add Comma Separated List</a></li>
							</ul>
						</li>
						<li><a href="/logout.php?logout&return=<?php echo $current_page; ?>">Logout</a></li>
					<?php } else { ?>
						<li><a href="/login.php?return=<?php echo $current_page; ?>">Login</a></li>
					<?php } ?>
					</ul>
				</nav>
			</div>
		</div>
	</header>
	<!-- Site content begins -->
	<main>