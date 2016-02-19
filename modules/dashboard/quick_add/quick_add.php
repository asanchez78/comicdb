<div data-module="quick_add">
  <form method="post" action="" class="quick-add-form">
    <h3>Quick Add</h3>
    <div class="form-group">
      <label>Series</label>
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
      <label for="issue_number">Issue #</label>
      <input name="issue_number" class="form-control" type="number" pattern="[0-9]*" inputmode="numeric" autocomplete="off" size="3" maxlength="4" value="" required aria-required="true" />
    </div>
    <input type="hidden" name="quickAddIssue" value="true" />
    <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><span class="icon-loading"><i class="fa fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-plus"></i> Add</span></button>
  </form>
</div>