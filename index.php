<?php 
session_start();
define("true-access", true);
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <meta name="description" content="">
  <meta name="author" content="">

  <title>ITMO-544-MP-FINAL</title>

  <!-- Bootstrap core CSS -->
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link href="css/custom.css" rel="stylesheet">
  <style type="text/css">
    .btn-custom
    {
        width: 150px;
        height: 35px;
    }
</style>
</head>
<body>
  <div class="container">
    <div class="header clearfix">
      <nav>
        <ul class="nav nav-pills pull-right">
          <li role="presentation"><a href="index.php">Home</a></li>
          <li role="presentation"><a href="gallery.php">Gallery</a></li>
          <li role="presentation"><a href="login-register.php">Login/Register</a></li>
        </ul>
      </nav>
      <h3 class="text-muted">ITMO-544-MP-FINAL</h3>
    </div>
    <div class="jumbotron text-center" >
        <p><a href="login-register.php" class="btn btn-primary btn-custom"><span class="glyphicon glyphicon-user"></span> Login / Register</a></p>
        <p><a href="gallery.php" class="btn btn-primary btn-custom"><span class="glyphicon glyphicon-th-large"></span> View gallery </a></p>
        <p><a href="introspection.php" class="btn btn-primary btn-custom"><span class="glyphicon glyphicon-download-alt"></span> Backup Database</a></p>
    </div>
    <footer class="footer text-center">
      <p>&copy;  by Vinodh Kannan</p>
    </footer>
  </div> <!-- /container -->
</body>
</html>
