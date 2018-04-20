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

<style>
  .btn-primary {
      background: #6f0029;
      color: #ffffff;
  }
  .btn-danger{
  padding: 0;
}

.btn-success{
  padding: 0;
}

.bg-dark{
  background: #6f0029 !important; 
}

.navbar-sidenav{
  background: #6f0029 !important;
}
#sidenavToggler{
  background: #6f0029 !important;
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
    <a class="navbar-brand" href="admin_page.php"><?php echo "Welcome, ". $_SESSION['username'] ?></a>
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
          <a class="nav-link" href="Lead_notes.php">
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
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="modify_event.php"><h3>Modify room reservations:</h3></a>
        </li>
      </ol>
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

        .textBox{
          width:100%;
          padding: 4px 8px;
          box-sizing: border-box;
          border: 2px solid #6f0029;
          border-radius: 4px;
        }

        .textBox[type=text]:focus {
          background-color: #ffe5ee;
        }
#mainNav.navbar-dark .navbar-collapse .navbar-sidenav > .nav-item > .nav-link {
    color: #f8f9fa;
}
 .navbar-dark .navbar-nav .nav-link {
    color: #f8f9fa;
    }
    </style>

      <?php
$teacher = new Teacher($con, $_SESSION['email']);
//getting teacher's CWID:
$myID = $teacher ->getID($_SESSION['email']);
//getting all the classes taught by the professor
$classes = $teacher -> classesNow($myID);
if(!empty($classes)){
  echo "<div id='accordion'>";
  $divID = 1;
  $divName = "collaps".$divID;
  foreach($classes as $class => $details){
          $Prefix = $details['Prefix'];
          $Number = $details['Number'];
          $CRN = $details['Course_ID'];
          
        echo "<div class='card'>
              <div class='card-header' id='$divName'>
               <h5 class='mb-0'>               
                <button class='btn btn-link' data-toggle='collapse' data-target='#$divName' aria-expanded='true' aria-controls='$divName'>
                 $Prefix $Number
                </button>
                </h5>
             </div>
          <div id='$divName' class='collapse show' aria-labelledby='$divName' data-parent='#accordion'>
            <div class='card-body'> ";
              echo "<h5 style='text-align: center' >Reserverd rooms:</h5>";
              
              $booked = $teacher -> giveBooked($CRN);
              if(!empty($booked)){
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


/*
                        <td>
                        <button type='button'  name = 'row $row' class='btn btn-danger btn-rounded btn-sm my-0' onclick='process($CRN, $room_id, $week_id);history.go(0)'>Cancel Request</button></td>
                        
                     </tr>";*/
                     $row++;
              
            }
          echo "</table>";  
              }
              else{
                echo "No approved reservations yet";
              }
           
         
           echo " </div> </div>";
           $pending = $teacher -> givePending($CRN);
           echo " <h5 style='text-align: center'>Pending Requests :</h5>";
           if(!empty($pending)){
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
          echo "</table>";  
              }
              else{
                echo " No pending requests";
              }
            echo "</div>";
          //echo "The info is: Prefix: $Prefix, Number: $Number, and CRN: $CRN  .. <br/>";
    }
    echo "</div> ";
}
  
?>

<div id = "name_feedback"></div>
      
     


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
<!-- this is for the registation button -->

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->







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
    <!-- this is for the registation button -->
    <script src="js/modefy.js"></script>
  </div>
</body>

</html>