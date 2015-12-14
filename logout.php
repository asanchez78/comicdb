<?php
  require_once('views/head.php');
?>
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