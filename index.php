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

<body class="bg-dark">
  <div class="container">

    <div class="card card-login mx-auto mt-5">
      
      <div class="card-header">Login</div>
      

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
