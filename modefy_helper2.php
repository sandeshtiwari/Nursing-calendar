<?php
require "config/config.php";


if(isset($_POST['courseID'])){

	$courseID = $_POST['courseID'];

}

if(isset($_POST['roomID'])){

	$roomID = $_POST['roomID'];

}

if(isset($_POST['weekID'])){

	$weekID = $_POST['weekID'];

}




//Setting Lead instructor for the all courses:
$sql2 = "DELETE FROM collision WHERE Course_ID = $courseID and Room_ID = '$roomID' and Week_ID = $weekID ;";




if(mysqli_query($con, $sql2)){
	echo "Sucess, this reservation was removed! ";
}     
else{
	echo "TRY AGAIN, ALMOST THERE";
}





?>