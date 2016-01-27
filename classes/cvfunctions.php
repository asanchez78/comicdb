<?php
/**
 *
 * @author asanchez
 * <pre>
 *Functions:
 *seriesLookup
 *</pre>
 */
class cvQuery {
	public $volumeID;
	public $resultsList;

	public function seriesLookup ($seriesName) {
		$apiURL = "http://www.comicvine.com/api/volumes/?api_key=8c685f7695c1dda5a4ecdf35c54402438a77b691&format=json&filter=name:$seriesName";
		$jsondata = file_get_contents($apiURL);
		$results = json_decode($jsondata, true);
		$this->resultNum = 1;
			foreach($results['results'] as $result) {
				
				$this->seriesDescription = $result['description'];
				$this->seriesName = $result['name'];
				$this->seriesID = $result['id'];
				$this->seriesStartYear = $result['start_year'];
				//$this->resultsList .= '<div class="issue-search-result col-xs-12 col-sm-6 col-md-4"><input name="wiki_id" id="wiki_id-' . $this->seriesID . '" value="' . $this->seriesID . '" type="radio" /> <label for="wiki_id-' . $this->seriesID . '">' . $this->seriesName . '</label></div>';
				$this->resultsList .= $this->resultNum . ' <a href="#">' . $this->seriesName . ' ' . $this->seriesStartYear . '</a> ' . $this->seriesID . '<br>' . $this->seriesDescription . '<br>';
				++$this->resultNum;
			}
	}
}
$series_name = "cable";
$seriesSearch = new cvQuery();
		$seriesSearch->seriesLookup("$series_name");

		echo $seriesSearch->resultsList;
