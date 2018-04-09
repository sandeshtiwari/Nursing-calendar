<?php
	require "config/config.php";
	require "classes/Room.php";
	require "classes/Teacher.php";
	require "classes/Admin.php";
	require "header.php";


	$room = new Room($con);
	$teacher = new Teacher($con, $_SESSION['email']);
	$semester_id = $teacher->getLatestSem();
	//echo $_POST['course_id'];
	//echo $_POST['room_id'];
	//print_r($_POST['requestDays']);
	if(isset($_POST['request']) && isset($_POST['move']))
	{
		//header("Location: collision.php");
		//print_r($_POST);
		$admin = new Admin($con, $_SESSION['email']);
		$admin->addCollision($_POST['room_id'],$_POST['course_id'],$semester_id,$_POST['week'],$_POST['day'], $_POST['roomToDelete']);
		header("Location: collision.php?moved");
	}
	if(isset($_POST['request']) && !isset($_POST['move']))
	{
		//echo "requesting";
		if(!isset($_POST['requestDays']))
		{
			header("Location: register.php?days");
			//echo $_POST['course_id'];
		}
		else
		{
			//echo true;
			//echo $room->checkRequested($_POST['course_id'], $_POST['room_id'], $semester_id, $_POST['weeks'], $_POST['requestDays']);
			if($room->checkRequested($_POST['course_id'], $_POST['room_id'], $semester_id, $_POST['weeks'], $_POST['requestDays']))
			{
				echo "previously requested";
				header("Location: register.php?requestFailed");
			}
			else
			{
				//echo "request successful";
				$room->addCollision($_POST['room_id'],$_POST['course_id'],$semester_id,$_POST['weeks'],$_POST['requestDays']);
				header("Location: register.php?requested");
			}
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
	else if(!isset($_POST['request']) && !isset($_POST['book']))
	{
		header("Location: calendar.php");
	}
?>

<?php

require "footer.php";

?>