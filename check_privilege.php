<?php
	require "config/config.php";
	function check_student($con)
	{
		$email = $_SESSION['email'];
		$check_database_query = mysqli_query($con, "SELECT * FROM student WHERE email = '$email'");
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
		$check_database_query = mysqli_query($con, "SELECT * FROM teacher WHERE email = '$email'");
		$row = mysqli_fetch_array($check_database_query);
		if(isset($row))
		{
			return true;
		}
		return false;
	}
?>