<?php

  require 'check_privilege.php';
  require 'classes/Teacher.php';
  if ($_SESSION['privilege'] != 'teacher' || isset($_SESSION['email']))
  {
    header("locaton: index.php");
  }

  if($_SESSION['privilege'] == 'teacher')
  {
    $person = new Teacher($con, $_SESSION['email']);
  }
  else if($_SESSION['privilege'] == 'lead')
  {
    $person = new Teacher($con, $_SESSION['email']);
  }
  else
  {
    $person = new Student($con, $_SESSION['email']);
  }
  $json = $person->getJSON();
?>

<!DOCTYPE html>

<html>

<head>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script>
 $(document).ready(function() {
  $('#nurcalendar').fullCalendar({ 
        eventRender: function(event, element, view) {
        var theDate = event.start
        var endDate = event.dowend;
        var startDate = event.dowstart;
        
        if (theDate >= endDate) {
                return false;
        }

        if (theDate <= startDate) {
          return false;
        }
        
        },

        defaultView: 'month',
        nowIndicator: true,
        header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
        },
        //defaultDate: '2016-01-15T16:00:00',
        events: giveEvents()
  });
  function giveEvents()
  {
    var data = <?php echo $json;?>;
    console.log(data[0]);
    for(i = 0; i < data.length; i++)
    {
      data[i]['dowstart'] = new Date(data[i]['dowstart']);
      data[i]['dowend'] = new Date(data[i]['dowend']);
    }
    return data;
  }
});
</script>
<style>

 body {
  margin-top: 40px;
  text-align: center;
  font-size: 14px;
  font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
  }

 #nurcalendar {
  width: 650px;
  margin: 0 auto;
  }
</style>

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
        

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Calendar">
          <a class="nav-link" href="crnTeachView.php">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Calendar</span>
          </a>
        </li>

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Add Notes">
          <a class="nav-link" href="crn_notes.php">
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

          <embed  height = "550%" width = "100%" scrolling = "no" src="styles/caltest.php">





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
