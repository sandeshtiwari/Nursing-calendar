<?php
	require "config/config.php";
	require "classes/Room.php";
	$room = new Room($con);
	if($_GET['collide'] == 'no')
	{
		if($room->reserveRoom($_GET['room_id'],$_GET['course_id']))
		{
			header('Location: register.php?registered=yes');
		}
		else
		{
			header('Location: register.php?registered=no');
		}

	}
	// if there is a collision
	else if($_GET['collide'] == 'yes')
	{
		$room->addCollision($_GET['course_id'], $_GET['room_id']);
		header("Location: register.php?requested");	
	}
	// if the teacher cancles the registration
	if(isset($_GET['remove']) && isset($_GET['course_id']))
	{
		$room->cancelRegistration($_GET['course_id']);
		header("Location: register.php?cancled");
	}
?>