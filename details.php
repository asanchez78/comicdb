<?php
require_once 'classes/wikiFunctions.php';

$wiki_id= filter_input(INPUT_GET, 'wiki_id');
$comic = new wikiQuery();
$comic->comicCover($wiki_id);
$comic->comicDetails($wiki_id);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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