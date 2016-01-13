<!-- Site wrapper begins -->
<div id="wrapper">
	<header>
		<div class="container">
			<div class="row">
				<!-- Title -->
				<div class="col-md-9 col-xs-12 logo">
					<h1><a href="/index.php"><img src="../assets/logo.svg" alt="POW! Comic Book Manager" />Comic Book Manager</a></h1>
				</div>
				<!-- Navigation -->
				<nav class="col-md-3 col-xs-12 sitenav">
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
						<li><a href="/index.php?logout&message=49">Logout</a></li>
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
		<?php 
			$message = $_GET['message'];
			// 50 is the start of error messaging
			if (isset($message)) {
				if ($message < 50) {
					$notifyClass = "bg-success";	
				} else {
					$notifyClass = "bg-danger";
				}
				
				switch ($message) {
					case 1:
						$messageText = "Issue added successfully.";	
						break;
					case 2:
						$messageText = "Issues added successfully.";	
						break;
					case 3:
						$messageText = "Series added successfully.";
						break;
					case 49:
						$messageText = "You have been successfully logged out.";
						break;
				}
			} else {
				$notifyClass = "notifications-hide";
			}
		?>
		<div class="notifications <?php echo $notifyClass; ?>">
			<div class="container">
				<div class="row">
					<p class="col-xs-11 notification-text"><?php echo $messageText; ?></p>
					<button type="button" class="close col-xs-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			</div>
		</div>