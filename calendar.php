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
		$display = "<a href='admin_page.php'>My Admin Page</a>";
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
  $json = $person->getJSON();
?>
<!DOCTYPE html>

<html>

<head>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />

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

 #calendar {

  width: 650px;

  margin: 0 auto;

  }

</style>

</head>


<body>

 <h2>Nursing Calendar</h2>
 <a href="logout.php">Logout</a>
 <?php
 	echo "<br/>".$display;
 ?>
 <br/>
 <br/>

 <div id='calendar'></div>

</body>


</html>