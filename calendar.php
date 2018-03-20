<?php
  require 'check_privilege.php';
  require 'classes/Teacher.php';
  require 'classes/Student.php';
  require 'classes/Admin.php';
  if(!isset($_SESSION['username']))
  {
    header("Location: index.php");
  }
  $_SESSION['privilege'] = 'admin';
  if(check_student($con))
  {
    $_SESSION['privilege'] = 'student';
  }
  else if(check_teacher($con))
  {
    $_SESSION['privilege'] = 'teacher';
  }
  $display = "";
  if($_SESSION['privilege'] == 'admin')
  {
    $display = "<a href='admin_page.php'>Admin Page</a>";
    $person = new Admin($con, $_SESSION['email']);
  }
  else if($_SESSION['privilege'] == 'teacher')
  {
    $display = "<a href='register.php'>Register Classroom</a>";
    $person = new Teacher($con, $_SESSION['email']);
  }
  else
  {
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
<!DOCTYPE html>

<html>

<head>


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




  $('#calendar').fullCalendar({
        
      

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
  
    background: url('ulm_logo.png');
    background-repeat:repeat-x;
    background-position:top;
    margin-top:0px;
}



}
 
</style>

<style type="text/css" media="print">
   .no-print { display: none; }
</style>

</head>
<div class="no-print">

<body>



<ul>
 
  <a class="navbar-brand" rel="home" href="#" title="University of Louisiana at Monroe">      
  
  <h4 align="center" style="color: #ffffff"> <?php 
  echo "Welcome ". $_SESSION['username']." ";
  ?> 
  </h4>
   
    </a>
 
 


  <li><a href="#home">Home</a></li>
  <li><a href="#news">News</a></li>
  
  
  

  <li style="float:right"><a href="logout.php">Logout</a></li>
  <li style="float:right">

  <?php
  echo "".$display;
 ?>

  </li>
  


  
</ul> 

 <img style="max-width:500px; margin-top: 7px;"
             src="styles/images/Nursing101.png" class="img-circle" align="middle" >
 
 
 <br/>
 <br/>
 <br/>

 <br/>
</div> 
 <div id='calendar'></div>
<br/>
<br/>
<br/>
<br/>

</body>


</html>

<?php

require "footer.php";

?>