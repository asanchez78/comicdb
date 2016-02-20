<section class="dashboard-index">
  <div class="dashboard-content">
    <?php include 'modules/dashboard/count_bar/count_bar.php'; ?>
    <?php include 'modules/dashboard/carousel_bar/carousel_bar.php'; ?>
    <div class="row">
      <div class="col-md-6 dash-half">
        <?php include 'modules/dashboard/quick_add/quick_add.php'; ?>
      </div>
      <div class="col-md-6 dash-half">
        <?php include 'modules/dashboard/pull_list/pull_list.php'; ?>
      </div>
    </div>
  </div>
  <aside class="sidebar">
    <?php include 'modules/dashboard/followers/following.php'; ?>
    <?php include 'modules/dashboard/followers/followers.php'; ?>
    <?php include 'modules/dashboard/user_feed/user_feed.php'; ?>
  </aside>
</section>