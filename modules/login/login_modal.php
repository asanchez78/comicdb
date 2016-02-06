<section data-module="login_modal" class="modal fade" id="loginFormModal" tabindex="-1" role="dialog" aria-labelledby="loginFormModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="" name="loginform" class="form-horizontal">
        <header class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="loginFormModalLabel">Log into your Account</h4>
        </header>
        <div class="modal-body">
          <div class="form-group">
            <label for="login_input_username" class="sr-only visuallyhidden">User Name</label>
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-user fa-fw"></i></div>
              <input id="login_input_username" class="form-control" type="text" name="user_name" placeholder="User Name" required />
            </div>
          </div>
          <div class="form-group">
            <label for="login_input_password" class="sr-only visuallyhidden">Password</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
              <input id="login_input_password" class="form-control" type="password" name="user_password" autocomplete="off" placeholder="Password" required />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-lg btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
          <button type="submit" name="login" class="btn btn-lg btn-danger form-submit"><span class="icon-loading"><i class="fa fa-spinner fa-spin"></i></span><span class="text-submit"><i class="fa fa-sign-in"></i> Login</span></button>
        </div>
      </form>
    </div>
  </div>
</section>