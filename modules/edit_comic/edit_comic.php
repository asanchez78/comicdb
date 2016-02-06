<form method="post" action="<?php echo $filename; ?>?comic_id=<?php echo $comic_id; ?>&type=edit-save">
  <header class="col-sm-12 headline">
    <h2>Edit Issue: <?php echo $series_name; ?> #<?php echo $issue_number; ?></h2>
  </header>
  <div class="col-md-8 col-sm-12">
    <div class="form-group">
      <label for="story_name">Story Name: </label>
      <input class="form-control" name="story_name" type="text" maxlength="255" value="<?php echo $story_name; ?>" />
    </div>
    <div class="form-group">
      <label for="released_date">Release Date:</label>
      <input class="form-control" name="released_date" size="10" maxlength="10" value="<?php if ($releaseDate) { echo $releaseDate; } ?>" type="date" placeholder="YYYY-MM-DD" />
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
        <?php if ($custPlot != '') { echo $custPlot; } else { echo $plot; }?>
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
        <textarea name="custPlot" class="form-control"><?php echo htmlspecialchars($custPlot); ?></textarea>
        <input type="hidden" name="plot" value="<?php echo htmlspecialchars($plot); ?>" />
      </div>
    </div>
    <div class="col-xs-12 text-center center-block">
      <button class="btn btn-lg btn-warning form-back"><i class="fa fa-arrow-left"></i> Back</button>
      <button type="submit" name="submit" class="btn btn-lg btn-danger form-submit"><span class="icon-loading"><i class="fa fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-paper-plane"></i> Save</span></button>
    </div>
  </div>
  <div class="col-md-4 sidebar">
    <div class="issue-image">
      <img src="<?php echo $coverURL; ?>" alt="Cover" />
      <div class="form-group">
        <label for="cover_image">Cover Image URL</label>
        <input type="text" class="form-control" name="cover_image" placeholder="Enter the URL" value="<?php echo $coverURL; ?>" />
        <small>Enter the URL of the image you wish to use. Default is the cover file from the ComicVine entry on this issue.</small>
        <input type="hidden" name="cover_image_file" value="<?php echo $coverFile; ?>" />
      </div>
    </div>
    <div class="issue-details">
      <h2>Issue Details</h2>
      <span class="logo-<?php echo $publisherShort; ?> pull-right"></span>
      <p>
        <big><strong><?php echo $series_name; ?></strong></big><br />
        <strong>Issue: #</strong><?php echo $issue_number; ?><br />
        <strong>Series Published: </strong><?php echo $series_vol; ?><br />
        <strong>Cover Date: </strong><?php echo $release_dateLong; ?><br />
      </p>
    </div>
    <?php if (isset($script) || isset($pencils) || isset($colors) || isset($inks) || isset($letters) || isset($editing) || isset($coverArtist)) { ?>
      <div class="issue-credits text-center">
        <div class="row">
          <?php if (isset($script)) { ?>
          <div class="<?php if (isset($pencils)) { ?>col-md-6<?php } else { ?>col-md-12<?php } ?> credit-writer">
            <h3>Script</h3>
            <?php echo $script; ?>
          </div>
          <?php } ?>
          <?php if (isset($pencils)) { ?>
          <div class="<?php if (isset($script)) { ?>col-md-6<?php } else { ?>col-md-12<?php } ?> credit-artist">
            <h3>Pencils</h3>
            <?php echo $pencils; ?>
          </div>
          <?php } ?>
        </div>

        <div class="row">
          <?php if (isset($colors)) { ?>
          <div class="col-xs-12 <?php if (isset($letters) && isset($inks)) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-inker">
            <h3>Colors</h3>
            <?php echo $colors; ?>
          </div>
           <?php } ?>
          <?php if (isset($inks)) { ?>
          <div class="col-xs-12 <?php if (isset($colors) && isset($letters)) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-inks">
            <h3>Inks</h3>
            <?php echo $inks; ?>
          </div>
           <?php } ?>
          <?php if (isset($letters)) { ?>
          <div class="col-xs-12 <?php if (isset($colors) && isset($inks)) { ?>col-md-4<?php } else { ?>col-md-6<?php } ?> credit-letters">
            <h3>Letters</h3>
            <?php echo $letters; ?>
          </div>
           <?php } ?>
        </div>
        
        <div class="row">
          <?php if (isset($editing)) { ?>
          <div class="col-xs-12 <?php if (isset($coverArtist)) { ?>col-md-6<?php } else { ?>col-md-12<?php } ?> credit-editor">
            <h3>Editing</h3>
            <?php echo $editing; ?>
          </div>
           <?php } ?>
          <?php if (isset($coverArtist)) { ?>
          <div class="col-xs-12 <?php if (isset($editing)) { ?>col-md-6<?php } else { ?>col-md-12<?php } ?> credit-cover">
            <h3>Cover</h3>
            <?php echo $coverArtist; ?>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
  </div>
  <input type="hidden" name="comic_id" value="<?php echo $comic_id; ?>" />
  <input type="hidden" name="series_name" value="<?php echo $series_name; ?>" />
  <input type="hidden" name="series_vol" value="<?php echo $series_vol; ?>" />
  <input type="hidden" name="creatorsList" value="<?php echo $creatorsList; ?>" />
  <input type="hidden" name="updatedSet" value="<?php echo $updatedSet; ?>" />
  <input type="hidden" name="updated" value="yes" />
</form>