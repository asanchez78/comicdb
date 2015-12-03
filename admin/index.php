<?php
require_once '../config/db.php';
//require_once '../classes/Login.php';

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
<head>
<link rel="stylesheet"
	href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.green-blue.min.css">
<script
	src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
<link rel="stylesheet"
	href="https://fonts.googleapis.com/icon?family=Material+Icons">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<title>Admin</title>
</head>
<body>
<?php include '../views/header.php';
	if ($login->isUserLoggedIn () == false) {
	include ("../views/not_logged_in.php");
	die ();
}

?>
	<a href="new.php">Add a single comic with details.</a>
	<br />
	<a href="multiadd.php">Add a ranged comics without details.</a>
	<br />
	<a href="multiaddc.php">Add a comma separated list of comics without details.</a>
	<br />
	<a href="index.php?logout">Logout</a>
</body>
</html>
