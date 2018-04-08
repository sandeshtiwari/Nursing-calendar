<?php
require 'config/config.php';
require "classes/Admin.php";
require "classes/Room.php";
if($_SESSION['privilege'] != 'admin' || !isset($_SESSION['email']))
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

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="admin_page.php">Nursing Admin</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Rooms">
          <a class="nav-link" href="admin_page.php">
            <i class="fa fa-fw fa-th"></i>
            <span class="nav-link-text">Rooms</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Calendar">
          <a class="nav-link" href="admin_calendar.php">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Master Calendar</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Collision">
          <a class="nav-link" href="collision.php">
            <i class="fa fa-minus-circle"></i>
            <span class="nav-link-text">Collision</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Students">
          <a class="nav-link" href="show_students.php">
            <i class="fa fa-fw fa-graduation-cap"></i>
            <span class="nav-link-text">Students</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Teachers">
          <a class="nav-link" href="show_teachers.php">
            <i class="fa fa-fw fa-leanpub"></i>
            <span class="nav-link-text">Teachers</span>
          </a>
        </li>

        <!--
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
          <a class="nav-link" href="tables.html">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Tables</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-wrench"></i>
            <span class="nav-link-text">Components</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents">
            <li>
              <a href="navbar.html">Navbar</a>
            </li>
            <li>
              <a href="cards.html">Cards</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-file"></i>
            <span class="nav-link-text">Example Pages</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseExamplePages">
            <li>
              <a href="login.html">Login Page</a>
            </li>
            <li>
              <a href="register.html">Registration Page</a>
            </li>
            <li>
              <a href="forgot-password.html">Forgot Password Page</a>
            </li>
            <li>
              <a href="blank.html">Blank Page</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Menu Levels">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMulti" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-sitemap"></i>
            <span class="nav-link-text">Menu Levels</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseMulti">
            <li>
              <a href="#">Second Level Item</a>
            </li>
            <li>
              <a href="#">Second Level Item</a>
            </li>
            <li>
              <a href="#">Second Level Item</a>
            </li>
            <li>
              <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMulti2">Third Level</a>
              <ul class="sidenav-third-level collapse" id="collapseMulti2">
                <li>
                  <a href="#">Third Level Item</a>
                </li>
                <li>
                  <a href="#">Third Level Item</a>
                </li>
                <li>
                  <a href="#">Third Level Item</a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Link">
          <a class="nav-link" href="#">
            <i class="fa fa-fw fa-link"></i>
            <span class="nav-link-text">Link</span>
          </a>
        </li>
      -->
      </ul>


      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        
        <!--
        <li class="nav-item">
          <form class="form-inline my-2 my-lg-0 mr-lg-2">
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Search for...">
              <span class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </span>
            </div>
          </form>
        </li>  -->

<!-- this is for the registation button -->
  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link">
            <?php
           

$setting;
$open = "yes";
$close = "no";

$sql = "SELECT register_permission FROM semester WHERE ID = 1";

$result = mysqli_query($con, $sql);

  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

          $setting = $row['register_permission'];


if($setting == $open){

  echo " <input type='button' class = 'btn btn-success' data-toggle = 'modal' data-target = '#myModal' value = 'Registration Open'>  ";
}

elseif($setting == $close){

  echo " <input type='button' class = 'btn btn-danger' data-toggle = 'modal' data-target = '#myModal' value = 'Registration Closed'> ";
}    

?>
          </a>
        </li>
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
          <a href="collision.php"><h3>Collisions</h3></a>
        </li>
      </ol>
      <?php
        if(isset($_GET['accessGranted']))
            {
              echo "<div class='alert alert-success'>Classroom access given successfully!</div>";
            } 
        else if(isset($_GET['deleted']))
        {
          echo "<div class='alert alert-success'>Request successfully deleted!</div>";
        } 
      ?>
      <div class="row">
        <div class="col-12">
          <div class='card mb-3'>
          <?php
            $admin = new Admin($con, $_SESSION['email']);
            $requests = $admin->giveRequestingClasses();
            $semsester_id = $admin->getLatestSem();
            // get rooms with collisions as key and details of the class as value
            if(!empty($requests))
            {
              // displaying collisions
              echo "<div id='accordion'>";
              echo "<div class='card'>";
              foreach($requests as $week=>$request)
              {
                $weekDates = $admin->giveWeekStartEnd($week, $semsester_id);
                echo "<div class='card-header' id='headingOne'>";
                echo "<h3 class='mb-0'>";
                echo "<button class='btn btn-link' data-toggle='collapse' data-target='#".$week."' aria-expanded='false' aria-controls='collapse".$week."'>";
                echo "Week - ".$weekDates['start_date']." / ".$weekDates['end_date']." &#9662;&#9652;";
                echo "</button>";
                echo "</h3></div>";
                echo "<div id='".$week."' class='collapse' aria-labelledby='".$week."' data-parent='#accordion'>
                <div class='card-body'>";
                //print_r($request);
                foreach($request as $requestDetails)
                {
                  echo "<div class= 'row'>";
                  echo "<div class='col-12'>";
                  echo "<div class='card mb-2'>";
                  //print_r($requestDetails);
                  if($requestDetails['M'] == 'yes')
                  {
                    displayClasses($con, $requestDetails['Course_ID'], "Monday", $requestDetails['Room_ID'], $week,$semsester_id);
                  }
                  if($requestDetails['T'] == 'yes')
                  {
                    displayClasses($con, $requestDetails['Course_ID'], "Tuesday",$requestDetails['Room_ID'], $week,$semsester_id);
                  }
                  if($requestDetails['W'] == 'yes')
                  {
                    displayClasses($con, $requestDetails['Course_ID'], "Wednesday",$requestDetails['Room_ID'], $week,$semsester_id);
                  }
                  if($requestDetails['R'] == 'yes')
                  {
                    displayClasses($con, $requestDetails['Course_ID'], "Thursday",$requestDetails['Room_ID'], $week,$semsester_id);
                  }
                  if($requestDetails['F'] == 'yes')
                  {
                    displayClasses($con, $requestDetails['Course_ID'], "Friday",$requestDetails['Room_ID'], $week,$semsester_id);
                  }
                  echo "</div>";
                  echo "</div>";
                  echo "</div>";
                }
                echo "</div></div>";
              }
              echo "</div>";
              echo "</div>";
            }
            else
            {
              // display message if no collision exists
              echo "<div class='card-header'>No collisions to display.</div>";
            }
          ?>
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
    <script src="js/admin_js.js"></script>
  </div>
</body>

</html>
