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

$pass =  $_POST['password1'];
$pass = password_hash($pass, PASSWORD_DEFAULT);
$email = $_SESSION['email'];

//Check if record exists
$sql = "UPDATE salesreps SET password='$pass' WHERE email='$email' ";
$result = $conn->query($sql);

$conn->close();

echo "1";
?>