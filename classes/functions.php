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
  public $inks;
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
        $this->comic_id = $row ['comic_id'];
        $this->issue_number = $row ['issue_number'];
        $this->plot = $row ['plot'];
        $this->release_date = $row ['release_date'];
        $this->story_name = $row ['story_name'];
        $this->cover_image = $row ['cover_image'];
        $this->series_name = $row ['series_name'];
        $this->series_id = $row ['series_id'];
        $this->series_vol = $row ['series_vol'];

        // Custom User fields
        $this->originalPurchase = $row ['originalPurchase'];
        $this->issue_quantity = $row ['quantity'];
        $this->custPlot = $row ['custPlot'];
        $this->var_cover_image = $row ['variantCover'];
        $this->custStoryName = $row ['custStoryName'];
        $this->issueCondition = $row ['issueCondition'];
      }
    }
    $sql = "SELECT creators.name, creators.job
      FROM creators_link
      LEFT JOIN creators
      ON creators.creator_id=creators_link.creator_id
      WHERE comic_id=$this->comic_id";
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
          $this->inks .= '<span>' . $creatorName . '</span>';
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
        $this->story_name = $row ['story_name'];
        $this->publisherShort = $row ['publisherShort'];
        $this->publisherName = $row ['publisherName'];
        if ($row['release_date']) {
          $this->release_date = DateTime::createFromFormat('Y-m-d', $row ['release_date'])->format('M Y');
        } else {
          $this->release_date = "";
        }
        $this->coverMed = $row ['cover_image'];
        $this->coverSmall = str_replace('-medium.', '-small.', $this->coverMed);
        $this->coverThumb = str_replace('-medium.', '-thumb.', $this->coverMed);
        $this->issue_list .= '<li class="col-xs-6 col-sm-4 col-md-3 col-lg-2" id="issue-' . $this->issue_number . '"><div class="series-list-row">';
        $this->issue_list .= '<a href="comic.php?comic_id=' . $this->comic_id . '" class="issue-info"><div class="series-list-row">';
          $this->issue_list .= '<div class="comic-image"><img src="' . $this->coverSmall . '" alt="" class="img-responsive" /></div>';
          $this->issue_list .= '<div class="story-name"><h3>' . $this->story_name . '</h3></div>';
        $this->issue_list .= '</div></a>';
        $this->issue_list .= '<div class="issue-number text-uppercase">#' . $this->issue_number . '</div>';
        $this->issue_list .= '<div class="release-date text-uppercase">' . $this->release_date . '</div>';
        $this->issue_list .= '</div></li>';
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
        $this->series_issue_count = '<span class="text-danger">' . $this->series_issue_count . '</span> Issue';
      } else {
        $this->series_issue_count = '<span class="text-danger">' . $this->series_issue_count . '</span> Issues';
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
        $this->latestCoverMed = implode(mysqli_fetch_row($this->db_connection->query ( $sql )));
        $this->latestCoverSmall = str_replace('-medium.', '-small.', $this->latestCoverMed);
        $this->latestCoverThumb = str_replace('-medium.', '-thumb.', $this->latestCoverMed);
      } else {
        $this->latestCoverSmall = "assets/nocover.jpg";
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
    $connection = mysqli_connect ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if (! $connection) {
      die ( "Connection failed: " . mysqli_connect_error () );
    }
    $creatorsList = explode(";", $creatorsList);
    foreach ($creatorsList as $creator) {
      $creatorsArray  = explode("_", $creator);
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
          $sqlMessage = '<strong class="text-success">Add Creator Link Success. :</strong><br /><code>' . $sql . '</code><br /><br />';
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
          $sqlMessage = '<strong class="text-success">Add Creator Link Success. :</strong><br /><code>' . $sql . '</code><br /><br />';
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