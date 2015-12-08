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

	/**
	 * uses an API call to marvel.wikia.com to return search results
	 * @param string $query
	 */
	public function wikiSearch($query, $series_name, $issue_number, $limit) {
		$comic = str_replace(' ', '+', $query);
		$api_url = "http://marvel.wikia.com/api/v1/Search/List?query=$comic&limit=$limit&minArticleQuality=10&batch=1&namespaces=0%2C14";
		$jsondata = file_get_contents($api_url);
		$results = json_decode($jsondata, true);

		if ($results['items']){
			foreach($results['items'] as $result) {
				$this->wikiSearchResultID = $result['id'];
				$this->wikiSearchResultTitle = $result['title'];
				$this->resultsList .= "<a href=\"admin/new.php?wiki_id=" . $this->wikiSearchResultID . "&series_name=$series_name&issue_number=$issue_number\">" . $this->wikiSearchResultTitle . "</a>";
				$this->resultsList .= "<br>\n";

			}
		} else {
			echo "No results. Perhaps you mispelled something?<br>";
			echo "You searched for " . "\"" . $query . "\"";
		}
	}
	/**
	 * grabs the cover image from marvel.wikia.com using the comic's wiki_id
	 * @param int $wiki_id
	 */
	public function comicCover($wiki_id) {
		$cover_api = "http://marvel.wikia.com/api/v1/Articles/Details?ids=$wiki_id";
		$cover_jsondata = file_get_contents($cover_api);
		$details_results = json_decode($cover_jsondata, true);
		$subject = $details_results['items'][$wiki_id]['thumbnail'];
		$pattern = "/(?<=jpg|png|jpeg).*/";
		$replacement = "";
		$wiki_id = $details_results['items'][$wiki_id]['id'];
		$this->coverURL = preg_replace($pattern, $replacement, $subject);
		$fileparts = explode("/", $this->coverURL);
		$this->coverFile = $fileparts[7];
	}
	/**
	 * grabs comic details from marvel.wikia.com using the comic's wiki_id
	 * @param int $wiki_id
	 */
	public function comicDetails($wiki_id) {
		$details_api = "http://marvel.wikia.com/api/v1/Articles/AsSimpleJson?id=$wiki_id";
		$issue_jsondata = file_get_contents($details_api);
		$issue_results = json_decode($issue_jsondata, true);
		$paragraphs = $issue_results['sections'][2]['content'];
		$paragraphNum = 0;
		$this->storyName = trim(str_replace('Appearing in ', "", $issue_results['sections'][1]['title']), '"');
		$this->wikiTitle = $issue_results['sections'][0]['title'];
		if (array_key_exists('text', $issue_results['sections'][2]['content'][0])) {
			foreach ($paragraphs as $paragraph) {
				$this->synopsis .= $issue_results['sections'][2]['content'][$paragraphNum]['text'] . "\r\n\r\n";
				++$paragraphNum;
			}
		} else {
			$this->synopsis = "";
		}
	}
}