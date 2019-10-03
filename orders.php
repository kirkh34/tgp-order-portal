<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once("functions.php");

session_start();

$sessionEmail = $_SESSION['email'];

if(!isset($_SESSION['email']))   // Checking whether the session is already there or not if
                              // true then header redirect it to the home page directly
 {
    header("Location:login.php");
 }


/* server
$servername = "localhost";
$username = "xxx";
$password = "xxx";
$dbname = "xxx";
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


if($_SESSION['id'] > 2){
$sql = "SELECT * FROM orders WHERE rep_email='$sessionEmail' ";
} else{
$sql = "SELECT * FROM orders";
}

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
$userName = $_SESSION['firstname'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>Team Gear Printing - Order Portal</title>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>



<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>



<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"/>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.5/css/rowReorder.dataTables.min.css"/>


<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css"/>

<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>


<link href="lightbox/dist/css/lightbox.css" rel="stylesheet">
<script src="lightbox/dist/js/lightbox.js"></script>
 <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>


 <script type="text/javascript" src="https://teamgearprinting.com/js/bootbox.min.js"></script>

<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">

<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">

<style type="text/css">

*{
margin:0;
padding:0;
border:0;
}
body {
font-family: 'Source Sans Pro', sans-serif !important;

}

a:hover{
	text-decoration:none;
	}



.main-table{
	text-align:center;
	}

.main-table, .main-table td, .main-table th, .main-table tr{border-color:#333;}


.borderless td, .borderless th {
    border: none;
}

.table-nostriped tbody tr:nth-of-type(odd) {
  background-color:transparent;
  border:none;
}
.table-responsive {
   overflow-x: inherit;
}


.order-status{
	font-weight:bold;
	}

td.details-control {
    background: url('js/datatables/images/plus.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('js/datatables/images/minus.png') no-repeat center center;
}

table.dataTable thead th {
    position: relative;
    background-image: none !important;
}

table.dataTable thead .sorting { content: "\f0de";font-size:17px; }
table.dataTable thead .sorting_asc { content: "\f0de";font-size:17px; }
table.dataTable thead .sorting_desc { content: "\f0de";font-size:17px; }

table.dataTable thead .sorting_asc_disabled { content: "\f0de";font-size:17px; }
table.dataTable thead .sorting_desc_disabled { content: "\f0de";font-size:17px; }


.childDetails{
	list-style:none;
  display:block;
	}


.order-received{background-color:<?PHP echo $colorOrderReceived;?>;}
.artwork-pending{background-color:<?PHP echo $colorArtworkPending;?>;}
.artwork-approved{background-color:<?PHP echo $colorArtworkApproved;?>;}
.partial-goods{background-color:<?PHP echo $colorPartialGoods;?>;}
.complete-goods{background-color:<?PHP echo $colorCompleteGoods;?>;}
.decorating-goods{background-color:<?PHP echo $colorDecoratingGoods;?>;}
.partially-shipped{background-color:<?PHP echo $colorPartiallyShipped;?>;}
.shipped-complete{background-color:<?PHP echo $colorShippedComplete;?>;}

.order-received-text {color:<?PHP echo $colorOrderReceived;?>;}
.artwork-pending-text {color:<?PHP echo $colorArtworkPending;?>;}
.artwork-approved-text {color:<?PHP echo $colorArtworkApproved;?>;}
.partial-goods-text {color:<?PHP echo $colorPartialGoods;?>;}
.complete-goods-text {color:<?PHP echo $colorCompleteGoods;?>;}
.decorating-goods-text {color:<?PHP echo $colorDecoratingGoods;?>;}
.partially-shipped-text {color:<?PHP echo $colorPartiallyShipped;?>;}
.shipped-complete-text {color:<?PHP echo $colorShippedComplete;?>;}


.btns button{
width:75px;
margin: 0px 0px 4px 2px;
}

.btn-2nd{
position:relative;
bottom:5px;
}

.btn{
margin-bottom:10px;
}

.material-icons{
position:relative;
top:6px;
right:2px;
}

.order-status{
cursor:pointer;
}

.childDetails{
position:relative;
margin:auto;
}


</style>

<script>
/* Formatting function for row details - modify as you need */
function format ( d ) {

	  var attachedLinks  ;
    // `d` is the original data object for the row
    return  '<div class="border border-dark p-2" style="background-color:#fff;">'+
    				'<table cellpadding="5" class="table borderless table-nostriped" cellspacing="0" border="0">'+
        		'<tr align="center">'+
            '<td><img class="img-fluid" src=\'bsn_logo.png\'/></td>'+

            '<td>'+
            '<table class="table-nostriped borderless">'+
            '<tr>'+
            '<td class="font-weight-bold">Ship To</td>'+
            '<td>'+d.ship_to+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="font-weight-bold">Cust PO</td>'+
            '<td>'+d.cust_po+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="font-weight-bold">Sold To</td>'+
            '<td>'+d.sold_to+'</td>'+
            '</tr>'+
            '</table>'+
            '</td>'+

            '<td>'+
            '<table class="table-bordered table-nostriped border border-dark">'+
            '<tr>'+
            '<td class="bg-secondary font-weight-bold border border-dark">LWO #</td>'+
            '<td class="border border-dark">'+d.lwo+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="bg-secondary font-weight-bold border border-dark">Cart ID</td>'+
            '<td class="border border-dark">'+d.cart_id+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="bg-secondary font-weight-bold border border-dark">Deco Group</td>'+
            '<td class="border border-dark">'+d.deco+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="bg-secondary font-weight-bold border border-dark">Order Date</td>'+
            '<td class="border border-dark">'+d.order_date+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="bg-secondary font-weight-bold border border-dark">Requested Date</td>'+
            '<td class="border border-dark">'+d.requested_date+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="bg-secondary font-weight-bold border border-dark">Ship Via</td>'+
            '<td class="border border-dark">'+d.ship_via+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="bg-secondary font-weight-bold border border-dark">Vendor</td>'+
            '<td class="border border-dark">'+d.vendor+'</td>'+
            '</tr>'+
            '</table>'+
            '</td>'+

        '</tr>'+
        '</table>'+


            '<table width=\'100%\' cellpadding="5" class="table table-bordered table-nostriped border-dark" cellspacing="0" border="0">'+
            '<tr>'+
            '<td class="border border-dark bg-secondary"></td>'+
            '<td class="border border-dark bg-secondary font-weight-bold">Contact Name</td>'+
            '<td class="border border-dark bg-secondary font-weight-bold">Phone</td>'+
            '<td class="border border-dark bg-secondary font-weight-bold">Email</td>'+
            '</tr>'+

            '<tr>'+
            '<td class="border border-dark">REP</td>'+
            '<td class="border border-dark">'+d.rep_name+'</td>'+
            '<td class="border border-dark">'+d.rep_phone+'</td>'+
            '<td class="border border-dark">'+d.rep_email+'</td>'+
            '</tr>'+


            '<tr>'+
            '<td class="border border-dark">CSR</td>'+
            '<td class="border border-dark">'+d.csr_name+'</td>'+
            '<td class="border border-dark">'+d.csr_phone+'</td>'+
            '<td class="border border-dark">'+d.csr_email+'</td>'+
            '</tr>'+

						'</table>'+

						'<table width=\'100%\' cellpadding="5" class="table table-bordered border-dark" cellspacing="0" border="0">'+
            '<tr>'+
            '<td class="border border-dark bg-secondary font-weight-bold">Attached Files</td>'+
            '<td class="border border-dark bg-secondary font-weight-bold">Notes</td>'+
            '</tr>'+
            '<tr>'+
            '<td style="display: flex;align-items: center;align-content: center;"><div style="text-align:left;margin:0 auto;">'+d.attached_links+'</div></td>'+
            '<td class="border border-dark">'+d.order_notes+'</td>'+
            '</tr>'+
						'</table>'+
						'</div>';



}

$(document).ready(function() {


	var orderResults = <?php echo json_encode($_SESSION['orderResults']); ?>;

	function sortDatePlugin(){
	$.fn.dataTable.moment = function (format,locale){
		var types = $.fn.dataTable.ext.type;
		// Add type detection
		types.detect.unshift( function ( d ) {
			return moment( d, format, locale, true ).isValid() ? 'moment-'+format : null;
		});
		// Add sorting method - use an integer for the sorting
		types.order[ 'moment-'+format+'-pre' ] = function ( d ) {
			return moment( d, format, locale, true ).unix();
		};
	};
}



		sortDatePlugin();

		$.fn.dataTable.moment('MM/DD/YYYY');

    var table = $('#example').DataTable( {
        data: orderResults,
        destroy: true,
        responsive: {
            details: {
                   renderer: function ( api, rowIdx, columns ) {


                   	columns.pop();
                   	console.log(columns);
                   	var tableData = "<center><ul class='childDetails'>";
                   	 $(columns).each(function() {
												if(this.hidden){
													console.log(this.title + "\n")
													if(this.title != ""){
														console.log("yep");
														tableData += "<li><b>" + this.title + "</b>&nbsp;&nbsp;" +this.data + "</li>";

														} else{
														//	data += "";
															}
													}

													console.log(tableData);

 											});

                    	tableData += "</ul></center>";



                    return tableData ?


                        $('<table/>').append( tableData ) :
                        false;


                }
            }
        },
        "columns": [
            {
                "className":      'order-status',
                "data":           "order_status",
                "defaultContent": 'Order Received'
            },
            { "data": "lwo" },
            { "data": "sold_to" },
            { "data": "order_date" },
            { "data": "requested_date" },
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            {
                "className":      'rep_email',
                "data":           "rep_email"
            },
                        {
                "className":      'csr_email',
                "data":           "csr_email"
            },
                        {
                "className":      'rep_name',
                "data":           "rep_name"
            },
                        {
                "className":      'csr_name',
                "data":           "csr_name"
            },
        ],



           "createdRow": function( row, data, dataIndex){

                if( data.order_status  == 0){
                   $(row).find('td:eq(0)').addClass("order-received");
                   $(row).find('td:eq(0)').html("Order Received")
                }
                if( data.order_status  == 1){
                   $(row).find('td:eq(0)').addClass("artwork-pending");
                   $(row).find('td:eq(0)').html("Artwork Pending")
                }
                if( data.order_status  == 2){
                   $(row).find('td:eq(0)').addClass("artwork-approved");
                   $(row).find('td:eq(0)').html("Artwork Approved")
                }
                if( data.order_status  == 3){
                   $(row).find('td:eq(0)').addClass("partial-goods");
                   $(row).find('td:eq(0)').html("Partial Goods")
                }
                if( data.order_status  == 4){
                   $(row).find('td:eq(0)').addClass("complete-goods");
                   $(row).find('td:eq(0)').html("Complete Goods")
                }
                if( data.order_status  == 5){
                   $(row).find('td:eq(0)').addClass("decorating-goods");
                   $(row).find('td:eq(0)').html("Decorating Goods")
                }
                if( data.order_status  == 6){
                   $(row).find('td:eq(0)').addClass("partially-shipped");
                   $(row).find('td:eq(0)').html("Partially Shipped")
                }
                if( data.order_status  == 7){
                   $(row).find('td:eq(0)').addClass("shipped-complete");
                   $(row).find('td:eq(0)').html("Shipped Complete")
                }




            }
    		});


table.columns( '.rep_email, .rep_name, .csr_email, .csr_name' ).visible( false );


    // Add event listener for opening and closing details
    $('#example tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        var originalBg = tr.css('background-color');
        var statusBg  = tr.find("td:first").css('background-color');
        var status  = tr.find("td:first").html();


 				var statusColor;


 				var statusOrderReceived = "Order Received";
 				var statusArtworkPending = "Artwork Pending";
 				var statusArtworkApproved = "Artwork Approved";
 				var statusPartialGoods = "Partial Goods";
 				var statusCompleteGoods = "Complete Goods";
 				var statusDecoratingGoods = "Decorating Goods";
 				var statusPartiallyShipped = "Partially Shipped";
 				var statusShippedComplete = "Shipped Complete";

 				switch(status) {
				  case statusOrderReceived:
				    statusColor = "order-received";
				    break;
				  case statusArtworkPending:
				    statusColor = "artwork-pending";
				    break;
				  case statusArtworkApproved:
				    statusColor = "artwork-approved";
				    break;
				  case statusPartialGoods:
				    statusColor = "partial-goods";
				    break;
				  case statusCompleteGoods:
				    statusColor = "complete-goods";
				    break;
				  case statusDecoratingGoods:
				    statusColor = "decorating-goods";
				    break;
				  case statusPartiallyShipped:
				    statusColor = "partially-shipped";
				    break;
				  case statusShippedComplete:
				    statusColor = "shipped-complete";
				    break;
				  default:
				    statusColor = "No Match";
				}



    if ( row.child.isShown() ) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');

        $cells = tr.children();
   		$cells.each(function(id) {
        var cellData = table.cell( this ).data();
        if(id != 0) $(this).removeClass(statusColor);


   		 });

    }
    else {
        // Open this row (the format() function would return the data to be shown)
        row.child( format(row.data()) ).show();
        tr.addClass('shown');

        tr.next('tr').find("td:first").addClass(statusColor);

        $cells = tr.children();
   		$cells.each(function() {
        var cellData = table.cell( this ).data();
        $(this).addClass(statusColor);
   		 });

    }


    } );



	var passValid;
	var passLength;
	var passMatch;


	$("input[type=password]").keyup(function(){
    var ucase = new RegExp("[A-Z]+");
	var lcase = new RegExp("[a-z]+");
	var num = new RegExp("[0-9]+");


    passValid = 0;
	passLength = 0;
	passMatch = 0;

	if($("#password1").val().length >= 8){

		$("#8char").css("color","#00A41E");
		passLength ++;
	}else{

		$("#8char").css("color","#FF0004");
		passLength = 0;
	}


	/*
	if(ucase.test($("#password1").val())){
		$("#ucase").removeClass("glyphicon-remove");
		$("#ucase").addClass("glyphicon-ok");
		$("#ucase").css("color","#00A41E");
	}else{
		$("#ucase").removeClass("glyphicon-ok");
		$("#ucase").addClass("glyphicon-remove");
		$("#ucase").css("color","#FF0004");
	}

	if(lcase.test($("#password1").val())){
		$("#lcase").removeClass("glyphicon-remove");
		$("#lcase").addClass("glyphicon-ok");
		$("#lcase").css("color","#00A41E");
	}else{
		$("#lcase").removeClass("glyphicon-ok");
		$("#lcase").addClass("glyphicon-remove");
		$("#lcase").css("color","#FF0004");
	}

	if(num.test($("#password1").val())){
		$("#num").removeClass("glyphicon-remove");
		$("#num").addClass("glyphicon-ok");
		$("#num").css("color","#00A41E");
	}else{
		$("#num").removeClass("glyphicon-ok");
		$("#num").addClass("glyphicon-remove");
		$("#num").css("color","#FF0004");
	}
	*/

	if($("#password1").val() == $("#password2").val()){

		$("#pwmatch").css("color","#00A41E");
		passMatch ++;
	}else{

		$("#pwmatch").css("color","#FF0004");
		passMatch = 0;
	}

		passValid = passLength + passMatch;
		passLength = 0;
		passMatch = 0;

		console.log(passValid);

});

$("#ordersLink").click(function(){

	$(".passwordDiv").hide();
	$("#ordersDiv").show();
	$("#passwordLink").removeClass('active');
	$(this).addClass('active');
	});

	$("#passwordLink").click(function(){

	$(".passwordDiv").show();
	$("#ordersDiv").hide();
	$("#ordersLink").removeClass('active');
	$(this).addClass('active');
	});



		$("#passwordForm").submit(function(e) {


    e.preventDefault(); // avoid to execute the actual submit of the form.
	if(passValid == 2){
    var form = $(this);
    var url = form.attr('action');
    var data = $(this).serialize();
    $.ajax({
           type: "POST",
           url: url,
           data: data, // serializes the form's elements.
           success: function(data)
           {

           console.log(data);
               if(data == "1"){
               $('#passwordForm').trigger("reset");
               $("#8char").css("color","#FF0004");
               $("#pwmatch").css("color","#FF0004");
              $(".passSuccessText").show().delay(3000).fadeOut(1000);
               }
               if(data == "0"){

               }
           }
         });
	}


	});



$(".pull-orders").click(function(){
 var table = $('#example').DataTable();
 var data = $(this).attr('data-pull');
 var orderStatus = new Array('order-received', 'artwork-pending','artwork-approved','partial-goods','complete-goods','decorating-goods','partially-shipped','shipped-complete');
 var search;
 orderStatus.forEach(function (value, i) {
    if(data == value) search = i;
});

 if(data != "all-orders"){
  table.column( 0 ).search( search ).draw();

  var colorClass = data + "-text";
  var viewText = data.replace(new RegExp("\\-","g"),' ').split(' ')
    .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
    .join(' ');
  //viewText = viewText.css("text-transform","capitalize");
  $("#viewingText").html(viewText).removeClass().addClass(colorClass);

	}else{
	  table.column( 0 ).search( "" ).draw();
	    $("#viewingText").html("All Orders").removeClass().addClass("text-primary");

	}

});

function getBadges(){

var data = $(this).attr('data-pull');
var url = "https://teamgearprinting.com/pullOrders.php";


$.ajax({
  type: 'GET',
  url: url,
  pullData: data,
  dataType: 'json',
  success: function(data) {

    var sum = 0;
 	var orderStatus = new Array('order-received', 'artwork-pending','artwork-approved','partial-goods','complete-goods','decorating-goods','partially-shipped','shipped-complete');



    $.each(data, function (i, value) {
    var id = orderStatus[i] + "-badge";
    var total = parseInt(value);
    sum += total;
    $("#"+id).html(value);
	});
    $("#all-orders-badge").html(sum);
  }
  });

}


getBadges();



 	var orderStatus = new Array('order-received', 'artwork-pending','artwork-approved','partial-goods','complete-goods','decorating-goods','partially-shipped','shipped-complete');

$('#example tbody').on('click', 'td.order-status', function () {

	var sessionID = "<?php echo $_SESSION['id'];?>";
	if(sessionID > 2) return false;

	var tr = $(this).closest('tr');
	var data = table.row(tr).data();
	console.log(data);

	var url = "https://teamgearprinting.com/updateStatus.php";


	function sendData(newStatus){

	var addNotes = $("#addNotes").val();
		  $.ajax({
			  method: "POST",
			  url: url,
			  data: { newStatus: newStatus, id: data.id, addNotes: addNotes }
			})
			  .done(function( msg ) {

			  	getBadges();

				     $.ajax({
    url: "https://teamgearprinting.com/gatherData.php",
    type: 'GET',
    dataType: 'json',
    success: function(res) {
        console.log(res);

    var datatable = table;
	datatable.clear().draw();
   datatable.rows.add(res); // Add new data
   datatable.columns.adjust().draw();

    }
});

			  });






	} //end sendData

	var statusText = orderStatus[data.order_status]
	var viewText = statusText.replace(new RegExp("\\-","g"),' ').split(' ')
    .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
    .join(' ');

	bootbox.dialog({
    title:  data.rep_name + " - " + data.ship_to + " - <span class='"+statusText+"-text'>" + viewText + "</span>",
    message: '<textarea id="addNotes" class="form-control" style="min-width: 100%" placeholder="Add Notes Here"></textarea>',
    size: 'large',
    onEscape: true,
    backdrop: true,
    className: "bootboxDiv",
    buttons: {
			orderReceived: {
            label: 'Order <br/> Received',
            className: 'order-received',
            callback: function(){
            var newStatus = 0;
			sendData(newStatus);

            }
        },
        	artworkPending: {
            label: 'Artwork <br/> Pending',
            className: 'artwork-pending',
            callback: function(){
            var newStatus = 1;
			sendData(newStatus);

            }
        },
            artworkApproved: {
            label: 'Artwork <br/> Approved',
            className: 'artwork-approved',
            callback: function(){
            var newStatus = 2;
			sendData(newStatus);

            }
        },
            partialGoods: {
            label: 'Partial <br/> Goods',
            className: 'partial-goods',
            callback: function(){
            var newStatus = 3;
			sendData(newStatus);

            }
        },
        	completeGoods: {
            label: 'Complete<br/>  Goods',
            className: 'complete-goods',
            callback: function(){
            var newStatus = 4;
			sendData(newStatus);

            }
        },
        	decoratingGoods: {
            label: 'Decorating <br/> Goods',
            className: 'decorating-goods',
            callback: function(){
            var newStatus = 5;
			sendData(newStatus);

            }
        },
            partiallyShipped: {
            label: 'Partially<br/>  Shipped',
            className: 'partially-shipped',
            callback: function(){
            var newStatus = 6;
			sendData(newStatus);

            }
        },
            completeShipped: {
            label: 'Shipped <br/> Complete',
            className: 'shipped-complete',
            callback: function(){
            var newStatus = 7;
			sendData(newStatus);

            }
    }
    }
}); //end bootbox

$(".modal-footer").css("display","-webkit-box");

}); // end order-status click




}); //end jquery
</script>
</head>

<body>






<div class="container-fluid justify-content-center ">

<div class="row justify-content-center p-3">
	<h2 class="col-sm-12 text-center bg-light text-dark p-3 border"><img class="mr-4 pb-3" src="tgp.bmp" width="90" alt="Team Gear Printing"/>Order Status</h2>
</div>

<div class="row justify-content-center">
	<div class="col-sm-3" style="">
		<h2 class="text-center">Hello,&nbsp;<?php echo $userName;?></h2><br />
		<div class="list-group text-center pb-3">
			<a id="ordersLink" href="#" class="list-group-item active">Orders</a>
			<a id="passwordLink" href="#" class="list-group-item">Password</a>
			<a id="logoutLink" href="logout.php" class="list-group-item">Logout</a>
		</div>
	</div>



    <div id="ordersDiv" class="col-sm-9 mb-4">


<div class="btn-toolbar-sm btn-group-horizontal btn-group-sm btns my-2 text-center">

<button type="button" class="btn btn-sm bg-primary pull-orders" data-pull="all-orders">
  <span>All<br/><span class="btn-2nd">Orders</span</span><br />
  <span class="badge badge-light btn-badge"><span id="all-orders-badge"></span></span>
</button>

<button type="button" id="btnOrderReceived" class="btn btn-sm order-received  pull-orders" data-pull="order-received">
  <span>Order<br/><span class="btn-2nd">Received</span</span><br />
  <span class="badge badge-light btn-badge"><span id="order-received-badge"></span></span>
</button>

<button type="button" class="btn btn-sm artwork-pending  pull-orders" data-pull="artwork-pending">
  <span>Artwork<br/><span class="btn-2nd">Pending</span</span><br />
  <span class="badge badge-light btn-badge"><span id="artwork-pending-badge"></span></span>
</button>

<button type="button" class="btn btn-sm artwork-approved  pull-orders" data-pull="artwork-approved">
  <span>Artwork<br/><span class="btn-2nd">Approved</span</span><br />
  <span class="badge badge-light btn-badge"><span id="artwork-approved-badge"></span></span>
</button>

<button type="button" class="btn btn-sm partial-goods  pull-orders" data-pull="partial-goods">
  <span>Partial<br/><span class="btn-2nd">Goods</span</span><br />
  <span class="badge badge-light btn-badge"><span id="partial-goods-badge"></span></span>
</button>

<button type="button" class="btn btn-sm complete-goods  pull-orders" data-pull="complete-goods">
  <span>Complete<br/><span class="btn-2nd">Goods</span</span><br />
  <span class="badge badge-light btn-badge"><span id="complete-goods-badge"></span></span>
</button>

<button type="button" class="btn btn-sm decorating-goods  pull-orders" data-pull="decorating-goods">
  <span>Decorating<br/><span class="btn-2nd">Goods</span</span><br />
  <span class="badge badge-light btn-badge"><span id="decorating-goods-badge"></span></span>
</button>

<button type="button" class="btn btn-sm partially-shipped  pull-orders" data-pull="partially-shipped">
  <span>Partially<br/><span class="btn-2nd">Shipped</span</span><br />
  <span class="badge badge-light btn-badge"><span id="partially-shipped-badge"></span></span>
</button>

<button type="button" class="btn btn-sm shipped-complete  pull-orders" data-pull="shipped-complete">
  <span>Shipped<br/><span class="btn-2nd">Complete</span</span><br />
  <span class="badge badge-light btn-badge"><span id="shipped-complete-badge"></span></span>
</button>


</div>

	<h3 class="text-center py-2">Viewing&nbsp;&nbsp;<span id="viewingText" class="text-primary">All Orders<span></h3>


		<div class="table-responsive">
			<table id="example" class="main-table table table-striped table-bordered table-sm display no-wrap" cellspacing="0" width="100%">
				<thead class="thead-dark">
					<tr>
					<th>Status</th>
					<th>LWO</th>
					<th>Customer</th>
					<th>Order Date</th>
					<th>Requested Date</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>


 	<div class="passwordDiv col-sm-3"></div>
	<div class="passwordDiv col-sm-3 pb-3" style="display:none;">

		<h1 class="text-primary text-center p-3">Change Password</h1>
		<p class="text-center">Use the form below to change your password. Your password cannot be the same as your username.</p>

		<form method="post" id="passwordForm" action="changePassword.php">
			<input type="password" class="input-lg form-control" name="password1" id="password1" placeholder="New Password" autocomplete="off">
				<div class="row py-2">
					<div class="col-sm-6">
						<i id="8char" style="color:#FF0004;" class="material-icons">clear</i> 8 Characters Long
					</div>
					<div class="col-sm-6" style="display:none;">
						<span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 Characters Long<br>
						<span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Uppercase Letter
					</div>
					<div class="col-sm-6" style="display:none;">
						<span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Lowercase Letter<br>
						<span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Number
					</div>
				</div>
			<input type="password" class="input-lg form-control" name="password2" id="password2" placeholder="Repeat Password" autocomplete="off">
				<div class="row py-2">
					<div class="col-sm-12">
						<i id="pwmatch" style="color:#FF0004;" class="material-icons">clear</i>Passwords Match
					</div>
				</div>
			<input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="Changing Password..." value="Change Password">
		</form>
	<div class="passSuccessText pt-3 text-success" style="display:none;">Password Successfully Changed!</div>
	</div><!--/col-->
 	<div class="passwordDiv col-sm-3"></div>





</div> <!-- row -->
</div> <!-- container-fluid -->

</body>
</html>
