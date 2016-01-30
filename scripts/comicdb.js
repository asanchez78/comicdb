jQuery(document).ready(function($) {
  var $notifyClose, $notifications, $addAnotherSeries, $backButton, $addComicsMenu;

  $notifications = $('.notifications');
  $notifyClose = $($notifications).find('.close');
  $($notifyClose).click(function(e) {
    e.preventDefault;
    $($notifications).addClass('notifications-close');
  });

  if (!$notifications.hasClass('notifications-close')) {
    setTimeout(function() {
      $($notifications).addClass('notifications-close');
    }, 6000);
  }

  // Hiding the "SERIES has been added successfully. Add another?" section after button click.
  $addAnotherSeries = $('.add-success').find('.add-another');
  $($addAnotherSeries).click(function(e) {
    e.preventDefault;
    // Let CSS do the work for us, just add a new class to the container.
    $('.add-success').addClass('success-hide');
  });

  // Back button
  $backButton = $('.form-back');
  $($backButton).click(function(e) {
    e.preventDefault;
    window.history.back();
  });

  // Add comics tabs
  $addComicsMenu = $('.add-menu').find('a');
  $($addComicsMenu).click(function(e) {
    e.preventDefault;
    var $section = $(this).attr('id');
    $($addComicsMenu).removeClass('active');
    $(this).addClass('active');
    var $addBlocks = $('.add-block');
    $.each($addBlocks, function() {
      if($(this).hasClass($section)) {
        $(this).addClass('active');
      } else {
        $(this).removeClass('active');
      }
    });
  });

  // Detects a hashtag in url for add comics
  var hash = window.location.hash;
  var $addBlocks = $('.add-block');
  if(hash) {
    $($addBlocks).removeClass('active');
    $($addComicsMenu).removeClass('active');
    if(hash == '#addseries') {
      $('#form-add-series').addClass('active');
      $('.form-add-series').addClass('active');
    } else if(hash == '#addissue') {
      $('#form-add-issue').addClass('active');
      $('.form-add-issue').addClass('active');
    } else if(hash == '#addrange') {
      $('#form-add-range').addClass('active');
      $('.form-add-range').addClass('active');
    } else if(hash == '#addlist') {
      $('#form-add-list').addClass('active');
      $('.form-add-list').addClass('active');
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
    $(this).text('[SAVE]');
    $('#plotInput').addClass('active');
    $('.plot-output').addClass('hidden');
  });
});