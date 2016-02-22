<?php

/**
 * functions relating to user information
 * <pre>
 * userMeta
 * userLookup
 * userFollows
 * userCovers
 * userFollowedBy
 * collectionCount
 * seriesCount
 * carouselComics
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
        if ($this->meta_key[$i] === 'admin') {
           $this->is_admin = $this->meta_val[$i];
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

  // Counts total number of books for the user in users_comics
  public function collectionCount($user_id) {
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connection failed:" );
    }

    $sql = "SELECT SUM(quantity) AS totalComics from users_comics where users_comics.user_id = $user_id";
    $this->result = $this->db_connection->query ( $sql );
    $row = $this->result->fetch_row ();
    $this->total_issue_count = $row [0];
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

  // Grabs X number of random comics from the user's collection and displays them in a carousel.
  public function carouselComics($user_id, $count) {
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
        WHERE users_comics.user_id=$user_id 
        ORDER BY RAND()
        LIMIT 5";
    $result = $this->db_connection->query ( $sql );
    if ($result->num_rows > 0) {
      $this->carousel_list = '';
      while ( $row = $result->fetch_assoc () ) {
        $this->series_name = $row ['series_name'];
        $this->series_id = $row ['series_id'];
        $this->series_vol = $row ['series_vol'];
        $this->comic_id = $row ['comic_id'];
        $this->issue_number = $row ['issue_number'];
        $this->plot = $row ['plot'];
        $this->story_name = $row ['story_name'];
        $this->custPlot = $row ['custPlot'];
        $this->var_cover_image = $row ['variantCover'];
        $this->custStoryName = $row ['custStoryName'];
        $this->publisherShort = $row ['publisherShort'];
        $this->publisherName = $row ['publisherName'];
        if ($row['release_date']) {
          $this->release_date = DateTime::createFromFormat('Y-m-d', $row ['release_date'])->format('M Y');
        } else {
          $this->release_date = "";
        }
        $this->coverMed = $row ['cover_image'];

        $this->carousel_list .= '<div class="item"><div class="carousel-caption"><div class="row">';
        $this->carousel_list .= '<div class="col-md-4"><a href="/comic.php?comic_id=' . $this->comic_id . '"><img src="' . $this->coverMed . '" alt="" class="img-responsive center-block" /></a></div>';
        $this->carousel_list .= '<div class="col-md-8"><div class="logo-' . $this->publisherShort . ' pull-right hidden-xs hidden-sm hidden-md"></div><h4><a href="/comic.php?comic_id=' . $this->comic_id . '">' . $this->series_name . ' #' . $this->issue_number . '</a></h4>';
        $this->carousel_list .= '<div class="story-block hidden-xs hidden-sm">';
        if (isset($this->story_name) || isset($this->custStoryName)) {
          if (isset($this->custStoryName) && $this->custStoryName != '') {
            $storyName = $this->custStoryName;
          } elseif (isset($this->story_name) && $this->story_name != '') {
            $storyName = $this->story_name;
          }
          if (isset($storyName) && $storyName != '') {
            $this->carousel_list .= '<h5>"' . $storyName . '"</h5>';
          }
        }
        if (isset($this->plot) || isset($this->custPlot)) {
          if (isset($this->custPlot) && $this->custPlot != '' ) {
            $plot = $this->custPlot;
          } else {
            $plot = $this->plot;
          }
          // Let's make an "excerpt"!
          $wrapped = wordwrap(strip_tags($plot), 115);
          $lines = explode("\n", $wrapped);
          // if our $plot is shorter than 120 characters, there will be no $lines[1]
          (array_key_exists('1', $lines)) ? $suffix = '...' : $suffix = '';
          $short_plot = $lines[0] . $suffix;
          $this->carousel_list .= $short_plot;
        }
        $this->carousel_list .= '<a href="/comic.php?comic_id=' . $this->comic_id . '" class="read-more">[Read More]</a>';
        $this->carousel_list .= '</div>';
        $this->carousel_list .= '<div class="button-block hidden-xs hidden-sm hidden-md"><a href="/comic.php?comic_id=' . $this->comic_id . '" class="btn btn-danger">View Issue</a> <a href="/issues.php?series_id=' . $this->series_id . '" class="btn btn-danger">View Series</a></div>';
        $this->carousel_list .= '</div>';
        $this->carousel_list .= '<div class="button-block hidden-lg center-block text-center col-xs-12"><a href="/comic.php?comic_id=' . $this->comic_id . '" class="btn btn-sm btn-danger">View Issue</a> <a href="/issues.php?series_id=' . $this->series_id . '" class="btn btn-sm btn-danger">View Series</a></div>';
        $this->carousel_list .= '</div></div></div>';
      }
    }
  }

  public function showFeed($followList, $feedLength) {
    $feed = '';
    $this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    if ($this->db_connection->connect_errno) {
      die ( "Connect failed:" );
    }

    $sql = "SELECT users.user_name, publishers.publisherName, series.series_name, comics.issue_number, comics.cover_image, users_comics.date_added, comics.comic_id
      FROM comics
      LEFT JOIN users_comics
      ON comics.comic_id=users_comics.comic_id
      LEFT JOIN series
      ON comics.series_id=series.series_id
      LEFT join publishers
      on series.publisherID=publishers.publisherID
      LEFT JOIN users
      on users.user_id=users_comics.user_id
      WHERE users_comics.user_id IN ($followList) ORDER BY users_comics.id DESC LIMIT $feedLength";
    $result = $this->db_connection->query ( $sql );
    while ($row = $result->fetch_assoc()) {
      $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
      );
      $timeAdded = DateTime::createFromFormat('Y-m-d H:i:s', $row['date_added'])->format('U');
      $timeAdded = $timeAdded + 25200; //adjusting for local time
      $timeNow = time(); // Current unix time
      $since = $timeNow - $timeAdded;
      if($since > 604800) {
        $print = date("M jS", $timeAdded);

        if($since > 31536000) {
          $print .= ", " . date("Y", $timeAdded);
        }
      }

      // $j saves performing the count function each time around the loop
      for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0) {
          break;
        }
      }

      $print = ($count == 1) ? '1 '.$name : "$count {$name}s";

      $added = $print . " ago ";

      $feed .= '<div><h3>' . $row['user_name'] . ' added <a href="comic.php?comic_id=' . $row['comic_id'] . '">' . $row['series_name'] . ' #' . $row['issue_number'] . '</a> - ' . $added . '</h3></div>';
    }
    $this->feed = $feed;
  }
}