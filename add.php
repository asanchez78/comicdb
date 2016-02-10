<?php
  require_once('views/head.php');
  $filename = $_SERVER["PHP_SELF"];
  
  // Reset all our form flags
  $seriesSearch = false;
  $seriesAdd = false;
  $seriesSubmit = false;
  $listSearch = false;
  $rangeSearch = false;
  $submitted = filter_input ( INPUT_POST, 'submitted' );
  if ($submitted) { include('admin/formprocess.php'); }
?>
  <title>Add :: POW! Comic Book Manager</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <?php if ($login->isUserLoggedIn () == false) {
    include (__ROOT__."/views/not_logged_in.php");
    die ();
  } ?>
  <div class="row">
    <ul class="add-menu nav nav-tabs" id="addTabs">
      <li role="presentation" class="active col-xs-3">
        <a href="#addSingle" id="form-add-issue" aria-controls="addSingle" role="tab" data-toggle="tab">
          <i class="fa fa-fw fa-plus"></i> <span class="hidden-sm hidden-xs">Add Issue</span>
        </a>
      </li>
      <li role="presentation" class="col-xs-3">
        <a href="#addRange" id="form-add-range" aria-controls="addRange" role="tab" data-toggle="tab">
          <i class="fa fa-fw fa-hashtag"></i> <span class="hidden-sm hidden-xs">Add Range of Issues</span>
        </a>
      </li>
      <li role="presentation" class="col-xs-3">
        <a href="#addList" id="form-add-list" aria-controls="addList" role="tab" data-toggle="tab">
          <i class="fa fa-fw fa-archive"></i> <span class="hidden-sm hidden-xs">Add List of Issues</span>
        </a>
      </li>
      <li role="presentation" class="col-xs-3">
        <a href="#addSeries" id="form-add-series" aria-controls="addSeries" role="tab" data-toggle="tab">
          <i class="fa fa-fw fa-folder-open"></i> <span class="hidden-sm hidden-xs">Add Series</span>
        </a>
      </li>
    </ul>
  </div>
  <div class="tab-content">
    <?php // ADD SINGLE ISSUE ?>
    <?php include(__ROOT__.'/modules/add_comic/add_issue/add_issue.php'); ?>

    <?php // ADD RANGE ?>
    <?php include(__ROOT__.'/modules/add_comic/add_range/add_range.php'); ?>
    
    <?php // ADD LIST ?>
    <?php include(__ROOT__.'/modules/add_comic/add_list/add_list.php'); ?>

    <?php // ADD SERIES ?>
    <?php include(__ROOT__.'/modules/add_comic/add_series/add_series.php'); ?>
  </div>
<?php include 'views/footer.php';?>
</body>
</html>