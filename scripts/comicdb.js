jQuery(document).ready(function($) {
  var $menuButton, $comicSubmenu, $notifyClose, $notifications, $addAnotherSeries;

  $menuButton = $('#button-add-comics');
  $comicSubmenu = $('#comics-submenu');

  // Shows the add comics submenu on hover
  $($menuButton).click(function() {
    if ($(this).hasClass('expanded') != true) {
      $(this).addClass('expanded');
    } else {
      $(this).removeClass('expanded');
    }
  });

  $($comicSubmenu).mouseleave(function() {
    $($menuButton).removeClass('expanded');
  });

  $notifications = $('.notifications');
  $notifyClose = $($notifications).find('.close');
  $($notifyClose).click(function() {
    $($notifications).addClass('notifications-close');
  });

  if (!$notifications.hasClass('notifications-close')) {
    setTimeout(function() {
      $($notifications).addClass('notifications-close');
    }, 6000);
  }

  // Hiding the "SERIES has been added successfully. Add another?" section after button click.
  $addAnotherSeries = $('.add-success').find('.btn');
  $($addAnotherSeries).click(function(e) {
    e.preventDefault;
    // Let CSS do the work for us, just add a new class to the container.
    $('.add-success').addClass('success-hide');
  });
});