jQuery(document).ready(function($) {

  // Back button. Triggers native browser back state
  var $backButton = $('.form-back');
  $($backButton).click(function(e) {
    e.preventDefault;
    window.history.back();
  });

  // Bootstrap tab trigger for the Add Comics menu
  $('#addTabs a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  });

  // Detects a hashtag in url for add comics
  var hash = window.location.hash;
  if(hash) {
    if(hash == '#addseries') {
      $('#addTabs a[href="#addSeries"]').tab('show');
    } else if(hash == '#addsingle') {
      $('#addTabs a[href="#addSingle"]').tab('show');
    } else if(hash == '#addrange') {
      $('#addTabs a[href="#addRange"]').tab('show');
    } else if(hash == '#addlist') {
      $('#addTabs a[href="#addList"]').tab('show');
    }
  }

  // Sort display controls
  var $sortControls, $comics, $comicsList;

  $sortControls = $('.sort-control');
  $comicsList = $('#inventory-table');
  $comics = $($comicsList).find('li');

  $($sortControls).click(function() {
    var controlId, layoutThumbLg, layoutThumbSm, layoutList;
    controlId = $(this).attr('id');
    
    // Layouts
    layoutThumbLg = 'col-xs-6 col-sm-4 col-md-3 col-lg-2';
    layoutThumbSm = 'col-xs-3 col-sm-3 col-md-2 col-lg-1';
    layoutList = 'col-xs-12';

    // Clears the active state from all buttons
    $($sortControls).removeClass('active');
    // Adds the active state to the pressed button
    $(this).addClass('active');

    if(controlId == 'sort-thumb-lg') {
      $($comicsList).attr('class','row layout-thumb-lg');
      // Loop through all of the grid items on the page and reset their CSS classes with the styles defined above
      $.each($comics, function() {
        $(this).attr('class', layoutThumbLg);
      });
    } else if(controlId == 'sort-thumb-sm') {
      $($comicsList).attr('class','row layout-thumb-sm');
      $.each($comics, function() {
        $(this).attr('class', layoutThumbSm);
      });
    } else {
      $($comicsList).attr('class','row layout-list');
      $.each($comics, function() {
        $(this).attr('class', layoutList);
      });
    }
  });

  // Triggers the Edit Plot function
  $('#editPlot').click(function(e) {
    e.preventDefault;
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

  // Hides the notification bar after 6 seconds (6000ms)
  var $notifications = $('.notifications');
  if($notifications) {
    setTimeout(function() {
      $($notifications).addClass('notify-hide');
    }, 6000);
  }
});