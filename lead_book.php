<?php
require "config/config.php";
require "classes/Room.php";
require "classes/Teacher.php";
if($_SESSION['privilege'] != 'lead' || !isset($_SESSION['email']))
{
  header("Location: index.php");
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
  <title>Nursing Admin</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
</head>



<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="admin_page.php"><?php echo "Welcome, ". $_SESSION['username']?></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Requests">
          <a class="nav-link" href="lead_register.php">
            <i class="fa fa-fw fa-th"></i>
            <span class="nav-link-text">Reserve Classroom</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Modify Requests">
          <a class="nav-link" href="modify_event.php">
            <i class="fa fa-fw fa-th"></i>
            <span class="nav-link-text">Modify Requests</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Calendar">
          <a class="nav-link" href="teachview.php">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Calendar</span>
          </a>
        </li>

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Add Notes">
          <a class="nav-link" href="#">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Add Notes</span>
          </a>
        </li>
        

      </ul>


      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        
        <li class="nav-item">
          <a class="nav-link" href = "javascript:history.go(-1)"onMouseOver"self.status.referrer;return true" data-target="#exampleModal">
            <i class="fa fa-fw fa-arrow-circle-left"></i>Back</a>
        </li>


        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.html">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Blank Page</li>
      </ol>-->
      <div class="row">
        <div class="col-12">
          
          <style>
            .btn-outline-primary{
              background: #6f0029;
              color: #ffffff;
            }

            .btn-outline-info{
              background: #6f0029;
              color: #ffffff;
            }

            .btn-primary{
              background: #6f0029;
              color: #ffffff;
            }

            .bg-dark{
              background: #6f0029 !important;
            }
            
            .navbar-sidenav{
              background: #6f0029 !important;
            }
            
          </style>


          <?php
  
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
            $teacher = new Teacher($con, $_SESSION['email']);
            if(!$teacher->checkRegistrationStatus())
            {
              echo "<div class='alert alert-info' role='alert'>
                    Note: Registration is off at the moment so even if you book a room, it will be requested.
                    </div>";
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
            function displayOccupiedRooms($roomList,$con, $occupiedRoomsAndDays, $course_id, $semester_id,$weeks, $room_id, $adminCheck)
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
                    if($room_id == $room['ID'])
                    {
                       echo "<td>Needs to be moved</td>"; 
                    }
                    else
                    {
                      echo "<td><button type = 'button' data-toggle = 'modal' data-target = #".$room['ID']." class='btn btn-outline-primary'>Book room</button></td>";  
                    }
                  //print_r($occupiedRoomsAndDays[$room['ID']]);
                }
                else
                {
                    if($room_id == $room['ID'])
                    {
                        echo "<td>Needs to be moved</td>";
                    }
                    else
                    {
                        echo "<td>All days booked</td>";
                    }
                }
                $roomObj->getOccupiedRoomAndDays($course_id,$semester_id,$weeks);
                // Display the message only if occupied by the same class for all the days
                //echo $room['ID'];
                if($roomObj->checkSelfOccupied($course_id, $room['ID']))
                {
                      if($room_id == $room['ID'])
                      {
                          echo "<td>Needs to be moved</td>";
                      }
                    else
                    {
                      echo "<td>All days booked for this class</td>";  
                    }
                        
                }
                // else display to request if all the days are not occupied by the same class trying to book
                else
                {
                    if($adminCheck == 'yes' && ($room_id != $room['ID']))
                      {
                          echo "<td><button type = 'button' data-toggle = 'modal' data-target = #request".$room['ID']." class='btn btn-outline-info'>Move to request</button></td>";
                      }
                    else if($adminCheck == "no")
                    {
                        echo "<td><button type = 'button' data-toggle = 'modal' data-target = #request".$room['ID']." class='btn btn-outline-info'>Request room</button></td>";
                    }
                    else if($adminCheck == "yes" && ($room_id == $room['ID']))
                    {
                        echo "<td>Needs to be moved</td>";
                    }
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
                echo "<input type='submit' class='btn btn-outline-primary' value = 'Confirm'>";
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
                  $week = $weeksToBook[0];
                  $checkBookedForADay = $room->checkVacancyForSingleDay(substr($day, 0, 1), $week, $semester_id, $occupiedRoom);
                  if($checkBookedForADay == "book")
                  {
                    echo $day."  <input type='checkbox' name = 'bookDays[]' value = ".substr($day,0,1).">  ";  
                  }
                  else
                  {
                    echo "Booked by some other class. Please request list the class.";
                  }
                }
                
                echo "<div class='modal-footer'>";
                echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
                inputWeeks($weeksToBook);
                echo "<input type= 'hidden' name = 'room_id' value=".$occupiedRoom.">";
                echo "<input type= 'hidden' name = 'book' id='book' value='true'>";
                echo "<input type= 'hidden' name = 'course_id' value=".$course_id.">";
                $week = $weeksToBook[0];
                $checkBookedForADay = $room->checkVacancyForSingleDay(substr($day, 0, 1), $week, $semester_id, $occupiedRoom);
                if($checkBookedForADay == "book")
                {
                  echo "<input type='submit' class='btn btn-outline-info' value = 'Confirm'>";  
                }
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
                  if($checkBookedForADay == "request")
                  {
                    echo $day."  <input type='checkbox' name = 'bookDays[]' value = ".substr($day,0,1).">  ";  
                  }
                  else
                  {
                    echo "Cannot be requested. Please book if needed";
                  }
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
                $week = $weeksToBook[0];
                $checkBookedForADay = $room->checkVacancyForSingleDay(substr($day, 0, 1), $week, $semester_id, $occupiedRoom);
                if($partiallyBooked == 'no')
                {
                  echo "<input type='submit' class='btn btn-outline-info' value = 'Confirm'>";  
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
                      $room_id = "";
                  if(empty($_GET))
                  {
                    $weeks = $rooms->getWeeksArray($_POST['start_date'],$_POST['end_date'], $semester_id);
                    $courseID = $_POST['courseID'];
                    $adminCheck = "no";
                  }
                  else
                  {
                    $weeks = array($_GET['week_id']);
                    $courseID = $_GET['course_id'];
                    $day = $_GET['day'];
                    $room_id = $_GET['room_id'];    
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
                  displayOccupiedRooms($occupiedRoomProperties,$con, $occupiedRoomsAndDays, $courseID, $semester_id,$weeks, $room_id, $adminCheck);
                ?>
                      
                    </tbody>
                 </table> 
                <table class="table">
                  <thead class='thead'>
                    <tr>
                      </tr>
                  </thead>
                  <tbody>
                     </tbody>
                 </table>
              </div>











      </div>



<!-- this is for the registation button -->

 <div class="modal fade" id = "myModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Do you want to save changes?</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          To confirm your choise, please, press Save Changes. <br>
                          Press Close to exit.
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary" onclick="switchReg()">Save changes</button>
                        </div>
                      </div>
                    </div>
              
            </div>             

         
           
            <div class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- this is for the registation button -->



        </div>
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small> © TeamGamma<?php echo @date("Y"); ?></small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="logout.php">Logout</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <script src="js/admin_js.js"></script>
  </div>
</body>

</html>
