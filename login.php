<?php 
	require "config/config.php";
	if(isset($_POST['login_button']))
	{
		$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); //Sanitize email
		$_SESSION['email'] = $email; // store email into session variable
		$check_database_query = mysqli_query($con, "SELECT * FROM person WHERE email = '$email'");
		$row = mysqli_fetch_array($check_database_query);
		if(isset($row) && ($_POST['password'] == $row['password']))
		{
			echo "logged in!";
		}
		else{
			echo "error";
		}
		
	}

 ?>