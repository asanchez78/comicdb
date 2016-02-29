<section data-module="add_list" class="row add-block">
  <header class="col-xs-12 headline">
    <h2>Add List of Issues</h2>
  </header>
  <div class="col-xs-12">
    <?php if ($addListSubmit != true) { ?>
    <form id="input_select" class="add-form" method="post" action="<?php echo $filename; ?>#addList">
      <p>Use the form below to add several issues of one series to your collection. Separate any issues with commas.</p>
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
      <div class="form-group">
        <label for="issueList">Comma separated list of issues</label>
        <input type="text" class="form-control" name="issueList" placeholder="5,29,156" autocomplete="off" />
      </div>
      <div class="form-group form-radio form-purchase">
        <label for="listOriginalPurchase">Purchased When Released</label>
        <fieldset>
          <input name="listOriginalPurchase" id="list-original-yes" value="1" type="radio" /> <label for="list-original-yes">Yes</label>
          <input name="listOriginalPurchase" id="list-original-no" value="0" type="radio" /> <label for="list-original-no">No</label>
        </fieldset>
      </div>
      <input type="hidden" name="addListSubmit" value="true" />
      <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><span class="icon-loading"><i class="fa fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-plus"></i> Add</span></button>
    </form>
    <?php } else { ?>
      <div class="add-success col-xs-12 bg-success">
        <div class="success-message">
          <div class="row">
            <div class="text-center">
              <h2><?php echo $series_name; ?></h2>
              #<?php echo $filtered_issue_list; ?>
              <p>have been added to your collection.</p>
            </div>
          </div>
          <div class="text-center center-block">
            <a href="/issues.php?series_id=<?php echo $series_id; ?>" class="btn btn-lg btn-success">View Series</a>
            <a href="/add.php?add=true#addList" class="btn btn-lg btn-info">Add More</a>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</section>