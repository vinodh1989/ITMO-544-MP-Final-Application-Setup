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
      <form class="form-horizontal" enctype="multipart/form-data" action="submit.php" method="POST">
        <fieldset>
          <?php 
          if(isset($_SESSION['username'])){
           echo '<h4>Welcome : '.$_SESSION['username'].'</h4>';
          }
          	#create rds client
			$rds = new Aws\Rds\RdsClient([
				'version' => 'latest',
				'region'  => 'us-east-1'
				]);

			#DB Instance connection 
			#to get the DBInstances Address
			$result = $rds->describeDBInstances([ 'DBInstanceIdentifier' => 'mp1-vinodh-db']);
			$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
			//print "============\n". $endpoint . "================\n";

			#DB CONNECTION SETUP
			$DB_USERNAME="controller";
			$DB_PASSWORD="letmein1234";
			$DB_NAME="customerrecords";
			$DB_PORT=3306;

			$link = mysqli_connect($endpoint, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_PORT);

			// check connection
			if (!$link) {
				die("Connection failed: " . mysqli_connect_error());
			}
			else
			{
			#select data from  customerrecords tbale
			
			$sql1 = "SELECT * FROM introspectionstatus ORDER BY post_datetime ASC LIMIT 1";
			$result = mysqli_query($link, $sql1);

				if (mysqli_num_rows($result) > 0) {
					// output data of each row
					while($row = mysqli_fetch_assoc($result)) {
					 echo $row["readOnlyStatus"];

					}
				}
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
              <input id="filebutton" name="userfile" class="input-file" type="file" accept="image/png, image/jpeg" Required>
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
