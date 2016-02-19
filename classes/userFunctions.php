  <?php

/**
 * functions relating to user information
 * <pre>
 * userMeta
 * userLookup
 * userFollows
 * userCovers
 * userFollowedBy
 * </pre>
 * @author asanchez
 * @author sloyless
 */
class userInfo {
  // Grabs user meta information for user on profile pages.
  public function userMeta($user_id) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }

    $sql = "SELECT *
        FROM users_meta
        WHERE user_id = $user_id";

    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      // Meta Key and Meta Value can be set as anything. We need to set an array to store all the values for the user.
      $this->meta_key = array();
      $this->meta_val = array();
      while ( $row = $result->fetch_assoc () ) {
        array_push($this->meta_key, $row ['meta_key']);
        array_push($this->meta_val, $row ['meta_value']);
      }
      // offset 1 for 0 array position
      $array_size = sizeof($this->meta_key) -1;
      // Loops through the meta_key array for values, then gets their associated key and assigns it to a global var.
      for ($i = 0; $i <= $array_size; $i++) {
        if ($this->meta_key[$i] === 'first_name') {
           $this->user_first_name = $this->meta_val[$i];
        }
        if ($this->meta_key[$i] === 'last_name') {
           $this->user_last_name = $this->meta_val[$i];
        }
        if ($this->meta_key[$i] === 'location') {
           $this->user_location = $this->meta_val[$i];
        }
        if ($this->meta_key[$i] === 'avatar') {
           $this->user_avatar = $this->meta_val[$i];
        }
        if ($this->meta_key[$i] === 'user_follows') {
           $this->user_follows = $this->meta_val[$i];
        }
        if ($this->meta_key[$i] === 'facebook_url') {
           $this->facebook_url = $this->meta_val[$i];
        }
        if ($this->meta_key[$i] === 'twitter_url') {
           $this->twitter_url = $this->meta_val[$i];
        }
        if ($this->meta_key[$i] === 'instagram_url') {
           $this->instagram_url = $this->meta_val[$i];
        }
      }
    }
  }

  // Looks up User information by profile name.
  public function userLookup($profile_name) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }
    $sql = "SELECT user_id, user_email
        FROM users
        WHERE user_name = '$profile_name'";
    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      while ( $row = $result->fetch_assoc () ) {
        // Grab userID and userEmail from database, convert user_email to hash string for security and to pass to Gravatar.
        $this->browse_user_id = $row ['user_id'];
        $this->browse_user_email_hash = md5(strtolower(trim( $row ['user_email'] )));
        $validUser=1;
      }
    } else {
      $validUser=0;
    }
  }

  // Grab the followers the user follows
  public function userFollows($profile_id) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }
    $sql = "SELECT user_name, user_email
        FROM users
        WHERE user_id = '$profile_id'";
    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      while ( $row = $result->fetch_assoc () ) {
        // Grab userID and userEmail from database, convert user_email to hash string for security and to pass to Gravatar.
        $this->follow_username = $row ['user_name'];
        $this->follow_email_hash = md5( strtolower( trim( $row ['user_email'] ) ) );
      }
    }
  }

  // Builds the 'Comic Wall' on the users profile page header.
  public function userCovers($user_id) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connect failed:" );
    }
    $sql = "SELECT cover_image
        FROM comics
        LEFT JOIN users_comics
        ON comics.comic_id=users_comics.comic_id
        WHERE users_comics.user_id=$user_id 
        ORDER BY RAND()
        LIMIT 36";
    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      $this->cover_list = '';
      while ( $row = $result->fetch_assoc () ) {
        $this->coverMed = $row ['cover_image'];
        $this->coverThumb = str_replace('-medium.', '-thumb.', $this->coverMed);
        $this->cover_list .= '<div class="col-xs-2 col-md-1 profile-bg-image"><img src="' . $this->coverThumb . '" alt="" class="img-responsive" /></div>';
      }
    }
  }

  // Counts the number of users that follow a user
  public function userFollowedBy($user_id) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }
    $sql = "SELECT user_id, meta_key, meta_value
        FROM users_meta
        WHERE meta_key = 'user_follows' AND user_id != '$user_id'";
    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      // Initialize our final array
      $this->followerList = array();

      while ( $row = $result->fetch_assoc () ) {
        // Grab the user_follows value from all users except logged in user
        $followerField = $row ['meta_value'];
        // Split string into array
        $followSplitList = preg_split('/\D/', $followerField, NULL, PREG_SPLIT_NO_EMPTY);
        // Count the # ids
        $preCount = count($followSplitList);
        for ($i = 0; $i <= $preCount; $i++) {
          if(isset($followSplitList[$i]) && $followSplitList[$i] == $user_id) {
            array_push($this->followerList, $followSplitList[$i]);
          }
        }
      }
      $this->followerCount = count($this->followerList);
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
      $numResults = $result->num_rows;
      if (mysqli_fetch_row($this->db_connection->query ( $sql )) > 0) {
        $list = NULL;
        while ($row = $result->fetch_assoc ()) {
          $list .= "series_id=" . $row ['series_id'] . " or ";
        }
        $idList = preg_replace('/(or(?!.*or))/', '', $list);

        // Start pagination
        $this->pageNum = filter_input(INPUT_GET, 'page');
        $profile_name = filter_input(INPUT_GET, 'user');
        if (!isset($this->pageNum)) {
          $this->pageNum = 1;
        }
        $this->hasPagination = false;
        // Prepare our string array
        $this->pagination = '';
        $sql = "SELECT *, CASE WHEN series_name
          LIKE 'The %' THEN trim(substr(series_name from 4)) else series_name end as series_name2
          FROM series WHERE $idList
          ORDER BY series_name2 ASC, series_vol ASC ";
        // Lock our results at 48 per page (to make even rows)
        if ($numResults > 48) {
          $this->hasPagination = true;
          $sql .= 'LIMIT 48 ';

          // Divides total number of results by our result limit to figure out number of pages
          $this->numPages = ceil($numResults / 48);

          // Loop to generate page number links
          for ($i = 1; $i <= $this->numPages; $i++) {
            if (isset($profile_name) && $profile_name !== '') { 
              $userBrowse = 'user=' . $profile_name . '&';
            } else {
              $userBrowse = '';
            }

            // Set class of page number as active if it matches the current page number
            if (isset($this->pageNum) && $i == $this->pageNum) {
              $this->pagination .= '<li class="active"><a href="/profile.php?' . $userBrowse . 'page=' . $i . '">' . $i . '</a></li>';
            } else {
              $this->pagination .= '<li><a href="/profile.php?' . $userBrowse . 'page=' . $i . '">' . $i . '</a></li>';
            }
          }

          // Removes "previous" and "next" functionality if at beginning/end of pagination.
          if ($this->pageNum == 1) {
            $this->previousPage = '';
          } else {
            $previousPageNum = $this->pageNum - 1;
            $this->previousPage = ' <li><a href="/profile.php?' . $userBrowse . 'page=' . $previousPageNum . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
          }
          if ($this->pageNum == $this->numPages) {
            $this->nextPage = '';
          } else {
            $nextPageNum = $this->pageNum + 1;
            $this->nextPage = ' <li><a href="/profile.php?' . $userBrowse . 'page=' . $nextPageNum . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
          }

          // Offset the results if on pages after 1
          if ($this->pageNum > 1) {
            $offsetNum = $this->pageNum - 1;
            $sql .= 'OFFSET ' . 48 * $offsetNum;
          }
          
        }
        $this->series_list_result = $this->db_connection->query ( $sql ); 
      }
    }
  }

  // Counts total number of books for the user in users_comics
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

  // Counts the number of series owned by the user
  public function seriesCount($user_id) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }

    $sql = "SELECT user_id, comics.comic_id, users_comics.comic_id, series_id
      FROM comics
      JOIN users_comics
      ON comics.comic_id=users_comics.comic_id
      WHERE users_comics.user_id=$user_id
      GROUP BY series_id";
    $this->total_series_count = mysqli_num_rows($this->db_connection->query ( $sql ));
  }
}