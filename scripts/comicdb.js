jQuery(document).ready(function($) {
  var $menuButton, $comicSubmenu, $notifyClose, $notifications;

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
    $($notifications).addClass('notifications-cloasdasdse');
  });

  if (!$notifications.hasClass('notifications-close')) {
    setTimeout(function() {
      $($notifications).addClass('notifications-close');
    }, 6000);
  }
});