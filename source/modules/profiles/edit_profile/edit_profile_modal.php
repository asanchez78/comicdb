<section data-module="edit_profile_modal" class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="/profile.php" name="editProfileForm">
        <header class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-logo center-block text-center" id="editProfileModalLabel"><img src="../assets/logo.svg" alt="POW! Comic Book Manager" class="img-responsive center-block" />Comic Book Manager</h4>
        </header>
        <div class="modal-body">
          <h5 class="text-center">Edit your profile</h5>
          <div class="row">
          <div class="form-group col-xs-6">
            <label for="profile_input_firstname">First Name</label>
            <input id="profile_input_firstname" class="form-control" type="text" name="first_name" <?php if ($first_name != '') { echo 'value="' . $first_name . '"'; } ?> />
          </div>
          <div class="form-group col-xs-6">
            <label for="profile_input_lastname">Last Name</label>
            <input id="profile_input_lastname" class="form-control" type="text" name="last_name" <?php if ($last_name != '') { echo 'value="' . $last_name . '"'; } ?> />
          </div>
          <div class="form-group col-xs-12">
            <label for="profile_input_location" class="sr-only">Location</label>
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-globe fa-fw"></i></div>
              <input id="profile_input_location" class="form-control" type="text" name="user_location" <?php if ($user_location != '') { echo 'value="' . $user_location . '"'; } ?> placeholder="Location" />
            </div>
          </div>
        </div>
        <hr />
        <h5 class="text-center">Social Networks</h5>
        <p>Enter your social network profile url's in the fields below.</p>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="profile_input_facebook" class="sr-only">Facebook</label>
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-facebook fa-fw"></i></div>
              <input id="profile_input_facebook" class="form-control" type="url" name="user_facebook" <?php if ($facebook_url != '') { echo 'value="' . $facebook_url . '"'; } ?> />
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="profile_input_twitter" class="sr-only">Twitter</label>
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-twitter fa-fw"></i></div>
              <input id="profile_input_twitter" class="form-control" type="url" name="user_twitter" <?php if ($twitter_url != '') { echo 'value="' . $twitter_url . '"'; } ?> />
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="profile_input_instagram" class="sr-only">Instagram</label>
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-instagram fa-fw"></i></div>
              <input id="profile_input_instagram" class="form-control" type="url" name="user_instagram" <?php if ($instagram_url != '') { echo 'value="' . $instagram_url . '"'; } ?> />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="user_id" value="<?php echo $userID; ?>" />
          <input type="hidden" name="edit_submit" value="true" />
          <button type="button" class="btn btn-lg btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-lg btn-success form-submit"><span class="icon-loading"><i class="fa fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-save"></i> Save</span></button>
        </div>
      </form>
    </div>
  </div>
</section>