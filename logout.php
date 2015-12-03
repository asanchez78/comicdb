<?php
require_once 'classes/Login.php';
require_once 'config/db.php';
?>

<html>
<head>
<link rel="stylesheet" href="material.min.css">
<script src="material.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comicdb</title>

</head>

<body>

<?php include 'views/header.php'; ?>
<div class="mdl-layout">
<div class="mdl-grid">

Logged out.
<br/>
<a href="<?php echo filter_input ( INPUT_GET, 'return' ); ?>">Go Back</a>
</div>
</div>
<?php include 'views/footer.php';?>

</body>
</html>