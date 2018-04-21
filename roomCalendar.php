<?php
  require 'config/config.php';
  require 'classes/Room.php';
  //error_reporting(0);
 
    $room = new Room($con);
    $id = $_GET['id'];
   
  $json = $room->getJSON($id);
?>
<!DOCTYPE html>

<html>

<head>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />

<link rel="stylesheet" href="../jquery-ui/jquery-ui.css" />

<link rel="stylesheet" href="../jquery-ui/jquery-ui.css" />

<link rel="stylesheet" href="../jquery-ui/jquery-ui.theme.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script>


 $(document).ready(function() {

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

        //defaultView: 'agendaWeek',
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
</div> 
<br>
<div id='calendar'></div>
<br/>
<br/>
<br/>
<br/>

</body>


</html>
