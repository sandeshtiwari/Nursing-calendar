<?php
require 'config/config.php';
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

.logo{
  height:35px;
}
</style>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="admin_page.php"><img id="logo" src="ulm_logo_new.png"><strong><?php echo "Welcome, ". $_SESSION['username']?></strong></a>
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
          <?php
  
  
  

  if($_SESSION['privilege'] != 'lead' || !isset($_SESSION['email']))
  {
    header("Location: index.php");
  }
  $teacher = new Teacher($con, $_SESSION['email']);
?>



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
          if(!$teacher->checkRegistrationStatus())
          {
            echo "<div class='alert alert-info' role='alert'>
                    Note: Registration is off at the moment so even if you book a room, it will be requested.
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
            echo "<form method='POST' action='lead_book.php'>";
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
