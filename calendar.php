<?php
	session_start();
	if(!isset($_SESSION['username']))
	{
		header("Location: index.php");
	}
	echo "Welcome ". $_SESSION['username'];
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
        header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
        },
        //defaultDate: '2016-01-15T16:00:00',
       events: [{
            id: 1,
            title:"Front End",
            start:'10:00', 
            end:  '13:00', 
            dow: [1, 2, 3, 4, 5],
            dowstart: new Date('2016/01/03'),
            dowend: new Date('2016/05/17')
       }
    ]
  });
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

 <br/>

 <div id='calendar'></div>

</body>


</html>