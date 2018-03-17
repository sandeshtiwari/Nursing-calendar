<?php
	require "config/config.php";
	require "classes/Room.php";
	require "classes/Teacher.php";
	$room = new Room($con);
	$teacher = new Teacher($con, $_SESSION['email']);
	$semester_id = $teacher->getLatestSem();
	if(isset($_POST['request']))
	{
		echo "requesting";
		if(!isset($_POST['requestDays']))
		{
			echo "days not selected";
		}
		else
		{
			echo "days selected";
		}
	}
	else if(isset($_POST['book']))
	{
		//echo "booking ";
		if(!isset($_POST['bookDays']))
		{
			header("Location: register.php?days");
		}
		else
		{
			//echo "days selected ";
			//print_r($_POST['bookDays']);
			//print_r($_POST['weeks']);
			//echo $_POST['room_id']. " ";
			//echo $_POST['course_id'];
			$room->reserveRoom($_POST['room_id'],$_POST['course_id'],$semester_id,$_POST['weeks'],$_POST['bookDays']);
			header("Location: register.php?registered=yes");
		}
	}
	else
	{
		header("Location: calendar.php");
	}
?>