<?php
// Create connection
require_once 'classes/functions.php';
require_once 'classes/wikiFunctions.php';
//require_once 'classes/Login.php';
require_once 'config/db.php';

$comic_id = filter_input ( INPUT_GET, 'comic_id' );
$details = new comicSearch ();
$details->issueLookup ( $comic_id );
$details->artistLookup ( $comic_id );
$details->writerLookup ( $comic_id );
$wikiLookup = new wikiQuery ();
$wikiLookup->comicCover ( $details->wiki_id );

?>
<html>
<head>
<link rel="stylesheet" href="material.min.css">
<script src="material.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comicdb</title>
</head>

<body>
<?php include 'views/header.php';?>
<br/>
<div class="mdl-layout">
<div class="mdl-grid">
	<div class="mdl-cell mdl-cell--6-col">
		<div class="mdl-card mdl-shadow--4dp">
			<div class="mdl-card__title">
				<h2 class="mdl-card__title-text">
					<?php echo $details->series_name . " #" . $details->issue_number; ?>
				</h2>
			</div>
			<div class="mdl-card__supporting-text">
				<?php echo $details->plot; ?>
			</div>
			<?php
				if ($details->writer) {
					echo "<div class=\"mdl-card__supporting-text\">";
					echo "Writer: " . $details->writer;
					echo "</div>";
				}

			 	if ($details->artist) {
					echo "<div class=\"mdl-card__supporting-text\">";
					echo "Artist: " . $details->artist;
					echo "</div>";
				}
			?>
			<div class="mdl-card__supporting-text">
				<table class="mdl-data-table mdl-js-data-table">
					<thead>
						<tr>
							<th>Issue Number</th>
							<td><?php echo $details->issue_number; ?></td>
						</tr>
						<tr>
							<th>Story Name</th>
							<td><?php echo $details->story_name; ?></td>
						</tr>
						<tr>
							<th>Cover Date</th>
							<td><?php echo $details->release_date; ?></td>
						</tr>
					</thead>
				</table>
			</div>
			<div class="mdl-card__actions">
				<?php
					if ($login->isUserLoggedIn () == true) {
						echo "<a href=\"admin/wikiaedit.php?comic_id=" . $details->comic_id . "&wiki_id=" . $details->wiki_id . "\" class=\"mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent\">Update Info</a>";
					}
				?>
			</div>
		</div>
	</div>
	<div class="mdl-cell mdl-cell--6-col">
		<div class="mdl-card mdl-shadow--4dp">
			<div class="mdl-card__media centered">
				<img alt="cover" height="520" src="<?php echo $details->cover_image; ?>" style="padding: 10px;"/>
			</div>
		</div>
	</div>
</div>
</div>
<?php include 'views/footer.php';?>
<br/><br/>
</body>
</html>
