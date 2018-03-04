<?php
  require "config/config.php";
  require "classes/Room.php";
  error_reporting(0);
  // authenticating the teacher or the person logged in
  if(isset($_GET['courseID']) && isset($_GET['email']) && isset($_SESSION['email']) && $_SESSION['privilege'] != 'student')
  {
    if(!password_verify($_SESSION['email'], $_GET['email']))
    {
      header('Location: index.php');
    }
  }
  else
  {
    header('Location: index.php');
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Register a room</title>
  </head>
  <body>
    <div class='container'>

      <h3></h3>
      <?php
        $rooms = new Room($con);
        $occupied = $rooms->getOccupiedRooms($_GET['courseID']);
        $occupiedRooms = $rooms->getFullRow($occupied);
        $vacant = $rooms->getVacantRooms($occupied);
        $vacantRooms = $rooms->getFullRow($vacant);
        //print_r($occupiedRooms);
        //print_r($vacantRooms);
       ?>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
  