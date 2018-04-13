<?php
	require "config/config.php";
	function check_student($con)
	{
		$email = $_SESSION['email'];
		$check_database_query = mysqli_query($con, "SELECT * FROM person WHERE email = '$email' AND role = 'student'");
		$row = mysqli_fetch_array($check_database_query);
		if(isset($row['email']))
		{
			return true;
		}
		return false;
	}

	function check_teacher($con)
	{
		$email = $_SESSION['email'];
		$check_database_query = mysqli_query($con, "SELECT * FROM person WHERE email = '$email' AND role = 'teacher'");
		$row = mysqli_fetch_array($check_database_query);
		if(isset($row))
		{
			return true;
		}
		return false;
	}

	function check_lead($con){
		$email = $_SESSION['email'];
		$check_database_query = mysqli_query($con, "SELECT * FROM person AS P,course WHERE email = '$email' AND role = 'teacher' AND course.Lead_teacher = P.CWID");
		$row = mysqli_fetch_array($check_database_query);
		if(isset($row))
		{
			return true;
		}
		return false;		
	}
?>
