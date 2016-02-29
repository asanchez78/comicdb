<?php
  require_once('views/head.php');
?>
  <title>Who Are We? :: POW! Comic Book Manager</title>
</head>
<body>
  <?php include 'views/header.php';?>
  <div class="row">
    <div class="col-xs-12">
      <header class="headline"><h2>Who Are We?</h2></header>
      <p class="about-header-image"><img class="img-responsive" src="/assets/about.jpg" alt="Anthony and Sean" /></p>
      <p><strong>POW! Comic Book Manager</strong> was created by two enthusiastic comic book collectors, Anthony Sanchez and Sean Loyless, as a way to catalog their extensive collection. Unhappy with all of the other comic book managers out there, the project was initially started so Anthony could refresh his PHP/HTML/CSS skills, but quickly evolved into a full fledged website.<p>
      <p>POW! CBM uses ComicVine's API to pull in details for comic books and series.</p>
    </div>
  </div>
  <hr />
  <div class="row">
    <div class="col-xs-12 col-md-6">
      <img class="pull-left about-avatar img-circle" src="/assets/avatar-ant.jpg" alt="Anthony Sanchez" />
      <h4>Anthony Sanchez</h4>
      <p>Anthony is a hobbyist developer and enterprise I.T. guy by trade. When he's not busy with his day job of managing 
      virtual computing platforms, storage networks, and application servers, he enjoys reading comics new and old, going to the movies, and watching live music. Anthony's first comics were <em>The Uncanny X-Men 171</em> and <em>Daredevil 235</em>, purchased for him by his aunt when he was 13.</p>
      <p><strong>Recommended Reads:</strong>  Chew, Paper Girls, New Mutants, The Brood Saga, Ms. Marvel v2015</p>
      <ul class="about-social list-inline text-center center-block">
        <li><a href="http://twitter.com/xebix" class="btn btn-link btn-xs" target="_blank"><i class="fa fa-twitter"></i> Twitter</a></li>
        <li><a href="http://facebook.com/xebix" class="btn btn-link btn-xs" target="_blank"><i class="fa fa-facebook"></i> Facebook</a></li>
        <li><a href="https://github.com/asanchez78" class="btn btn-link btn-xs" target="_blank"><i class="fa fa-github"></i> Github</a></li>
        <li><a href=" 
https://www.linkedin.com/in/anthony-sanchez-92971618" class="btn btn-link btn-xs" target="_blank"><i class="fa fa-linkedin"></i> LinkedIn</a></li>
      </ul>
    </div>
    <div class="col-xs-12 col-md-6">
      <img class="pull-right about-avatar img-circle" src="/assets/avatar-sean.jpg" alt="Sean Loyless" />
      <h4>Sean Loyless</h4>
      <p>Sean has been a web designer/developer for almost 20 years, and a geek for 38 years. Sean's first comic book was <em>Transformers: Head Masters</em> when he was 7 years old.</p>
      <p><strong>Recommended Reads:</strong> Saga, Y: The Last Man, Secret Wars, The Maxx, The Walking Dead</p>
      <ul class="about-social list-inline text-center center-block">
        <li><a href="http://twitter.com/SeanLoyless" class="btn btn-link btn-xs" target="_blank"><i class="fa fa-twitter"></i> Twitter</a></li>
        <li><a href="http://facebook.com/seanloyless" class="btn btn-link btn-xs" target="_blank"><i class="fa fa-facebook"></i> Facebook</a></li>
        <li><a href="https://github.com/sloyless" class="btn btn-link btn-xs" target="_blank"><i class="fa fa-github"></i> Github</a></li>
        <li><a href="https://www.linkedin.com/in/seanloyless" class="btn btn-link btn-xs" target="_blank"><i class="fa fa-linkedin"></i> LinkedIn</a></li>
      </ul>
    </div>
  </div>
  <?php include 'views/footer.php';?>
</body>
</html>