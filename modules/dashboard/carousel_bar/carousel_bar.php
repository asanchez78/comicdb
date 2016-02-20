<?php
  $carouselIssues = new userInfo ();
  $carouselSlides = 5;
  $carouselIssues->carouselComics($userID, $carouselSlides);
?>

<div data-module="carousel_bar">
  <div class="row">
    <h3 class="text-center">Comic Spotlight</h3>
    <div id="carousel_bar_carousel" class="carousel slide" data-ride="carousel">
      <!-- Wrapper for slides -->
      <div class="carousel-inner" role="listbox">
        <?php echo $carouselIssues->carousel_list; ?>
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