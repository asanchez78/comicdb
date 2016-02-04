<?php

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
 * 
 * </pre>
 * @author asanchez
 * @author sloyless
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
  public $comic_id;
  public $series_name;
  public $series_id;
  public $originalPurchase;
  /**
   * List of comic series in database
   *
   * @var ArrayObject
   */
  public $series_list_result;
  public $volume_number;
  public $publisherShort;
  public $pencils;
  public $script;
  public $colors;
  public $coverArtist;
  public $editing;
  public $letters;
  public $creatorsList;

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

    $sql = "SELECT *
            FROM comics
            LEFT JOIN series
            ON comics.series_id=series.series_id
            LEFT JOIN users_comics
            ON comics.comic_id=users_comics.comic_id
            WHERE comics.comic_id = $comic_id";
    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      while ( $row = $result->fetch_assoc () ) {
        $comic_id = $row ['comic_id'];
        $issue_number = $row ['issue_number'];
        $plot = $row ['plot'];
        $release_date = $row ['release_date'];
        $story_name = $row ['story_name'];
        $cover_image = $row ['cover_image'];
        $series_name = $row ['series_name'];
        $series_id = $row['series_id'];
        $originalPurchase = $row['originalPurchase'];
        $series_vol = $row['series_vol'];
      }
    }
    $sql = "SELECT creators.name, creators.job
      FROM creators_link
      LEFT JOIN creators
      ON creators.creator_id=creators_link.creator_id
      WHERE comic_id=$comic_id";
    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      while ( $row = $result->fetch_assoc () ) {
        $creatorName = $row ['name'];
        $creatorJob = $row ['job'];
        if ($creatorJob == 'pencils') {
          $this->pencils .= '<span>' . $creatorName . '</span>';
        }
        if ($creatorJob == 'script') {
          $this->script .= '<span>' . $creatorName . '</span>';
        }
        if ($creatorJob == 'inker') {
          $this->colors .= '<span>' . $creatorName . '</span>';
        }
        if ($creatorJob == 'colors') {
          $this->colors .= '<span>' . $creatorName . '</span>';
        }
        if ($creatorJob == 'editing') {
          $this->editing .= '<span>' . $creatorName . '</span>';
        }
        if ($creatorJob == 'coverArtist') {
          $this->coverArtist .= '<span>' . $creatorName . '</span>';
        }
        if ($creatorJob == 'letters') {
          $this->letters .= '<span>' . $creatorName . '</span>';
        }
      }
    }
    $this->cover_image = $cover_image;
    $this->plot = $plot;
    $this->issue_number = $issue_number;
    $this->story_name = $story_name;
    $this->release_date = $release_date;
    $this->comic_id = $comic_id;
    $this->series_name = $series_name;
    $this->originalPurchase = $originalPurchase;
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
  public function issuesList($series_id, $ownerID) {
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
        $this->issue_list .= '<div class="issue-meta issue-number text-uppercase">#' . $this->issue_number . '</div>';
        $this->issue_list .= '<div class="issue-meta release-date text-uppercase">' . $this->release_date . '</div>';
        $this->issue_list .= '</li>';
      }
    }
  }
  /**
   * Returns a list of comic series
   */
  public function seriesList($listAll, $publisherSearchId, $ownerID) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }
    // List all books/publishers
    if ($listAll == 1) {
      $sql = "SELECT * , CASE WHEN series_name
        LIKE 'The %' THEN trim(substr(series_name from 4)) else series_name end as series_name2
        FROM series ORDER BY series_name2 ASC, series_vol ASC";
      $this->series_list_result = $this->db_connection->query ( $sql );
    // List all owned books by publisher
    } elseif ($listAll == 2) {
      $sql = "SELECT DISTINCT comics.series_id
          FROM comics
          LEFT JOIN users_comics
          ON comics.comic_id=users_comics.comic_id
          LEFT JOIN series
          ON comics.series_id=series.series_id
          WHERE users_comics.user_id=$ownerID AND series.publisherID=$publisherSearchId
          ORDER BY series_id;";
      $result = $this->db_connection->query ( $sql );
      if (mysqli_fetch_row($this->db_connection->query ( $sql )) > 0) {
        $list = NULL;
        while ($row = $result->fetch_assoc ()) {
          $list .= "series_id=" . $row ['series_id'] . " or ";
        }
        $idList = preg_replace('/(or(?!.*or))/', '', $list);
        $sql = "SELECT *, CASE WHEN series_name
          LIKE 'The %' THEN trim(substr(series_name from 4)) else series_name end as series_name2
          FROM series WHERE $idList
          ORDER BY series_name2 ASC, series_vol ASC";
        $this->series_list_result = $this->db_connection->query ( $sql ); 
      }
    } else {
    // List all owned books in a series
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
        $sql = "SELECT *, CASE WHEN series_name
          LIKE 'The %' THEN trim(substr(series_name from 4)) else series_name end as series_name2
          FROM series WHERE $idList
          ORDER BY series_name2 ASC, series_vol ASC";
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

  public function seriesInfo($series_id, $ownerID) {
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
        $this->publisherID = $row ['publisherID'];
        $this->publisherName = $row ['publisherName'];
        $this->publisherShort = $row ['publisherShort'];
        $this->cvVolumeID = $row ['cvVolumeID'];
      }
    }

    // Gets custom information from the user table
    if (isset($ownerID)) {
      $sql = "SELECT *
        FROM comics
        LEFT JOIN users_comics
        ON comics.comic_id=users_comics.comic_id
        WHERE comics.series_id=$series_id AND users_comics.user_id=$ownerID";
      
      // Issue count
      $this->series_issue_count = mysqli_num_rows($this->db_connection->query ( $sql ));
      if ($this->series_issue_count == 1) {
        $this->series_issue_count = $this->series_issue_count . ' Issue';
      } else {
        $this->series_issue_count = $this->series_issue_count . ' Issues';
      }

      // Custom Plot, Story Name, and Variant cover
      $result = $this->db_connection->query ( $sql );
      if ($result->num_rows > 0) {
        while ( $row = $result->fetch_assoc () ) {
          $this->custPlot = $row ['custPlot'];
          $this->custStoryName = $row ['custStoryName'];
          $this->variantCover = $row ['variantCover'];
        }
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

  public function publisherInfo($publisher_id) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }
    $sql = "SELECT *
          FROM publishers
          WHERE publisherID=$publisher_id";

    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      while ( $row = $result->fetch_assoc () ) {
        $this->publisherName = $row ['publisherName'];
        $this->publisherShort = $row ['publisherShort'];
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

  // Add creators to creators table
  public function insertCreators($comic_id, $creatorsList) {
    $connection = mysqli_connect ( 'localhost', 'comicdb', 'comicdb', 'comicdb' );
    if (! $connection) {
      die ( "Connection failed: " . mysqli_connect_error () );
    }
    $creatorsList = explode(";", $creatorsList);
    foreach ($creatorsList as $creator) {
      $creatorsArray  = explode(",", $creator);
      $person = $creatorsArray[0];
      $job = $creatorsArray[1];
      $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
      if ($this->db_connection->connect_errno) {
        die ( "Connection failed:" );
      }
      $sql = "SELECT *
        FROM creators
        WHERE name='$person' AND job='$job'";
      $result = $this->db_connection->query ( $sql );
      if ($result->num_rows > 0) {
        $this->creatorExists = 1;
        while ($row  = $result->fetch_assoc () ) {
          $this->creator_id = $row['creator_id'];
        }
      } else {
        $this->creatorExists = 0;
      }
      if ($this->creatorExists == 1) {
        // Checks if the creators are already in the database. If so, just create the link to the new comic.
        $creator_id = $this->creator_id;
        $sql = "INSERT INTO creators_link (comic_id, creator_id) VALUES ('$comic_id', '$creator_id')";
        if (mysqli_query ( $connection, $sql )) {
          //messages here?
          $success = true;
        } else {
          $sqlMessage = '<strong class="text-warning">Error:</strong> ' . $sql . '<br>' . mysqli_error ( $connection );
          $messageNum = 51;
        }
      } else {
        // Creator is not in the database. Add them and associate them with their issue.
        $sql_creator = "INSERT INTO creators (name, job) VALUES ('$person', '$job')";
        if (mysqli_query($connection, $sql_creator)) {
          $creator_id = mysqli_insert_id($connection);
          } else {
          $sqlMessage = '<strong class="text-warning">Error</strong>: ' . $sql_creator . '<br>' . mysqli_error ( $connection );
        }
        $sql = "INSERT INTO creators_link (comic_id, creator_id) VALUES ('$comic_id', '$creator_id')";
        if (mysqli_query ( $connection, $sql )) {
          //messages here?
          $success = true;
        } else {
          $sqlMessage = '<strong class="text-warning">Error:</strong> ' . $sql . '<br>' . mysqli_error ( $connection );
          $messageNum = 51;
        }
      }
    }
  }

  public function collectionCount($user_id) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }

    $sql = "SELECT user_id, comic_id, quantity
        FROM users_comics
        WHERE user_id = $user_id";
    $this->total_issue_count = mysqli_num_rows($this->db_connection->query ( $sql ));
  }
}