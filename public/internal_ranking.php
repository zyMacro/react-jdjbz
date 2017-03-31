<?php

function getNameById($school)
{
    $fp = file("/var/www/jdjbz/danwei.txt");
    $school = $fp[$school];
    return $school;
}
function local_ranking($scope, $rank_date)
{
    require("/var/www/jdjbz/mysql_connect.php");
   
    if($scope == 0)
		$query = "SELECT name, step FROM user_table";
    else
		$query = "SELECT name, step FROM user_table WHERE school_id = $scope";

	$queryresult = mysql_query($query);

	if(!$queryresult) {
	   die('Error while fetching data from DB: ' . mysql_error()); 
	}
	
	$ranklist = array();
	$num_rows = mysql_num_rows($queryresult);
	for($i=0; $i<$num_rows; $i++) {
	    $name = mysql_result($queryresult, $i, 0);
	    $step = mysql_result($queryresult, $i, 1);
	    if($step != 0) {
			$ranklist[$name] = $step;
	    }
	}
	
	arsort($ranklist);

	mysql_close($link);
	if($queryresult != NULL) 
	{
	    mysql_free_result($queryresult);
	}

	return $ranklist;
}

	session_start();
	$myname = $_SESSION['name'];

	if(empty($_SESSION['userId']) || $_SESSION['userId'] == 123456 ||  $_SESSION['userId'] == -1){
	    $url =  $redirectHost.'index.html';
        $_SESSION['returning-URL'] = "internal-ranking.php";	    
	    Header("HTTP/1.1 302 Found");
	    Header("Location: $url");
	    return;
	}
	$school = $_SESSION['school'];
	$ranklist = local_ranking($school, 0);
	$schoolName = getNameById($school);

	$data=array('rankList'=>$ranklist,'schoolName'=>$schoolName);
	$data=json_encode($data);

	echo $data;




    



?>