<?php session_start(); ?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <meta name="description" content="">
  <meta name="author" content="">

  <title>ITMO-544-MP2</title>

  <!-- Bootstrap core CSS -->
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link href="css/custom.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="header clearfix">
      <nav>
        <ul class="nav nav-pills pull-right">
          <li role="presentation"><a href="main.php">Home</a></li>
          <li role="presentation"><a href="gallery.php">Gallery</a></li>
          <li role="presentation"><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
      <h3 class="text-muted">ITMO-544-MP2</h3>
    </div>

    <div class="jumbotron">
      <form class="form-horizontal" enctype="multipart/form-data" action="submit.php" method="POST">
        <fieldset>
          <?php 
          if(isset($_SESSION['username'])){
           echo '<h4>Welcome user: '.$_SESSION['username'].'</h4>';
          }
          ?>
          <hr/>
          <!-- File size hidden type --> 
          <div class="form-group">
            <div class="col-md-4">
              <!-- MAX_FILE_SIZE must precede the file input field -->
              <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
              <!-- Name of input element determines name in $_FILES array -->
            </div>
          </div>
          <!-- File Button --> 
          <div class="form-group">
            <label class="col-md-4 control-label" for="filebutton">Select Image File</label>
            <div class="col-md-4">
              <input id="filebutton" name="userfile" class="input-file" type="file" accept="image/png, image/jpeg">
            </div>
          </div>
          <!-- Button -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="submit"></label>
            <div class="col-md-4">
              <button id="submit" name="submit" class="btn btn-primary btn-xs">Submit</button>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
    <footer class="footer text-center">
      <p>&copy;  by Vinodh Kannan</p>
    </footer>

  </div> <!-- /container -->
</body>
</html>