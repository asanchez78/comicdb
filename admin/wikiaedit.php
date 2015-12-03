<?php
require_once '../classes/wikiFunctions.php';
require_once '../classes/functions.php';
require_once '../config/db.php';
require_once '../classes/Login.php';
$login = new Login ();

if ($login->isUserLoggedIn () == false) {
	include ("../views/not_logged_in.php");
	die ();
}

$wiki_id = filter_input ( INPUT_GET, 'wiki_id' );
$comic_id = filter_input(INPUT_GET, 'comic_id');
$comic = new wikiQuery ();
$comic->comicCover ( $wiki_id );
$comic->comicDetails ( $wiki_id );
$dbValues = new comicSearch();
$dbValues->issueLookup($comic_id);

echo "<img alt=\"cover\" src=" . $comic->coverURL . ">\n";
echo "<br>\n";
echo "<br>\n";

echo $comic->wikiTitle;

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Add Issue</title>
</head>
<body>


<form method="post" action="formprocess.php">
<div>
<h2>Update Issue</h2>

</div>

<label>Series </label>
<br/>
<input name="series_name" type="text" maxlength="255" value="<?php echo $dbValues->series_name; ?>" />
<br/>

<label>Issue Number </label>
<br/>
<input name="issue_number" type="text" maxlength="255" value="<?php echo $dbValues->issue_number; ?>" />
<br/>
<label>Story Name </label>
<br/>
<input name="story_name" type="text" maxlength="255" value="<?php echo $comic->storyName; ?>" />
<br/>
<label>Release Date </label>
<br/>
<input name="released_date" size="10" maxlength="10" value="<?php echo $dbValues->release_date; ?>" type="text" />
<label>YYYY-MM-DD</label>
<br/>
<label>Plot </label>
<br/>
<textarea rows="10" cols="175" name="plot"><?php echo $comic->synopsis; ?></textarea>
<br/>
<label>Cover Image URL</label>
<br/>
<input name="cover_image" type="text" maxlength="255" value="<?php echo $comic->coverURL; ?>" />
<label>Destination Filename</label> <input name="cover_image_file" type="text" maxlength="255" value="<?php echo $comic->coverFile; ?>" />

<input type="hidden" name="original_purchase" value="<?php echo $dbValues->original_purchase; ?>" />

<input type="hidden" name="update" value="yes" />
				
<input type="hidden" name="comic_id" value="<?php echo $comic_id; ?>" />
				
<input type="hidden" name="series_id" value="<?php echo $dbValues->series_id; ?>" />
				
<input type="submit" name="submit" value="Submit" />

</form>

<a href="comic.php?logout">Logout</a>
		
</body>
</html>