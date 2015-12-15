jQuery(document).ready(function($) {
  var $menuButton, $comicSubmenu;

  $menuButton = $('#button-add-comics');
  $comicSubmenu = $('#comics-submenu');

  // Shows the add comics submenu on hover
  $($menuButton).hover(function() {
    $($comicSubmenu).slideToggle( "slow", function(){});
  });
});