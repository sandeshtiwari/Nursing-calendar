<?php

  require 'check_privilege.php';
  require 'classes/Teacher.php';
  if ($_SESSION['privilege'] != 'lead' || isset($_SESSION['email']))
  {
    header("locaton: index.php");
  }

  if($_SESSION['privilege'] == 'lead')
  {
    $person = new Teacher($con, $_SESSION['email']);
  }
  else if($_SESSION['privilege'] == 'teacher')
  {
    $person = new Teacher($con, $_SESSION['email']);
  }
  else
  {
    $person = new Student($con, $_SESSION['email']);
  }
 
?>

<!DOCTYPE html>

<html>

<head>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Teacher Notes</title>
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
          <a class="nav-link" href="teacherNotes.php">
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
    
    <div class="container" >   
  <div class="card-header">

    <h3>Notes for students:</h3>
  </div>
  <div class="card-body">
    <form method="post" action="noteHelp.php">
      
      <div class="form-group">
        <label for="exampleFormControlSelect1">Course:</label>
        <select class="form-control" id="exampleFormControlSelect1" name="Course">


          <?php

        $teacher = new Teacher($con, $_SESSION['email']);
        $myId = $teacher->getID($_SESSION['email']);

        $myClasses = $teacher->classesNow($myId);

        if(!empty($myClasses)){

          foreach($myClasses as $class => $details){

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

        $weeks = $teacher->weeks();
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
        <label for="Note">Note</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="Note" rows="3"></textarea>
      </div>
       <button type="submit" name = "save" class="btn btn-primary">Submit</button>
    </form>

    <h4 align="center">All notes:</h4>

    <?php

    if(!empty($myClasses)){

          foreach($myClasses as $class => $details){

            $Prefix = $details['Prefix'];
          $Number = $details['Number'];
          $Course_ID = $details['Course_ID'];
          $Title = $details['Title'];
          $Name;

          echo "

          <button class='btn btn-secondary btn-lg btn-block mt-2' type='button' data-toggle='collapse' data-target='#$Course_ID' aria-expanded='false' aria-controls='$Course_ID'>
              $Title
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

          $notes = $teacher->dispalyMyNotes($_SESSION['email'], $Course_ID);  
          $myName = $teacher->getName($_SESSION['email']);
          $fullName = $myName[0]." ".$myName[1];


          
          if(!empty($notes)){


          foreach($notes as $note => $details){

            $Name = $details['Name'];
            $Note = $details['Note'];
            $Week = $details['Week_ID'];



           echo"<tr>
                  <th scope='row'>$Week</th>
                  <td>$Name: $Note
                  <div id ='$Note' class='collapse'>

                  <form method='post' action='noteHelp.php?course=$Course_ID&week=$Week'>
                   
                    
                     <textarea method = 'class='form-control' id='exampleFormControlTextarea1' name='NewNote' rows='3'></textarea>
                      <button type='submit' name = 'update' class='btn btn-primary btn-sm'>Submit</button>

                  
                    </form>      
                  

                 </div></td>";

                  if($fullName == $Name){
                 echo " <td>

                 <button class='btn btn-warning btn-sm' type='button' data-toggle='collapse' data-target='#$Note' aria-expanded='false' aria-controls='$Note'>Edit </button>                 
                  <a onclick='return confirm('are you sure?') href='noteHelp.php?id=delete&week=$Week&course=$Course_ID' class = 'btn btn-danger btn-rounded btn-sm my-0'>Delete</a><td>
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
