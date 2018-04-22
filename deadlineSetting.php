<?php
require "config/config.php";

if(isset($_POST['date'])){
	$selectedDate = $_POST['date'];
}


try{


$date_array=explode("/",$selectedDate);
$mont;

if($date_array[0] == "Jan"){
	$mont = "01";
}
if($date_array[0] == "Feb"){
	$mont = "02";
}
if($date_array[0] == "Mar"){
	$mont = "03";
}
if($date_array[0] == "Apr"){
	$mont = "04";
}
if($date_array[0] == "May"){
	$mont = "05";
}
if($date_array[0] == "Jun"){
	$mont = "06";
}
if($date_array[0] == "Jul"){
	$mont = "07";
}
if($date_array[0] == "Aug"){
	$mont = "08";
}
if($date_array[0] == "Sep"){
	$mont = "09";
}
if($date_array[0] == "Oct"){
	$mont = "10";
}
if($date_array[0] == "Nov"){
	$mont = "11";
}
if($date_array[0] == "Dec"){
	$mont = "12";
}

$new_date_array=array($date_array[2],  $date_array[0], $date_array[1]);



$new_date=implode("-",$new_date_array);
$from_date = date("Y-m-d", strtotime($selectedDate));

//Setting Lead instructor for the all courses:
$sql = "UPDATE semester SET deadline = '$new_date' WHERE ID = 1;";
if(mysqli_query($con, $sql)){
header('Location: registration.php');	
}     
else{
header('Location: registration.php');	
}
}
catch(Exception $e){
header('Location: registration.php');
$message = "wrong answer";
echo "<script type='text/javascript'>alert('$message');</script>";
}
?>