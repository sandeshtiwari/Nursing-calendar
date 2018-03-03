<?php
  require "config/config.php";
  error_reporting(0);
  // authenticating the teacher or the person logged in
  if(isset($_GET['email']) && isset($_SESSION['email']) && $_SESSION['privilege'] != 'student')
  {
    if(!password_verify($_SESSION['email'], $_GET['email']))
    {
      header('Location: index.php');
    }
  }
  else
  {
    header('Location: index.php');
  }


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

    <center>Add Page</center>
     

    <!-- <form action="bookRoom.php" method="POST">
       Name: <input type="text" name="user"><br>
       Email: <input type="mail" name="mail"><br>
       Password:<input type="password" name="pass"> <br>
       <input type="submit" name="submit" value="Send Info">
       </form>  -->




     <form class="bookForm" action="bookRoom.php" method="POST">
      
      <h5>Booking settings</h5>
      Room 332<input type="checkbox" name="room"><br>
      Room 338<input type="checkbox" name="room"><br>
      Room 340<input type="checkbox" name="room"><br>
      Room 242<input type="checkbox" name="room"> <br>
      Room 107<input type="checkbox" name="room"> <br><br>

      Start time:<br>
      <input type="time" name="startT"><br>
      End time:<br>
      <input type="time" name="endT"><br><br>

      <input type="submit" name="submit" value="Book a room(s)">

     </form>
     

   </div> 


  </body>
</html>