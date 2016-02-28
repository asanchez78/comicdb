<section data-module="edit_comic" data-comic-id="<?php echo $comic_id; ?>">
  <form method="post" action="<?php echo $filename; ?>?comic_id=<?php echo $comic_id; ?>&type=edit-save">
    <header class="col-sm-12 headline">
      <h2>Edit Issue: <?php echo $series_name; ?> #<?php echo $issue_number; ?></h2>
    </header>
    <div class="col-md-8 issue-content">
      <div class="manage-comic-container">
        <div class="text-center">
          <button class="btn btn-sm btn-warning form-back"><i class="fa fa-fw fa-arrow-left"></i> <span class="sr-only">Back</span></button>
          <button type="submit" name="submit" class="btn btn-sm btn-success form-submit"><span class="icon-loading"><i class="fa fa-fw fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-fw fa-save"></i> <span class="hidden-sm hidden-xs">Save</span></span></button>
        </div>
      </div>
      <div class="form-group">
        <label for="story_name">Story Name: </label>
        <input class="form-control" name="custStoryName" type="text" maxlength="255" value="<?php if (isset($custStoryName) && $custStoryName != '') { echo $custStoryName; } else { echo $story_name; }?>" placeholder="<?php echo $story_name; ?>" />
        <small>Enter a custom story name, otherwise leave blank to import ComicVine entry.</small>
        <input type="hidden" name="story_name" value="<?php echo $story_name; ?>" />
      </div>
      <div class="row">
        <div class="col-xs-3">
          <label for="quantity">Quantity</label>
          <input name="quantity" class="form-control" type="number" pattern="[0-9]*" inputmode="numeric" autocomplete="off" size="3" maxlength="4" value="<?php echo $quantity; ?>" required aria-required="true" />
        </div>
        <div class="col-xs-4">
          <div class="form-radio">
            <label for="originalPurchase">Purchased When Released:</label>
            <fieldset>
              <input name="originalPurchase" id="original-yes" value="1" type="radio" <?php if ($originalPurchase == 1) { echo 'selected'; } ?> /> <label for="original-yes" <?php if ($originalPurchase == 1) { echo 'data-selected="true"'; } ?> />Yes</label>
              <input name="originalPurchase" id="original-no" value="0" type="radio" <?php if ($originalPurchase == 0) { echo 'selected'; } ?> /> <label for="original-no">No</label>
            </fieldset>
          </div>
        </div>
        <div class="col-xs-5">
          <label for="condition">Condition</label>
          <input name="condition" class="form-control" type="text" autocomplete="off" value="" placeholder="Mint, CCG 9.5, etc." />
        </div>
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
          <textarea name="custPlot" class="form-control">
            <?php if ($custPlot != '') { echo htmlspecialchars($custPlot); } else { echo htmlspecialchars($plot); }?>
          </textarea>
          <input type="hidden" name="plot" value="<?php echo htmlspecialchars($plot); ?>" />
        </div>
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
            <div class="<?php if (isset($pencils)) { ?>col-xs-6<?php } else { ?>col-xs-12<?php } ?> credit-writer">
              <h3>Script</h3>
              <?php echo $script; ?>
            </div>
            <?php } ?>
            <?php if (isset($pencils)) { ?>
            <div class="<?php if (isset($script)) { ?>col-xs-6<?php } else { ?>col-xs-12<?php } ?> credit-artist">
              <h3>Pencils</h3>
              <?php echo $pencils; ?>
            </div>
            <?php } ?>
          </div>

          <div class="row">
            <?php if (isset($colors)) { ?>
            <div class="<?php if (isset($letters) && isset($inks)) { ?>col-xs-4<?php } else { ?>col-xs-6<?php } ?> credit-inker">
              <h3>Colors</h3>
              <?php echo $colors; ?>
            </div>
             <?php } ?>
            <?php if (isset($inks)) { ?>
            <div class="<?php if (isset($colors) && isset($letters)) { ?>col-xs-4<?php } else { ?>col-xs-6<?php } ?> credit-inks">
              <h3>Inks</h3>
              <?php echo $inks; ?>
            </div>
             <?php } ?>
            <?php if (isset($letters)) { ?>
            <div class="<?php if (isset($colors) && isset($inks)) { ?>col-xs-4<?php } else { ?>col-xs-6<?php } ?> credit-letters">
              <h3>Letters</h3>
              <?php echo $letters; ?>
            </div>
             <?php } ?>
          </div>
          
          <div class="row">
            <?php if (isset($editing)) { ?>
            <div class="<?php if (isset($coverArtist)) { ?>col-xs-6<?php } else { ?>col-xs-12<?php } ?> credit-editor">
              <h3>Editing</h3>
              <?php echo $editing; ?>
            </div>
             <?php } ?>
            <?php if (isset($coverArtist)) { ?>
            <div class="<?php if (isset($editing)) { ?>col-xs-6<?php } else { ?>col-xs-12<?php } ?> credit-cover">
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
    <input type="hidden" name="released_date" value="<?php if ($releaseDate) { echo $releaseDate; } ?>" />
    <input type="hidden" name="updatedSet" value="<?php echo $updatedSet; ?>" />
    <input type="hidden" name="updated" value="yes" />
  </form>
</section>