<?php
	require "config/config.php";
	require "classes/Room.php";
	require "classes/Teacher.php";
	require "header.php";


	$room = new Room($con);
	$teacher = new Teacher($con, $_SESSION['email']);
	$semester_id = $teacher->getLatestSem();
	//echo $_POST['course_id'];
	//echo $_POST['room_id'];
	//print_r($_POST['requestDays']);
	if(isset($_POST['request']))
	{
		//echo "requesting";
		if(!isset($_POST['requestDays']))
		{
			header("Location: register.php?days");
			//echo $_POST['course_id'];
		}
		else
		{
			//echo "days selected";
			//print_r($_POST['conflictingCourses']);
			//print_r($_POST['conflictingWeeks']);
			//echo " ".$_POST['course_id']." ";
			//echo " ".$_POST['room_id']." ";
			//print_r($_POST['weeks']);
			//print_r($_POST['requestDays']);
			$room->addCollision($_POST['room_id'],$_POST['course_id'],$semester_id,$_POST['weeks'],$_POST['requestDays'],$_POST['conflictingCourses'], $_POST['conflictingWeeks']);
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

<?php

require "footer.php";

?>