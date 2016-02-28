<section data-module="logout_modal" class="modal fade" id="logoutFormModal" tabindex="-1" role="dialog" aria-labelledby="logoutFormModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="/" name="logoutform" class="form-horizontal">
        <header class="modal-header text-center">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="logoutFormModalLabel">Are you sure you want to sign out?</h4>
        </header>
        <div class="modal-body center-block text-center">
          <input type="hidden" name="messageNum" value="49" />
          <button type="button" class="btn btn-lg btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
          <button type="submit" name="logout" class="btn btn-danger btn-lg form-submit"><i class="fa fa-sign-out"></i> Sign Out</button>
        </div>
      </form>
    </div>
  </div>
</section>