<?php
  require "config/config.php";
  if(isset($_SESSION['username']))
  {
    header("Location: calendar.php");
  }
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Nursing Calendar</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
</head>

<style type="text/css">
   body { background: navy !important; }

    /* Adding !important forces the browser to overwrite the default style applied by Bootstrap */

    .mytopnav {
  float: none;
}
    .card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: #660000;
    border-bottom: 1px solid rgba(0,123,255,.5);
    font-family: 'Lobster', cursive;
    color: white;
}
body {margin:0;font-family:Arial}

.topnav {
  
  background-color: #660000;
  padding: 25px;
  text-align: center !important;
  color: white !important;
  font-family: sans-serif !important;
  font-style: italic;
  font-size: 25px;
  font-weight: bolder;
  }

#logo{
    height: 40px;
    float: left;
  }

 .bg-dark{
  background-image: url("cs.jpg") !important;
  background-repeat: no-repeat;
  background-size: cover !important;
 } 

.card-body {
  background-color: #F5F5F5;
}
.active {
  
  color: white;
}
.icon {
background: #660000 !important;

}
.topnav .icon {
  display: none;
}

.dropdown {
    float: left;
    overflow: hidden;
}

.dropdown .dropbtn {
    font-size: 17px;    
    border: none;
    outline: none;
    color: white;
    padding: 14px 16px;
    background-color: inherit;
    font-family: inherit;
    margin: 0;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    float: none;
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    text-align: left;
}

.topnav a:hover, .dropdown:hover .dropbtn {
  background-color: #555;
  color: white;
}

.dropdown-content a:hover {
    background-color: #ddd;
    color: black;
}

.dropdown:hover .dropdown-content {
    display: block;
}

@media screen and (max-width: 600px) {
  .topnav a:not(:first-child), .dropdown .dropbtn {
    display: none;
  }
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
  .topnav.responsive {
    position: relative;
    display: block;
}
  .center .link{
  display: none !important;
  
}
  .topnav.responsive a {
    float: none;
    display: block;
    text-align: left;
  }
  .topnav.responsive .dropdown {float: none;}
  .topnav.responsive .dropdown-content {position: relative;}
  .topnav.responsive .dropdown .dropbtn {
    display: block;
    width: 100%;
    text-align: left;
  }

</style>
</style>

<body class="bg-dark">
 
<div class="topnav" id="myTopnav">The University of Louisiana at Monroe</a></div>
       


<!-- <script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script> -->

  <div class="container">

    <div class="card card-login mx-auto mt-5">
      <div class="card-header"><img style="width:100%; margin-top: 7px;" src="styles/images/Nursing101.png" class="img-circle" align="middle"></div>
      
    

      <div class="card-body">

        <?php
        if(isset($_GET['error']))
        {
          echo "<div class='alert alert-danger' role='alert'>
            Login failed
            </div>";
        }
        else if(isset($_GET['logout']))
        {
            echo "<div class='alert alert-success' role='alert'>
            Successfully logged out!
            </div>";
        }
         {

 echo '<body style="background-color:white">';
}
      ?>
                
        <form action = "login.php" method = "POST">
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input name = "email" class="form-control" id="exampleInputEmail1" type="email" aria-describedby="emailHelp" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input name = "password" class="form-control" id="exampleInputPassword1" type="password" placeholder="Password">
          </div>
          
          <button type = "submit" class="btn btn-primary btn-block" name = "login_button">Login</button>
        </form>
        
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
