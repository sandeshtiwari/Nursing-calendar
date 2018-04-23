<?php

require 'check_privilege.php';
require "classes/Teacher.php";
require "classes/Room.php";
if($_SESSION['privilege'] != 'lead' || !isset($_SESSION['email']))
{
  header("Location: index.php");
}
function displayClasses($con, $requesting_course, $day, $room_id, $week_id, $semester_id)
{
  $admin = new Admin($con, $_SESSION['email']);
  $collidingCourse = $admin->giveCollidingCourse($room_id, $week_id, $day, $semester_id);
  echo "<table class='table table-hover'>";
  echo "<thead><tr><th>Requests on ".$day."</th>";
  echo "<td><a href='resolveCollision.php?delete=true&course_id=".$requesting_course."&room_id=".$room_id."&week_id=".$week_id."&day=".$day."&semester_id=".$semester_id."' class='btn btn-outline-secondary'>Delete Request</a></td>";
  echo "</tr></thead>";
  echo "<tbody>";
  echo "<tr>";
  echo "<td>".$requesting_course." ".$admin->giveCourseName($requesting_course)." <strong>requested</strong> ". $admin->giveRoomName($room_id)."</td>";
  echo "<td><a href='resolveCollision.php?override=true&collidingCourse=".$collidingCourse."&course_id=".$requesting_course."&room_id=".$room_id."&week_id=".$week_id."&day=".$day."&semester_id=".$semester_id."' class='btn btn-secondary'>Give Access</a></td>";
  echo "</tr>";
  echo "<tr>";
  if(!empty($collidingCourse))
  {
    echo "<td>".$collidingCourse." ".$admin->giveCourseName($collidingCourse)." has <strong>booked</strong> ". $admin->giveRoomName($room_id)."</td>";  
    echo "<td><a href='resolveCollision.php?move=true&room_id=$room_id&course_id=$collidingCourse&day=$day&week_id=$week_id&semester_id=$semester_id' class='btn btn-secondary'>Move class </a></td>";
  }
  else
  {
    echo "<td>The course is not colliding with any courses for this day but is has a range of weeks that is requested</td>";
  }
  echo "</tr>";
  echo "</tbody>";
  echo "</table>";
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

<style>
.btn-primary {
      background: #660000;
      color: #ffffff;
  }
  .btn-danger{
  padding: 0;
}

.btn-success{
  padding: 0;
}

.bg-dark{
  background: #660000 !important;
}

.navbar-sidenav{
  background: #660000 !important;
}
#sidenavToggler{
  background: #660000 !important;
}
#logo{
    height: 35px;
   
}
#mainNav.navbar-dark .navbar-collapse .navbar-sidenav > .nav-item > .nav-link {
    color: #e9ecef;
}
.navbar-dark .navbar-nav .nav-link {
    color: #e9ecef;
}
</style>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
<!-- sweet alert boxes cdn  

<script src="http://tristanedwards.me/u/SweetAlert/lib/sweet-alert.js"></script>
<link href="http://tristanedwards.me/u/SweetAlert/lib/sweet-alert.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

-->
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="admin_page.php"><img id="logo" src="ulm_logo_new.png">Nursing Admin</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Requests">
          <a class="nav-link" href="lead_register.php">
            <i class="fa fa-check-circle"></i>
            <span class="nav-link-text">Reserve Classroom</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Modify Requests">
          <a class="nav-link" href="modify_event.php">
            <i class="fa fa-edit"></i>
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
          <a class="nav-link" href="lead_Notes.php">
            <i class="fa fa-sticky-note"></i>
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
        
       


<!-- this is for the registation button -->
  

<!-- this is for the registation button -->

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
    
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="modify_event.php"><h3>Modify room reservations</h3></a>
        </li>
      </ol>


<div class="card">
  <div class="card-header">
   <h4> All Room Reservations By Class:</h4>
  </div>
      <?php
$teacher = new Teacher($con, $_SESSION['email']);
//getting teacher's CWID:
$myID = $teacher ->getID($_SESSION['email']);
//getting all the classes taught by the professor
$classes = $teacher -> classesNow($myID);

if(!empty($classes)){
//If we have classes, put them in accordion toggle.
echo "<div id='accordion' >";
  foreach($classes as $class => $details){
          $Prefix = $details['Prefix'];
          $Number = $details['Number'];
          $CRN = $details['Course_ID'];
          $Title = $details['Title'];

        echo"  <div class='card'>
          <div class='card-header' id='headingOne'>
            <h5 class='mb-0'>
              <button class='btn btn-link' data-toggle='collapse' data-target='#".$Prefix.$Number."' aria-expanded='false' aria-controls='".$Prefix.$Number."'>
                $Title
              </button>
            </h5>
          </div>";

          /**********************************************
            the inside will have 2 tables: 
            Already confirmed reservations and 
            the table with Pending requests
           *********************************************/ 
            //first table is for confirmed reservations:

          $booked = $teacher -> giveBooked($CRN);

           echo"  <div id='".$Prefix.$Number."' class='collapse' aria-labelledby='headingOne' data-parent='#accordion'>
              <div class='card-body'>";

          if(!empty($booked)){

         
                
                echo "<h5 class='card-title' >Confirmed Reservations:</h5>";
              echo "<table class='table table-striped table-responsive-md btn-table'>";
              echo "<!--Table head-->
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days</th>
                        <th>Button</th>
                    </tr>
                </thead>
                <!--Table head-->
                 <!--Table body-->
                    <tbody>";
              $row = 1;
                foreach($booked as $booking => $details){
                  $Room = $details['Name'];
                  $room_id = $details['Room_ID'];
                  $start_date = $details['start_date'];
                  $end_date = $details['end_date'];
                  $week_id = $details['week_id'];
                  $M = $details['M'];
                  $T = $details['T'];
                  $W = $details['W'];
                  $R = $details['R'];
                  $F = $details['F'];

                  $days = $teacher->getDays($M, $T, $W, $R, $F);
                  echo "<tr>
                            <th scope='row'>$row</th>
                            <td>$Room</td>
                            <td>$start_date</td>
                            <td>$end_date</td>
                            <td>$days</td>
                           
                            <td>
                            <form method = 'post' action='modefy_helper.php?room=$room_id&week=$week_id&course=$CRN&days=$days'>
                              <button type='submit' name = 'occupied' class='btn btn-primary btn-sm'>Cancel reservation</button>
                            </form>

                           </td>
                           </tr>";   
                         $row++;
              
            }
          echo "</tbody>
          </table>";  




        
               
          }
          else{

            echo "
                <h5 class='card-title'>No Confirmed Reservations</h5>";
            
           
          }


          //Now checking if class has a Pending requests:
           $pending = $teacher -> givePending($CRN);

           if(!empty($pending)){
              echo "<h5 class='card-title' >Pending Requests:</h5>";
              echo "<table class='table table-striped table-responsive-md btn-table'>";
              echo "<!--Table head-->
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days</th>
                        <th>Button</th>
                    </tr>
                </thead>
                <!--Table head-->
                 <!--Table body-->
                    <tbody>";
              $row = 1;
                foreach($pending as $booking => $details){
                  $Room = $details['Name'];
                  $room_id = $details['Room_ID'];
                  $start_date = $details['start_date'];
                  $end_date = $details['end_date'];
                  $week_id = $details['week_id'];
                  $M = $details['M'];
                  $T = $details['T'];
                  $W = $details['W'];
                  $R = $details['R'];
                  $F = $details['F'];
                  $days = $teacher->getDays($M, $T, $W, $R, $F);

                  echo "<tr>
                            <th scope='row'>$row</th>
                            <td>$Room</td>
                            <td>$start_date</td>
                            <td>$end_date</td>
                            <td>$days</td>
                           
                            <td>
                            <form method = 'post' action='modefy_helper.php?room=$room_id&week=$week_id&course=$CRN&days=$days'>
                              <button type='submit' name = 'collision' class='btn btn-primary btn-sm'>Cancel reservation</button>
                            </form>

                           </td>
                           </tr>";   
                         $row++;
              
            }
          echo "</tbody>
          </table>";  
          }




           

           else{
            echo " <h5 class='card-title'>No Pending Reservations</h5>";
              
           }

         echo"  </div>
            </div>
          </div>";



         }
echo "</div>";         
}






else{

echo "<h5 class='card-title'>No Reservations</h5>";

}


?>

</div>

      
     



          
        
<!-- this is for the registation button -->

 <div class="modal fade" id = "myModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Do you want to change the booking status?</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          To change the booking status for requesting rooms, press 'Change Booking Status'. <br>
                          Press Close to exit.
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary" onclick="switchReg()">Change Booking Status</button>
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
    </div>
</body>

</html>
