<?php

require 'config/config.php';


$setting;
$open = "on";
$close = "off";

$sql = "SELECT Override FROM semester WHERE ID = 1";

$result = mysqli_query($con, $sql);

	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	        $setting = $row['Override'];


if($setting == $open){

	$sql1 = "UPDATE semester SET Override= 'off' WHERE ID = 1";
	$setting = "off";
}

elseif($setting == $close){

	$sql1 = "UPDATE semester SET Override= 'on' WHERE ID = 1";
	$setting = "on";
}	   

if(mysqli_query($con, $sql1)){
	echo "Success, the override button has been turned ".$setting;
}     
else{
	echo "TRY AGAIN, ALMOST THERE";
}
	     



?>