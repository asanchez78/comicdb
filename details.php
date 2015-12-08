<?php
require_once 'classes/wikiFunctions.php';

$wiki_id= filter_input(INPUT_GET, 'wiki_id');
$comic = new wikiQuery();
$comic->comicCover($wiki_id);
$comic->comicDetails($wiki_id);
?>

<?php include 'views/head.php';?>
  <title>Add Issue</title>
</head>
<body>
<?php
echo "<img alt=\"cover\" src=" . $comic->coverURL . ">";
echo "<br>\n";
echo "<br>\n";

echo $comic->wikiTitle;
echo "<br>\n";
echo "<br>\n";
echo $comic->storyName;
echo "<br>\n";
echo "<br>\n";
echo $comic->synopsis;
?>

</body>
</html>