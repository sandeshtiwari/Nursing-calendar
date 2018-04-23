<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color: #660000;
}

.topnav a {
  float: right;
  display: block;
  color: #white !important;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 18px;
}

.topnav a:hover {
  background-color: #660000;
  color: black;
}

.active {
  background-color: #660000;
  color: white;
}

.topnav .icon {
  display: none;
}

@media screen and (max-width: 600px) {
  .topnav a:not(:first-child) {display: none;}
  .topnav a.icon {
    float: right;
    display: block;
  }
}

@media screen and (max-width: 600px) {
  .topnav.responsive {position: relative;}
  .topnav.responsive .icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  .topnav.responsive a {
    float: none;
    display: block;
    text-align: left;
  }
  .topnav.responsive #logout{
    margin-top: 2em;
  } 
}

.usrname{
	float: left !important;
  color: white !important;
}
#logo{
    height: 35px;
   
}
</style>
</head>
<body>
<script src="http://tristanedwards.me/u/SweetAlert/lib/sweet-alert.js"></script>
<link href="http://tristanedwards.me/u/SweetAlert/lib/sweet-alert.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>


<div class="topnav active" id="myTopnav">
  <a class = "usrname"> <img id="logo" src="ulm_logo_new.png">Welcome, 
  	<strong><?php
  	
  	echo $_SESSION['username']
  	?>
  		
  	</strong>
  </a>
  <a href="logout.php" title="r" class="active nav-tools" id="logout">Logout</a>
  <a href="https://moodle.ulm.edu" class="active">Moodle</a>
  <a href="https://my.ulm.edu" class="active">myULM</a>
  <a href="calendar.php" class="active">Calendar</a>  
  <a href="student_Notes.php" class="active">View Notes</a> 
  <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
</div>



<script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>
<script>
  $("#logout").click(function(e) {
  e.preventDefault()
    swal({
    title : "",
    text : "Would you like to log out from the system?",
        type : "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
   },
function(isConfirm){
  if (isConfirm) {
      //window.location="logut page url"; // if you need redirect page 
    window.location="logout.php";
  } else {
      swal("Cancelled");
  }
    })
});
</script>

</body>
</html>
