	</main>
  <!-- Site content ends -->
  <?php 
    if (isset($messageNum) || isset($_GET['m'])) {
      if (!isset($messageNum) && isset($_GET['m'])) {
        $messageNum = $_GET['m'];
      }
      if ($messageNum < 50) {
        $notifyClass = "bg-success";
      } else {
        $notifyClass = "bg-danger";
      }

      switch ($messageNum) {
      // SUCCESS MESSAGES
        case 1:
          $messageText = "Issue added successfully."; 
          break;
        case 2:
          $messageText = "Issues added successfully.";  
          break;
        case 3:
          $messageText = '<em>' . $series_name . '</em> added to your collection successfully.';
          break;
        case 4:
          $messageText = '<em>' . $series_name . ' #' . $first_issue . ' - ' . $last_issue . '</em> added to your collection successfully.';
          break;
        case 5:
          $messageText = "Issue updated successfully.";
          break;
        case 49:
          $messageText = "You have been successfully logged out.";
          break;
        // ERROR MESSAGES
        case 50:
          $messageText = '<strong>ERROR</strong>: Cannot add <em>' . $error . '</em> to your collection. Series already exists.';
          break;
        case 51:
          $messageText = '<strong>ERROR</strong>: Cannot add the issues you entered.';
          break;
        case 52:
          $messageText = '<strong>ERROR</strong>: User "' . $user . '" not found. Here is a random comic instead.';
          break;
      }
    } else {
      $notifyClass = "notifications-hide";
    }
  ?>
  <?php if(isset($messageText)) { ?>
  <div class="notifications <?php echo $notifyClass; ?>">
    <div class="container">
      <div class="row">
        <p class="col-xs-11 notification-text"><?php echo $messageText; ?></p>
        <button type="button" class="close col-xs-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
<!-- Site wrapper ends -->

<!-- Scripts at the bottom to improve load time -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="/scripts/comicdb.js"></script>