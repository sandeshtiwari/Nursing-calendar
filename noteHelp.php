<?php
require 'config/config.php';
require "classes/Admin.php";
require "classes/Teacher.php";
require "classes/Room.php";
//require 'check_privilege.php';


	if($_SESSION['privilege'] == 'admin')
  {
  	$teacher = new Admin($con, $_SESSION['email']);
	$name = $teacher->getName($_SESSION['email']);	 
	$FullName = $name[0]." ".$name[1];
	$semester = $teacher->getLatestSem();
    
  }
   else if($_SESSION['privilege'] == 'teacher' || $_SESSION['privilege'] == 'lead'){

    $teacher = new Teacher($con, $_SESSION['email']);
	$name = $teacher->getName($_SESSION['email']);	 
	$FullName = $name[0]." ".$name[1];
	$semester = $teacher->getLatestSem();
   }
   else{
   	echo " Neither nor";
   }

   


if (isset($_GET['id'])){
	$request = $_GET['id'];

	$room = new Room($con);

	if($request == 'update'){

		$Course = $_GET['course'];
		$Week = $_GET['week'];

		echo "The clicked course is : $Course and the week is : $Week";


	}


	if($request == 'delete'){

				
		$weekId = $_GET['week'];

		$Course_ID = $_GET['course'];

		$sql = "DELETE FROM notes WHERE Course_ID = $Course_ID and Name = '$FullName' and Week_ID = $weekId and Semester_ID = $semester;";

		if(mysqli_query($con, $sql)){

			$room_id = "0";
		$activity = "Deleted notes";		

		$room->updateLog($activity, $Course_ID, $room_id, $weekId);
				echo "Sucess! Note was removed";

			}

		else{
				echo "Try agane, note is still there";
			}
	}
}	


if (isset($_POST['save'])) {

	 

	$room = new Room($con);

	if (isset($_POST['Course'])){

		$Course = $_POST['Course'];
	}

	if(isset($_POST['Week'])){

		$Week = $_POST['Week'];
	}

	if (isset($_POST['Note'])) {

		$Note = $_POST['Note'];
	}
	

	//check status. If note for this week already exists, the isNew will be 1, and note will be updates. If status is 0, note will be created.
	$status = $teacher->checkNote($semester, $Course, $Week, $FullName);

	

	

	if($status == 0){

		$sql = "INSERT INTO notes (`Course_ID`, `Week_ID`, `Note`, `Name`, `Semester_ID`) VALUES ('$Course', '$Week', '$Note', '$FullName', '$semester');";

		if(mysqli_query($con, $sql)){

		$room_id = "0";
		$activity = "Added notes";		

		$room->updateLog($activity, $Course, $room_id, $Week);

			echo "Sucess! Inserted";

		}
		else{
			echo "Try agane";
		}

	}
	else{
		$sql = "UPDATE notes SET Note= '$Note' WHERE Course_ID = $Course and Week_ID = $Week and Semester_ID = $semester;";
		if(mysqli_query($con, $sql)){

		$room_id = "0";
		$activity = "Updated notes";		

		$room->updateLog($activity, $Course, $room_id, $Week);
			echo "Sucess! Updated";

		}
		else{
			echo "Try agane";
		}
	}



}



if (isset($_POST['NewNote'])) {

	$room = new Room($con);
	
		$Course = $_GET['course'];

		$Week = $_GET['week'];

		$Note = htmlspecialchars($_POST['NewNote']);

	

		$sql = "UPDATE notes SET Note= '$Note' WHERE Course_ID = $Course and Week_ID = $Week and Semester_ID = $semester;";
		if(mysqli_query($con, $sql)){
			echo "Sucess! Updated";

		}
		else{
			echo "Try agane";
		}

		$room_id = "0";
		$activity = "Updated notes";		

		$room->updateLog($activity, $Course, $room_id, $Week);

	}

	if($_SESSION['privilege'] == 'admin'){
		$redirect_url = 'admin_notes.php';
	}
	elseif($_SESSION['privilege'] == 'teacher' || $_SESSION['privilege'] == 'lead'){
		$redirect_url = 'teacherNotes.php';
	}
	else{
		$redirect_url = 'calendar.php';
	}


	header('refresh: 0; URL='.$redirect_url);




	



?>
