<?php
require_once 'classes/wikiFunctions.php';
require_once 'classes/functions.php';

$series_name = filter_input(INPUT_POST, 'series_name');
$issue_number = filter_input(INPUT_POST, 'issue_number');
$query = $series_name . " " . $issue_number;

//$comic = str_replace(' ', '+', $query);
//$api_url = "http://marvel.wikia.com/api/v1/Search/List?query=$comic&limit=25&minArticleQuality=10&batch=1&namespaces=0%2C14";
//$jsondata = file_get_contents($api_url);
//$results = json_decode($jsondata, true);

$returnedResults = new wikiQuery();
$returnedResults->wikiSearch($query, $series_name, $issue_number, 25);


//while ($row = $returnedResults->wikiSearchResultTitle) {
//	echo "<a href=\"details.php?wiki_id=" . $returnedResults->wikiSearchResultID . "\">" . $returnedResults->wikiSearchResultTitle . "</a> - ";
//	echo "<br>\n";
//}

echo $returnedResults->resultsList
?>