<?php
  require "config/config.php";
  require "classes/Room.php";
  require "classes/Teacher.php";
  require "header.php";
  error_reporting(0);
  // authenticating the teacher or the person logged in
  $check = (!isset($_GET['course_id']) && !isset($_GET['day']) && !isset($_GET['week_id']));
  if(($check || (!isset($_POST['courseID']) && !isset($_POST['email']))) && !isset($_SESSION['email']) && $_SESSION['privilege'] == 'student')
  {
    //if(!password_verify($_SESSION['email'], $_POST['email']))
    //{
      header('Location: index.php');
    //}
  }
  if(empty($_GET))
  {
    if(($_POST['start_date'] == 'Select Start-date') || ($_POST['end_date'] == 'Select End-date'))
    {
      header("Location: register.php?date");
    }
    else if($_POST['end_date']<$_POST['start_date'])
    {
      header("Location: register.php?date");
    }
  }
  $rooms = new Room($con);
  $teacher = new Teacher($con, $_SESSION['email']);
  $semester_id = $teacher->getLatestSem();
  // getting all the columns from the rooms table to display
  $properties = $rooms->getRoomProperties();
  // checking if a course is already booked or not
  //$checkIfBooked = $rooms->checkBookStatus($_GET['courseID']);
  //$checkIFRequested = $rooms->checkRequested($_GET['courseID']);
  // funciton to display the heading of the table being displayed
  function heading($properties)
  {
    //going over all the column names except the ID column and making them the header
    for($count = 1 ; $count<sizeof($properties);$count++)
    {
      echo "<th scope='col'>".$properties[$count]."</th>";
    }
    // adding one to the count to calculate the colspan in case of empty table
    echo "<th scope='col'>Book Room</th>";
    echo "<th scope='col'>Request Room</th>";
  }
  // function to display rooms from a list of rooms
  function displayVacantRooms($roomList)
  {
    //echo "here";
    //print_r($roomList);
    foreach($roomList as $room)
    {
      echo "<tr>";
      for($i = 1; $i < (sizeof($room)/2); $i++)
      {
        echo "<td>".$room[$i]."</td>";
      }
      echo "<td><button type = 'button' data-toggle = 'modal' data-target = #".$room['ID']." class='btn btn-outline-primary'>Book room</button></td>";
      echo "<td>All days available</td>";
      echo "</tr>";
    }
  }
  function displayOccupiedRooms($roomList,$con, $occupiedRoomsAndDays, $course_id, $semester_id,$weeks, $adminCheck)
  {
    //echo "here";
    //print_r($roomList);
    //$occupiedRoomIndex = 0;
    $roomObj = new Room($con);
    foreach($roomList as $room)
    {
      echo "<tr>";
      // dividing by two for loop iteration because the array also has the indices as keys
      for($i = 1; $i < (sizeof($room)/2); $i++)
      {
        echo "<td>".$room[$i]."</td>";
      }
      //echo "here";
      //print_r($occupiedRoomsAndDays[$room['ID']]);
      //echo $course_id;
      $showBookButton= $roomObj->checkVacancy($occupiedRoomsAndDays[$room['ID']], $course_id, $semester_id);
      if($showBookButton)
      {
        if($adminCheck == "yes" && $roomObj->checkSelfOccupied($course_id, $room['ID']))
          {
            echo "<td>Needs to be moved from this class</td>"; 
          }
          else
          {
            echo "<td><button type = 'button' data-toggle = 'modal' data-target = #".$room['ID']." class='btn btn-outline-primary'>Book room</button></td>";
          }
        //print_r($occupiedRoomsAndDays[$room['ID']]);
        
      }
      else
      {
        if($adminCheck == "yes" && $roomObj->checkSelfOccupied($course_id, $room['ID']))
          {
            echo "<td>Needs to be moved from this class</td>"; 
          }
          else
          {
            echo "<td>All days booked</td>";
          }
        
      }
      if($adminCheck == "yes")
      {
        $displayName = "Move to Request";
      }
      else
      {
        $displayName = "Request room";
      }
      $roomObj->getOccupiedRoomAndDays($course_id,$semester_id,$weeks);
      // Display the message only if occupied by the same class for all the days
      //echo $room['ID'];
      if($roomObj->checkSelfOccupied($course_id, $room['ID']))
      {
          if($adminCheck == "yes")
          {
            echo "<td>Needs to be moved from this class</td>"; 
          }
          else
          {
            echo "<td>All days booked for this class</td>";  
          }
      }
      // else display to request if all the days are not occupied by the same class trying to book
      else
      {
          echo "<td><button type = 'button' data-toggle = 'modal' data-target = #request".$room['ID']." class='btn btn-outline-info'>".$displayName."</button></td>";
      }
      echo "</tr>";
    }
  }
  function vacantRoomModal($vacantRooms, $con, $semester_id, $course_id, $weeksToBook, $adminCheck, $day)
  {
    //print_r($occupiedRoomAndDays);
    
    foreach($vacantRooms as $vacantRoom)
    {
      //echo $vacantRoom;
      $room = new Room($con);
      echo "<div class='modal fade' id=".$vacantRoom." tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
      echo "<div class='modal-dialog' role='document'>";
      echo "<div class='modal-content'>";
      echo "<div class='modal-header'>Register ".$room->getRoomName($vacantRoom)."</div>";
      echo "<div class='modal-body'>";
      echo "<form class='form-group' action='roomSelected.php' method='POST'>";
      if($adminCheck == 'no')
      {
        $days = $room->getDaysOfWeek($course_id, $semester_id);
        //print_r($days);
        foreach($days as $day => $check)
        {
          if($check == 'yes')
          {
            $name = "";
            if($day == 'M')
            {
              $name = 'Monday';
            }
            else if($day == 'T')
            {
              $name = 'Tuesday';
            }
            else if($day == 'W')
            {
              $name = 'Wednesday';
            }
            else if($day == 'R')
            {
              $name = 'Thursday';
            }
            else if($day == 'F')
            {
              $name = 'Friday';
            }
            echo $name."  <input type='checkbox' name = 'bookDays[]' value = ".$day.">  ";
          }
        }  
      }
      else
      {
        echo $day."  <input type='checkbox' name = 'bookDays[]' value = ".substr($day,0,1).">  ";
      }
      echo "<div class='modal-footer'>";
      echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
      //echo "<input type= 'hidden' name = ".$semester_id." id='book' value='true'>";
      echo "<input type= 'hidden' name = 'room_id' value=".$vacantRoom.">";
      echo "<input type= 'hidden' name = 'course_id' value=".$course_id.">";
      inputWeeks($weeksToBook);
      echo "<input type= 'hidden' name = 'book' value='true'>";
      echo "<input type='submit' class='btn btn-primary' value = 'Confirm'>";
      echo "</div>";
      echo "</form>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
    }
  }
  function inputWeeks($weeks)
  {
    foreach($weeks as $week)
    {
      //echo $week;
      echo "<input type= 'hidden' name = 'weeks[]' value=".$week.">";
    }
  }
  function preOccupedRoomModal($occupiedRoomsAndDays, $con,$semester_id, $course_id, $weeksToBook, $adminCheck, $day)
  {
    foreach($occupiedRoomsAndDays as $occupiedRoom => $days)
    {
      //echo $vacantRoom;
      $room = new Room($con);
      echo "<div class='modal fade' id=".$occupiedRoom." tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
      echo "<div class='modal-dialog' role='document'>";
      echo "<div class='modal-content'>";
      echo "<div class='modal-header'>Register ".$room->getRoomName($occupiedRoom)."</div>";
      echo "<div class='modal-body'>";
      echo "<form class='form-group' action='roomSelected.php' method='POST'>";
      $allDays = $room->fillArrayWithDays($days);
      //print_r($allDays);
      $courseDays = $room->getDaysOfWeek($course_id, $semester_id);
      //print_r($courseDays);
      $courseDaysWithIndex = array();
      foreach($courseDays as $courseDay)
      {
        $courseDaysWithIndex[] = $courseDay;
      }
      $daysAtIndex = 0;
      if($adminCheck == 'no')
      {
        foreach($allDays as $day => $allowedDays)
        {
          // if the room is not occupied the store the name to display
          if($allowedDays == 'no' && $courseDaysWithIndex[$daysAtIndex] == 'yes')
          {
            $name = "";
            if($day == 'M')
            {
              $name = 'Monday';
            }
            else if($day == 'T')
            {
              $name = 'Tuesday';
            }
            else if($day == 'W')
            {
              $name = 'Wednesday';
            }
            else if($day == 'R')
            {
              $name = 'Thursday';
            }
            else if($day == 'F')
            {
              $name = 'Friday';
            }
            echo $name."  <input type='checkbox' name = 'bookDays[]' value = ".$day.">  ";
          }
          $daysAtIndex++;
        }
      }
      else
      {
        echo $day."  <input type='checkbox' name = 'bookDays[]' value = ".substr($day,0,1).">  ";
      }
      
      echo "<div class='modal-footer'>";
      echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
      inputWeeks($weeksToBook);
      echo "<input type= 'hidden' name = 'room_id' value=".$occupiedRoom.">";
      echo "<input type= 'hidden' name = 'book' id='book' value='true'>";
      echo "<input type= 'hidden' name = 'course_id' value=".$course_id.">";
      echo "<input type='submit' class='btn btn-primary' value = 'Confirm'>";
      echo "</div>";
      echo "</form>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";

      // setting up the request modal
      echo "<div class='modal fade' id='request".$occupiedRoom."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
      echo "<div class='modal-dialog' role='document'>";
      echo "<div class='modal-content'>";
      echo "<div class='modal-header'>Request ".$room->getRoomName($occupiedRoom)."</div>";
      echo "<div class='modal-body'>";
      echo "<form class='form-group' action='roomSelected.php' method='POST'>";
      //print_r($days);
      // variable to check if the requested weeks are partially booked by the same class or not
      $partiallyBooked = "yes";
      if($adminCheck == 'no')
      {
        foreach($allDays as $day => $check)
        {
          //echo "here";
          // if the room is not occupied and is not occupied by the course trying to register the store the name to display for registration
          $sameClass = $room->checkBookedBySameClass($course_id, $semester_id, $weeksToBook, $day,$occupiedRoom);
          //echo $check;
          // if the room is not occupied and is not the same class which booked the room for a certain day, then display the days
          if($check == 'yes'&& !$sameClass)
          {
            // if the day can be requested and is not partially occupied by the class that is requesting room
            $partiallyBooked = "no";
            $name = "";
            if($day == 'M')
            {
              $name = 'Monday';
            }
            else if($day == 'T')
            {
              $name = 'Tuesday';
            }
            else if($day == 'W')
            {
              $name = 'Wednesday';
            }
            else if($day == 'R')
            {
              $name = 'Thursday';
            }
            else if($day == 'F')
            {
              $name = 'Friday';
            }
            echo $name."  <input type='checkbox' name = 'requestDays[]' value = ".$day.">  ";
          }
        }
      }
      else
      {
        echo $day."  <input type='checkbox' name = 'bookDays[]' value = ".substr($day,0,1).">  ";
        $partiallyBooked = "no";
      }
      
      $room->getOccupiedRoomAndDays($course_id, $semester_id,$weeksToBook);
      $weeks = $room->getOccupiedWeekClassRoom();
      /*foreach($weeks as $week => $courses)
      {
        foreach($courses as $course => $bookedRooms)
        {
          if($course != $course_id)
          {
            foreach($bookedRooms as $bookedRoom)
            {
              if($bookedRoom == $occupiedRoom)
              {
                  //echo $week." ".$course." ".$bookedRoom." ";
                  echo "<input type= 'hidden' name = 'conflictingWeeks[]' value=".$week.">";
                  echo "<input type= 'hidden' name = 'conflictingCourses[]' value=".$course.">";
              }
            }
          }
        }

      }*/
      if($partiallyBooked == 'yes')
      {
        //echo "true";
        echo "<h6>The range you have selected has some weeks already occupied by the class you selected.</h6>";
      }
      echo "<div class='modal-footer'>";
      echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
      //inputWeeks($room->getOccupiedWeekClassRoom());
      echo "<input type= 'hidden' name = 'room_id' value=".$occupiedRoom.">";
      //echo $occupiedRoom;
      echo "<input type= 'hidden' name = 'course_id' value=".$course_id.">";
      inputWeeks($weeksToBook);
      echo "<input type= 'hidden' name = 'request' value='true'>";
      if($partiallyBooked == 'no')
      {
        echo "<input type='submit' class='btn btn-info' value = 'Confirm'>";  
      }
      echo "</div>";
      echo "</form>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
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
          echo "<h3>Available classes</h3>";
      ?>
      <table class="table">
        <thead class='thead'>
          <tr>
            <?php 
              // get the heading for the table to be shown
              heading($properties);  
            ?>
          </tr>
        </thead>
        <tbody>
      <?php
        $adminCheck = "yes";
        if(empty($_GET))
        {
          $weeks = $rooms->getWeeksArray($_POST['start_date'],$_POST['end_date'], $semester_id);
          $courseID = $_POST['courseID'];
          $adminCheck = "no";
        }
        else{
          $weeks = array($_GET['week_id']);
          $courseID = $_GET['course_id'];
          $day = $_GET['day'];
        }
        // get the rooms that are not available to register as keys with array with days as value
        $occupiedRoomsAndDays = $rooms->getOccupiedRoomAndDays($courseID, $semester_id,$weeks);
        //print_r($occupiedRoomsAndDays);
        // getting an array with the rooms that are not available to register to get the vacant rooms 
        $occupiedRooms = $rooms->getRoomFromKeys($occupiedRoomsAndDays);
        //print_r($occupiedRooms);
        // getting the vacant rooms using the occupied rooms
        $vacant = $rooms->getVacantRooms($occupiedRooms, $semester_id);
        // get the completely vacant rooms in an array
        $vacantRooms = $rooms->getRoomFromKeys($vacant);
        //print_r($vacantRooms);
        
        
        // getting the properties for the vacant rooms
        $vacantRoomProperties = $rooms->getFullRow($vacantRooms);
        // getting the modal ready for input for vacant rooms
        vacantRoomModal($vacantRooms,$con, $semester_id, $courseID, $weeks, $adminCheck, $day);
        displayVacantRooms($vacantRoomProperties);
        
        // display all the preoccuped rooms
        $occupiedRoomProperties = $rooms->getFullRow($occupiedRooms);
        preOccupedRoomModal($occupiedRoomsAndDays, $con,$semester_id, $courseID, $weeks, $adminCheck, $day);
        displayOccupiedRooms($occupiedRoomProperties,$con, $occupiedRoomsAndDays, $courseID, $semester_id,$weeks, $adminCheck);
      ?>
            
          </tbody>
       </table>
<!-- Classes that are not available -->
      <?php
        //if(!$checkIfBooked)
        //{
         // echo "<h3>Classes you can request</h3>";
        //}
      ?> 
      <table class="table">
        <thead class='thead'>
          <tr>
            <?php
            //if(!$checkIfBooked)
            //{
              // get the heading 
              //heading($properties);  
            //}
            
            ?>
          </tr>
        </thead>
        <tbody>
      <?php
      //if(!$checkIfBooked)
      //{
        //displayRooms($occupiedRooms, 'yes',$count, $checkIFRequested,  $checkIfBooked,$con);
      //}
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
<?php

require "footer.php";
?>