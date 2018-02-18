<?php
ob_start(); // Turns on output buffering
session_start();

	$timezone = date_default_timezone_set("America/New_York");

	$con = mysqli_connect("localhost", "root", "", "nursing_scratch");
	if(mysqli_connect_error())
	{
		die("Failed to Connect");
	}
?>