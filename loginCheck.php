<?php

/* server
$servername = "localhost";
$username = "kirk";
$password = "Tard99es5";
$dbname = "tgp";
*/

// Local
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

$email = $_POST['email'];
$password =  $_POST['password'];

//Check if record exists
$sql = "SELECT * FROM salesreps WHERE email='$email' ";
$result = $conn->query($sql);

$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$hash = $row['password'];

if (password_verify($password, $hash)) {
    //echo 'Password is valid!';
    echo "1";
} else {
    //echo 'Invalid password.';
    echo "0";
}


$conn->close();



session_start();
$_SESSION['email'] = $row['email'];
$_SESSION['firstname'] = $row['firstname'];
$_SESSION['id'] = $row['id'];
?>
