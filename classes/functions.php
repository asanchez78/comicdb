<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * functions relating to searching comic information
 * <pre>
 * issueLookup
 * artistLookup
 * writerLookup
 * issuesList
 * seriesList
 * seriesFind
 * seriesInfo
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
  public $volume_number;
  public $publisherShort;

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

    $sql = "SELECT * FROM comics LEFT JOIN series ON comics.series_id=series.series_id WHERE comics.comic_id = $comic_id";
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
        $series_vol = $row['series_vol'];
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
    $this->series_vol = $series_vol;
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
    $ownerID = $_SESSION ['user_id'];
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connect failed:" );
    }
    $sql = "SELECT *
        FROM comics
        LEFT JOIN users_comics
        ON comics.comic_id=users_comics.comic_id
        LEFT JOIN series
        ON comics.series_id=series.series_id
        LEFT join publishers
        on series.publisherID=publishers.publisherID
        WHERE comics.series_id=$series_id AND users_comics.user_id=$ownerID ORDER BY comics.issue_number
";
    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      while ( $row = $result->fetch_assoc () ) {
        $this->comic_id = $row ['comic_id'];
        $this->wiki_id = $row ['wiki_id'];
        $this->issue_number = $row ['issue_number'];
        $this->publisherShort = $row ['publisherShort'];
        $this->publisherName = $row ['publisherName'];
        if ($row['release_date']) {
          $this->release_date = DateTime::createFromFormat('Y-m-d', $row ['release_date'])->format('M Y');
        } else {
          $this->release_date = "";
        }
        $this->cover_image = $row ['cover_image'];
        $this->issue_list .= '<li class="col-xs-6 col-sm-4 col-md-3 col-lg-2" id="issue-' . $this->issue_number . '">';
        $this->issue_list .= '<a href="comic.php?comic_id=' . $this->comic_id . '" class="series-info"><div class="comic-image"><img src="' . $this->cover_image . '" alt="" class="img-responsive" /></div></a>';
        $this->issue_list .= '<div class="issue-meta issue-number">Issue #' . $this->issue_number . '</div>';
        $this->issue_list .= '<div class="issue-meta release-date">' . $this->release_date . '</div>';
        $this->issue_list .= '</li>';
      }
    } else {
      echo "0 results";
    }
  }
  /**
   * Returns a list of comic series
   */
  public function seriesList($listAllSeries) {
    $ownerID = $_SESSION ['user_id'];
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }
    if ($listAllSeries == 1) {
      $sql = "SELECT * FROM series ORDER BY series_name ASC";
      $this->series_list_result = $this->db_connection->query ( $sql );
    } else {
      $sql = "SELECT DISTINCT comics.series_id
          FROM comics
          JOIN users_comics
          ON comics.comic_id=users_comics.comic_id
          WHERE users_comics.user_id=$ownerID ORDER BY series_id;";
      $result = $this->db_connection->query ( $sql );
      if (mysqli_fetch_row($this->db_connection->query ( $sql )) > 0) {
        $list = NULL;
        while ($row = $result->fetch_assoc ()) {
          $list .= "series_id=" . $row ['series_id'] . " or ";
        }
        $idList = preg_replace('/(or(?!.*or))/', '', $list);
        $sql = "SELECT * FROM series where $idList ORDER BY series_name ASC";
        $this->series_list_result = $this->db_connection->query ( $sql ); 
      }
    }
  }

  public function publisherList() {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }
    $sql = "SELECT * FROM publishers ORDER BY publisherName ASC";
    $this->publisher_list_result = $this->db_connection->query ( $sql );
  }

  public function seriesFind($series_name) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }

    $sql = "SELECT * FROM series where series_name = '$series_name'";
    $this->series = $this->db_connection->query ( $sql );
  }

  public function seriesInfo($series_id) {
    if (isset($_SESSION['user_id'])) {
      $ownerID = $_SESSION ['user_id'];
    }
    
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }

    $sql = "SELECT *
        FROM series
        LEFT JOIN publishers
        ON publishers.publisherID=series.publisherID
        WHERE series_id = $series_id";
    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      while ( $row = $result->fetch_assoc () ) {
        $this->series_name = $row ['series_name'];
        $this->series_vol = $row ['series_vol'];
        $this->publisherName = $row ['publisherName'];
        $this->publisherShort = $row ['publisherShort'];
      }
    } else {
      echo "0 results";
    }

    // Gets the number of issues in each series and outputs a text string
    if (isset($ownerID)) {
      $sql = "SELECT *
        FROM comics
        LEFT JOIN users_comics
        ON comics.comic_id=users_comics.comic_id
        WHERE comics.series_id=$series_id AND users_comics.user_id=$ownerID";
      $this->series_issue_count = mysqli_num_rows($this->db_connection->query ( $sql ));
      if ($this->series_issue_count == 1) {
        $this->series_issue_count = $this->series_issue_count . ' Issue';
      } else {
        $this->series_issue_count = $this->series_issue_count . ' Issues';
      }
    }


    // Gets the latest comic book cover image for the series
    if (isset($ownerID)) {
      $sql = "SELECT cover_image
          FROM comics
          JOIN users_comics
          ON comics.comic_id=users_comics.comic_id
          WHERE users_comics.user_id=$ownerID AND series_id=$series_id
          ORDER BY issue_number DESC LIMIT 1";
      if (mysqli_fetch_row($this->db_connection->query ( $sql )) > 0) {
        $this->series_latest_cover = implode(mysqli_fetch_row($this->db_connection->query ( $sql )));
      } else {
        $this->series_latest_cover = "assets/nocover.jpg";
      }
    }
  }

  public function issueCheck($series_id, $issue_number) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }
    $sql = "SELECT comic_id, series_id, issue_number
        FROM comics
        WHERE series_id=$series_id AND issue_number=$issue_number";
    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      $this->issueExists = 1;
      while ($row  = $result->fetch_assoc () ) {
        $this->comic_id = $row['comic_id'];
      }
    } else {
      $this->issueExists = 0;
    }
  }
}