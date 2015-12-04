<?php
// This will get the current URL the user is on
$current_page = urlencode($_SERVER['REQUEST_URI']);

if ( preg_match('/^%2Fcomicdb%2Fadmin%2F.*/', $current_page)) {
	require_once '../classes/Login.php';
	$background = "../images/background.jpg";
	$multipage = "multiadd.php";
	$multicpage = "multiaddc.php";
	$searchpage = "../search.php";
	$logoutPage = "../logout.php?logout";
} else {
	require_once 'classes/Login.php';
	$background = "images/background.jpg";
	$multipage = "admin/multiadd.php";
	$multicpage = "admin/multiaddc.php";
	$searchpage = "search.php";
	$logoutPage = "logout.php?logout";
}
$login = new Login();
?>

<!-- Uses a transparent header that draws on top of the layout's background -->
	<style>
.demo-layout-transparent {
	background: url('<?php echo $background; ?>') top/cover;
}

.demo-layout-transparent .mdl-layout__header, .demo-layout-transparent .mdl-layout__drawer-button
	{
	/* This background is dark, so we set text to white. Use 87% black instead if
     your background is light. */
	color: black;
}
</style>

	<div class="demo-layout-transparent mdl-layout mdl-js-layout">
		<header class="mdl-layout__header mdl-layout__header--transparent">
			<div class="mdl-layout__header-row">
				<!-- Title -->
				<a class="mdl-navigation__link mdl-layout-title" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/comicdb/index.php">MyComics</a>
				<!-- Add spacer, to align navigation to the right -->
				<div class="mdl-layout-spacer"></div>
				<!-- Navigation -->
				<nav class="mdl-navigation">
					<?php if ($login->isUserLoggedIn () == true) {
						echo "<a class=\"mdl-navigation__link\" href=\"" . $logoutPage . "&return=" . htmlspecialchars($current_page) . "\">Logout</a>";
					} else {
						echo "<a class=\"mdl-navigation__link\" href=\"login.php?return=" . htmlspecialchars($current_page) . "\">Login</a>";
					}
					?>
				</nav>
			</div>
		</header>
		<?php if ($login->isUserLoggedIn () == true) {
		echo '<div class="mdl-layout__drawer">
			<span class="mdl-layout-title">MyComics</span>
			<nav class="mdl-navigation">
				<a class="mdl-navigation__link" href="' . $multipage . '">Add Range</a>
				<a class="mdl-navigation__link" href="' . $multicpage . '">Add Comma Separated List</a>
				<a class="mdl-navigation__link" href="' . $searchpage . '">Add Single Issue</a>
			</nav>
		</div>';
		}
		?>

		<main class="mdl-layout__content">