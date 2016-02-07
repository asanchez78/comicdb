<!-- if you need user information, just put them into the $_COOKIE variable and output them here -->
Hey, <?php echo __userName__; ?>. You are logged in.
Try to close this browser tab and open it again. Still logged in! ;)

<!-- because people were asking: "index.php?logout" is just my simplified form of "index.php?logout=true" -->
<a href="index.php?logout">Logout</a>
