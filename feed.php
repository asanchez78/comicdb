<?php
  require_once('views/head.php');
?>
  <title>User Feed :: POW! Comic Book Manager</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <div class="row">
    <div class="col-xs-12">
      <header class="headline"><h2>Recent Activity from your Followers</h2></header>
      <?php include 'modules/feed/feed.php'; ?>
    </div>
  </div>
  <?php include 'views/footer.php';?>
</body>
</html>