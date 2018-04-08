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
		else if(isset($_GET['move']))
		{
			//print_r($_GET);
			$course_id = $_GET['course_id'];
			$day = $_GET['day'];
			$week_id = $_GET['week_id'];
            $room_id = $_GET['room_id'];
			header("Location: bookRoom.php?course_id=$course_id&day=$day&week_id=$week_id&room_id=$room_id");
		}
	}
	else
	{
		header("Location: index.php");
	}
?>