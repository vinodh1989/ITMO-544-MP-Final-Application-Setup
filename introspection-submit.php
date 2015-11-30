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
date_default_timezone_set('America/Chicago');
// Include the AWS SDK using the Composer autoloader.
require 'vendor/autoload.php';

if(!empty($_POST)){

	//echo $_POST['backupdb'];
	//echo $_POST['disableimgupload'];
	
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
	
	// to check db backup option
	$temp=$_POST['backupdb'];
	if(strcmp($temp,"yes") == 0){
		
	
	//to create backup of database
	$backupFile = '/tmp/database_backup_'.date("Y-m-d-H-i-s").'_.sql';
    $command = "mysqldump --opt -h $endpoint -u $DB_USERNAME -p$DB_PASSWORD $DB_NAME | gzip > $backupFile";
    system($command);
    
	#use Aws\S3\S3Client;
	## create S3 client 
	$s3 = new Aws\S3\S3Client([
		'version' => 'latest',
		'region'  => 'us-east-1'
		]);

	#bucket unique id
	$bucket = uniqid("ITMO-544-MP-Final-DB-",false);


	# To S3 create bucket
	$result = $s3->createBucket([
		'ACL' => 'public-read',
		'Bucket' => $bucket
		]);

	$result = $s3->waitUntil('BucketExists', array('Bucket' => $bucket));

	//echo "-----------------------------------------\n";
	# To upload rawURL file to S3 Bucket

	$result = $s3->putObject([
		'ACL'    	 => 'public-read',
		'Bucket' 	 => $bucket,
		'Key'    	 => "backupURL".$backupFile,
		'SourceFile'   => $backupFile
		]);

	$backupURL = $result['ObjectURL'];
	//echo $backupURL;
	//echo "-----------------------------------------\n"; 
	echo '<h4 class="text-center text-muted">DB Backup successfully stored in S3 Bucket</h4>';
	}
	else {
	 $backupURL = '';
	 echo '<h4 class="text-center text-muted">DB Backup not selected</h4>';
	}
	
	// to check disable option
	$temp=$_POST['disableimgupload'];
	if(strcmp($temp,"yes") == 0){
		$readOnlyStatus = 1; // disable on 
		echo '<h4 class="text-center text-muted">Image upload option is disable</h4>';
	}
	else {
		$readOnlyStatus = 0; // disable off
		echo '<h4 class="text-center text-muted">Image upload option is enabled</h4>';
	}
	
	//to insert data in database
	# prepared statement
		if (!($stmt = $link->prepare("INSERT INTO introspectionstatus(backupS3URL,readOnlyStatus) VALUES (?,?)"))) {
			echo "Prepare failed: (" . $link->errno . ") " . $link->error;
		}
		else
		{
			//echo "----Prepare success";
		}
		
	if (!$stmt->bind_param("si",$backupS3URL,$readOnlyStatus)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	else 
	{
		//echo "----Binding parameters success";
	}

	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	echo '<div class="text-center"><a href="main.php" class="btn btn-primary btn-lg">Back to Home</a><div>';
	#explicit close of prepared statement recommended 
	$stmt->close();
	
	#close db connection
	mysqli_close($link);
	
	}
}
else {
	echo "Post data is empty";
}
?>
</div> <!-- /container -->
</body>
</html>