<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once("functions.php");

session_start();
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


//$sql = "SELECT * FROM orders WHERE rep_email='$sessionEmail' ";
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
$orderResults = [];

  while ($myObj = mysqli_fetch_object($result)) {
  	
  		$myObj->order_date = date( 'm/d/Y', strtotime( $myObj->order_date ) );
  		$myObj->requested_date = date( 'm/d/Y', strtotime( $myObj->requested_date ) );	
  		$input = unserialize($myObj->cust_po);
  		$input = implode("<br />",$input);
  		$myObj->cust_po = $input;
  		$myObj->attached_links = attached_links($myObj->lwo);
  		$myObj->order_notes = order_notes($myObj->order_status,$myObj->order_notes);
  		$userName = $myObj->rep_name;
  		$userName = explode(" ",$userName);
  		$userName = $userName[0];
      $orderResults[] = $myObj;
    }

$conn->close();

$_SESSION['orderResults'] = $orderResults;

echo json_encode($orderResults);
?>