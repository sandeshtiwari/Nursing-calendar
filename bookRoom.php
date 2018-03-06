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
  $rooms = new Room($con);
  $properties = $rooms->getRoomProperties();
  $checkIfBooked = $rooms->checkBookStatus($_GET['courseID']);
  function heading($properties)
  {
    $count = 0;
    //going over all the column names except the ID column and making them the header
    for($count = 1 ; $count<sizeof($properties);$count++)
    {
      echo "<th scope='col'>".$properties[$count]."</th>";
    }
    // adding one to the count to calculate the colspan in case of empty table
    $count += 1;
    echo "<th scope='col'></th>";
    return $count;
  }
  function displayRooms($roomList, $collide,$count)
  {
    if(!empty($roomList))
        {
          //print_r($vacantRooms);
          foreach($roomList as $room)
          {
            echo "<tr>";
            //print_r($vacantRoom);
            for($i = 1; $i< sizeof($room); $i++)
            {
              //echo $vacantRoom[$i];
              // displaying the fields like Name, Available etc.
              echo "<td>".$room[$i]."</td>";
            }
            // the buttun can send get request with room id i.e. $vacantRoom[0] or $vacantRoom['ID'] and send get class id i.e. $_GET['courseID']
            if($collide == 'no')
            {
              echo "<td><a href='roomSelected.php?collide=".$collide."&room_id=".$room["ID"]."&course_id=".$_GET['courseID']."' class='btn btn-primary'>Book room</a></td>";
            }
            else
            {
              echo "<td><a href='roomSelected.php?collide=".$collide."&room_id=".$room["ID"]."&course_id=".$_GET['courseID']."' class='btn btn-info'>Request room</a></td>";
            }
            echo "</tr>";
          } 
        }
        else
          {
            if($collide == 'yes')
            {
              echo "<tr><td colspan=".$count.">All classrooms are available to you<td></tr>";
            }
            else
            {
              echo "<tr><td colspan=".$count.">There are not rooms available for this class time.<td></tr>";
            }
          }
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

      <?php
        if(!$checkIfBooked)
        {
          echo "<h3>Available classes</h3>";
        }
      ?>
      <table class="table">
        <thead class='thead'>
          <tr>
            <?php 
            if(!$checkIfBooked)
            {
              // get the heading for the table to be shown
              $count = heading($properties);  
            }
            else
            {
              echo "<h2>The class is already registered. Please submit the button to cancel registration</h2>";
              echo "<a href='#' class='btn btn-warning'>Submit</a>";
            }
            
            ?>
          </tr>
        </thead>
        <tbody>
      <?php
      if(!$checkIfBooked)
      {
        // getting the IDs of the occupied rooms
        $occupied = $rooms->getOccupiedRooms($_GET['courseID']);
        // getting all the fields from database of all the occupied rooms
        $occupiedRooms = $rooms->getFullRow($occupied);
        // getting the IDs of the vacant rooms
        $vacant = $rooms->getVacantRooms($occupied);
        // getting all the fields from the database of all the vacant rooms
        $vacantRooms = $rooms->getFullRow($vacant);
        displayRooms($vacantRooms, 'no',$count);
      }
        
        //print_r($occupiedRooms);
        //print_r($vacantRooms);
       ?>
            
          </tbody>
       </table>
<!-- Classes that are not available -->
      <?php
        if(!$checkIfBooked)
        {
          echo "<h3>Classes you can request</h3>";
        }
      ?> 
      <table class="table">
        <thead class='thead'>
          <tr>
            <?php
            if(!$checkIfBooked)
            {
              // get the heading 
              heading($properties);  
            }
            
            ?>
          </tr>
        </thead>
        <tbody>
      <?php
      if(!$checkIfBooked)
      {
        displayRooms($occupiedRooms, 'yes',$count);
      }
       ?>
            
          </tbody>
       </table>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
  