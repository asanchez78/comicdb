<section data-module="add_series" class="row add-block">
  <?php if (isset($addSeriesSearch) && $addSeriesSearch == 'true') { ?>
  <header class="headline col-xs-12">
    <h2>Your Search Results</h2>
  </header>
  <div class="col-xs-12">
    <form method="post" action="<?php echo $filename; ?>#addSeries" class="form-inline add-form" id="add-series-search">
      <p>We found the following series on ComicVine related to: <em><?php echo $series_name; ?></em></p>
      <p>Check if it's the correct series by clicking the thumbnail of each of the results from ComicVine to open it in a new tab.</p>
      <div class="form-group form-radio">
        <label for="add-series-search">Choose the result that matches your series:</label>
        <fieldset class="row">
          <?php echo $wiki->resultsList; ?>
        </fieldset>
      </div>
      <input type="hidden" name="publisherID" value="<?php echo $publisherID; ?>" />
      <input type="hidden" name="addSeriesSubmit" value="true" />
      <div class="text-center center-block button-block">
        <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
        <button type="submit" name="submit" class="btn btn-lg btn-danger form-search"><span class="icon-loading"><i class="fa fa-fw fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-plus"></i> Add</span></button>
      </div>
    </form>
  </div>
  <?php } elseif (isset($addSeriesSubmit) && $addSeriesSubmit == 'true') {
    if ($seriesSubmitted == true) { ?>
    <div class="add-success bg-success col-xs-12">
      <div class="success-message text-center">
        <h3><?php echo $series_name; ?><br /><small>(<?php echo $series_vol; ?>)</small></h3>
        <p>has been added to your collection.</p>
        <a class="btn btn-lg btn-success add-another" href="/add.php#addseries"><i class="fa fa-plus-square"></i> Add another?</a>
      </div>
    </div>
    <?php } else { ?>
    <div class="add-success bg-danger col-xs-12">
      <div class="success-message text-center">
        <h3><?php echo $series_name; ?><br /><small>(<?php echo $series_vol; ?>)</small></h3>
        <p>is already in your collection</p>
        <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
        <a class="btn btn-lg btn-success add-another" href="/add.php?add=true#addSeries"><i class="fa fa-plus-square"></i> Add another?</a>
      </div>
    </div>
    <?php } ?>
  <?php } else {?>
  <header class="headline col-xs-12"><h2>Add Series</h2></header>
  <div class="col-xs-12" id="form-series-add">
    <form method="post" action="<?php echo $filename; ?>#addSeries" class="form-inline add-form" id="form-add-series-1">
      <p>Use the form below to add a new series to your collection.</p>
      <div class="form-group">
        <label for="publisherID">Publisher</label>
        <select class="form-control" name="publisherID" required>
          <option value="">Choose a Publisher</option>
          <?php
            $comic = new comicSearch ();
            $comic->publisherList ('%');
            while ( $row = $comic->publisher_list_result->fetch_assoc () ) {
              $list_publisher_name = $row ['publisherName'];
              $list_publisherID = $row ['publisherID'];
              echo '<option value="' . $list_publisherID . '">' . $list_publisher_name . '</option>';
            } 
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="series_name">Series Name</label>
        <input name="series_name" class="form-control" type="text" size="50" value="" required />
      </div>
      <input type="hidden" name="addSeriesSearch" value="true" />
      <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><span class="icon-loading"><i class="fa fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-search"></i> Search</span></button>
    </form>
  </div>
  <?php } ?>
</section>