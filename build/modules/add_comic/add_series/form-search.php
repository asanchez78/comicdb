<?php
  // ADD SERIES: Part one of the series process. Displays API search results.
  require_once(__ROOT__.'/classes/wikiFunctions.php');
  $series_name = filter_input ( INPUT_POST, 'series_name' );
  $publisherID = filter_input ( INPUT_POST, 'publisherID' );
  $series_vol = filter_input ( INPUT_POST, 'series_vol' );
  $wiki = new wikiQuery();
  $wiki->seriesSearch("$series_name");
?>