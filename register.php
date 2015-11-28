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
//echo $_POST['email'];
//echo $_POST['phone'];
//echo $_POST['password'];
//echo $_POST['subscription'];

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
if(!$link) {
die("Connection failed: " . mysqli_connect_error());
}
else
{
# prepared statement
if(!($stmt = $link->prepare("INSERT INTO customer(UName,Email,PhoneForSMS,SubscriptionStatus,TopicARN) VALUES (?,?,?,?,?)"))) {
echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}
else
{
//echo "----Prepare success";
}

# bind and execute 
# insert data
$uname         = $_POST['username'];
$email         = $_POST['email'];
$phone         = $_POST['phone'];


//to check subscription
$temp=$_POST['subscription'];
if(strcmp($temp,"yes") == 0){
$subscriptionStatus	= 1;
}
else
{
$subscriptionStatus	= 0;
}

#create sns client
$sns = new Aws\Sns\SnsClient([
'version' => 'latest',
'region'  => 'us-east-1'
]);

//to list topics
$result = $sns->listTopics(array(

));

//to get Topic ARN of MPFinalImageSubscriptions
foreach ($result['Topics'] as $key => $value){

if(preg_match("/MPFinalImageSubscriptions/", $result['Topics'][$key]['TopicArn'])){
$topicARN =$result['Topics'][$key]['TopicArn'];
}
}

//to subscribe user
$result = $sns->subscribe(array(
// TopicArn is required
'TopicArn' => $topicARN,
// Protocol is required
'Protocol' => 'email',
'Endpoint' => $_POST['email'],
));

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
else
{
//printf("%d Row inserted.\n", $stmt->affected_rows);
}

#explicit close of prepared statement recommended 
$stmt->close();

# prepared statement for login
if (!($stmt1 = $link->prepare("INSERT INTO login(UName,Password) VALUES (?,?)"))) {
echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}
else
{
//echo "----Prepare success";
}

# bind and execute 
# insert data
$uname	   = $_POST['username'];
$password  = $_POST['password'];

if (!$stmt1->bind_param("ss",$uname,$password)) {
echo "Binding parameters failed: (" . $stmt1->errno . ") " . $stmt1->error;
}
else 
{
//echo "----Binding parameters success";
}

if (!$stmt1->execute()) {
echo "Execute failed: (" . $stmt1->errno . ") " . $stmt1->error;
}
else
{
//printf("%d Row inserted.\n", $stmt1->affected_rows);
echo '<h4 class="text-center text-muted">You have registered successfully..!!</h4>';
//to check subscription
$temp=$_POST['subscription'];
if(strcmp($temp,"yes") == 0){
echo '<h4 class="text-center text-muted">You have subscribed for SNS Email Subscription</h4>';
echo '<h4 class="text-center text-muted">Please confirm you email address</h4>';
}
else
{
echo '<h4 class="text-center text-muted">And not subscribed for SNS Email Subscription </h4>';
}
echo '<h4 class="text-center text-muted">Go to home and login</h4>';
echo '<div class="text-center"><a href="index.php" class="btn btn-primary btn-lg">Back to Home</a><div>';
}

#explicit close of prepared statement recommended 
$stmt1->close();
#close db connection
mysqli_close($link);
}
}
?>
</div> <!-- /container -->
</body>
</html>