<?php
  require 'check_privilege.php';
  require 'classes/Teacher.php';
  require 'classes/Student.php';
  require 'classes/Admin.php';

    if( isset($_POST['submit']) )
    {
       $feature = $_POST['Notes'];
       
       $feature2 = $_POST['LastName'];

    $sql2 = "UPDATE course SET Notes = '$feature' WHERE Course_ID ='$feature2'";

    $result = mysqli_query($con, $sql2);

    }
    else
    	{echo "whoops";}

    header('Location: calendar.php'); 
exit(); 
?>