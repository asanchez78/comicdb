jQuery(document).ready(function($) {
  var $menuButton, $comicSubmenu;

  $menuButton = $('#button-add-comics');
  $comicSubmenu = $('#comics-submenu');

  // Shows the add comics submenu on hover
  $($menuButton).click(function() {
    if ($(this).hasClass('expanded') != true) {
      $(this).addClass('expanded');
    }
  });

  $($comicSubmenu).mouseleave(function() {
    $($menuButton).removeClass('expanded');
  });

});