      <?php include(__ROOT__.'/modules/notifications/notifications.php'); ?>
    </main>
    <footer class="main-footer col-xs-12 text-center center-block">
      <p>&copy;<?php echo date("Y"); ?> Anthony Sanchez and Sean Loyless. All content from ComicVine</p>
    </footer>
  </div>
  <!-- Site content ends -->
  <?php if ($login->isUserLoggedIn () != true) { ?>
    <?php include(__ROOT__.'/modules/login/login_modal.php'); ?>
  <?php } else { ?>
    <?php include(__ROOT__.'/modules/login/logout_modal.php'); ?>
  <?php } ?>  
</div>
<!-- Site wrapper ends -->

<!-- Scripts at the bottom to improve load time -->
<script src="/scripts/bootstrap/alert.js"></script>
<script src="/scripts/bootstrap/transition.js"></script>
<script src="/scripts/bootstrap/collapse.js"></script>
<script src="/scripts/bootstrap/modal.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-73612000-1', 'auto');
  ga('send', 'pageview');

</script>