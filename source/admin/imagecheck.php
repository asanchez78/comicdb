<?php
require_once '../views/head.php';
require_once '../classes/wikiFunctions.php';
$downloadList = NULL;
$wiki = new wikiQuery();

$sql = "SELECT comics.comic_id, comics.series_id, series.series_vol, series.cvVolumeID, comics.issue_number
  FROM comics
  LEFT JOIN series
  ON comics.series_id=series.series_id 
  WHERE wikiUpdated=0";

$result = $connection->query ($sql);
set_time_limit(0);
while ($row = $result->fetch_assoc()) {
  $wiki = new wikiQuery();
  $cvVolumeID = $row['cvVolumeID'];
  $issue_number = $row['issue_number'];
  $series_vol = $row['series_vol'];
  $comic_id = $row['comic_id'];
  $wiki->issueSearch($cvVolumeID, $issue_number, $series_vol);
  $cover_image = $wiki->coverURL;
  if ($cover_image == 'assets/nocover.jpg') {
    $cover_image_file = 'assets/nocover.jpg';
  } else {
    $cover_image_file = $wiki->coverFile;
    $path = '../' . $cover_image_file;
    $wiki->downloadFile ( $cover_image, $path );
  }
  $sql = "UPDATE comics SET comics.cover_image='$cover_image_file', comics.wikiUpdated=1 WHERE comics.comic_id=$comic_id";
  if (mysqli_query ( $connection, $sql )) {
  } else {
  $rangeSearch = false;
  $messageNum = 51;
  $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
  }
  $message = "The following cover images were downloaded:";
  $downloadList .= "URL: $wiki->coverURL";
  $downloadList .= "<br/>";
  $downloadList .= "Saved to: $path";
  $downloadList .= "<br/>";
  sleep(4);
}

if (empty($downloadList)) {
  $message = "All records have a cover image.";
}

?>

<title>POW! Comic Book Manager</title>
</head>
<body>
        <?php include '../views/header.php';?>
        <div class="container content">
                <div class="row">
                        <div class="col-sm-12">
                        <p><?php echo $message; ?></p>
                        <p><?php echo $downloadList; ?></p>
                </div>
        </div>
        </div>
<?php include '../views/footer.php';?>
</body>
</html>
