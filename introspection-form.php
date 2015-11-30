<?php 
session_start(); 
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
</head>
<body>
  <div class="container">
    <div class="header clearfix">
      <nav>
        <ul class="nav nav-pills pull-right">
          <?php 
          if(isset($_SESSION['username']))
          {
            echo '<li role="presentation"><a href="main.php">Home</a></li>';
          }
          else
          {
            echo '<li role="presentation"><a href="index.php">Home</a></li>';
          }
          ?>
          <li role="presentation"><a href="gallery.php">Gallery</a></li>
          <li role="presentation"><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
      <h3 class="text-muted">ITMO-544-MP-FINAL</h3>
    </div>
    <div class="jumbotron">
	<form class="form-horizontal" enctype="multipart/form-data" action="introspection-submit.php" method="POST">
		<fieldset>
			<?php 
			if(isset($_SESSION['username'])){
				echo '<h4>Welcome : '.$_SESSION['username'].'</h4>';
			}

			if(isset($_SESSION['userrole']))
			{
				if($_SESSION['userrole'] == 0)
				{
				echo '<h4>Only admin have access to this page...!!!</h4>';
				echo '<h4>Login as Admin.</h4>';
				echo '<div class="text-center"><a href="main.php" class="btn btn-primary btn-lg">Back to Home</a><div>';
				}	
				else
				{
			?>
			<hr/>
			<!-- Back Up option-->
			<div class="form-group">
				<label class="col-md-4 control-label" for="backupdb">Backup Database</label>  
				<div class="col-md-5">
				<input type="radio" name="backupdb" value="yes" tabindex="2" checked>
				<span class="text-muted"> Yes</span>
				<input type="radio" name="backupdb" value="no" tabindex="2">
				<span class="text-muted">No</span>
				</div>
			</div>

			<!-- Disable Image Upload-->
			<div class="form-group">
				<label class="col-md-4 control-label" for="disableimgupload">Disable Image Upload</label>  
				<div class="col-md-5">
				<input type="radio" name="disableimgupload" value="yes" tabindex="2">
				<span class="text-muted"> Yes</span>
				<input type="radio" name="disableimgupload" value="no" tabindex="2" checked>
				<span class="text-muted">No</span>
				</div>
			</div>

			<!-- Button -->
			<div class="form-group">
				<label class="col-md-4 control-label" for="submit"></label>
				<div class="col-md-4">
				<button id="submit" name="submit" class="btn btn-primary btn-xs">Submit</button>
				</div>
			</div>
			<?php 
				}
			}
			?>  	
			</fieldset>
		</form>
    </div>
    <footer class="footer text-center">
      <p>&copy;  by Vinodh Kannan</p>
    </footer>

  </div> <!-- /container -->
</body>
</html>
