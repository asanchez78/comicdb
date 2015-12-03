<?php
require_once '../classes/Login.php';
require_once '../config/db.php';
?>
<html>
<head>
<link rel="stylesheet" href="../material.min.css">
<script src="../material.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comicdb</title>

</head>

<body>
<?php include '../views/header.php';
?>

<div class="mdl-layout">
<div class="mdl-grid">
<?php
if (!$_POST) {
	$filename=$_SERVER["PHP_SELF"];
	?>
	<form method="post" action="<?php echo $filename; ?>">
		<label>Series Name</label>
		<input name="series_name" type="text" size="50" value=""/>
		<input type="submit" name="submit" value="Submit" />
	</form>
	<?php
} else {
	$series_name = filter_input ( INPUT_POST, 'series_name' );
	$sql = "INSERT INTO series (series_name)
	VALUES ('$series_name')";

	if (mysqli_query ( $connection, $sql )) {
		echo $series_name . " series created in database.";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error ( $connection );
	}
}
include '../views/footer.php';
?>
</div>
</body>
</html>
