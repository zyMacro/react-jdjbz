<?php
function get_my_steps($ranklist)
    {
		$name = get_my_name();
		while (list($key, $val) = each($ranklist)) {
		    if($key == $name)
			return $val;
		}
		return 0;	    
    }

    function get_my_position($ranklist)
    {
		if(count($ranklist) == 0)
		    return 0;

		$name = get_my_name();
		$i = 0;

		while (list($key, $val) = each($ranklist)) {
		    $i ++;
		    if($key == $name)
			return $i;
		}
		return count($ranklist);	    
    }

    function get_num_players($ranklist)
    {
		return count($ranklist);
    }

    function local_ranking_from_file($scope, $rank_date)
    {
		if($scope == 0)
		    $query = "SELECT name FROM user_table";
		else
		    $query = "SELECT name FROM user_table WHERE school_id = $scope";

		require('/var/www/jdjbz/mysql_connect.php');
		$queryresult = mysql_query($query);
		$rows = mysql_num_rows($queryresult);
		$peoples = array();

		for ($i=0; $i < $rows; $i++) {
		     $peoples[$i] = mysql_result($queryresult, $i, 0);  
		}

		if(!$queryresult) {
		   die('Invalid query: ' . mysql_error()); 
		}

		//retrieve everyone on the list.    
		$ranklist = topTenOnDate($rank_date);
		$newlist = array();

		if(count($ranklist) == 0) {
		    echo "<p>No data. </p>";
			} else {
			    $i = 0;
			    while (list($key, $val) = each($ranklist))
			    {
				if(in_array($key, $peoples)) {
				    if($val != 0) {
						$newlist[$key] = $val;
				    }
				}
		    } 
		}

		mysql_close($link);
		if($queryresult != NULL) 
		{
		    mysql_free_result($queryresult);
		}
		return $newlist;
    }

    function getSleepTimeOnDate($userid, $ondate)
    {
		global $jdjbzDataPath;
        $pathname = $jdjbzDataPath."/".$userid;

        if(!file_exists($pathname)) {
//            echo "data file $pathname not exist. <br >\n";
            return 0;
        }

        $filename = $pathname . "/". $ondate . ".txt";
        if(!file_exists($filename)) {
            return 0;
        }

        $fp = file($filename);

		$match = 0;
		$i = count($fp) - 1;
		if($i < 0)
			return 0;
		$datas[0] = 0;
		$datas[1] = 0;
	        //read the last matchable line from the file...
	        while(!$match){
			$line = $fp [$i];
	        	$match = preg_match("/^\d+\s\d+\s\d+\s\d+\s\d+\s\d+\s\d+\s(\d+)\s(\d+)\s\d+\s\d+\s\d+$/", $line, $datas) ;

	        	if ($match == 1) {
	            		return $datas[1]+$datas[2];
	        	} else {
	            		$match = preg_match("/^\d+:\d+:\d+\s\d+\s\d+\s\d+\s\d+\s\d+\s\d+\s\d+\s(\d+)\s(\d+)\s\d+\s\d+\s\d+$/", $line, $datas) ;
	        	}
			$i--;
			if($i < 0)
				break;
	      	}
		if($match)		
        		return $datas[1]+$datas[2];
		else
			return 0;
	}

	function fillSessionFromJaccountInfo()
	{
		session_start();
		$userid = $_SESSION['userId'];
		$jaccount = $_SESSION['jaccount'];
		if($jaccount == NULL || $userid != NULL)
			return;
		
		$querystring = "SELECT xiaomiid FROM jaccount_xm_assoc WHERE jaccount=\"" ."$jaccount". "\"";

		require("/var/www/jdjbz/mysql_pdo_connect.php");
		$query = $db->prepare($querystring);
		$query->execute();
		$rows = $query->fetchAll();
//		var_dump($rows);
		unset($db);		

		if(count($rows) == 1){
			$xiaomiid = $rows[0]['xiaomiid'];
			$querystring = "SELECT school_id FROM user_table WHERE userid = $xiaomiid ";
			require_once('/var/www/jdjbz/mysql_connect.php');
//			echo "fillSessionWithJaccountInfo::$querystring <br />";
			$queryresult = mysql_query($querystring);
			if($queryresult!= NULL) {
				$num_rows = mysql_num_rows($queryresult);
				if($num_rows != 0){
				    $school = mysql_result($queryresult, $i, 0); 
				}
			}
		}

		$querystring = "SELECT code, name, organize FROM jaccount_table WHERE jaccount=\"" ."$jaccount". "\"";
		require_once('/var/www/jdjbz/mysql_connect.php');
		$queryresult = mysql_query($querystring);
		if(!$queryresult) {
		   return;
		}
		$num_rows = mysql_num_rows($queryresult);

		if($num_rows == 0){
			return;
		}

	    $code = mysql_result($queryresult, $i, 0);  
	    $name = mysql_result($queryresult, $i, 1);  
	    $org = mysql_result($queryresult, $i, 2);  

    	$_SESSION['userId'] = ($xiaomiid != NULL)?$xiaomiid:$code;
    	$_SESSION['school'] = ($school != NULL)?$school:$org;
    	$_SESSION['employeeid'] = $code;
    	if(!$_SESSION['name']){
    		$_SESSION['name'] = $name;
    	}
    	return;
	}

	function fillSessionFromXiaomiInfo()
	{
		session_start();
		$userid = $_SESSION['userId'];
		$school = $_SESSION['school'];
		if($userid == NULL || $jaccount != NULL)
			return;
		
		$querystring = "SELECT jaccount FROM jaccount_xm_assoc WHERE xiaomiid = $userid";

		require("/var/www/jdjbz/mysql_pdo_connect.php");
		$query = $db->prepare($querystring);
		$query->execute();
		$rows = $query->fetchAll();
//		var_dump($rows);
		unset($db);		

		if(count($rows) == 1){
			$jaccount = $rows[0]['jaccount'];
			$querystring = "SELECT code, name, organize FROM jaccount_table WHERE jaccount=\"" ."$jaccount". "\"";
			require_once('/var/www/jdjbz/mysql_connect.php');
			$queryresult = mysql_query($querystring);
			if(!$queryresult) {
			   return;
			}
			$num_rows = mysql_num_rows($queryresult);

			if($num_rows == 0){
				return;
			}

		    $code = mysql_result($queryresult, $i, 0);  
		    $name = mysql_result($queryresult, $i, 1);  
		    $org = mysql_result($queryresult, $i, 2);  
		    $_SESSION['jaccount'] = $jaccount;
	    	$_SESSION['employeeid'] = $code;
	    	if(!$_SESSION['school']){
	    		$_SESSION['school'] = $org;
	    	}
	    	if(!$_SESSION['name']){
	    		$_SESSION['name'] = $name;
	    	}
		}

    	return;
	}
	function updateJaccountAssocTable()
	{
		$userid = $_SESSION['userId'];
		$jaccount = $_SESSION['jaccount'];

		if($userid == NULL || $jaccount == NULL){
		//if either xm and jaccount are not logged in, then no association should be created.
			return;
		}

		$querystring = "SELECT xiaomiid FROM jaccount_xm_assoc WHERE jaccount=\"" ."$jaccount". "\"";

		require("/var/www/jdjbz/mysql_pdo_connect.php");
		$query = $db->prepare($querystring);
		$query->execute();
		$rows = $query->fetchAll();
	//	var_dump($rows);
		unset($db);		
		if(count($rows) != 0){
			//find an association.
			$olduserid = $rows[0]['xiaomiid'];
			if($olduserid != $userid){
				//user might have changed xiaomi id
				$querystring = "UPDATE jaccount_xm_assoc SET xiaomiid = '$userid' WHERE jaccount = '$jaccount' ";
				require("/var/www/jdjbz/mysql_pdo_connect.php");
				$query = $db->prepare($querystring);
				$query->execute();			

	//			$querystring = "DELETE FROM user_table
	//							WHERE userid = $olduserid ";
	//			require("mysql_pdo_connect.php");
	//			$query = $db->prepare($querystring);
	//			$query->execute();			
				$filename = "/var/log/jdjbz/jaccount-xm-user-to-be-deleted.log";
				$printstr = sprintf("%s: jaccount:%s, old xiaomid id: %s, new xiaomi id: %s",
									date("Y-m-d H:i:s"), $account, $olduserid, $userid);
	    		if(!file_put_contents ($filename, $printstr, FILE_APPEND | LOCK_EX))
					error_log ("Error write data into file for user $account\n");
			}
		}else{
			$querystring = "SELECT code FROM jaccount_table WHERE jaccount=\"" ."$jaccount". "\"";
			require_once('/var/www/jdjbz/mysql_connect.php');
			$queryresult = mysql_query($querystring);
			if(!$queryresult) {
			   return;
			}
			$num_rows = mysql_num_rows($queryresult);

			if($num_rows == 0){
				return;
			}

		    $code = mysql_result($queryresult, $i, 0);  
		    if($code == $userid)
		    	return;

			//there is no association.
			if($userid != NULL && $jaccount != NULL){
				//if both acccounts are actively longed in, 
				//then there must be a intentional association from user
				//let's create an entry in the association table
				$querystring = "INSERT INTO jaccount_xm_assoc (xiaomiid, jaccount) VALUES ($userid,  '$jaccount' )";
				require("/var/www/jdjbz/mysql_connect.php");
			    $queryresult = mysql_query($querystring);

			    if(!$queryresult) {
			       die("While insert into jaccount_xm_table:" . mysql_error()); 
			    }
			}
		}
	}

	function loadXiaomiAccountInfo($jaccount){
		if($jaccount == NULL)
			return 0;

		$querystring = "SELECT xiaomiid FROM jaccount_xm_assoc WHERE jaccount= '$jaccount' ";
		require("/var/www/jdjbz/mysql_pdo_connect.php");
		$query = $db->prepare($querystring);
		$query->execute();
		$rows = $query->fetchAll();
		if($rows >=1)
			return $rows[0]['xiaomiid'];
		else
			return 0;
	}

	function get_line($file,$line) {    
        $fp = fopen($file,'r');    
        $i = 0;    
        while(!feof($fp)) {    
            $i++;    
            $c = fgets($fp);    
            if($i==$line) {    
                // echo $c;    
                break;    
            }    
        }    
    } 
  function get_my_name()
{
    return $_SESSION['name'];	
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
    function get_user_type($userid)
{
    $query = "SELECT type FROM user_table WHERE userid = $userid";

	require("/var/www/jdjbz/mysql_connect.php");
	$queryresult = mysql_query($query);
	$rows = mysql_num_rows($queryresult);

	if($rows == 0)
		return NULL;
	else {
		$usertype = mysql_result($queryresult, 0, 0); 	
		return $usertype;
	}

	if(!$queryresult) {
	   die('Invalid query: ' . mysql_error()); 
	}

}
function trans_user_type ($type)
{
	switch ($type) {
		case '0':
			return "非会员";
			break;

		case '1':
			return "大众会员";
			break;

		case '2':
			return "金牌会员";
			break;
		
		default:
			return "非会员";
			break;
	}
}
    function topTenOnDate($ondate) 
    {
    	global $jdjbzDataPath;
		$ranklist = array();

		$pathname = "$jdjbzDataPath/features/fullRanking";
		$filename = "$pathname/$ondate.txt";

		if(!file_exists($filename)) {
			error_log("Cannot read $filename!\n");
		}

		$fp = file($filename);
		$ranklist = array();

		for($i=0; $i<count($fp); $i++) {
		    $line = $fp [$i];
			$match = preg_match("/(...+)\s+(\d+)$/", $line, $datas) ;

		    $ranklist[$datas[1]] = $datas[2];
		}
		
		return $ranklist;
    }


require_once('xiaomi.inc.php');
session_start();
$jdjbzPath='/var/www/jdjbz/';
$jdjbzDataPath = '/var/www/jdjbz/data/';
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

// $jaccount = $_SESSION['jaccount'];
// $userid = $_SESSION['userId'];

// fillSessionFromJaccountInfo();
// $xiaomi_exists = loadXiaomiAccountInfo($jaccount);

// fillSessionFromXiaomiInfo();
// $jaccount_exists = ($_SESSION['jaccount'] != NULL)?1:0;

$jaccount = $_SESSION['jaccount'];
$userid = $_SESSION['userId'];
$week_days_abbr = array('一', '二', '三', '四', '五', '六', '日');
$date = date('Y-m-d');

$steps_array=array();   //接收过去一周每天的steps
$date_array=array();    //接收过去一周每天的date
$week_array=array();    //接收过去一周每天是周几
$rank_school_array=array();
$num_players_school_array=array();
$rank_univ_array=array();
$num_players_array=array();
$sleep_hour_array=array();
$sleep_min_array=array();

for($i=0; $i < 7; $i++){
        $date = mktime(0, 0, 0, date("n", $date1), date("j", $date1)-7+$i, date("Y", $date1));
		$today_index = date('N', $date);
		$today_date  = $week_days_abbr[$today_index-1];
		$today_date2 = date("m-d", $date);
		$date_array[$i]=$today_date;
		$week_array[$i]=$today_date2;
		$today_string = date('Y-m-d', $date);


///////
		//for yesterday...
		$ranklist_school = local_ranking_from_file ($school, $today_string);

		$steps = get_my_steps($ranklist_school);
        $steps_array[$i]=$steps;

        // echo '$steps_array'.$i.':  '.$steps_array[$i];

		$total_steps += $steps;
		$rank_school = get_my_position($ranklist_school);
		$num_players_school = get_num_players($ranklist_school);
		$total_rank_school += $rank_school/$num_players_school;

		//for yesterday...
		$ranklist = local_ranking_from_file (0, $today_string);
		$rank_univ = get_my_position($ranklist);
		$num_players = get_num_players($ranklist);
		$total_rank += $rank_univ/$num_players;

		$sleep = getSleepTimeOnDate($userid, $today_string);
		$total_sleep_time += $sleep;
		$sleep_min = $sleep%60;
		$sleep_hour = ($sleep - $sleep_min)/60;
		$rank_school_array[$i]=$rank_school;
		$num_players_school_array[$i]=$num_players_school;
		$rank_univ_array[$i]=$rank_univ;
		$num_players_array[$i]=$num_players;
		$sleep_hour_array[$i]=$sleep_hour;
		$sleep_min_array[$i]=$sleep_min;


	if($num_players_school != 0)
	    $percent_school = round($rank_school/$num_players_school*100);
	else
	    $percent_school = 0;

	if($percent_school < 1 && $percent_school > 0)
	    $percent_school = 1;

	if($num_players != 0)
	    $percent_univ = round($rank_univ/$num_players*100);
	else
	    $percent_univ = 0;

	if($percent_univ < 1 && $percent_univ > 0)
	    $percent_univ = 1;

//////		
		// if($i%2==0)
		// 	echo "	<tr>\n";
		// else
		// 	echo "	<tr bgcolor='#FFE4A4'>\n";

		// echo "		<td width='25%'>$today_date2 (周".$today_date.")</td>\n";
		// echo "		<td width='15%'>$steps</td>\n";
		// echo "		<td width='20%'>$rank_school / $num_players_school </td>\n";
		// echo "		<td width='20%'>$rank_univ / $num_players</td>\n";
		// echo "		<td width='20%'>$sleep_hour"."小时".$sleep_min."分钟</td>\n";
		// echo "	</tr>\n";
	}
    
	$sleep = round($total_sleep_time/7);
	$sleep_min = $sleep%60;
	$sleep_hour = ($sleep - $sleep_min)/60;
	$steps = round($total_steps/7);
	$rank_school = $total_rank_school/7;
	$rank = $total_rank/7;
	$rank_school_text = round($rank_school*100);
	$rank_text = round($rank*100);

	$data=array('weekArray'=>$week_array,
                'dateArray'=>$date_array,
                'stepsArray'=>$steps_array,
                'rankSchoolArray'=>$rank_school_array,
                'numPlayersSchoolArray'=>$num_players_school_array,
                'rankUnivArray'=>$rank_univ_array,
                'numPlayersArray'=>$num_players_array,
                'sleepHourArray'=>$sleep_hour_array,
                'sleepMinArray'=>$sleep_min_array,
                'sleepMin'=>$sleep_min,
                'sleepHour'=>$sleep_hour,
                'steps'=>$steps,
                'rankSchoolText'=>$rank_school_text,
                'rankText'=>$rank_text,
		);
	$data=json_encode($data);
	echo $data;

?>