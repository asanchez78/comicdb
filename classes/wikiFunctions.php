<?php
/**
 *
 * @author asanchez
 * <pre>
 *Functions:
 *wikiSearch
 *comicCover
 *comicDetails
 *</pre>
 */
class wikiQuery {
	private $wikiSearchResultTitle;
	private $query;
	/**
	 * a list of search results from marvel wiki
	 * @var string
	 */
	public $resultsList;
	public $coverURL;
	public $coverFile;
	public $storyName;
	public $wikiTitle;
	public $synopsis;
	public $wikiSearchResultID;
	public $AddWikiIDMsg;
	public $addDetailsMsg;
	public $newWikiIDs;
	public $updatedList;
	public $coverSearchErr;

	/**
	 * queries ComicVine API to get the API URL of the series searched for
	 * @param  int $seriesName name of the comic series being searched for
	 * @return string             list of results
	 */
	public function seriesSearch ($seriesName) {
		$apiURL = "http://www.comicvine.com/api/volumes/?api_key=8c685f7695c1dda5a4ecdf35c54402438a77b691&format=json&filter=name:$seriesName";
		$jsondata = file_get_contents($apiURL);
		$results = json_decode($jsondata, true);
		$this->resultNum = 1;
			foreach($results['results'] as $result) {
				$this->seriesName = $result['name'];
				$this->cvVolumeID = $result['id'];
				$this->seriesStartYear = $result['start_year'];
				$this->seriesURL = $result['site_detail_url'];
				$this->apiURL = $result['api_detail_url'];
				$this->resultsList .= '<div class="series-search-result col-xs-12 col-sm-6 col-md-4">
											<input name="apiURL" id="apiURL-' . $this->cvVolumeID . '" value="' . $this->apiURL . '" type="radio" />
												<label for="apiURL-' . $this->cvVolumeID . '">' . $this->resultNum . ': '  . $this->seriesName . ' ' . $this->seriesStartYear .'</label>
											<a href="' . $this->seriesURL . '" target="_blank">' . $this->seriesURL . '</a>
										</div>';
				++$this->resultNum;
			}
	}

	public function seriesLookup ($apiDetailURL) {
		$apiURL = $apiDetailURL . "?api_key=8c685f7695c1dda5a4ecdf35c54402438a77b691&format=json";
		$jsondata = file_get_contents($apiURL);
		$results = json_decode($jsondata, true);
		$this->seriesName = $results['results']['name'];
		$this->cvVolumeID = $results['results']['id'];
		$this->seriesStartYear = $results['results']['start_year'];
		$this->siteDetailURL = $results['results']['site_detail_url'];
		$this->apiDetailURL = $results['results']['api_detail_url'];
	}

	public function downloadFile($url, $path) {
		$newfname = $path;
		$file = fopen ( $url, "rb" );
		if ($file) {
			$newf = fopen ( $newfname, "wb" );

			if ($newf) {
				while ( ! feof ( $file ) ) {
					fwrite ( $newf, fread ( $file, 1024 * 8 ), 1024 * 8 );
				}
			} else {
				die ( 'Could not write cover image file.' );
			}
		}

		if ($file) {
			fclose ( $file );
		}

		if ($newf) {
			fclose ( $newf );
		}
	}

	/**
	 * uses an API call to wikia.com to return search results
	 * @param string $query
	 */
public function wikiSearch($publisherAPI, $query, $limit) {
		$comic = str_replace(' ', '+', $query);
		$api_url = "http://$publisherAPI.wikia.com/api/v1/Search/List?query=$comic&limit=$limit&minArticleQuality=10&batch=1&namespaces=0%2C14";
		$jsondata = file_get_contents($api_url);
		$results = json_decode($jsondata, true);
		if (count($results['items']) == 1) {
			foreach($results['items'] as $result) {
				$this->wikiSearchResultID = $result['id'];
				$this->wiki_id = $this->wikiSearchResultID;
			}
		} else if (count($results['items']) > 1){
			foreach($results['items'] as $result) {
				$this->wikiSearchResultID = $result['id'];
				$this->wikiSearchResultTitle = $result['title'];
				$this->resultsList .= '<div class="issue-search-result col-xs-12 col-sm-6 col-md-4"><input name="wiki_id" id="wiki_id-' . $this->wikiSearchResultID . '" value="' . $this->wikiSearchResultID . '" type="radio" /> <label for="wiki_id-' . $this->wikiSearchResultID . '">' . $this->wikiSearchResultTitle . '</label></div>';
				$this->wikiID = $this->wikiSearchResultID;
			}
		} else {
			echo "No results. Perhaps you mispelled something?<br>";
			echo "You searched for " . "\"" . $query . "\"";
		}
	}
	/**
	 * grabs the cover image from wikia.com using the comic's wiki_id
	 * @param int $wiki_id
	 */
	public function comicCover($publisherAPI, $wiki_id) {
		$cover_api = "http://$publisherAPI.wikia.com/api/v1/Articles/Details?ids=$wiki_id";
		$cover_jsondata = file_get_contents($cover_api);
		$details_results = json_decode($cover_jsondata, true);
		$wiki_id = $details_results['items'][$wiki_id]['id'];
		//make sure entry has a cover URL
		if ($details_results['items'][$wiki_id]['thumbnail']) {
			$subject = $details_results['items'][$wiki_id]['thumbnail'];
			$pattern = "/(?<=jpg|png|jpeg).*/";
			$replacement = "";
			$this->coverURL = preg_replace($pattern, $replacement, $subject);
			$fileparts = explode("/", $this->coverURL);
			$this->coverFile = 'images/' . $fileparts[7];
			$this->coverFile = str_replace("%28", "", $this->coverFile);
			$this->coverFile = str_replace("%29", "", $this->coverFile);
			$this->coverFile = str_replace("%3F", "", $this->coverFile);
		} else {
			$this->coverFile = 'assets/nocover.jpg';
			$this->coverURL = 'assets/nocover.jpg';
			$this->noCover = true;
			$sql = "SELECT comics.comic_id, series.series_name, comics.issue_number
			FROM comics
			LEFT JOIN series ON comics.series_id=series.series_id
			WHERE wiki_id=$wiki_id";
			$this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
			$result = $this->db_connection->query($sql);
			while ($row = $result->fetch_assoc()) {
				$comic_id = $row['comic_id'];
				$series_name = $row['series_name'];
				$issue_number = $row['issue_number'];
				$this->coverSearchErr .= "$series_name $issue_number has no cover image result.";
			}
		}
	}
	/**
	 * grabs comic details from wikia.com using the comic's wiki_id
	 * @param int $wiki_id
	 */
	public function comicDetails($publisherAPI, $wiki_id) {
		$details_api = "http://$publisherAPI.wikia.com/api/v1/Articles/AsSimpleJson?id=$wiki_id";
		$issue_jsondata = file_get_contents($details_api);
		$issue_results = json_decode($issue_jsondata, true);
		$paragraphs = $issue_results['sections'][2]['content'];
		$paragraphNum = 0;
		$this->storyName = trim(str_replace('Appearing in ', "", $issue_results['sections'][1]['title']), '"');
		$this->wikiTitle = $issue_results['sections'][0]['title'];
		if (array_key_exists('text', $issue_results['sections'][2]['content'][0])) {
			foreach ($paragraphs as $paragraph) {
				$this->synopsis .= "<p>\r\n";
				if (array_key_exists('text', $issue_results['sections'][2]['content'][$paragraphNum])) {
				$this->synopsis .= $issue_results['sections'][2]['content'][$paragraphNum]['text'];
				}
				if (array_key_exists('elements', $issue_results['sections'][2]['content'][$paragraphNum])) {
					$bullets = $issue_results['sections'][2]['content'][$paragraphNum]['elements'];
					$bulletNum = 0;
					$this->synopsis .= "<ul>\r\n";
					foreach ($bullets as $bullet) {
						$this->synopsis .= "<li>" . $issue_results['sections'][2]['content'][$paragraphNum]['elements'][$bulletNum]['text'] . "</li>\r\n";
						++$bulletNum;
					}
					$this->synopsis .= "</ul>";
				}
				$this->synopsis .= "\r\n</p>\r\n";
				++$paragraphNum;
			}
		} else {
			$this->synopsis = "";
		}
	}

	/**
	 * searches wikia api using series name and issue number to attempt
	 * to get a wiki ID
	 */
	public function addWikiID() {

		//Get wiki ids for records that do not have them by searching the marvel wikia api
		$sql = "SELECT comics.comic_id, series.series_name, series.series_vol, comics.issue_number
			FROM comics
			LEFT JOIN series ON comics.series_id=series.series_id
			WHERE comics.wiki_id=0";
		$this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
		$result = $this->db_connection->query ($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$comic_id = $row ['comic_id'];
				$series_name = $row ['series_name'];
				$series_vol = $row['series_vol'];
				$issue_number = $row['issue_number'];
				$query = $series_name . " vol " . $series_vol . " " . $issue_number;
				$wikiDetails = $this->wikiSearch($query, $series_name, $issue_number, 1);
				$sql = "UPDATE comics SET wiki_id=$wikiDetails WHERE comic_id='$comic_id'";
				set_time_limit(0);
				$this->newWikiIDs .= "<a href=\"../comic.php?comic_id=" . $comic_id . "\" target=\"_blank\">New entry created for ". $series_name . " #" . $issue_number ."</a>\n";
				$this->newWikiIDs .= "<br/>\n";
				if (mysqli_query ( $this->db_connection, $sql )) {
					$this->AddWikiIDMsg = "wiki IDs entered";
				} else {
					echo '<p><strong class="text-danger">Error</strong>: ' . $sql . '</p><code>' . mysqli_error ( $this->db_connection ) . '</code>';
				}
			}
		} else {
			$this->AddWikiIDMsg = "All entries have a wiki id.";
		}
	}

	/**
	 * fills in details for entries with a wiki id where wikiUpdated flag is 0
	 */
	public function addDetails() {
		$sql = "SELECT comics.comic_id, series.series_name, comics.issue_number, wiki_id
			FROM comics
			LEFT JOIN series ON comics.series_id=series.series_id
			WHERE comics.wiki_id !=0
			AND comics.wikiUpdated=0";
		$result = $this->db_connection->query ( $sql );
		if ($result->num_rows > 0) {
			while ( $row = $result->fetch_assoc () ) {
				$comic_id = $row ['comic_id'];
				$wiki_id = $row ['wiki_id'];
				$series_name = $row ['series_name'];
				$issue_number = $row['issue_number'];
				$this->comicCover($wiki_id);
				$this->comicDetails($wiki_id);
				if ($this->coverFile) {
					$url = $this->coverURL;
					$path = "../images/$this->coverFile";
					$this->downloadFile($url, $path);
				}
				$synopsis = addslashes($this->synopsis);
				$storyName = addslashes($this->storyName);
				$sql = "UPDATE comics
				SET story_name='$storyName',  plot='$synopsis', cover_image='images/$this->coverFile', wikiUpdated=1
				WHERE comic_id='$comic_id'";
				set_time_limit(0);
				if (mysqli_query ( $this->db_connection, $sql )) {
					$this->addDetailsMsg = "Entries below Updated with new information.";
					$this->updatedList .= "<a href=\"../comic.php?comic_id=" . $comic_id . "\" target=\"_blank\">Details entered for ". $series_name . " #" . $issue_number ."</a>\n";
					$this->updatedList .= "<br/>\n";
				} else {
					echo "Error: " . $sql . "<br>" . mysqli_error ( $this->db_connection );
				}
				//resetting synopsis so that it doesn't concatinate with the next result
				$this->synopsis = "";
			}
		} else {
			$this->addDetailsMsg = "All records have wiki information filled in";
		}
	}
}