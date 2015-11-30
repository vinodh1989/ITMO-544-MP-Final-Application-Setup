<?php
// Start the session
session_start();
require 'vendor/autoload.php';

//to create rds client
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
#$link = mysqli_connect($endpoint,"controller","letmein1234","customerrecords", 3306);


// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
else
{
echo "Connected successfully";

#drop login table if exists
$sql1 = "DROP TABLE IF EXISTS customer";

if(!mysqli_query($link, $sql1)) {
   echo "Error : " . mysqli_error($link);
}

// sql to create customer table
$sql2 = "CREATE TABLE customer (
ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
UName VARCHAR(50) NOT NULL,
Email VARCHAR(50) NOT NULL,
PhoneForSMS VARCHAR(50) NOT NULL,
SubscriptionStatus INT(1) NOT NULL,
TopicARN VARCHAR(256) NULL
)";

if (mysqli_query($link, $sql2)) {
    echo "Table login created successfully";
} 
else 
{
    echo "Error creating table: " . mysqli_error($link);
}

//to insert data into customer table
# prepared statement
if(!($stmt = $link->prepare("INSERT INTO customer(UName,Email,PhoneForSMS,SubscriptionStatus,TopicARN) VALUES (?,?,?,?,?)"))) {
	echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}
else
{
	echo "----Prepare success";
}

# bind and execute 
# insert data
$uname         		= 'admin';
$email         		= 'vsadayam@hawk.iit.edu';
$phone         		= '13126477593';
$subscriptionStatus	= 0;
$topicARN			= '';

if (!$stmt->bind_param("sssis",$uname,$email,$phone,$subscriptionStatus,$topicARN)) {
echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
else 
{
//echo "----Binding parameters success";
}

if (!$stmt->execute()) {
echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}



#drop login table if exists
$sql3 = "DROP TABLE IF EXISTS login";

if(!mysqli_query($link, $sql3)) {
   echo "Error : " . mysqli_error($link);
}

// sql to create login table
// role 1- admin | 0 - user
$sql4 = "CREATE TABLE login (
ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
UName VARCHAR(50) NOT NULL,
Password VARCHAR(50) NOT NULL,
Role INT(1) NOT NULL 
)";

if (mysqli_query($link, $sql4)) {
    echo "Table login created successfully";
} 
else 
{
    echo "Error creating table: " . mysqli_error($link);
}

//to insert data into login table
# prepared statement
if (!($stmt = $link->prepare("INSERT INTO login(UName,Password,Role) VALUES (?,?,?)"))) {
	echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}
else
{
	echo "----Prepare success";
}

# bind and execute 
# insert data
$Uname    = 'admin';
$Password = 'admin';
$Role	  = 1;

if (!$stmt->bind_param("ssi",$Uname,$Password,$Role)) {
	echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
else 
{
	echo "----Binding parameters success";
}

if (!$stmt->execute()) {
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

#drop customerrecords table if exists
$sql5 = "DROP TABLE IF EXISTS customerrecords";

if(!mysqli_query($link, $sql5)) {
   echo "Error : " . mysqli_error($link);
}

// sql to create  customerrecords table
$sql6 = "CREATE TABLE customerrecords (
ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
Email VARCHAR(50)NOT NULL,
RawS3URL VARCHAR(256),
FinishedS3URL VARCHAR(256),
FileName VARCHAR(256),
State TINYINT(3) NOT NULL DEFAULT 0,
DateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($link, $sql6)) {
    echo "Table customerrecords created successfully";
} 
else 
{
    echo "Error creating table: " . mysqli_error($link);
}

// sql to create  introspectionStatus table
$sql7 = "CREATE TABLE introspectionstatus (
ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
backupS3URL VARCHAR(256),
readOnlyStatus INT(1) NOT NULL,
DateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($link, $sql7)) {
    echo "Table introspectionstatus created successfully";
} 
else 
{
    echo "Error creating table: " . mysqli_error($link);
}
mysqli_close($link);
}

?>