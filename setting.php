<?php

require 'config/config.php';


$setting;
$open = "yes";
$close = "no";

$sql = "SELECT register_permission FROM semester WHERE ID = 1";

$result = mysqli_query($con, $sql);

	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	        $setting = $row['register_permission'];


if($setting == $open){

	$sql1 = "UPDATE semester SET register_permission= 'no' WHERE ID = 1;";
	$setting = "NO";
}

elseif($setting == $close){

	$sql1 = "UPDATE semester SET register_permission= 'yes' WHERE ID = 1;";
	$setting = "YES";
}	   

if(mysqli_query($con, $sql1)){
	echo "Sucess, the status of regestration is ".$setting;
}     
else{
	echo "TRY AGAIN, ALMOST THERE";
}
	     



?>