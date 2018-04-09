<?php
require "config/config.php";


if(isset($_POST['Fname'])){

	$Fname = $_POST['Fname'];

}

if(isset($_POST['Lname'])){

	$Lname = $_POST['Lname'];

}

if(isset($_POST['class'])){

	$class = $_POST['class'];

}

$Number = preg_replace('/[^0-9]/', '', $class);
$Prefix = preg_replace('/[^a-zA-Z]/', '', $class);

//Retriving the ID of the teacher that teaches that class.
$sql = "SELECT  CWID FROM course c join person p on p.CWID = c.Teacher_CWID where Prefix = '$Prefix' and Number = '$Number' and Fname = '$Fname' and Lname = '$Lname'";

	$res = mysqli_query($con, $sql);

	while ($process = mysqli_fetch_array($res, MYSQLI_ASSOC)) {

		$leadId = $process['CWID'];
	} 


//Setting Lead instructor for the all courses:
$sql2 = "UPDATE course SET Lead_teacher = '$leadId' WHERE Prefix = '$Prefix' and Number = '$Number' and Semester_ID = 1";




if(mysqli_query($con, $sql2)){
	echo "Sucess, the status of regestration is ".$leadId;
}     
else{
	echo "TRY AGAIN, ALMOST THERE";
}


	




?>