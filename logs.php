<?php
require 'config/config.php';
require "classes/Admin.php";
if($_SESSION['privilege'] != 'admin' || !isset($_SESSION['email']))
{
  header("Location: index.php");
}
$admin = new Admin($con, $_SESSION['email']);

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
  <style type="text/css">
    #cwidInput {
    background-position: 10px 12px; /* Position the search icon */
    background-repeat: no-repeat; /* Do not repeat the icon image */
    width: 100%; /* Full-width */
    font-size: 16px; /* Increase font-size */
    padding: 12px 20px 12px 40px; /* Add some padding */
    border: 1px solid #ddd; /* Add a grey border */
    margin-bottom: 12px; /* Add some space below the input */
}

#students {
    /* Remove default list styling */
    list-style-type: none;
    padding: 0;
    margin: 0;
}

#students li a {
    border: 1px solid #ddd; /* Add a border to all links */
    margin-top: -1px; /* Prevent double borders */
    background-color: #f6f6f6; /* Grey background color */
    padding: 12px; /* Add some padding */
    text-decoration: none; /* Remove default text underline */
    font-size: 18px; /* Increase the font-size */
    color: black; /* Add a black text color */
    display: block; /* Make it into a block element to fill the whole list */
}

#students li a:hover:not(.header) {
    background-color: #eee; /* Add a hover effect to all links, except for headers */
}
  </style>
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
.btn-logdwn{
  background: #6f0029;
  color: #ffffff;
  position: absolute;
  right: 160px;
  top: 81px;
  width: 125px;
  padding: 10px;
}

.btn-btndel{
  background: #6f0029;
  color: #ffffff;
  position: absolute;
  right: 20px;
  top: 81px;
  width: 125px;
  padding: 10px;
}

.btn-btndel:hover, .btn-btndel:focus, .btn-btndel:active, .btn-btndel.active, .open>.dropdown-toggle.btn-btndel {
    color: #fff;
    background-color: #f00;
}
</style>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!--delete modal -->
  <script src="http://tristanedwards.me/u/SweetAlert/lib/sweet-alert.js"></script>
  <link href="http://tristanedwards.me/u/SweetAlert/lib/sweet-alert.css" rel="stylesheet"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>



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
            <li class="nav-item" data-toggle="tooltip" data-placement="right">
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
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="logs.php"><h3>Logs</h3></a>
                <a href="#" class = "btn btn-logdwn" id ="export" role='button'>Download Logs
                </a>
                <a href="logs.php?deleteLogs" class = "btn btn-btndel" id = "logdel">Delete logs</a>
        </li>

        <script>
          $("#logdel").click(function(e) {
          e.preventDefault()
            swal({
            title : "",
            text : "Do you want to delete all the logs?",
                type : "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
           },
        function(isConfirm){
          if (isConfirm) {
              //window.location="logut page url"; // if you need redirect page 
              swal("Poof! Your logs have been deleted!", {
              icon: "success",
            });
            window.location="logs.php?deleteLogs";
          } else {
              swal("Cancelled");
          }
            })
        });
        </script>

      </ol>
      <div class="row">
        <div class="col-12">
          <?php
            //$admin = new Admin($con,$_SESSION['email']);
            //echo "<input type='text' id='cwidInput' onkeyup='filter()' placeholder='Search by CWID here..'>";
            if(isset($_GET['deleteLogs']))
            {
                if($admin->deleteLogs())
                {
                    echo "<div class='alert alert-success' role='alert'>
                            Log records successfully deleted!
                        </div>"; 
                }
                else
                {
                    echo "<div class='alert alert-danger' role='alert'>
                            Failed to delete log records.
                        </div>";
                }
            }
            $logs = $admin->giveLogs();
            echo " <div id='dvData'>";
            echo "<table class='table' id='table'>";
            echo "<tr>";
            echo "<th scope = 'col'>CWID</th>";
            echo "<th scope = 'col'>Name</th>";
            echo "<th scope = 'col'>Activity</th>";
            echo "<th scope = 'col'>Course Affected</th>";
            echo "<th scope = 'col'>Room Affected</th>";
            echo "<th scope = 'col'>Week</th>";
            echo "<th scope = 'col'>Date & Time</th></tr>";
            //print_r($logs);
            if(!empty($logs))
            {
              foreach($logs as $log)
              {
                //print_r($log);
                echo "<tr>";
                echo "<td>".$log['CWID']."</td>";
                echo "<td>".$log['Name']."</td>";
                echo "<td>".$log['activity']."</td>";
                echo "<td>".$log['Course_ID']."</td>";
                echo "<td>".$log['Room_ID']."</td>";
                echo "<td>".$log['Week_ID']."</td>";
                echo "<td>".$log['Time']."</td>";
                echo "</tr>";
              }
            }
            else
            {
              echo "<tr><td colspan='7'>No logs records right now</td></tr>";
            }
            echo "</table>";
            echo "</div>";
          ?>



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
                          To change the booking status for booking rooms, press 'Change Booking Status'. <br>
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
    <!-- Scripts ----------------------------------------------------------- -->
        <script type='text/javascript' src='https://code.jquery.com/jquery-1.11.0.min.js'></script>
        <!-- If you want to use jquery 2+: https://code.jquery.com/jquery-2.1.0.min.js -->
        <script type='text/javascript'>
        $(document).ready(function () {
            console.log("HELLO")
            function exportTableToCSV($table, filename) {
                var $headers = $table.find('tr:has(th)')
                    ,$rows = $table.find('tr:has(td)')
                    // Temporary delimiter characters unlikely to be typed by keyboard
                    // This is to avoid accidentally splitting the actual contents
                    ,tmpColDelim = String.fromCharCode(11) // vertical tab character
                    ,tmpRowDelim = String.fromCharCode(0) // null character
                    // actual delimiter characters for CSV format
                    ,colDelim = '","'
                    ,rowDelim = '"\r\n"';
                    // Grab text from table into CSV formatted string
                    var csv = '"';
                    csv += formatRows($headers.map(grabRow));
                    csv += rowDelim;
                    csv += formatRows($rows.map(grabRow)) + '"';
                    // Data URI
                    var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
                $(this)
                    .attr({
                    'download': filename
                        ,'href': csvData
                        //,'target' : '_blank' //if you want it to open in a new window
                });
                //------------------------------------------------------------
                // Helper Functions 
                //------------------------------------------------------------
                // Format the output so it has the appropriate delimiters
                function formatRows(rows){
                    return rows.get().join(tmpRowDelim)
                        .split(tmpRowDelim).join(rowDelim)
                        .split(tmpColDelim).join(colDelim);
                }
                // Grab and format a row from the table
                function grabRow(i,row){
                     
                    var $row = $(row);
                    //for some reason $cols = $row.find('td') || $row.find('th') won't work...
                    var $cols = $row.find('td'); 
                    if(!$cols.length) $cols = $row.find('th');  
                    return $cols.map(grabCol)
                                .get().join(tmpColDelim);
                }
                // Grab and format a column from the table 
                function grabCol(j,col){
                    var $col = $(col),
                        $text = $col.text();
                    return $text.replace('"', '""'); // escape double quotes
                }
            }
            // This must be a hyperlink
            $("#export").click(function (event) {
                // var outputFile = 'export'
                var outputFile = window.prompt("What do you want to name your output file (Note: This won't have any effect on Safari)") || 'export';
                outputFile = outputFile.replace('.csv','') + '.csv'
                 
                // CSV
                exportTableToCSV.apply(this, [$('#dvData>table'), outputFile]);
                
                // IF CSV, don't do event.preventDefault() or return false
                // We actually need this to be a typical hyperlink
            });
        });
    </script>
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
