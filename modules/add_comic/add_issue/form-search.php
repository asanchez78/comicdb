<?php
  require_once(__ROOT__.'/classes/wikiFunctions.php');
  $series_id = filter_input ( INPUT_POST, 'series_id' );
  $issue_number = filter_input ( INPUT_POST, 'issue_number' );
  $comic = new comicSearch();
  $comic->seriesInfo($series_id, $userID);
  $cvVolumeID = $comic->cvVolumeID;
  $series_vol = $comic->series_vol;

  if (isset($comic->publisherName)) {
    $publisherName = $comic->publisherName;
    $publisherShort = $comic->publisherShort;
  } else {
    $messageNum = 60;
  }
  $wiki = new wikiQuery;
  $wiki->issueSearch($cvVolumeID, $issue_number, $series_vol);
  $searchResults = $wiki->searchResults;
  if ($searchResults != false) {
    $series_name = $comic->series_name;
    $series_vol = $comic->series_vol;
    $story_name = $wiki->storyName;
    $plot = $wiki->synopsis;
    $release_date = $wiki->releaseDate;
    $release_dateShort = DateTime::createFromFormat('Y-m-d', $wiki->releaseDate)->format('M Y');
    $release_dateLong = DateTime::createFromFormat('Y-m-d', $wiki->releaseDate)->format('M d, Y');
    $script = $wiki->script;
    $pencils = $wiki->pencils;
    $colors = $wiki->colors;
    $letters = $wiki->letters;
    $editing = $wiki->editing;
    $coverArtist = $wiki->coverArtist;
    $coverURL = $wiki->coverURL;
    $coverFile = $wiki->coverFile;
    $coverThumbURL = $wiki->coverThumbURL;
    $coverThumbFile = $wiki->coverThumbFile;
    $coverSmallURL = $wiki->coverSmallURL;
    $coverSmallFile = $wiki->coverSmallFile;
    $creatorsList = $wiki->creatorsList;
  } else {
    $messageNum = 65;
  }
?>