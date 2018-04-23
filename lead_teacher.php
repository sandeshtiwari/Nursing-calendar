<?php
require 'config/config.php';
require "classes/admin.php";
if ($_SESSION['privilege'] != 'admin' || isset($_SESSION['email']))
{
  header("locaton: index.php");
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
  <title>Leader</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

  <link rel="stylesheet" href="css/bootstrap-select.css">
</head>

<style>
.btn-primary {
    background: #660000;
    color: #ffffff;
}
.bg-dark{
  background-color: #660000 !important;
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

body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="admin_page.php"><img id="logo" src="ulm_logo_new.png">Nursing Admin</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Calendar">
          <a class="nav-link" href="admin_calendar.php">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Master Calendar</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Rooms">
          <a class="nav-link" href="admin_page.php">
            <i class="fa fa-fw fa-th"></i>
            <span class="nav-link-text">Rooms</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Assign Lead Teacher">
          <a class="nav-link" href="lead_teacher.php">
            <i class="fa fa-address-book"></i>
            <span class="nav-link-text">Assign Lead Teacher</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Collision">
          <a class="nav-link" href="collision.php">
            <i class="fa fa-minus-circle"></i>
            <span class="nav-link-text">Collision</span>
          </a>
        </li>
         <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Registration Deadline">
          <a class="nav-link" href="registration.php">
            <i class="fa fa-power-off"></i>
            <span class="nav-link-text">Registration Deadline</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Add notes">
          <a class="nav-link" href="admin_notes.php">
            <i class="fa fa-sticky-note"></i>
            <span class="nav-link-text">Add notes</span>
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
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Teachers">
          <a class="nav-link" href="logs.php">
            <i class="fa fa-th-list"></i>
            <span class="nav-link-text">Logs</span>
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
         <li class="nav-item" data-toggle="tooltip" data-placement="right">
          <a class="nav-link">
               <?php

            $admin = new Admin($con, $_SESSION['email']);

            $admin->regBtn();


            ?>
          </a>
        </li>

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
          <h5 style="text-align: center;">The Lead Techers at this moment:</h5>
<!--Table-->



   <?php
$admin = new Admin($con, $_SESSION['email']);
//Getting an array of classes taught for this semester. Class has prefix and number
//Retriving current leaders for each course:
$leaders = $admin->displayLeaders();
$rowID = 1;
if(!empty($leaders)){
  echo "<table class='table table-bordered'>
    <!--Table head-->
    <thead>
        <tr>
            <th>#</th>
            <th>Course</th>
            <th>First Name</th>
            <th>Last Name</th>
        </tr>
    </thead>
    <!--Table head-->
    
<tbody>";
   foreach($leaders as $lead => $details){
    $Prefix = $details['Prefix'];
    $Number = $details['Number'];
    $Fname = $details['Fname'];
    $Lname = $details['Lname'];
    echo " <tr>
            <th scope='row'>$rowID</th>
            <td>$Prefix $Number</td>
            <td>$Fname</td>
            <td>$Lname</td>
        </tr>";
        $rowID++;
    }
    echo " </tbody>
        </table>";
}   
$classes = $admin->giveClasses();
//Printing a form for the class
 if(!empty($classes)){
  
   foreach($classes as $class => $details){
    $Prefix = $details['Prefix'];
    $Number = $details['Number'];
    $String = $Prefix.$Number;
    echo "<form class='form-group ' >";
      echo "<div class='form-group'>";
      echo " <label css = 'text-align:center' > <b>".$Prefix." ".$Number."</b></label>";      
      echo "<div class='form-controle'>";
      echo "<select id= '".$Prefix.$Number."' class='form-control' data-live-search='true' >";
      
      $teachers = $admin->generateOptions($Prefix, $Number);
      if(!empty($teachers)){
        foreach($teachers as $teacher => $details){
        $name = $details['Fname']." ".$details['Lname'];  
        echo "<option value = '$name' >".$details['Fname']." ".$details['Lname']."</option>";
        }
    }
      
   
      echo "</select>";          
      echo "<input type='submit' name = '$String' onclick='myFunction(this.name)' class = 'btn btn-primary' data-toggle = 'modal' data-target = '#myModal' value = 'Assign as Leader' />";
      echo "</div>";
      echo"</div>  
      </form>"; 
    
    }
 }
 
  ?>
 

        
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
    <script src="js/leader_setting.js"></script>
  </div>
    </div>


</body>

</html>