<?php
  $type = $_GET['type'];
  if ($type) {
    require_once(__ROOT__.'/classes/wikiFunctions.php');
    require_once(__ROOT__.'/classes/functions.php');
    switch ($type) {
      

      case 'edit':
        $ownerID = __userID__;
        $updatedSet = '';
        $comic_id = filter_input(INPUT_GET, 'comic_id');
        $comic = new comicSearch();
        $wiki = new wikiQuery ();
        $comic->issueLookup($comic_id);
        $series_id = $comic->series_id;
        $comic->seriesInfo ($series_id, $userID);
        $issue_number = $comic->issue_number;
        $cvVolumeID = $comic->cvVolumeID;
        $series_vol = $comic->series_vol;
        $series_name = $comic->series_name;
        $originalPurchase = $comic->originalPurchase;
        $quantity = $comic->issue_quantity;
        $issue_condition = $comic->issueCondition;
        
        // Wiki updates automatically if field is blank
        $wiki->issueSearch($cvVolumeID, $issue_number, $series_vol);
        $creatorsList = $wiki->creatorsList;
        
        // Release Date
        if ($comic->release_date != '') {
          if ($wiki->releaseDate !== '' && $wiki->releaseDate == $comic->release_date) {
            $releaseDate = $comic->release_date;
          } else {
            $releaseDate = $wiki->releaseDate;
            $updatedSet .= 'Release Date; ';
          }
          
        } else {
          if ($wiki->releaseDate !== '') {
            $releaseDate = $wiki->releaseDate;
            $updatedSet .= 'Release Date; ';
          }
        }
        $release_dateShort = DateTime::createFromFormat('Y-m-d', $releaseDate)->format('M Y');
        $release_dateLong = DateTime::createFromFormat('Y-m-d', $releaseDate)->format('M d, Y');

        // Plot and Custom Plot
        if ($comic->plot !== '') {
          if ($wiki->synopsis !== '' && $wiki->synopsis == $comic->plot) {
            $plot = $comic->plot;
          } else {
            $plot = $wiki->synopsis;
            $updatedSet .= 'Plot; ';
          }
        } else {
          if ($wiki->synopsis !== '') {
            $plot = $wiki->synopsis;
            $updatedSet .= 'Plot; ';
          }
        }

        if ($comic->custPlot != '') {
          if ($comic->custPlot != $plot) {
            $custPlot = $comic->custPlot;
          }
        } else {
          $custPlot = '';
        }

        // Story Name and Custom Story Name
        if ($comic->story_name !== '') {
          if ($wiki->storyName !== '' && $wiki->storyName == $comic->story_name) {
            $story_name = $comic->story_name;
          } else {
            $story_name = $wiki->storyName;
            $updatedSet .= 'Story Name; ';
          }
        } else {
          if ($wiki->storyName !== '') {
            $story_name = $wiki->storyName;
            $updatedSet .= 'Story Name; ';
          }
        }

        if ($comic->custStoryName != '') {
          if ($comic->custStoryName != $story_name) {
            $custStoryName = $comic->custStoryName;
          }
        } else {
          $custStoryName = '';
        }

        // Creators
        if ($comic->script != '') {
          if ($wiki->script !== '' && $wiki->script == $comic->script) {
            $script = $comic->script;
          } else {
            $script = $wiki->script;
            $updatedSet .= 'Script; ';
          }
        } else {
          if ($wiki->script !== '') {
            $script = $wiki->script;
            $updatedSet .= 'Script; ';
          }
        }

        // Pencils
        if ($comic->pencils != '') {
          if ($wiki->pencils !== '' && $wiki->pencils == $comic->pencils) {
            $pencils = $comic->pencils;
          } else {
            $pencils = $wiki->pencils;
            $updatedSet .= 'Pencils; ';
          }
        } else {
          if ($wiki->pencils !== '') {
            $pencils = $wiki->pencils;
            $updatedSet .= 'Pencils; ';
          }
        }

        // Colors
        if ($comic->colors != '') {
          if ($wiki->colors !== '' && $wiki->colors == $comic->colors) {
            $colors = $comic->colors;
          } else {
            $colors = $wiki->colors;
            $updatedSet .= 'Colors; ';
          }
        } else {
          if ($wiki->colors !== '') {
            $colors = $wiki->colors;
            $updatedSet .= 'Colors; ';
          }
        }

        // Inks
        if ($comic->inks != '') {
          if ($wiki->inks !== '' && $wiki->inks == $comic->inks) {
            $inks = $comic->inks;
          } else {
            $inks = $wiki->inks;
            $updatedSet .= 'Inks; ';
          }
        } else {
          if ($wiki->inks !== '') {
            $inks = $wiki->inks;
            $updatedSet .= 'Inks; ';
          }
        }

        // Letters
        if ($comic->letters != '') {
          if ($wiki->letters !== '' && $wiki->letters == $comic->letters) {
            $letters = $comic->letters;
          } else {
            $letters = $wiki->letters;
            $updatedSet .= 'Letters; ';
          }
        } else {
          if ($wiki->letters !== '') {
            $letters = $wiki->letters;
            $updatedSet .= 'Letters; ';
          }
        }

        // Editing
        if ($comic->editing != '') {
          if ($wiki->editing !== '' && $wiki->editing == $comic->editing) {
            $editing = $comic->editing;
          } else {
            $editing = $wiki->editing;
            $updatedSet .= 'Editing; ';
          }
        } else {
          if ($wiki->editing !== '') {
            $editing = $wiki->editing;
            $updatedSet .= 'Editing; ';
          }
        }

        // CoverArtist
        if ($comic->coverArtist != '') {
          if ($wiki->coverArtist !== '' && $wiki->coverArtist == $comic->coverArtist) {
            $coverArtist = $comic->coverArtist;
          } else {
            $coverArtist = $wiki->coverArtist;
            $updatedSet .= 'Cover Artist; ';
          }
        } else {
          if ($wiki->coverArtist !== '') {
            $coverArtist = $wiki->coverArtist;
            $updatedSet .= 'Cover Artist; ';
          }
        }

        // Cover Image
        if ($comic->cover_image != '') {
          $coverURL = $comic->cover_image;
          $coverFile = $comic->cover_image;
        } else {
          $coverURL = $wiki->coverURL;
          $coverFile = $wiki->coverFile;
          $updatedSet .= 'Cover Artist; ';
        }
        
        // Publisher
        if (isset($comic->publisherName)) {
          $publisherName = $comic->publisherName;
          $publisherShort = $comic->publisherShort;
        } else {
          $messageNum = 60;
        }
        break;
      case 'edit-save':
        $issueEdit = true;
        $ownerID = __userID__;
        $comic_id = filter_input ( INPUT_POST, 'comic_id' );
        $released_date = filter_input ( INPUT_POST, 'released_date' );
        $story_name = addslashes ( filter_input ( INPUT_POST, 'story_name' ) );
        $custStoryName = addslashes ( filter_input ( INPUT_POST, 'custStoryName' ) );
        $quantity = addslashes ( filter_input ( INPUT_POST, 'quantity' ) );
        $plot = addslashes ( filter_input ( INPUT_POST, 'plot' ) );
        $custPlot = addslashes ( filter_input ( INPUT_POST, 'custPlot' ) );
        $condition = addslashes ( filter_input ( INPUT_POST, 'condition' ) );
        $cover_image = filter_input ( INPUT_POST, 'cover_image' );
        $cover_image_file = filter_input ( INPUT_POST, 'cover_image_file' );
        $originalPurchase = filter_input ( INPUT_POST, 'originalPurchase' );
        $creatorsList = filter_input ( INPUT_POST, 'creatorsList' );
        $updatedSet = filter_input ( INPUT_POST, 'updatedSet' );

        // Formats date
        if ($released_date == 0000 - 00 - 00) {
          $release_date = "";
        } else {
          $release_date = $released_date;
        }

        // Checks if the plot has been modified by the user
        if ($custStoryName == $story_name) {
          // If it's the same as the API data, then clear it out.
          $custStoryName = '';
        }

        // Checks if the plot has been modified by the user
        if ($custPlot == $plot) {
          // If it's the same as the API data, then clear it out.
          $custPlot = '';
        }

        $sql = "UPDATE comics SET story_name='$story_name', release_date='$release_date', plot='$plot', cover_image='$cover_image_file', wikiUpdated=1 WHERE comic_id='$comic_id'";
        if (mysqli_query ( $connection, $sql )) {
          $sqlMessage = '<strong class="text-success">Comic Database Update Success</strong>: Issue updated<br /><code>' . $sql . '</code><br /><br />';
          // Add creators to creators table
          $comic->insertCreators($comic_id, $creatorsList);
          $sql_user = "UPDATE users_comics SET quantity='$quantity', originalPurchase='$originalPurchase', custPlot='$custPlot', custStoryName='$custStoryName' WHERE user_id='$ownerID' AND comic_id='$comic_id'";
          if (mysqli_query ( $connection, $sql_user )) {
            $messageNum = 5;
            $sqlMessage .= '<strong class="text-success">User Database Update Success</strong>: Issue updated<br /><code>' . $sql_user . '</code>';
          } else {
            $sqlMessage = '<strong class="text-warning">Error</strong>: ' . $sql_user . '<br>' . mysqli_error ( $connection );
            $messageNum = 66;
          }
        } else {
            $messageNum = 62;
            $sqlMessage = '<strong class="text-danger">Error</strong>: ' . $sql . '<br><code>' . mysqli_error ( $connection ) . '</code>';
        }
        break;
    }
  } else {
    // If no type is detected, something is wrong. Display general error message.
    $submitted = 'no';
    $messageNum = 99;
  }
?>