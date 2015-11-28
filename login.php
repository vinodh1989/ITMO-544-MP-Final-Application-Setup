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
<link href="css/login.css" rel="stylesheet">
<!-- Custom js and jQuery for this template -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/login.js"></script>

</head>
<body>
<div class="container">
<div class="header clearfix">
<h3 class="text-muted text-center">ITMO-544-MP-FINAL</h3>
</div>
<?php
// Start the session
session_start();

// Include the AWS SDK using the Composer autoloader.
require 'vendor/autoload.php';

if(!empty($_POST)){
//echo $_POST['username'];
//echo $_POST['password'];
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

function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}

#select data from  customerrecords tbale
$UName = $_POST['username'];
$sql1 = "SELECT UName, Password FROM login WHERE UName ='$UName'";
$result1 = mysqli_query($link, $sql1);

if (mysqli_num_rows($result1) > 0) {
	$sql2 = "SELECT Email FROM customer WHERE UName ='$UName'";
	$result2 = mysqli_query($link, $sql2);

	if (mysqli_num_rows($result2) > 0) {
	    $row = mysqli_fetch_assoc($result2);
	    
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['email'] = $row['Email'];
		$url = "main.php";
		redirect($url);
	}
} 
else {
echo '<h4 class="text-center text-muted">Username is not registered...!!</h4>';
echo '<h4 class="text-center text-muted">Go back and try again ...!!</h4>';
echo '<div class="text-center"><a href="index.php" class="btn btn-primary btn-lg">Back</a><div>';
}

#close db connection
mysqli_close($link);

}
}
?>
</div> <!-- /container -->
</body>
</html>