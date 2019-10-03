<?php
include_once("functions.php");

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


//Variables
$pdfName = $_POST['PDF_NAME'];
$pdfURL = $_POST['PDF_URL'];
$attachment = $_POST['ATTACHMENT'];
$shipTo = $_POST['SHIP_TO'];
$custPO = serialize(array_values(array_filter(preg_split('/<br[^>]*>\s*/i', $_POST['CUST_PO']))));
$soldTo = $_POST['SOLD_TO'];
$lwo = $_POST['LWO'];
$cartID = $_POST['CART_ID'];
$decoGroup = $_POST['DECO_GROUP'];
$orderDate = date( 'Y-m-d', strtotime(  $_POST['ORDER_DATE'] ) );
$requestedDate = date( 'Y-m-d', strtotime( $_POST['REQUESTED_DATE'] ) );
$shipVia = $_POST['SHIP_VIA'];
$vendor = $_POST['VENDOR'];
$repName = $_POST['REP_NAME'];
$repPhone = $_POST['REP_PHONE'];
$repEmail = $_POST['REP_EMAIL'];
$csrName = $_POST['CSR_NAME'];
$csrPhone = $_POST['CSR_PHONE'];
$csrEmail = $_POST['CSR_EMAIL'];
$pluginDir = $_SERVER['DOCUMENT_ROOT'] . "/plugin/";
$orderDir = $_SERVER['DOCUMENT_ROOT'] . "/plugin/" . $lwo . "/";
$ftpDir = $lwo . "/";
$pdfDest = $orderDir . $pdfName;

//Check if record exists
$sql = "SELECT * FROM orders WHERE lwo='$lwo' ";
$result = $conn->query($sql);

//If record exists, exit now, otherwise keep going
if(mysqli_num_rows($result) > 0) exit();


//Insert into database
$sql = "INSERT INTO orders (ship_to, cust_po, sold_to, lwo, cart_id, deco, order_date, requested_date, ship_via, vendor, rep_name, rep_phone, rep_email, csr_name, csr_phone, csr_email)
VALUES ('$shipTo', '$custPO', '$soldTo', '$lwo', '$cartID', '$decoGroup', '$orderDate', '$requestedDate', '$shipVia', '$vendor', '$repName', '$repPhone', '$repEmail', '$csrName', '$csrPhone', '$csrEmail')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

//customer database stuff
$repNameArray = explode(" ", $repName);



$firstname = $repNameArray[0];
$lastname = $repNameArray[1];
$pass = $firstname . $lastname;
$pass = strtolower($pass);
$pass = password_hash($pass, PASSWORD_DEFAULT);

//Check if record exists
$sql = "SELECT * FROM salesreps WHERE firstname='$firstname' AND lastname ='$lastname' ";
$result = $conn->query($sql);

//If record exists, exit now, otherwise keep going
if(mysqli_num_rows($result) < 1){
$sql = "INSERT INTO salesreps (firstname, lastname, email, password) VALUES ('$firstname','$lastname', '$repEmail', '$pass')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

} 


$conn->close();




//Create order directory if doesn't exist
if (!is_dir($orderDir)) {
    mkdir($orderDir, 0777, true);
}

//Copy order pdf from URL
$contents = file_get_contents($pdfURL);
file_put_contents($pdfDest,$contents);



//If attachments, loop through and unzip if necessary into order directory
if($attachment != ""){
	foreach ($attachment as $attachmentName => $attachmentURL){
	  
  	$attachmentURL = "https://shop.ssgecom.com/" . $attachmentURL;
  	$attachmentName = $orderDir . $attachmentName;
  	$ext = pathinfo($attachmentName, PATHINFO_EXTENSION);

	  $contents = file_get_contents($attachmentURL); 
		file_put_contents($attachmentName,$contents);

  		if($ext == "zip"){
 		
 				$zip = new ZipArchive;
				$res = $zip->open($attachmentName);
					if ($res === TRUE) {
  					$zip->extractTo($orderDir);
  					$zip->close();
  					unlink($attachmentName);
  					echo 'Unzip Success!';
					} else {
  					echo 'Unzip Failure!';
					}
					
			} 
	} //end foreach
} // end if attachments



// connect and login to FTP server
$ip = "74.93.83.86";
$ftp_server = gethostbyaddr($ip);

$ftp_conn = ftp_connect($ftp_server, 21) or die("Could not connect to $ftp_server");
$ftp_login = ftp_login($ftp_conn, 'anonymous', 'pass');


if(ftp_size($ftp_conn, $ftpDir) < 0){
    ftp_mkdir($ftp_conn,$ftpDir);
}

ftp_putAll($ftp_conn,$orderDir,$ftpDir);

ftp_close($ftp_conn);     




?>
