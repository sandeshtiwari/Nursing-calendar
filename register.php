<?php
  require "config/config.php";
  require "classes/Teacher.php";
  require "header.php";

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
          //print_r($classes);
          echo "<h2>Register a room for your classes.</h2>";
          // display message if registered or not
          if(isset($_GET['registered']) && $_GET['registered'] == 'yes')
          {
            echo "<div class='alert alert-success' role='alert'>
                    Registered Successfully
                  </div>";
          }
          // display message if registration failed
          else if(isset($_GET['registered']) && $_GET['registered'] == 'no')
          {
            echo "<div class='alert alert-danger' role='alert'>
                    Failed to register
                  </div>";
          }
          if(isset($_GET['days']))
          {
             echo "<div class='alert alert-danger' role='alert'>
                    Please enter days to request or register a class
                  </div>";
          }
          if(isset($_GET['requestFailed']))
          {
             echo "<div class='alert alert-danger' role='alert'>
                    Request failed because you have an overlapping request. Please cancel the previous request to make this request.
                  </div>";
          }
          // display message if request was successfull
          if(isset($_GET['requested']))
          {
            echo "<div class='alert alert-info' role='alert'>
                    Request placed successfully
                  </div>";
          }
          // display message if successfully cancled registration
          if(isset($_GET['cancled']))
          {
            echo "<div class='alert alert-success' role='alert'>
                    Successfully cancled registration.
                  </div>";
          }
          if(isset($_GET['date']))
          {
            echo "<div class='alert alert-danger' role='alert'>
                    Please enter a valid date range.
                  </div>";
          }
          echo "<table class='table'>";
          $hassedEmail = password_hash($_SESSION['email'], PASSWORD_DEFAULT);
          $latestSem = $teacher->getLatestSem();
          $weekDates = $teacher->getWeekDates($latestSem);
          //print_r($weekDates);
          //print_r($classes);
          foreach($classes as $class)
          {
            $parts = explode(" ",$class);
            echo "<tr>";
            echo "<td class='list-group-item list-group-item-light'>".$class."</td>";
            echo "<form method='POST' action='bookRoom.php'>";
            echo "<input type= 'hidden' name = 'email' id='email' value=".$hassedEmail.">";
            echo "<input type= 'hidden' name = 'courseID' id='courseID' value=".$parts[0].">";
            echo "<td><select class='form-control' name = 'start_date'>";
            echo "<option>Select Start-date</option>";
            for($i = 0; $i<sizeof($weekDates); $i++)
            {
              echo "<option>".$weekDates[$i]['start_date']."</option>";
            }
            echo "</select>";
            echo "<td>";
            echo "<td><select class='form-control' name = 'end_date'>";
            echo "<option>Select End-date</option>";
            for($i = 0; $i<sizeof($weekDates); $i++)
            {
              echo "<option>".$weekDates[$i]['end_date']."</option>";
            }
            echo "</select>";
            echo "<td>";
            echo "<td><input type = 'submit' value ='See Rooms' class='btn btn-primary value ='Register'></td>";
            echo "</form></tr>";
          }
          echo "</table>";
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

<?php

require "footer.php";
?>