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
    height: 33px;
}
#mainNav.navbar-dark .navbar-collapse .navbar-sidenav > .nav-item > .nav-link {
    color: #e9ecef;
}
.navbar-dark .navbar-nav .nav-link {
    color: #e9ecef;
}

.btn-classlist{
  background: #6f0029;
  color: #fff;
  margin-top: .5em;
  width: 100%;

}
.btn-delt{
  float: right;
  margin-right: -7em;
  padding: .25em;
}

.btn-edit{
  float :right;
  margin-right: 5em;
  padding: .45em;
  padding-right: 1em;
  padding-left: 1em; 
}



</style>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
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
    
     

<!-- this is for the registation button -->



       
      <div class="container" >   
  <div class="card-header mt-1.5">

    <a href = "admin_notes.php"><h3>Notes to students and teachers</h3></a>
  </div>
  <div class="card-body">
    <form method="post" action="noteHelp.php">
      
      
      <div class="form-group">
        <label for="exampleFormControlSelect1">Course:</label>
        <select class="form-control" id="exampleFormControlSelect1" name="Course">


      <?php

    $admin = new Admin($con, $_SESSION['email']);
     $classes = $admin->giveClasses();

     if(!empty($classes)){

      foreach($classes as $class => $details){

        $Prefix = $details['Prefix'];
        $Number = $details['Number'];
        $Course_ID = $details['Course_ID'];
        $Title = $details['Title'];

         echo "<option value = '$Course_ID'> $Title  </option> ";

      }
    }

   
    
    echo "</select>
       </div>";

  echo "<div class='form-group'>
    <label for='exampleFormControlSelect2'>Week</label>
    <select class='form-control' id='exampleFormControlSelect2' name = 'Week'>";

    $weeks = $admin->weeks();
      if(!empty($weeks)){

      foreach($weeks as $week => $details){

        $start_date = $details['start_date'];
      $end_date = $details['end_date'];
      $ID = $details['ID'];
      

      echo "<option value = $ID>$ID: ($start_date - $end_date) </option> ";

      }

    }
   
   echo " </select>
     </div>";

     ?>
     <div class="form-group">
    <label for="exampleFormControlTextarea1">Note to the students</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" name="Note" rows="3"></textarea>
  </div>
   <button type="submit" name = "save" class="btn btn-primary">Submit</button>
</form>

<h4 align="center">All notes:</h4>

<?php

if(!empty($classes)){

      foreach($classes as $class => $details){

      $Prefix = $details['Prefix'];
      $Number = $details['Number'];
      $Course_ID = $details['Course_ID'];
      $Title = $details['Title'];
      $Name;

     echo " <button class='btn btn-classlist btn-lg ' type='button' data-toggle='collapse' data-target='#$Course_ID' aria-expanded='false' aria-controls='$Course_ID'>
             $Course_ID $Title
          </button><br>

          <div id ='$Course_ID' class='collapse'>
          <table class='table table-hover'>
              <thead>          
              <tr>
                  <th scope='col'>Week</th>
                  <th scope='col'>Note</th>      
                </tr>
              </thead>

              <tbody> ";

      $notes = $admin->dispalyMyNotes($_SESSION['email'], $Course_ID);  
      $myName = $admin->getName($_SESSION['email']);
      $fullName = $myName[0]." ".$myName[1];
      
      if(!empty($notes)){


          foreach($notes as $note => $details){

            $Name = $details['Name'];
            $Note = $details['Note'];
            $Week = $details['Week_ID'];



           echo"<tr>
                  <th scope='row'>$Week</th>
                  <td><strong>$Name</strong>: $Note
                  <div id ='$Note' class='collapse'>

                  <form method='post' action='noteHelp.php?course=$Course_ID&week=$Week'>
                   
                    
                     <textarea method = 'class='form-control' id='exampleFormControlTextarea1' name='NewNote' rows='3'></textarea>
                      <button type='submit' name = 'update' class='btn btn-primary btn-sm'>Submit</button>

                  
                    </form>      
                  

                 </div></td>";

                  if($fullName == $Name){
                 echo " <td>

                 <button class='btn btn-warning btn-sm btn btn-edit' type='button' data-toggle='collapse' data-target='#$Note' aria-expanded='false' aria-controls='$Note'>Edit </button>                 
                  <a onclick='return confirm('are you sure?') href='noteHelp.php?id=delete&week=$Week&course=$Course_ID' class = 'btn btn-danger btn-rounded btn-delt'> Delete </a><td>
                        ";
                  }           
               echo " </tr>";

          }



      } 

      else{

        echo"<tr>
              <th scope='row'>0</th>
              <td>No notes has been posted</td>           
            </tr>";

      }

     echo "</tbody>
            </table>
            </div>
            "; 

      }

    }



?>


    
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