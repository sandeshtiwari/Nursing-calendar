<!DOCTYPE html>

<html>

<head>
<div class = "no-print">
 <?php
  require 'check_privilege.php';
  require 'classes/Teacher.php';
  require 'classes/Student.php';
  require 'classes/Admin.php';
  require 'head.php';
  if(!isset($_SESSION['username']))
  {
    header("Location: index.php");
  }
    $teacher = new Teacher($con, $_SESSION['email']);
$status= $teacher->checkRegistrationStatus();
  $_SESSION['privilege'] = 'admin';
  if(check_student($con))
  {
    $_SESSION['privilege'] = 'student';
  }
  
  else if(check_lead($con))
  {
    $_SESSION['privilege'] = 'lead';
  }
  else if (check_teacher($con)){
    $_SESSION['privilege'] = 'teacher';
  }
    
  $display = "";
  if($_SESSION['privilege'] == 'admin')
  {
    header("location:admin_calendar.php");
  }
   else if($_SESSION['privilege'] == 'teacher'){
    header("location: crnTeachView.php");
   }
  else if($_SESSION['privilege'] == 'lead')
  {
   //test to see if registration is open or closed 
    
          header("location:lead_register.php?hyaa='$status'");
      
  }
  else
  {
    //$display = "<a href = 'student_notes.php'>View notes</a>";
    $person = new Student($con, $_SESSION['email']);
  }
  // if the admin wants to view a specific student's calendar
  if(isset($_GET['email']) && isset($_GET['student']) && ($_SESSION['privilege'] == 'admin'))
  {
    $person = new Student($con, $_GET['email']);
    echo "<h2>".$_GET['student']."'s</h2>";
  }
  else if(isset($_GET['email']) && isset($_GET['teacher']) && ($_SESSION['privilege'] == 'admin'))
  {
    $person = new Teacher($con, $_GET['email']);
    echo "<h2>".$_GET['teacher']."'s</h2>";
  }
  $json = $person->getJSON();
?>

</div>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />



<style>
 body {
  margin-top: 0px;
  text-align: center;
  font-size: 14px;
  font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
  background-color: #F5F5F5;
  }

.tbl1{
  width: 65%;
  border: 2px solid #6f0029;
    margin: auto;
    text-align: left;
}
.tbl1 td{
  line-height: 1.5;
    display: inline-block;
    vertical-align: middle;
}
.blockquote-footer, .mb-0, .blockquote, .card{
  background-color: #F5F5F5;
}
}
 
</style>


</head>
<div class="no-print">

<body>
</div> 



 <div class="container-fluid">
    <div class="row">

      <div class="col">

<?php
$myId = $person -> getID($_SESSION['email']);
$myClasses = $person ->getMyClasses($myId);
$weekId = $person ->weekID();
echo "<div class='card' >
       <div class='card-header'><h3>Notes for week $weekId</h3></div>
       <div class='card-body'>";
  if(!empty($myClasses)){
        foreach($myClasses as $class => $details){
          $CRN = $details['Course_ID'];
          $title = $person ->getCourseName($CRN);
          echo "<h3><u>$title</u></h3>";
          $notesForThisClass = $person-> selectNotes($CRN, $weekId);
          if(!empty($notesForThisClass)){
              foreach($notesForThisClass as $note => $details){
                $Note = $details['Note'];
                $Name = $details['Name'];
                echo "<blockquote class='blockquote'>
                      <p class='mb-0'>$Note</p>
                      <footer class='blockquote-footer'><cite title='Source Title'>$Name</cite></footer>
                    </blockquote>";               
              }
            }
            else {
              echo "<blockquote class='blockquote'>
                      <p class='mb-0'>No notes for this Week</p>
                     
                    </blockquote>";
            }
          
         }
         echo "</div></div></div>";
  }  
  $weekId ++;
 echo "<div class='col'>
 <div class='card' '>
       <div class='card-header'><h3>Notes for week $weekId</h3></div>
       <div class='card-body'>";
  if(!empty($myClasses)){
        foreach($myClasses as $class => $details){
          $CRN = $details['Course_ID'];
          $title = $person ->getCourseName($CRN);
          echo "<h3><u>$title</u></h3>";
          $notesForThisClass = $person-> selectNotes($CRN, $weekId);
          if(!empty($notesForThisClass)){
              foreach($notesForThisClass as $note => $details){
                $Note = $details['Note'];
                $Name = $details['Name'];
                echo "<blockquote class='blockquote'>
                      <p class='mb-0'>$Note</p>
                      <footer class='blockquote-footer'><cite title='Source Title'>$Name</cite></footer>
                    </blockquote>";   
              }
            }
            else {
              echo "<blockquote class='blockquote'>
                      <p class='mb-0'>No notes for this Week</p>
                      
                    </blockquote>";
            }
          
         }
         echo "</div></div><div>";
  }              
?>
</div>
</div>
</div>

</br></br></br>


    <div class = "no-print">

        <?php
        require "footer.php";
        ?>

    </div>


</body>


</html>
