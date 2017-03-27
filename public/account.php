<?php
	require_once('xiaomi.inc.php');
	session_start();

	if($_SESSION['login'] == NULL){
		//not logged in. return.
	    $url =  $redirectHost.'index.html';
	    Header("HTTP/1.1 302 Found");
	    Header("Location: $url");				
	}

	if($_SESSION['login'] == 'JA'){
		$jaccount_logged_in = 1;
		$xm_logged_in = 0;
		$jaccount_exists = 1;
	}else if($_SESSION['login'] == 'MI'){
		$jaccount_logged_in = 0;
		$xm_logged_in = 1;
		$xiaomi_exists = 1;
	}
	$userid = $_SESSION['userId'];
	$name=$_SESSION['name'];
	$time = date('A');
	if($time == "AM")
	    $greet = "早上好";
	else
	    $greet = "下午好";

	$account_info=Array('xiaomiid'=>$userid,'name'=>$name,'time'=>$greet);
	 $account_info_=json_encode($account_info);
	 echo $account_info_;
?>