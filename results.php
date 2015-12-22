<?php
  require_once('views/head.php');
  require_once 'classes/wikiFunctions.php';

  $series_name = filter_input(INPUT_POST, 'series_name');
  $issue_number = filter_input(INPUT_POST, 'issue_number');
  $query = $series_name . " " . $issue_number;

  $returnedResults = new wikiQuery();
  $results = $returnedResults->wikiSearch($query, $series_name, $issue_number, 50);
?>
<?php include 'views/header.php';?>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h2>Your Search Results</h2>
        <p>We found the following issues on the Marvel Wikia related to your search:</p>
        <?php echo $results; ?>
      </div>
    </div>
  </div>
<?php include 'views/footer.php';?>