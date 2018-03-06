<?php
  require "config/config.php";
  require "classes/Teacher.php";
  if($_SESSION['privilege'] != 'teacher' || !isset($_SESSION['email']))
  {
    header("Location: index.php");
  }
  $teacher = new Teacher($con, $_SESSION['email']);
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Registration</title>
  </head>
  <body>
    <div class='container'>
      <?php
        $classes = $teacher->myClasses();
        if(isset($classes))
        {
          echo "<h2>Register a room for your classes.</h2>";
          if(isset($_GET['registered']) && $_GET['registered'] == 'yes')
          {
            echo "<div class='alert alert-success' role='alert'>
                    Registered Successfully
                  </div>";
          }
          echo "<ul class='list-group'>";
          $hassedEmail = password_hash($_SESSION['email'], PASSWORD_DEFAULT);
          //print_r($classes);
          foreach($classes as $class)
          {
            $parts = explode(" ",$class);
            echo "<li class='list-group-item list-group-item-light'>".$class."
            <a href='bookRoom.php?email=".$hassedEmail."&courseID=".$parts[0]."' class='btn btn-primary'>Register</a></li>";
          }
          echo "</ul>";
        }
        else
        {
          echo "<h2>You are not assigned to teach any classes so far.</h2>";
        }
      ?>
      </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>