<?php
/**
 *
 * @author asanchez
 * <pre>
 *Functions:
 *seriesSearch
 *SeriesLookup
 *issueSearch
 *</pre>
 */
class wikiQuery {
	private $jsondata;
	private $results;
	private $detailResults;
	private $subject;
	private $pattern;
	private $replacement;
	private $fileparts;
	private $newfname;
	private $path;
	private $file;
	private $newf;
	private $url;
	/**
	 * a list of search results from ComicVine
	 * @var string
	 */
	public $resultsList;
	public $coverURL;
	public $coverFile;
	public $storyName;
	public $synopsis;
	public $coverSearchErr;
	public $apiURL;
	public $resultNum;
	public $seriesName;
	public $cvVolumeID;
	public $seriesStartYear;
	public $seriesURL;
	public $apiDetailURL;
	public $siteDetailURL;

	/**
	 * queries ComicVine API to get the API URL of the series searched for
	 * @param  int $seriesName name of the comic series being searched for
	 * @return string             list of results
	 */
	public function seriesSearch ($seriesName) {
		$apiURL = "https://comicvine.gamespot.com/api/volumes/?format=json&filter=name:$seriesName&api_key=" . __apiKey__;
		$ch = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_USERAGENT, "WadeWilson" );
		$jsondata = curl_exec($ch);
		$results = json_decode($jsondata, true);
		curl_close($ch);
		$this->resultNum = 1;

		if ($results['number_of_page_results'] < 5) {
			if ($results['number_of_page_results'] === 1) {
				$sizeClasses = 'col-xs-6 col-md-3 col-lg-6';
			} else {
				$sizeClasses = 'col-xs-6 col-md-3 col-lg-3';
			}
		} else {
			$sizeClasses = 'col-xs-6 col-md-3 col-lg-2';
		}

		foreach($results['results'] as $result) {
			$this->seriesName = $result['name'];
			$this->cvVolumeID = $result['id'];
			$this->seriesStartYear = $result['start_year'];
			$this->seriesURL = $result['site_detail_url'];
			$this->apiURL = $result['api_detail_url'];
			$this->thumb = $result['image']['thumb_url'];
			$this->issueCount = $result['count_of_issues'];
			$this->resultsList .= '<div class="series-search-result ' . $sizeClasses . '">
										<input name="apiURL" id="apiURL-' . $this->cvVolumeID . '" value="' . $this->apiURL . '" type="radio" />
										<label for="apiURL-' . $this->cvVolumeID . '" class="text-center"><a href="' . $this->seriesURL . '" target="_blank" class="center-block"><img src="' . $this->thumb . '" alt="" class="img-responsive" />' . $this->seriesName . ' (' . $this->seriesStartYear .')</a>
										<small>' . $this->issueCount . ' issues</small></label>
									</div>';
			++$this->resultNum;
		}
	}

	/**
	 * Uses a comic series' API URL to get information about that series.
	 * @param  string $apiDetailURL The API URL for a comic series
	 * @return string               gets series name, volume ID, series start year, and details URLs
	 */
	public function seriesLookup ($apiDetailURL) {
		$apiURL = $apiDetailURL . "?format=json&api_key=" . __apiKey__;
		$ch = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_USERAGENT, "WadeWilson" );
		$jsondata = curl_exec($ch);
		$results = json_decode($jsondata, true);
		curl_close($ch);
		$this->seriesName = $results['results']['name'];
		$this->cvVolumeID = $results['results']['id'];
		$this->seriesStartYear = $results['results']['start_year'];
		$this->siteDetailURL = $results['results']['site_detail_url'];
		$this->apiDetailURL = $results['results']['api_detail_url'];
	}

	/**
	 * Uses a series' ComicVine volume ID and a comic's issue number to get issue details
	 * @param  int $cvVolumeID   ComicVine volume ID of the series
	 * @param  int $issue_number issue number of the comic
	 * @return string               issue's story name, plot, etc.
	 */
	public function issueSearch ($cvVolumeID, $issue_number, $series_vol) {
		$apiURL = "https://comicvine.gamespot.com/api/issues/?filter=volume:$cvVolumeID,issue_number:$issue_number&format=json&api_key=" . $_COOKIE["apiKey"];
		$ch = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_USERAGENT, "WadeWilson" );
		$jsondata = curl_exec($ch);
		$results = json_decode($jsondata, true);
		curl_close($ch);

		// Checks if the results returned anything
		if ($results['number_of_page_results'] > 0 && $results !== FALSE) {
			$this->searchResults = true;
			$apiDetailURL = $results['results']['0']['api_detail_url'] . "?format=json&api_key=" . __apiKey__;
			$ch = curl_init($apiDetailURL);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt( $ch, CURLOPT_USERAGENT, "WadeWilson" );
			$jsondata = curl_exec($ch);
			$detailResults = json_decode($jsondata, true);
			curl_close($ch);
			$this->storyName = $detailResults['results']['name'];
			$this->releaseDate = $detailResults['results']['cover_date'];
			$this->synopsis = $detailResults['results']['description'];
			$this->seriesName = $detailResults['results']['volume']['name'];
			$issueCreditsArray = $detailResults['results']['person_credits'];
			$this->issueCreditsArray = $issueCreditsArray;

			// Initializing the credit strings
			$this->pencils = '';
			$this->script = '';
			$this->colors = '';
			$this->inks = '';
			$this->coverArtist = '';
			$this->editing = '';
			$this->letters = '';
			$this->creatorsList = '';

			if (count($issueCreditsArray) > 0) {
				foreach($issueCreditsArray as $item) {
					if (strpos($item['role'], 'artist') !== FALSE || strpos($item['role'], 'penciler') !== FALSE) {
						$this->role = 'pencils';
						$this->pencils .= '<span>' . $item['name'] . '</span>';
						$this->creatorsList .= $item['name'] . '_' . $this->role . ';';
					}
					if (strpos($item['role'], 'writer') !== FALSE) {
						$this->role = 'script';
						$this->script .= '<span>' . $item['name'] . '</span>';
						$this->creatorsList .= $item['name'] . '_' . $this->role . ';';
					}
					if (strpos($item['role'], 'inker') !== FALSE) {
						$this->role = 'inker';
						$this->inks .= '<span>' . $item['name'] . '</span>';
						$this->creatorsList .= $item['name'] . '_' . $this->role . ';';
					}
					if (strpos($item['role'], 'colorist') !== FALSE) {
						$this->role = 'colors';
						$this->colors .= '<span>' . $item['name'] . '</span>';
						$this->creatorsList .= $item['name'] . '_' . $this->role . ';';
					}
					if (strpos($item['role'], 'editor') !== FALSE) {
						$this->role = 'editing';
						$this->editing .= '<span>' . $item['name'] . '</span>';
						$this->creatorsList .= $item['name'] . '_' . $this->role . ';';
					}
					if (strpos($item['role'], 'cover') !== FALSE) {
						$this->role = 'coverArtist';
						$this->coverArtist .= '<span>' . $item['name'] . '</span>';
						$this->creatorsList .= $item['name'] . '_' . $this->role . ';';
					}
					if (strpos($item['role'], 'letterer') !== FALSE) {
						$this->role = 'letters';
						$this->letters .= '<span>' . $item['name'] . '</span>';
						$this->creatorsList .= $item['name'] . '_' . $this->role . ';';
					}
				}
				$this->creatorsList = preg_replace('/(;(?!.*;))/', '', $this->creatorsList);
			}

			if ($detailResults['results']['image']['medium_url']) {
				$subject = $detailResults['results']['image']['medium_url'];
				$pattern = "/(?<=jpg|png|jpeg).*/";
				$replacement = "";
				$this->coverURL = preg_replace($pattern, $replacement, $subject);
				$imageDir = 'images/';
				$seriesPlain = preg_replace('/[^a-z0-9]+/i', '_', $this->seriesName) . '-v' . $series_vol;
				$this->coverFile = $imageDir . $seriesPlain . '/' . $seriesPlain . '_' . $issue_number . '-medium.jpg';
			} else {
				$this->coverFile = 'assets/nocover.jpg';
				$this->coverURL = 'assets/nocover.jpg';
				$this->noCover = true;
			}
			if ($detailResults['results']['image']['thumb_url']) {
				$subject = $detailResults['results']['image']['thumb_url'];
				$pattern = "/(?<=jpg|png|jpeg).*/";
				$replacement = "";
				$this->coverThumbURL = preg_replace($pattern, $replacement, $subject);
				$imageDir = 'images/';
				$seriesPlain = preg_replace('/[^a-z0-9]+/i', '_', $this->seriesName) . '-v' . $series_vol;
				$this->coverThumbFile = $imageDir . $seriesPlain . '/' . $seriesPlain . '_' . $issue_number . '-thumb.jpg';
			} else {
				$this->coverThumbFile = 'assets/nocover.jpg';
				$this->coverThumbURL = 'assets/nocover.jpg';
				$this->noThumbCover = true;
			}
			if ($detailResults['results']['image']['small_url']) {
				$subject = $detailResults['results']['image']['small_url'];
				$pattern = "/(?<=jpg|png|jpeg).*/";
				$replacement = "";
				$this->coverSmallURL = preg_replace($pattern, $replacement, $subject);
				$imageDir = 'images/';
				$seriesPlain = preg_replace('/[^a-z0-9]+/i', '_', $this->seriesName) . '-v' . $series_vol;
				$this->coverSmallFile = $imageDir . $seriesPlain . '/' . $seriesPlain . '_' . $issue_number . '-small.jpg';
			} else {
				$this->coverSmallFile = 'assets/nocover.jpg';
				$this->coverSmallURL = 'assets/nocover.jpg';
				$this->noSmallCover = true;
			}
		} else {
			$this->searchResults = false;
		}
	}

	public function downloadFile($url, $path) {
		$fp = fopen($path, 'w+');

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FILE, $fp); // output to file
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
    	curl_setopt($ch, CURLOPT_USERAGENT, 'WadeWilson');
    	// curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
    	curl_exec($ch);

    	curl_close($ch);
    	fclose($fp);

    	if (file_exists($path)) {
    		$this->imageDownloaded = true;
    	} else {
    		$this->imageDownloaded = false;
    	}
	}
}