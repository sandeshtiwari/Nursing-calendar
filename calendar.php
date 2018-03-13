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
  echo "Welcome ". $_SESSION['username']."<br/>";
  echo $_SESSION['privilege'];
  $display = "";
  if($_SESSION['privilege'] == 'admin')
  {
    $display = "<a href='admin_page.php'></a>";
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

        defaultView: 'month',
        nowIndicator: true,
        theme: true,
        default: 'false',


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

  background-color: #F5F5F5;
  }

 #calendar {

  width: 650px;

  margin: 0 

  auto;

  }

  ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #AA1F01;
}

li {
    float: left;
    border-right:1px solid #bbb;
}

li:last-child {
    border-right: none;
}

li a {
    display: block;
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
    margin-top:90px;
}



}
 
</style>

</head>


<body>



<ul>
  
<a class="navbar-brand" rel="home" href="#" title="University of Louisiana at Monroe">
        <img style="max-width:140px; margin-top: 7px;"
             src="ulm_logo.png" class="img-circle" align="middle" >
    </a>
 

  

  <li><a href="#home">Home</a></li>
  <li><a href="#news">News</a></li>
 

  <li style="float:right"><a href="logout.php">Logout</a></li>
  <li style="float:right"><a href="admin_page.php">My Admin Page</a></li>
</ul> 

 <h2 style="color:#AA1F01";>Nursing Calendar</h2>
 
 <?php
  echo "<br/>".$display;
 ?>
 <br/>
 <br/>

 <div id='calendar'></div>





</body>


</html>