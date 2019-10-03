<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once("functions.php");

session_start();

$sessionEmail = $_SESSION['email'];

$servername = "localhost";
$username = "xxx";
$password = "xxx";
$dbname = "xxx";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$orderStatus = array('order-received', 'artwork-pending','artwork-approved','partial-goods','complete-goods','decorating-goods','partially-shipped','shipped-complete');


$statusCount = array();

foreach($orderStatus as $key => $value){

//$sql = "SELECT * FROM orders WHERE rep_email='$sessionEmail' ";

if($_SESSION['id'] > 2){
$sql = "SELECT * FROM orders WHERE order_status='$key' AND rep_email='$sessionEmail' ";
}
else{
$sql = "SELECT * FROM orders WHERE order_status='$key'";
}
$result = $conn->query($sql);
$count = $result->num_rows;

$statusCount[] = $count;
//echo $statusCount . "\n";
}


$conn->close();

$_SESSION['statusCount'] = $statusCount;

echo json_encode($statusCount);

?>