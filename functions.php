<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//colors 
$colorOrderReceived = "#03a9f4";
$colorArtworkPending = "#c2185b";
$colorArtworkApproved = "#9c27b0";
$colorPartialGoods = "#ffc107";
$colorCompleteGoods = "#ff9800";
$colorDecoratingGoods = "#e64a19";
$colorPartiallyShipped = "#cddc39";
$colorShippedComplete = "#4caf50";


function ftp_putAll($conn_id, $src_dir, $dst_dir) {
    $d = dir($src_dir);
    while($file = $d->read()) { // do this for each file in the directory
        if ($file != "." && $file != "..") { // to prevent an infinite loop
            if (is_dir($src_dir."/".$file)) { // do the following if it is a directory
                if (!@ftp_chdir($conn_id, $dst_dir."/".$file)) {
                    ftp_mkdir($conn_id, $dst_dir."/".$file); // create directories that do not yet exist
                }
                ftp_putAll($conn_id, $src_dir."/".$file, $dst_dir."/".$file); // recursive part
            } else {
                $upload = ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY); // put the files
            }
        }
    }
    $d->close();
}

function attached_links($lwo){
	$link = "";
	
	$path = "plugin/" . $lwo . "/";

foreach (new DirectoryIterator($path) as $fileInfo) {
    if($fileInfo->isDot()) continue;
    $file =  $path.$fileInfo->getFilename();
		$fileLink = "https://teamgearprinting.com/" . $file;
		$filename = substr($file, strrpos($file, '/' )+1)."\n";
		
		$unique = uniqid();
		$ext = pathinfo($fileInfo,PATHINFO_EXTENSION);
		
		switch ($ext) {
    case "pdf":
        $imageView =  "<a target='_BLANK' href='".$fileLink."'>&nbsp;<img width='12' src='magnifying.png'/></a><br />";
        break;
    case "jpg":
        $imageView =  "<a target='_BLANK' href='".$fileLink."' data-lightbox='".$unique."' data-title='".$filename."'>&nbsp;<img width='12' src='magnifying.png'/></a><br />";
        break;
    case "jpeg":
        $imageView =  "<a target='_BLANK' href='".$fileLink."' data-lightbox='".$unique."' data-title='".$filename."'>&nbsp;<img width='12' src='magnifying.png'/></a><br />";
        break;
    case "png":
        $imageView =  "<a target='_BLANK' href='".$fileLink."' data-lightbox='".$unique."' data-title='".$filename."'>&nbsp;<img width='12' src='magnifying.png'/></a><br />";
        break;
    default:
        $imageView = "";
}
		
		
		$link .= "<div class='mb-2'><span>".$fileInfo."</span><a href='".$fileLink."' download='".$fileInfo."'>&nbsp;&nbsp;<img width='12' src='download.png'/></a>" . $imageView .  "</div>";
   
	}
	
	return $link;

} //end function


function order_notes($status, $order_notes){

if($order_notes == ""){
	
	if ($status == 0) $order_notes = "<span class='order-received-text font-weight-bold'>We have received your order.</span>";
	if ($status == 1) $order_notes = "<span class='artwork-pending-text font-weight-bold'>The artwork is pending approval.</span>";
	if ($status == 2) $order_notes = "<span class='artwork-approved-text font-weight-bold'>The artwork is approved.</span>";
	if ($status == 3) $order_notes = "<span class='partial-goods-text font-weight-bold'>We have received a partial shipment of the goods.</span>";
	if ($status == 4) $order_notes = "<span class='complete-goods-text font-weight-bold'>All goods have arrived but artwork is pending.</span>";
	if ($status == 5) $order_notes = "<span class='decorating-goods-text font-weight-bold'>The order is in decoration now.</span>";
	if ($status == 6) $order_notes = "<span class='partially-shipped-text font-weight-bold'>The order is partially shipped to the customer.</span>";
	if ($status == 7) $order_notes = "<span class='shipped-complete-text font-weight-bold'>The order is shipped to the customer.</span>";

	return $order_notes;
	
} else{
	
	if ($status == 0) $order_notes = "<span class='order-received-text font-weight-bold'>".$order_notes."</span>";
	if ($status == 1) $order_notes = "<span class='artwork-pending-text font-weight-bold'>".$order_notes."</span>";
	if ($status == 2) $order_notes = "<span class='artwork-approved-text font-weight-bold'>".$order_notes."</span>";
	if ($status == 3) $order_notes = "<span class='partial-goods-text font-weight-bold'>".$order_notes."</span>";
	if ($status == 4) $order_notes = "<span class='complete-goods-text font-weight-bold'>".$order_notes."</span>";
	if ($status == 5) $order_notes = "<span class='decorating-goods-text font-weight-bold'>".$order_notes."</span>";
	if ($status == 6) $order_notes = "<span class='partially-shipped-text font-weight-bold'>".$order_notes."</span>";
	if ($status == 7) $order_notes = "<span class='shipped-complete-text font-weight-bold'>".$order_notes."</span>";	
	
	
	return $order_notes;
	
	
	}



} //end function order_notes

?>