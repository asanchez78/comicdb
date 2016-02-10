<?php
  // ADD SERIES: Part two of the series process. Checks the database for existing series, and then adds series to the database.
  require_once(__ROOT__.'/classes/wikiFunctions.php');
  $apiDetailURL = filter_input ( INPUT_POST, 'apiURL' );
  $seriesLookup = new wikiQuery();
  $seriesLookup->seriesLookup($apiDetailURL);
  $series_name = $seriesLookup->seriesName;
  $series_vol = $seriesLookup->seriesStartYear;
  $publisherID = filter_input ( INPUT_POST, 'publisherID' );
  $sql = "INSERT INTO series (series_name, series_vol, publisherID, cvVolumeID, apiDetailURL, siteDetailURL)
          VALUES ('$series_name', '$series_vol', '$publisherID', '$seriesLookup->cvVolumeID', '$seriesLookup->apiDetailURL', '$seriesLookup->siteDetailURL')";
  if (mysqli_query ( $connection, $sql )) {
    $messageNum = 3;
    $seriesSubmitted = true;
    $sqlMessage = '<strong class="text-success">Success</strong>: <code>' . $sql . '</code>';

    // Create the directory to store the issue images in
    $seriesDir = __ROOT__.'/images/' . preg_replace('/[^a-z0-9]+/i', '_', $series_name) . '-v' . $series_vol;
    if (!file_exists($seriesDir)) {
      mkdir($seriesDir, 0777, true);
    }
  } else {
    $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br /><code>' . mysqli_error ( $connection ) . $connection->errno . '</code>';
    $seriesSubmitted = false;
    if ($connection->errno == 1062) {
      $messageNum = 50;
    }
  }
?>