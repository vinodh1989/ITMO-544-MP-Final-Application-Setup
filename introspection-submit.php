<?php
// Start the session
session_start();
date_default_timezone_set('America/Chicago');
// Include the AWS SDK using the Composer autoloader.
require 'vendor/autoload.php';

if(!empty($_POST)){

	echo $_POST['backupdb'];
	echo $_POST['disableimgupload'];
	
	#create rds client
	$rds = new Aws\Rds\RdsClient([
		'version' => 'latest',
		'region'  => 'us-east-1'
		]);

	#DB Instance connection 
	#to get the DBInstances Address
	$result = $rds->describeDBInstances([ 'DBInstanceIdentifier' => 'mp1-vinodh-db']);
	$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
	print "============\n". $endpoint . "================\n";

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
	
	//to create backup of database
	$backupFile = '/tmp/database_backup_'.date("Y-m-d-H-i-s").'.sql';
    $command = "mysqldump --opt -h $endpoint -u DB_USERNAME -p DB_PASSWORD DB_NAME | gzip > $backupFile";
    exec($command);
    
	#use Aws\S3\S3Client;
	## create S3 client 
	$s3 = new Aws\S3\S3Client([
		'version' => 'latest',
		'region'  => 'us-east-1'
		]);

	#bucket unique id
	$bucket = uniqid("ITMO-544-MP-Final-DB",false);


	# To S3 create bucket
	$result = $s3->createBucket([
		'ACL' => 'public-read',
		'Bucket' => $bucket
		]);

	$result = $s3->waitUntil('BucketExists', array('Bucket' => $bucket));

	echo "-----------------------------------------\n";
	# To upload rawURL file to S3 Bucket

	$result = $s3->putObject([
		'ACL'    	 => 'public-read',
		'Bucket' 	 => $bucket,
		'Key'    	 => "backupURL".$backupFile,
		'SourceFile' => $backupFile
		]);

	$backupURL = $result['ObjectURL'];
	echo $backupURL;
	echo "-----------------------------------------\n"; 
	
	//to insert data in database
	# prepared statement
		if (!($stmt = $link->prepare("INSERT INTO introspectionstatus(backupS3URL,readOnlyStatus) VALUES (?,?)"))) {
			echo "Prepare failed: (" . $link->errno . ") " . $link->error;
		}
		else
		{
			echo "----Prepare success";
		}

	# bind and execute 
	# insert data
	$backupS3URL    = $backupURLs;
	
	// to check disable option
	$temp=$_POST['disableimgupload'];
	if(strcmp($temp,"yes") == 0){
		$readOnlyStatus = 1; // disable on 
	}
	else {
		$readOnlyStatus = 0; // disable off
	}
	
	if (!$stmt->bind_param("si",$backupS3URL,$readOnlyStatus)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	else 
	{
		echo "----Binding parameters success";
	}

	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	#explicit close of prepared statement recommended 
	$stmt->close();
	
	#close db connection
	mysqli_close($link);
	
	//function to redirect
	function redirect($url, $statusCode = 303)
	{
		header('Location: ' . $url, true, $statusCode);
		die();
	}

	$url	= "main.php";
	//redirect($url);
}

}
else {
	echo "Post data is empty";
}


?>