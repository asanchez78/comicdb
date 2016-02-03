jQuery(document).ready(function($) {
  var $notifyClose, $notifications, $addAnotherSeries, $backButton, $addComicsMenu;

  // Back button
  $backButton = $('.form-back');
  $($backButton).click(function(e) {
    e.preventDefault;
    window.history.back();
  });

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
    layoutThumbLg = 'col-xs-6 col-sm-4 col-md-3 col-lg-2';
    layoutThumbSm = 'col-xs-4 col-sm-3 col-md-2 col-lg-1';
    layoutList = 'col-xs-12';

    $($sortControls).removeClass('active');
    $(this).addClass('active');
    if(controlId == 'sort-thumb-lg') {
      $($comicsList).attr('class','row layout-thumb-lg');
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

  $('#editPlot').click(function(e) {
    e.preventDefault;
    $(this).text('');
    $('#plotInput').addClass('active');
    $('.plot-output').addClass('hidden');
  });
});