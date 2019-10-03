<!DOCTYPE html>
<html lang="en">
<head>
<title>Team Gear Printing - Order Portal Login</title>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>

<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
<style type="text/css">
*{
margin:0;
padding:0;
border:0;
}
body {
font-family: 'Source Sans Pro', sans-serif !important;

}
</style>

<script>

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}


$(document).ready(function() {


	$("input[type=email]").keyup(function(){

	var email = $("#email").val();

	if(isEmail(email)){
	$("#emailValid").hide();
	} else{
	$("#emailValid").show();
	}

	});


	$("#login").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');
    var data = $(this).serialize();
    console.log(data);
    $.ajax({
           type: "POST",
           url: url,
           data: data, // serializes the form's elements.
           success: function(data)
           {
            console.log(data);

               if(data == "1"){
                window.location = "https://teamgearprinting.com/orders.php";
                 console.log("yes");
               }
               if(data == "0"){
               $("#passValid").show();;
                console.log("no");
               }
           }
         });


});


}); //END jQuery
</script>

</head>
<body>

<!-- no additional media querie or css is required -->
<div class="container-fluid">
        <div class="row justify-content-center align-items-center" style="height:100vh">
            <div class="col-sm-4">

            <h1 class="text-primary text-center p-3"><img class="mr-4 pb-4" src="tgp.bmp" width="75" alt="Team Gear Printing"/>Order Portal</h1>

                <div class="card">
                    <div class="card-body">
                        <form id="login" action="loginCheck.php" autocomplete="off">
                            <div class="form-group">
                                <input id="email" type="email" class="form-control" name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                        <br />
                        <a href="#" class="card-link" style="display:none;">Forgot Password</a>
                        <span id="emailValid" class="pr-2" style="display:none;"><span class="glyphicon glyphicon-remove" style="color:#FF0004;"></span><span>&nbsp;&nbsp;Email is not valid</span></span>
                        <span id="passValid" style="display:none;"><span class="glyphicon glyphicon-remove" style="color:#FF0004;"></span><span>&nbsp;&nbsp;Email & Password Combination Invalid</span></span>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>
