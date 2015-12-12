<?php
// This will get the current URL the user is on
$current_page = htmlspecialchars(urlencode($_SERVER['REQUEST_URI']));
require_once(__ROOT__.'/classes/Login.php');
$login = new Login();
?>

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
					<?php if ($login->isUserLoggedIn () == true) { ?>
						<a href="#" id="button-add-comics">Add Comics</a>
						<div id="comics-submenu">
							<a href="/admin/addseries.php">Add Series</a>
							<a href="/search.php">Add Single Issue</a>
							<a href="/admin/multiadd.php">Add Range</a>
							<a href="/admin/multiaddc.php">Add Comma Separated List</a>
						</div>
						<a href="/logout.php?logout&return=<?php echo $current_page; ?>">Logout</a>
					<?php } else { ?>
						<a href="/login.php?return=<?php echo $current_page; ?>">Login</a>
					<?php } ?>
				</nav>
			</div>
		</div>
	</header>
	<!-- Site content begins -->
	<main>