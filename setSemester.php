<?php
require "config/config.php";

if(isset($_POST['start_date'])){
	$selectedDate1 = $_POST['start_date'];
}
if(isset($_POST['end_date'])){
	$selectedDate2 = $_POST['end_date'];
}
if(isset($_POST['semname'])){
	$semname = $_POST['semname'];
}

try{

$id = 1;

$date_array1=explode("/",$selectedDate1);
$date_array2=explode("/",$selectedDate2);

$new_date_array1=array($date_array1[2],  $date_array1[0], $date_array1[1]);
$new_date_array2=array($date_array2[2],  $date_array2[0], $date_array2[1]);


$new_date1=implode("-",$new_date_array1);
$new_date2=implode("-",$new_date_array2);



$sql = "INSERT INTO semester(ID, semester, start_date, end_date, register_permission, deadline, Override) VALUES('$id','$semname','$new_date1','$new_date2','no','1990-10-10','off')";

if(mysqli_query($con, $sql)){
header('Location: registration.php?datesSet');	
}     
else{
header('Location: registration.php');	
}
}
catch(Exception $e){
header('Location: registration.php');
}

?>