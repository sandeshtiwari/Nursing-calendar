<?php
	require "config/config.php";
	require "classes/Room.php";
	require "classes/Teacher.php";
	require "classes/Admin.php";
	require "header.php";


	$room = new Room($con);
	$teacher = new Teacher($con, $_SESSION['email']);
	$admin = new Admin($con, $_SESSION['email']);
	$semester_id = $teacher->getLatestSem();
	//echo $_POST['course_id'];
	//echo $_POST['room_id'];
	//print_r($_POST);
	if(isset($_POST['request']) && isset($_POST['move']))
	{
		//header("Location: collision.php");
		//print_r($_POST);
		$admin->addCollision($_POST['room_id'],$_POST['course_id'],$semester_id,$_POST['week'],$_POST['day'], $_POST['roomToDelete']);
		$admin->updateOccupied($_POST['course_id'], $_POST['roomToDelete'], $_POST['week'], $_POST['day'], $semester_id);
		header("Location: collision.php?moved");
	}
	else if(isset($_POST['book']) && isset($_POST['move']))
	{
		print_r($_POST['weeks']);
		$room->reserveRoom($_POST['room_id'],$_POST['course_id'],$semester_id,$_POST['weeks'],$_POST['bookDays']);
		$admin->updateOccupied($_POST['course_id'], $_POST['roomToDelete'], $_POST['week'], $_POST['day'], $semester_id);
		 //$admin->updateOccupied($_POST['course_id'], $_POST['roomToDelete'], $_POST['weeks'], $_POST['day'], $semester_id);
		header("Location: collision.php?registered=yes");
	}
	if(isset($_POST['request']) && !isset($_POST['move']))
	{
		//echo "requesting";
		if(!isset($_POST['requestDays']))
		{
			header("Location: lead_register.php?days");
			//echo $_POST['course_id'];
		}
		else
		{
			//echo true;
			//echo $room->checkRequested($_POST['course_id'], $_POST['room_id'], $semester_id, $_POST['weeks'], $_POST['requestDays']);
			if($room->checkRequested($_POST['course_id'], $_POST['room_id'], $semester_id, $_POST['weeks'], $_POST['requestDays']))
			{
				echo "previously requested";
				header("Location: lead_register.php?requestFailed");
			}
			else
			{
				//echo "request successful";
				$room->addCollision($_POST['room_id'],$_POST['course_id'],$semester_id,$_POST['weeks'],$_POST['requestDays']);
				header("Location: lead_register.php?requested");
			}
		}
	}
	else if(isset($_POST['book']) && !isset($_POST['move']))
	{
		//echo "booking ";
		if(!isset($_POST['bookDays']))
		{
			header("Location: lead_register.php?days");
		}
		else
		{
			//echo "days selected ";
			//print_r($_POST['bookDays']);
			//print_r($_POST['weeks']);
			//echo $_POST['room_id']. " ";
			//echo $_POST['course_id'];
            if($teacher->checkRegistrationStatus())
            {
                $room->reserveRoom($_POST['room_id'],$_POST['course_id'],$semester_id,$_POST['weeks'],$_POST['bookDays']);
			     header("Location: lead_register.php?registered=yes");
            }
			else
            {
                $room->addCollision($_POST['room_id'],$_POST['course_id'],$semester_id,$_POST['weeks'],$_POST['bookDays']);
				header("Location: lead_register.php?requested");
            }
		}
	}
	else if(!isset($_POST['request']) && !isset($_POST['book']))
	{
		header("Location: index.php");
	}
?>

<?php

require "footer.php";

?>