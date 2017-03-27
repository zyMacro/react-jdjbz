<?php
$time = date('A');
	if($time == "AM")
	    $greet = "早上好";
	else
	    $greet = "下午好";
	echo $greet;
?>