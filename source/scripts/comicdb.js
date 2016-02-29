jQuery(document).ready(function($) {
  var displayChange = function (type) {
    var layoutThumbLg, layoutThumbSm, layoutList, days, myDate, cookieString, $comics, $comicsList, typeClass;
    
    // Set Cookie to remember list display
    days=30;
    myDate = new Date();
    myDate.setTime(myDate.getTime()+(days*24*60*60*1000));
    cookieString = 'DisplayStyle=' + type + '; expires=' + myDate.toGMTString() + '; path=/';
    document.cookie = cookieString;
    
    // Layouts
    layoutThumbLg = 'col-xs-6 col-sm-4 col-md-3 col-lg-2';
    layoutThumbSm = 'col-xs-3 col-sm-3 col-md-2 col-lg-1';
    layoutList = 'col-xs-12';

    // Grab the objects
    $comicsList = $('#inventory-table');
    $comics = $($comicsList).find('li');

    // Reset rows and classes on each cell
    $($comicsList).attr('class','row layout-' + type);
    if (type == 'thumbLg') {
      typeClass = layoutThumbLg;
    } else if (type == 'thumbSm') {
      typeClass = layoutThumbSm;
    } else {
      typeClass = layoutList;
    }
    $.each($comics, function() {
      $(this).attr('class', typeClass);
    });
  };

  var $sortControls, displayCookie;
  $sortControls = $('.sort-control');

  displayCookie = document.cookie.split(';').map(function(x){ return x.trim().split('='); }).filter(function(x){ return x[0]==='DisplayStyle'; }).pop();

  if (displayCookie !== undefined) {
    if (displayCookie[1] == 'thumbSm') {
      $($sortControls).removeClass('active');
      $('#sort-thumb-sm').addClass('active');
      displayChange('thumbSm');
    } else if (displayCookie[1] == 'list') {
      $($sortControls).removeClass('active');
      $('#sort-list').addClass('active');
      displayChange('list');
    }
  }

  // Back button. Triggers native browser back state
  var $backButton = $('.form-back');
  $($backButton).click(function(e) {
    e.preventDefault();
    window.history.back();
  });

  // Bootstrap tab trigger for the Add Comics menu
  $('#addTabs a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  });

  // Detects a hashtag in url for add comics
  var hash = window.location.hash;
  if(hash && hash !== '') {
    $('#addTabs a[href="' + hash + '"]').tab('show');
  }

  $('#addTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    window.location.hash = e.target.hash.substr(1) ; 
    return false;
  });

  // Sort display control
  $($sortControls).on("click", function() {
    var controlId;
    controlId = $(this).attr('id');

    // Clears the active state from all buttons
    $($sortControls).removeClass('active');
    // Adds the active state to the pressed button
    $(this).addClass('active');

    if(controlId == 'sort-thumb-lg') {
      displayChange('thumbLg');
    } else if(controlId == 'sort-thumb-sm') {
      displayChange('thumbSm');
    } else {
      displayChange('list');
    }
  });

  // Triggers the Edit Plot function
  $('#editPlot').click(function(e) {
    e.preventDefault();
    $(this).text('');
    // Shows the textarea container 
    $('#plotInput').addClass('active');
    // Hides the plot output
    $('.plot-output').addClass('hidden');
  });

  // Triggers the loading spinner icon when any submit button is pressed
  $('.form-submit').click(function() {
    $(this).addClass('loading');
  });

  $('.form-save').click(function() {
    $(this).addClass('loading');
  });

  // Hides the notification bar after 6 seconds (6000ms)
  var $notifications = $('.notifications');
  if($notifications) {
    setTimeout(function() {
      $($notifications).addClass('notify-hide');
    }, 6000);
  }

  // Sets the menu item active if you are on the page
  var currentPage, $menuItem;

  currentPage = window.location.pathname;
  $menuItem = $('#main-nav-collapse li').find('a');

  $.each($menuItem, function() {
    if (currentPage == $(this).attr('href')) {
      $(this).parent().addClass('active');
    } else {
      // Exceptions to the rule, like Dashboard, or Issues/Comic pages
      if (currentPage == '/index.php' && $(this).attr('href') == '/') { 
        $(this).parent().addClass('active'); 
      }
      if (currentPage == '/issues.php' && $(this).attr('href') == '/profile.php') { 
        $(this).parent().addClass('active'); 
      }
      if (currentPage == '/comic.php' && $(this).attr('href') == '/profile.php') { 
        $(this).parent().addClass('active'); 
      }
    }
  });
});