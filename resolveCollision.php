<?php
	require "config/config.php";
	require "classes/Admin.php";
	if($_SESSION['privilege'] != 'admin' || !isset($_SESSION['email']))
	{
  		header("Location: index.php");
	}
	if(!empty($_GET))
	{
		$admin = new Admin($con, $_SESSION['email']);
		if(isset($_GET['override']))
		{
			$admin->giveAccess($_GET['collidingCourse'], $_GET['course_id'],$_GET['room_id'],$_GET['week_id'],$_GET['day'],$_GET['semester_id']);
			header("Location: collision.php?accessGranted");
		}
		else if(isset($_GET['delete']))
		{
			$admin->deleteRequest($_GET['course_id'],$_GET['room_id'],$_GET['week_id'],$_GET['day'],$_GET['semester_id']);
			header("Location: collision.php?deleted");
		}
	}
	else
	{
		header("Location: index.php");
	}
?>