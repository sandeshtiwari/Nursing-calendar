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
?>

  