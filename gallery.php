<?php
session_start();

require 'vendor/autoload.php';
#create rds client
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

#DB Instance connection 
#to get the DBInstances Address
$result = $rds->describeDBInstances([ 'DBInstanceIdentifier' => 'mp1-vinodh-db']);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
$link = mysqli_connect($endpoint,"controller","letmein1234","customerrecords", 3306);

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.2/js/bootstrap.min.js"></script>
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/gallery.css" rel="stylesheet">
    <script src="js/photo-gallery.js"></script>
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
      <h3 class="text-muted">ITMO-544-MP-FINAL</h3>
    </div>  
<div class="container">
    <ul class="row">
<?php

// check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
else
{

if(isset($_POST['useremail'])){
#select data from  customerrecords table
$email = $_POST['useremail'];
$sql1 = "SELECT * FROM customerrecords WHERE Email ='$email'";
}
else {
#select data from  customerrecords tbale
$sql1 = "SELECT * FROM customerrecords";
}

$result = mysqli_query($link, $sql1);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
    echo '<li class="col-lg-3 col-md-4 col-xs-6">';
    echo '<img class="img-responsive thumbnail" src="'.$row["RawS3URL"].'">';
    echo '</li>';

    }
}
else {
    echo '<li><h4 class="text-center muted">Image gallery is empty...!!</h4></li>';
    echo '<li><h4 class="text-center muted">Goto Home and upload images</h4></li>';
}

#close db connection
mysqli_close($link);
}

?>    
</ul>    
</div>    <!-- /container -->   
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">         
          <div class="modal-body">                
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
      
    </div><!-- /.modal -->           
    <footer class="footer text-center">
      <p>&copy;  by Vinodh Kannan</p>
    </footer>

  </div> <!-- /container -->
</body>
</html>