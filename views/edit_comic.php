<form method="post" action="<?php echo $filename; ?>?comic_id=<?php echo $comic_id; ?>&type=edit-save">
  <header class="col-sm-12 headline">
    <h2>Edit Issue: <?php echo $series_name; ?> #<?php echo $issue_number; ?></h2>
  </header>
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
      <a class="btn btn-xs btn-link pull-right" id="editPlot">[edit]</a>
      <label for="plot">Plot:</label>
      <div class="plot-output">
        <?php echo $plot; ?>
      </div>
      <div id="plotInput">
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script>
          tinymce.init({ 
            selector:'textarea',
            height: 300,
            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
            menubar: false
          });
        </script>
        <textarea name="plot" class="form-control"><?php echo htmlspecialchars($plot); ?></textarea>
      </div>
    </div>
    <div class="col-xs-12 text-center center-block">
      <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
      <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><i class="fa fa-save"></i> Save</button>
    </div>
  </div>
  <div class="col-md-4 sidebar">
    <div class="issue-image">
      <img src="<?php echo $coverURL; ?>" alt="Cover" />
      <div class="form-group">
        <label for="cover_image">Cover Image URL</label>
        <input type="text" class="form-control" name="cover_image" placeholder="Enter the URL" value="<?php echo $coverURL; ?>" />
        <small>Enter the URL of the image you wish to use. Default is the cover file from the Wikia entry on this issue.</small>
        <input type="hidden" name="cover_image_file" value="<?php echo $coverFile; ?>" />
      </div>
    </div>
    <div class="issue-details">
      <h2>Issue Details</h2>
      <span class="logo-<?php echo $publisherShort; ?> pull-right"></span>
      <p>
        <big><strong><?php echo $series_name; ?></strong></big><br />
        <strong>Issue: #</strong><?php echo $issue_number; ?><br />
        <strong>Volume: </strong><?php echo $series_vol; ?><br />
        <strong>Cover Date: </strong><?php echo $release_dateLong; ?><br />
      </p>
    </div>
    <?php if ($script || $pencils || $colors || $letters || $editing || $cover) { ?>
      <div class="issue-credits text-center">
        <div class="row">
          <?php if ($script) { ?>
          <div class="<?php if ($pencils) { ?>col-md-6<?php } else { ?>col-md-12<?php } ?> credit-writer">
            <h3>Script</h3>
            <?php echo $script; ?>
          </div>
          <?php } ?>
          <?php if ($pencils) { ?>
          <div class="<?php if ($script) { ?>col-md-6<?php } else { ?>col-md-12<?php } ?> credit-artist">
            <h3>Pencils</h3>
            <?php echo $pencils; ?>
          </div>
          <?php } ?>
        </div>
        <div class="row">
          <?php if ($colors) { ?>
          <div class="col-xs-12 <?php if ($letters && $editing && $cover) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-inker">
            <h3>Inks/Colors</h3>
            <?php echo $colors; ?>
          </div>
           <?php } ?>
          <?php if ($letters) { ?>
          <div class="col-xs-12 <?php if ($colors && $editing && $cover) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-letters">
            <h3>Letters</h3>
            <?php echo $letters; ?>
          </div>
           <?php } ?>
          <?php if ($editing) { ?>
          <div class="col-xs-12 <?php if ($letters && $colors && $cover) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-editor">
            <h3>Editing</h3>
            <?php echo $editing; ?>
          </div>
           <?php } ?>
        </div>
        <div class="row">
          <?php if ($cover) { ?>
          <div class="col-xs-12 credit-cover">
            <h3>Cover</h3>
            <?php echo $cover; ?>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
  </div>
  <input type="hidden" name="series_name" value="<?php echo $series_name; ?>" />
  <input type="hidden" name="series_vol" value="<?php echo $series_vol; ?>" />
  <input type="hidden" name="issue_number" value="<?php echo $issue_number; ?>" />
  <input type="hidden" name="plot" value="<?php echo htmlspecialchars($wiki->synopsis); ?>" />
  <input type="hidden" name="series_id" value="<?php echo $series_id; ?>" />
  <input type="hidden" name="submitted" value="yes" />
</form>