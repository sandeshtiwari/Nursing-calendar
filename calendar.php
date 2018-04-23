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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />

<link rel="stylesheet" href="jquery-ui/jquery-ui.css" />

<link rel="stylesheet" href="jquery-ui/jquery-ui.css" />

<link rel="stylesheet" href="jquery-ui/jquery-ui.theme.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script>


 $(document).ready(function() {

  i = 0;

  $('#calendar').fullCalendar({    
        // when the event is clicked show the day of the event and change the view  to day
        eventClick: function(calEvent, jsEvent, view){
            jsEvent.preventDefault();
            // Go to and show day view for start date of clicked event
            $('#calendar').fullCalendar('gotoDate', calEvent.start);
            $('#calendar').fullCalendar('changeView', "agendaDay");
        },
        // when the day is clicked show the day clicked by changing the view to day
        dayClick: function(date, jsEvent, view){
            $('#calendar').fullCalendar('changeView', 'agendaDay', date);
        }, 
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
        windowResize: function(view) {
  },


   //eventColor: '#488016',
   //eventColor : giveColor(i),
   
   eventTextColor: 'black',
  

    customButtons: {
    myCustomButton: {
      text: 'Print',
      click: function() {

     $('.fc-agendaWeek-button').hide();
     $('.fc-agendaDay-button').hide();
     $('.fc-month-button').hide();
     $('.fc-prev-button').hide();
     $('.fc-next-button').hide();
     $('.fc-today-button').hide();
     $('.fc-myCustomButton-button').hide();
      window.print();
     $('.fc-agendaWeek-button').show();
     $('.fc-agendaDay-button').show();
     $('.fc-month-button').show();
     $('.fc-prev-button').show();
     $('.fc-next-button').show();
     $('.fc-today-button').show();
     $('.fc-myCustomButton-button').show();
        
    
       
      }
    }
  },

        defaultView: 'month',
        nowIndicator: true,
        theme: true,
        default: 'false',


         
        header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
        },
        
        buttonText: {
        prev: '<',
        next: '>',
        today: 'Today',
        month: 'Month',
        agendaWeek: 'Week',
        agendaDay: 'Day',
        },

         footer: {
         right: 'myCustomButton'
        },
        //defaultDate: '2016-01-15T16:00:00',
        events: giveEvents(),
       


          
  });
  function giveEvents()
  {
    var data = <?php echo $json;?>;
    console.log(data[0]);
    for(i = 0; i < data.length; i++)
    {
      data[i]['dowstart'] = new Date(data[i]['dowstart']);
      data[i]['dowend'] = new Date(data[i]['dowend']);
      data[i]['color'] = giveColor(i);
      //giveColor(i);
    }
    return data;
  }

  function giveColor(i){
    var Pink = '#BB606B'; //Green
    var Green = '#4C693A'; //red
    var Blue = '#97B2A0'; //blue
    var Yelow = '#d49a29'; //yeallow
    

    number = i%4;


    if(number == 1){colorID = Pink}
    
    if(number == 2){colorID = Green}


    if(number == 3){colorID = Blue}
    

    if(number == 0){colorID = Yelow}  
    i++;      

    return colorID;

  }
});
</script>
<style>

 body {

  margin-top: 0px;

  text-align: center;

  font-size: 14px;

  font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;

  background-color: #F5F5F5;
  }

  h4 { 
    display: block;
    font-size: 1em;
    margin-top: 1.0em;
    margin-bottom: 1.0em;
    margin-left: 4.0em;
    margin-right: 0;
    font-weight: bold;
}

  .footer {
   position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  padding: 0px;
  background-color: #000000;
  text-align: center;
}

 #calendar {

  width: 650px;

  margin: 0 

  auto;

  }

  ul {

    list-style-type: none;
    padding: 0;
    overflow: hidden;
    background-color: #6f0029;
}

li {
    float: left;
     margin-top: 1.0em;
    margin-bottom: 1.0em;
    border-right:1px solid #bbb;
}

li:last-child {
   margin-top: 1.0em;
    margin-bottom: 1.0em;
    border-left: 1px solid #bbb;
}

li a {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover:not(.active) {
    background-color: #EBAF0F;
}

.active {
    background-color: #870505;
    
}
img {  
  
    
    background-repeat:repeat-x;
    background-position:top;
    margin-top:0px;
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

<style type="text/css" media="print">
   .no-print { display: none; }
</style>

</head>
<div class="no-print">

<body>
</div> 
<br>
<div id='calendar'></div>
    <br/>
    <br/>
    <br/>
    <br/>


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


</br></br></br>


    <div class = "no-print">

        <?php

        require "footer.php";

        ?>

    </div>


</body>


</html>

