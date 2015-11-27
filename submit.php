<?php
// Start the session
session_start();

// Include the AWS SDK using the Composer autoloader.
require 'vendor/autoload.php';

if(!empty($_POST)){
# check file
    if(isset($_FILES['userfile'])){

        $uploaddir = '/tmp/';
        $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

        echo '<pre>';
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Possible file upload attack!\n";
        }

        echo 'Here is some more debugging info:';
        var_dump($uploadfile);
        print_r($_FILES);
        echo "</pre>";

		#use Aws\S3\S3Client;
		## create S3 client 
        $s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1'
            ]);

		#bucket unique id
        $bucket = uniqid("ITMO-544-MP2-",false);


		# To S3 create bucket
        $result = $s3->createBucket([
            'ACL' => 'public-read',
            'Bucket' => $bucket
            ]);

        $result = $s3->waitUntil('BucketExists', array('Bucket' => $bucket));

        echo "-----------------------------------------\n";
		# To upload file to S3 Bucket

        $result = $s3->putObject([
            'ACL'    => 'public-read',
            'Bucket' => $bucket,
            'Key'    => "uploads".$uploadfile,
            'ContentType' => $_FILES['userfile']['type'],
            'Body'   => fopen($uploadfile, 'r+')
            ]);
        $url = $result['ObjectURL'];
        echo $url;
        echo "test bucket";
        echo "-----------------------------------------\n"; 

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

	# prepared statement
        if (!($stmt = $link->prepare("INSERT INTO customerrecords(Email,RawS3URL,FinishedS3URL,FileName,State) VALUES (?,?,?,?,?)"))) {
           echo "Prepare failed: (" . $link->errno . ") " . $link->error;
       }
       else
       {
        echo "----Prepare success";
    }

	# bind and execute 
	# insert data
    $email         = $_SESSION['username'];
	$s3rawurl      = $url; //  $result['ObjectURL']; from above
	$s3finishedurl = "none";
	$filename      = basename($_FILES['userfile']['name']);
	$status        = 0;

	if (!$stmt->bind_param("ssssi",$email,$s3rawurl,$s3finishedurl,$filename,$status)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	else 
	{
		echo "----Binding parameters success";
	}

	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	else
	{
		//printf("%d Row inserted.\n", $stmt->affected_rows);
		#create sns client
		$sns = new Aws\Sns\SnsClient([
			'version' => 'latest',
			'region'  => 'us-east-1'
			]);

		//to list topic list
		$result = $sns->listTopics(array(

			));
		//to get Topic ARN of MP2ImageSubscriptions
		foreach ($result['Topics'] as $key => $value){
			if(preg_match("/MP2ImageSubscriptions/", $result['Topics'][$key]['TopicArn'])){
			$topicARN =$result['Topics'][$key]['TopicArn'];
			}
		}	
		// to publish message
		$result = $sns->publish(array(
			'TopicArn' => $topicARN,
			// Message is required
			'Subject' => 'AWS Email Notification',
			'Message' => 'New image is uploaded..!!',
		));
	}

	#explicit close of prepared statement recommended 
	$stmt->close();
	
	/*
	#select data from  customerrecords tbale
	$sql1 = "SELECT ID, RawS3URL FROM customerrecords";
	$result = mysqli_query($link, $sql1);

	if (mysqli_num_rows($result) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) {
			echo "id: " . $row["ID"]."- RawS3URL" . $row["RawS3URL"]. "<br>";
		}
	} 
	else {
		echo "----0 results";
	}
	*/
	
	#close db connection
	mysqli_close($link);

	function redirect($url, $statusCode = 303)
	{
	 header('Location: ' . $url, true, $statusCode);
	 die();
	}

	$url	= "gallery.php";
	redirect($url);
	}

}
else {
    echo "Post data is empty";
}


?>