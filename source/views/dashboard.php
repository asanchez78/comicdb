<section class="dashboard-index">
  <div class="dashboard-content">
    <?php include 'modules/dashboard/count_bar/count_bar.php'; ?>
    <?php include 'modules/dashboard/carousel_bar/carousel_bar.php'; ?>
    <div class="row">
      <div class="col-lg-6 dashContentLeft">
        <?php include 'modules/dashboard/quick_add/quick_add.php'; ?>
        <?php include 'modules/dashboard/most_owned/most_owned.php'; ?>
      </div>
      <div class="col-lg-6 dashContentRight">
        <?php include 'modules/dashboard/pull_list/pull_list.php'; ?>
      </div>
    </div>
  </div>
  <aside class="sidebar">

    <div class="row">
      <div class="col-xs-6 col-md-12"><?php include 'modules/dashboard/followers/following.php'; ?></div>
      <div class="col-xs-6 col-md-12"><?php include 'modules/dashboard/followers/followers.php'; ?></div>
    </div>
    <?php include 'modules/dashboard/user_feed/user_feed.php'; ?>
  </aside>
</section>