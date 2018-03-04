<?php 
  require "config/config.php";

$room;

$sql = "select Coll_ID, Room from collision where Coll_ID in (Select Coll_ID from collision GROUP by Coll_ID having COUNT(Coll_ID)> 1 ) group by coll_ID;";



$resullt = mysqli_query($con, $sql);



  while ($row = mysqli_fetch_array($resullt, MYSQLI_ASSOC)){

            $collisionID = $row['Coll_ID'];

            $sql1 = "SELECT Course_ID, Room  FROM collision WHERE Coll_ID = ".$collisionID.";";

            $resultData = mysqli_query($con, $sql1);

            echo "<b>Grupe by Collision Id: ".$collisionID."</b><br/>";     

            echo "<table>";
            echo "<tr><th>Course Id</th><th>Room</th><tr>";

              while ($row1 = mysqli_fetch_array($resultData, MYSQLI_ASSOC)){

                echo "<tr><td>";
                echo $row1['Course_ID'];
                echo "</td><td>";
                echo $row1['Room'];
                echo "</td></tr>";


      }
     echo "</table><br/>";

  }

 
  
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

    <center>Collision Page</center>
     

   </div> 


  </body>
</html>