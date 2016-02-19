<div data-module="carousel_bar" class="row">
  <div id="carousel_bar_carousel" class="carousel slide" data-ride="carousel">
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <div class="carousel-caption">
          <div class="row">
            <div class="col-md-5">
              <img class="img-responsive" src="/images/A_Force-v2016/A_Force-v2016_1-small.jpg" alt="">
            </div>
            <div class="col-md-7">
              <img src="/assets/logos/marvel_comics.svg" class="pull-right" />
              <h3>A-Force #1</h3>
              <div class="story-block">
                <h4>"Part One"</h4>
                <p>Digger attacks another of Morris Forelli's interests, but Spider-Man is there to stop him. Unfortunately so is a hit crew hired by Forelli to take down Digger for good.</p>
                <a href="#">[Read More]</a>
              </div>
              <div class="button-block">
                <a href="/comic.php?comic_id=<?php echo $comic_id; ?>" class="btn btn-lg btn-danger">View Issue</a>
                <a href="/comic.php?comic_id=<?php echo $series_id; ?>" class="btn btn-lg btn-danger">View Series</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="item">
        <div class="carousel-caption">
          <div class="row">
            <div class="col-md-5">
              <img class="img-responsive" src="/images/A_Force-v2016/A_Force-v2016_2-small.jpg" alt="">
            </div>
            <div class="col-md-7">
              <img src="/assets/logos/marvel_comics.svg" class="pull-right" />
              <h3>A-Force #2</h3>
              <div class="story-block">
                <h4>"Part Two"</h4>
                <p>Stuff Happens.</p>
                <a href="#">[Read More]</a>
              </div>
              <div class="button-block">
                <a href="/comic.php?comic_id=<?php echo $comic_id; ?>" class="btn btn-lg btn-danger">View Issue</a>
                <a href="/comic.php?comic_id=<?php echo $series_id; ?>" class="btn btn-lg btn-danger">View Series</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>
<script src="/scripts/bootstrap/carousel.js"></script>
<script src="/modules/dashboard/carousel_bar/carousel_bar.js"></script>