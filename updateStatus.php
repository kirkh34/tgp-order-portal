<?php 
session_start();
//MYSQL Connect
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

$id =  $_POST['id'];
$newStatus = $_POST['newStatus'];
$addNotes = $_POST['addNotes'];
//Check if record exists
$sql = "UPDATE orders SET order_status='$newStatus', order_notes='$addNotes' WHERE id='$id' ";
$result = $conn->query($sql);

$conn->close();

?>