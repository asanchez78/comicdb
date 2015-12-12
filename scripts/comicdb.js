jQuery(document).ready(function($) {
  var $menuButton, $comicSubmenu;

  $menuButton = $('#button-add-comics');
  $comicSubmenu = $('#comics-submenu');

  // Shows the add comics submenu on hover
  $($menuButton).click(function(e) {
    e.preventDefault();
    $($comicSubmenu).toggleClass('expanded');
  });

});