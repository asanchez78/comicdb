<?php require_once('views/head.php'); ?>
  <title>Logout :: comicDB</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        Logged out.
        <br/>
        <a href="<?php echo filter_input ( INPUT_GET, 'return' ); ?>">Go Back</a>
     </div>
    </div>
  </div>
  <?php include 'views/footer.php';?>
</body>
</html>