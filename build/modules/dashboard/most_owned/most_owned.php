<?php
  $userInfo->mostOwnedComics();
  if (isset($userInfo)) {
?>

<div data-module="most_owned">
  <h3>Most Owned Comics</h3>
  <div>
    <?php echo $userInfo->mostOwnedList; ?>
  </div>
</div>

<?php } ?>