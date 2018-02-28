<?php
  require "config/config.php";
  
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
        <li>Book a room</li>
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