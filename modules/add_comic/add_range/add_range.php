<section data-module="add_range" class="row add-block">
  <?php if ($addRangeSubmit != true) { // This shows the form if the user has not submitted yet. ?>
  <header class="headline col-xs-12">
    <h2>Add a range of issues</h2>
  </header>
  <div class="col-xs-12">
    <form id="input_select" class="add-form" method="post" action="">
      <p>Use the form below to add several issues of one series in consecutive order. To add details like <strong>quantity</strong>, <strong>custom story name</strong>, <strong>custom plot</strong>, and <strong>variant cover</strong> please edit the individual issues after submitting.</p>
      <div class="row">
        <div class="col-xs-12 col-md-6">
          <div class="form-group">
            <label for="series_name">Series</label>
            <select class="form-control" name="series_id" required>
              <option value="" disabled selected>Choose a series</option>
              <?php 
                $listAllSeries=1;
                $comic = new comicSearch ();
                $comic->seriesList ($listAllSeries, NULL, $userID);
                while ( $row = $comic->series_list_result->fetch_assoc () ) {
                  $list_series_name = $row ['series_name'];
                  $list_series_vol = $row ['series_vol'];
                  $list_series_id = $row ['series_id'];
                  echo '<option value="' . $list_series_id . '">' . $list_series_name . ' (' . $list_series_vol . ')</option>';
                }  
              ?>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-md-6">
          <div class="form-inline">
            <div class="form-group">
              <label for="first_issue">First Issue</label>
              <input name="first_issue" type="number" pattern="[0-9]*" inputmode="numeric" autocomplete="off" class="form-control" maxlength="3" size="3" />
            </div>
            <div class="form-group">
              <label for="last_issue">Last Issue</label>
              <input name="last_issue" type="number" pattern="[0-9]*" inputmode="numeric" autocomplete="off" class="form-control" maxlength="3" size="3" />
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <div class="form-inline">
            <div class="form-group form-radio form-purchase">
              <label for="rangeOriginalPurchase">Purchased When Released</label>
              <fieldset>
                <input name="rangeOriginalPurchase" id="range-original-yes" value="1" type="radio" /> <label for="range-original-yes">Yes</label>
                <input name="rangeOriginalPurchase" id="range-original-no" value="0" type="radio" /> <label for="range-original-no">No</label>
              </fieldset>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" name="addRangeSubmit" value="true" />
      <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><span class="icon-loading"><i class="fa fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-plus"></i> Add</span></button>
    </form>
  </div>
  <?php } else { ?>
    <div class="add-success col-xs-12 bg-success">
      <div class="success-message">
        <div class="row">
          <div class="text-center">
            <h2><?php echo $series_name; ?></h2>
            <?php echo $addedList; ?>
            <p>have been added to your collection.</p>
          </div>
        </div>
        <div class="text-center center-block">
          <a href="/issues.php?series_id=<?php echo $series_id; ?>" class="btn btn-lg btn-success">View Series</a>
          <a href="/add.php?add=true#addRange" class="btn btn-lg btn-info">Add More</a>
        </div>
      </div>
    </div>
    ?>
  <?php } ?>
</section>