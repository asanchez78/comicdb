<div data-module="carousel_bar">
  <div class="row">
    <h3 class="text-center">Comic Spotlight</h3>
    <div id="carousel_bar_carousel" class="carousel slide" data-ride="carousel">
      <!-- Wrapper for slides -->
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <div class="carousel-caption">
            <div class="row">
              <div class="col-md-5">
                <a href="#"><img class="img-responsive center-block" src="/images/A_Force-v2016/A_Force-v2016_1-small.jpg" alt=""></a>
              </div>
              <div class="col-md-7">
                <div class="logo-marvel pull-right hidden-xs hidden-sm hidden-md"></div>
                <h4><a href="#">A-Force #1</a></h4>
                <div class="story-block hidden-xs hidden-sm">
                  <h5>"Part One"</h5>
                  <p>Digger attacks another of Morris Forelli's interests, but Spider-Man is there to stop him. Unfortunately so is a hit crew hired by Forelli to take down Digger for good.</p>
                  <a href="#">[Read More]</a>
                </div>
              </div>
              <div class="button-block text-center col-xs-12">
                <a href="/comic.php?comic_id=<?php echo $comic_id; ?>" class="btn btn-danger">View Issue</a>
                <a href="/comic.php?comic_id=<?php echo $series_id; ?>" class="btn btn-danger">View Series</a>
              </div>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="carousel-caption">
            <div class="row">
              <div class="col-md-5">
                <a href="#"><img class="img-responsive" src="/images/A_Force-v2016/A_Force-v2016_2-small.jpg" alt=""></a>
              </div>
              <div class="col-md-7">
                <div class="logo-dc pull-right hidden-xs hidden-sm hidden-md"></div>
                <h4><a href="#">A-Force #2</a></h4>
                <div class="story-block hidden-xs hidden-sm">
                  <h5>"Part Two"</h5>
                  <p>Digger attacks another of Morris Forelli's interests, but Spider-Man is there to stop him. Unfortunately so is a hit crew hired by Forelli to take down Digger for good.</p>
                  <a href="#">[Read More]</a>
                </div>
              </div>
              <div class="button-block text-center col-xs-12">
                <a href="/comic.php?comic_id=<?php echo $comic_id; ?>" class="btn btn-danger">View Issue</a>
                <a href="/comic.php?comic_id=<?php echo $series_id; ?>" class="btn btn-danger">View Series</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Controls -->
      <a class="left carousel-control" href="#carousel_bar_carousel" role="button" data-slide="prev">
        <span aria-hidden="true"><i class="fa fa-fw fa-chevron-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#carousel_bar_carousel" role="button" data-slide="next">
        <span aria-hidden="true"><i class="fa fa-fw fa-chevron-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>
</div>
<script src="/scripts/bootstrap/carousel.js"></script>
<script src="/modules/dashboard/carousel_bar/carousel_bar.js"></script>