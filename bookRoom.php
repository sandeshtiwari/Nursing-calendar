<?php
  
  require "config/config.php";
  error_reporting(0);





$collisionID;

$sql = "select Course_ID, Coll_ID, Room from collision where Coll_ID in (Select Coll_ID from collision GROUP by Coll_ID having COUNT(Coll_ID)> 1 ) group by coll_ID;";

$resullt = $mysqli_query($con, $sql);

  echo "<table>";
  echo "<tr><th>Collision ID</th><th>Cours ID</th><th>Room</th><tr>";

  while ($row = mysql_fetch_array($resullt, MYSQL_ASSOC)){

    echo "<tr><td>";
    echo $row['Course_ID'];
    echo "</td><td>";
    echo $row['Coll_ID'];
    echo "</td><td>";
    echo $row['Room'];
    echo "</td></tr>";


  }

 echo "</table>";


//********************************************************************************//

/*
    $email = $_SESSION['email'];
    $rooms = $_POST['room'];
    $startTime = $_POST['startT'];
    $endTime = $_POST['endT'];
  
    function booking(){

        if($_POST['submit']){

          print_r($_POST['room']);

          if (isset($_POST['room'])) {
              echo "You chose the following room(s): <br>";

              foreach ($rooms as $room){
                  echo $room."<br />";
              }
          } else {
              echo "You did not choose a room.";
          }

        }
    }
      

    function checkTable(){

     

      if(!isset($this -> rooms)){
        echo "Please select at least one room";
      }

      else if (!isset($this -> startTime)){

        echo "Please select the start time for the booking";
      }

      else if (!isset($this -> endTime)){

        echo "Please select the end time for the booking";

      }

      else{

         booking();
      }



    }
/*
  $name = $_POST['user'];
  $mail = $_POST['mail'];
  $pass = $_POST['pass'];

  $sql = "INSERT into data (name, email, pass) values ('$name', '$mail', '$pass')";

  if($_POST['submit']){

    if (mysqli_query($con, $sql)){

      echo "Data added successfuly";
    }

    else{
      echo "Something went wrong";
    }

  }*/
  
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Admin Page</title>

    <link rel="stylesheet" type="text/css" href="adminStyle.css">
  </head>
  <body>
   
   <div id="header">

    
      <img src="ulm_logo.png" alt="logo" id="logo">
      
      <h1>Admin Page</h1>
     


   </div> 

   <div id="sidebar">

      <ul>
        
        <a href="collisions.php"><li>View Conflicts</li></a>
        <a href="bookRoom.php"><li>Book a room</li></a>
        <li>Set Deadlines</li>
        <a href="calendar.php"><li>View Master Calendar</li></a>
        <a href="admin_page.php"><li>Admin Page</li></a>


      </ul>
     

   </div> 

   <div id="data"><br>

    <center>Add Page</center>
     

    <!-- <form action="bookRoom.php" method="POST">
       Name: <input type="text" name="user"><br>
       Email: <input type="mail" name="mail"><br>
       Password:<input type="password" name="pass"> <br>
       <input type="submit" name="submit" value="Send Info">
       </form>  -->




     <form class="bookForm" action="checkTable()" method="POST">
      
      <h5>Booking settings</h5>
      Room 332<input type="checkbox" name="room[]" id="room" value="Room 332"><br>
      Room 338<input type="checkbox" name="room[]" id="room" value="Room 338"><br>
      Room 340<input type="checkbox" name="room[]" id="room" value="Room 340"><br>
      Room 242<input type="checkbox" name="room[]" id="room" value="Room 242"><br>
      Room 107<input type="checkbox" name="room[]" id="room" value="Room 107 (Auditorium)"><br>

      Start time:<br>
      <input type="time" name="startT"><br>
      End time:<br>
      <input type="time" name="endT"><br><br>

      <input type="submit" name="submit" value="Book a room(s)">

     </form>


     <div id="collisions"></div>
     

   </div> 


  </body>
</html>