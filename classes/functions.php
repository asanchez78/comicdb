<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * downloads an image from $url and saves it to $path
 *
 * @author Anthony
 */
class grab_cover {
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
}
/**
 * functions relating to searching comic information
 * <pre>
 * comicrackLookup
 * issueLookup
 * artistLookup
 * writerLookup
 * issuesList
 * seriesList
 * </pre>
 * @author asanchez
 *
 */
class comicSearch {
	private $db_connection;
	public $cover_image;
	public $plot;
	public $issue_number;
	/**
	 *
	 * @var string Story name of comic.
	 */
	public $story_name;
	public $release_date;
	public $artist;
	public $writer;
	public $issue_list;
	public $wiki_id;
	public $comic_id;
	public $series_name;
	public $series_id;
	public $original_purchase;
	/**
	 * List of comic series in database
	 *
	 * @var ArrayObject
	 */
	public $series_list_result;

	/**
	 * Looks up a comic from the ComicRack
	 *
	 * @param string $series_name
	 * @param int $issue_no
	 */
	public function comicrackLookup($series_name, $issue_no) {
		$this->db_connection = new mysqli ( 'chronos', 'comicrack', 'comicrack', 'comicrack' );
		if ($this->db_connection->connect_errno) {
			die ( "Connection failed: " );
		}

		$sql = "SELECT id, data FROM comics WHERE data LIKE '%<Series>%$series_name%<Number>$issue_no</Number>%'";
		$result = $this->db_connection->query ( $sql );
		if ($result->num_rows >= 1) {
			$xml_result = $result->simplexml_load_string ();
			print_r ( $xml_result );
		}
	}
	/**
	 * Looks up a single comic issue using comic_id
	 *
	 * @param int $comic_id
	 */
	public function issueLookup($comic_id) {
		$this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
		if ($this->db_connection->connect_errno) {
			die ( "Connection failed: " );
		}

		$sql = "SELECT comics.wiki_id, comics.series_id, comics.comic_id, series.series_name, comics.issue_number, comics.release_date, comics.story_name, comics.cover_image, comics.plot, comics.original_purchase FROM comics LEFT JOIN series ON comics.series_id=series.series_id WHERE comics.comic_id = $comic_id";
		$result = $this->db_connection->query ( $sql );
		if ($result->num_rows > 0) {
			while ( $row = $result->fetch_assoc () ) {
				$comic_id = $row ['comic_id'];
				$issue_number = $row ['issue_number'];
				$plot = $row ['plot'];
				$release_date = $row ['release_date'];
				$story_name = $row ['story_name'];
				$cover_image = $row ['cover_image'];
				$wiki_id = $row['wiki_id'];
				$series_name = $row ['series_name'];
				$series_id = $row['series_id'];
				$original_purchase = $row['original_purchase'];
			}
		}
		$this->cover_image = $cover_image;
		$this->plot = $plot;
		$this->issue_number = $issue_number;
		$this->story_name = $story_name;
		$this->release_date = $release_date;
		$this->wiki_id = $wiki_id;
		$this->comic_id = $comic_id;
		$this->series_name = $series_name;
		$this->original_purchase = $original_purchase;
		$this->series_id = $series_id;
	}
	/**
	 * Looks up the artist of a given comic using comic_id
	 *
	 * @param int $comic_id
	 */
	public function artistLookup($comic_id) {
		$this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
		if ($this->db_connection->connect_errno) {
			die ( "Connection failed: " );
		}

		$sql = "SELECT artist_name FROM artist_link LEFT JOIN artists ON (artists.artist_id = artist_link.artist_id) WHERE artist_link.comic_id = $comic_id";
		$result = $this->db_connection->query ( $sql );
		if ($result->num_rows > 0) {
			while ( $row = $result->fetch_assoc () ) {
				$this->artist = $row ['artist_name'];
			}
		}

		if ($result->num_rows == 0) {
			$this->artist = "";
		}
	}
	/**
	 * Looks up the writer of a given comic using comic_id
	 *
	 * @param int $comic_id
	 */
	public function writerLookup($comic_id) {
		$this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
		if ($this->db_connection->connect_errno) {
			die ( "Connection failed: " );
		}

		$sql = "SELECT writer_name FROM writer_link LEFT JOIN writers ON (writers.writer_id = writer_link.writer_id) WHERE writer_link.comic_id = $comic_id";
		$result = $this->db_connection->query ( $sql );
		if ($result->num_rows > 0) {
			while ( $row = $result->fetch_assoc () ) {
				$this->writer = $row ['writer_name'];
			}
		}

		if ($result->num_rows == 0) {
			$this->writer = "";
		}
	}
	/**
	 * Lists issues of a given series
	 *
	 * @param int $series_id
	 */
	public function issuesList($series_id) {
		$this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
		if ($this->db_connection->connect_errno) {
			die ( "Connect failed:" );
		}
		$sql = "SELECT comics.comic_id, comics.issue_number, comics.plot, comics.release_date, comics.story_name, comics.wiki_id
			FROM comics
			LEFT JOIN series ON comics.series_id = series.series_id
			WHERE comics.series_id = '$series_id'
			ORDER BY comics.issue_number";
		$result = $this->db_connection->query ( $sql );
		if ($result->num_rows > 0) {
			while ( $row = $result->fetch_assoc () ) {
				$this->comic_id = $row ['comic_id'];
				$this->wiki_id = $row ['wiki_id'];
				$this->issue_number = $row ['issue_number'];
				$this->story_name = $row ['story_name'];
				$this->issue_list .= "<tr>\n";
				$this->issue_list .= "<td class=\"mdl-data-table__cell--non-numeric\">" . "<a href=\"comic.php?comic_id=$this->comic_id\">" . $this->issue_number . "</a>" . "</td>";
				$this->issue_list .= "<td class=\"mdl-data-table__cell--non-numeric\">" . "<a href=\"comic.php?comic_id=$this->comic_id\">" . $this->story_name . "</a>" . "</td>";
				$this->issue_list .= "</tr>";
			}
		} else {
			echo "0 results";
		}
	}
	/**
	 * Returns a list of comic series
	 */
	public function seriesList() {
		$this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
		if ($this->db_connection->connect_errno) {
			die ( "Connection failed:" );
		}

		$sql = "SELECT * FROM series";
		$this->series_list_result = $this->db_connection->query ( $sql );
	}
}