<form method="post" action="<?php echo $filename; ?>?comic_id=<?php echo $comic->comic_id; ?>&type=edit-save">
  <div class="col-sm-12 headline">
    <h2>Edit Issue: <?php echo $series_name; ?> (Vol <?php echo $series_vol; ?>) #<?php echo $issue_number; ?></h2>
  </div>
  <div class="col-md-8 col-sm-12">
    <div class="form-group">
      <label for="story_name">Story Name: </label>
      <input class="form-control" name="story_name" type="text" maxlength="255" value="<?php echo $comic->story_name; ?>" />
    </div>
    <div class="form-group">
      <label for="released_date">Release Date:</label>
      <input class="form-control" name="released_date" size="10" maxlength="10" value="<?php if ($release_date) { echo $release_date; } ?>" type="date" placeholder="YYYY-MM-DD" />
    </div>
    <div class="form-group form-radio">
      <label for="originalPurchase">Purchased When Released:</label>
      <fieldset>
        <input name="originalPurchase" id="original-yes" value="1" type="radio" <?php if ($originalPurchase == 1) { echo 'selected'; } ?> /> <label for="original-yes">Yes</label>
        <input name="originalPurchase" id="original-no" value="0" type="radio" <?php if ($originalPurchase == 0) { echo 'selected'; } ?> /> <label for="original-no">No</label>
      </fieldset>
    </div>
    <div class="plot form-group">
      <label for="plot">Plot:</label>
      <small><a href="#">[edit]</a></small>
      <?php echo $wiki->synopsis; ?>
    </div>
  </div>
  <div class="col-md-4 issue-image">
    <img src="<?php echo $wiki->coverURL; ?>" alt="Cover" />
    <div class="form-group">
      <label for="cover_image">Cover Image URL</label>
      <input type="url" class="form-control" name="cover_image" placeholder="Enter the URL" value="<?php echo $wiki->coverURL; ?>" />
      <small>Enter the URL of the image you wish to use. Default is the cover file from the Wikia entry on this issue.</small>
      <input type="hidden" name="cover_image_file" value="<?php echo $wiki->coverFile; ?>" />
    </div>
  </div>
  <input type="hidden" name="series_name" value="<?php echo $series_name; ?>" />
  <input type="hidden" name="series_vol" value="<?php echo $series_vol; ?>" />
  <input type="hidden" name="issue_number" value="<?php echo $issue_number; ?>" />
  <input type="hidden" name="plot" value="<?php echo htmlspecialchars($wiki->synopsis); ?>" />
  <input type="hidden" name="series_id" value="<?php echo $series_id; ?>" />
  <input type="hidden" name="wiki_id" value="<?php echo $wiki_id; ?>" />
  <input type="hidden" name="submitted" value="yes" />
  <div class="col-xs-12 text-center center-block">
    <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
    <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-paper-plane"></i> Next</button>
  </div>
</form>