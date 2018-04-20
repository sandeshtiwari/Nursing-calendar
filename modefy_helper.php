<?php
require "config/config.php";
require 'classes/Room.php';




if(isset($_POST['occupied'])){


	$room = new Room($con);


	if(isset($_GET['room'])){
		$room_id = $_GET['room'];
		
	}
	if(isset($_GET['week'])){
		$week = $_GET['week'];
		
	}
	if(isset($_GET['course'])){

		$course = $_GET['course'];
		
	}	
	if(isset($_GET['days'])){

		$days = $_GET['days'];
		
	}	

	$activity = "Cancel reservation for $days";

	$sql2 = "DELETE FROM occupied WHERE Course_ID = $course and Room_ID = '$room_id' and Week_ID = $week ;";

	if(mysqli_query($con, $sql2)){
		//$room->updateLog("", 1, 1, 1);
		$room->updateLog($activity, $course, $room_id, $week);
		echo "Sucess, this reservation was removed! ";

	

	}     
	else{
		echo "TRY AGAIN, ALMOST THERE";
	}


}


if(isset($_POST['collision'])){


	$room = new Room($con);


	if(isset($_GET['room'])){
		$room_id = $_GET['room'];
		
	}
	if(isset($_GET['week'])){
		$week = $_GET['week'];
		
	}
	if(isset($_GET['course'])){

		$course = $_GET['course'];
		
	}	
	if(isset($_GET['days'])){

		$days = $_GET['days'];
		
	}	

	$activity = "Cancel request for $days";

	$sql = "DELETE FROM collision WHERE Course_ID = $course and Room_ID = '$room_id' and Week_ID = $week ;";

	if(mysqli_query($con, $sql)){

		$room->updateLog($activity, $course, $room_id, $week);
		echo "Sucess, this reservation was removed! ";
		
	}     
	else{
		echo "TRY AGAIN, ALMOST THERE";
	}

	

}

header('refresh: 0; URL="modify_event.php"');



?>