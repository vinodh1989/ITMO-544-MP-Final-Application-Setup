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
if (!defined("true-access"))
{
  die("direct cannot access denied");
}
// Include the AWS SDK using the Composer autoloader.
require 'vendor/autoload.php';

if(isset($_SESSION['username'])){
	unset($_SESSION['username']);
}

if(isset($_SESSION['username'])){
	unset($_SESSION['email']);
}
echo '<h4 class="text-center text-muted">You have logged out...!!</h4>';
echo '<div class="text-center"><a href="index.php" class="btn btn-primary btn-lg">Back to Login</a><div>';
?>
</div> <!-- /container -->
</body>
</html>