<?php
	require_once ("jdjbz.php");
	require("vendor/autoload.php");

	use fkooman\OAuth\Client;
	use fkooman\OAuth\Client\Guzzle3Client;
	use fkooman\OAuth\Client\ClientConfig;
	use fkooman\OAuth\Client\SessionStorage;
	use fkooman\OAuth\Client\Api;
	use fkooman\OAuth\Client\Context;

	require('jaccount.inc.php');
	require("jaccount-data.php");
	require("JaccountUser.php");

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

		require('mysql_connect.php');
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

		require("mysql_pdo_connect.php");
		$query = $db->prepare($querystring);
		$query->execute();
		$rows = $query->fetchAll();
//		var_dump($rows);
		unset($db);		

		if(count($rows) == 1){
			$xiaomiid = $rows[0]['xiaomiid'];
			$querystring = "SELECT school_id FROM user_table WHERE userid = $xiaomiid ";
			require_once('mysql_connect.php');
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
		require_once('mysql_connect.php');
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

		require("mysql_pdo_connect.php");
		$query = $db->prepare($querystring);
		$query->execute();
		$rows = $query->fetchAll();
//		var_dump($rows);
		unset($db);		

		if(count($rows) == 1){
			$jaccount = $rows[0]['jaccount'];
			$querystring = "SELECT code, name, organize FROM jaccount_table WHERE jaccount=\"" ."$jaccount". "\"";
			require_once('mysql_connect.php');
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

		require("mysql_pdo_connect.php");
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
				require("mysql_pdo_connect.php");
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
			require_once('mysql_connect.php');
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
				require("mysql_connect.php");
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
		require("mysql_pdo_connect.php");
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

//////////////////

	require_once('jdjbz.php');
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

	//update the association table, if necessary.
	updateJaccountAssocTable();

	//at this point, we should have complete jaccount info in jaccount table, if jaccount is logged in
	//or, we should have complete xiaomi info in user_table, if xiaomi is logged in.
	//and, we should have created/updated the association entry, if both are logged in.

	//we can not reach here if non is logged in.
	$jaccount = $_SESSION['jaccount'];
	$userid = $_SESSION['userId'];

	fillSessionFromJaccountInfo();
	$xiaomi_exists = loadXiaomiAccountInfo($jaccount);

	fillSessionFromXiaomiInfo();
	$jaccount_exists = ($_SESSION['jaccount'] != NULL)?1:0;
	
	$jaccount = $_SESSION['jaccount'];
	$userid = $_SESSION['userId'];
	
	$date = date('Y-m-d');

	$filename = "$jdjbzDataPath/$userid/$date.txt";

	$fp = file($filename);
	$match = 0;
	$i = count($fp) -1;
    $calorie = 0;
	$runtime = 0;
    $runDistance = 0;
    $runCalorie = 0;
    $walkTime = 0;
    $steps = 0;
    $walkDistance = 0;
    $shallowSleepTime = 0;
    $deepSleepTime = 0;
    $sleepStartTime = 0;
    $sleepEndTime = 0;
    $wakeTime = 0;		

	if($i >= 0){
		while(!$match){
			$line = $fp[$i];
		    $match2 = preg_match("/^(\d+:\d+:\d+)\s(\d+)\s(\d+)\s(\d+)\s(\d+)\s(\d+)\s(\d+)\s(\d+)\s(\d+)\s(\d+)\s(-?\d+)\s(-?\d+)\s(\d+)$/", $line, $datas) ;    
		    if ($match2) {
		    	$last_sync_time = $datas[1];
				$calorie = $datas[2];
				$runtime = $datas[3];
				$runDistance = $datas[4];
				$runCalorie = $datas[5];
				$walkTime = $datas[6];
				$steps = $datas[7];
				$walkDistance = $datas[8];
				$shallowSleepTime = $datas[9];
				$deepSleepTime = $datas[10];
				$sleepStartTime = $datas[11];
				$sleepEndTime = $datas[12];
				$wakeTime = $datas[13];
			    $match = 1;
		    }
			$i--;
			if($i < 0)
				break;
		}
	}
	
	$school = $_SESSION['school'];

	//for today...
	$ranklist_school = local_ranking ($school, 0);
	$num_players_school = get_num_players($ranklist_school);
	$my_position_school = get_my_position($ranklist_school);

	//for today...
	$ranklist = local_ranking (0,0);
	$num_players = get_num_players($ranklist);
	$my_position = get_my_position($ranklist);

	$user_type = get_user_type($userid);
	$user_type_string = trans_user_type($user_type);

	if($user_type == 0 && $xiaomi_exists){
		$user_type_string .= " <a href='member_registration_2017.php'>加入会员</a>";

	}
	$time = date('A');
	if($time == "AM")
	    $greet = "早上好";
	else
	    $greet = "下午好";
    
	xm_print_nav();
	$bindstring = $jaccount_exists?"Jaccount已关联":"<a href='jaccount-register.php'>Jaccount未关联</a>";

	if($jaccount_logged_in){
		$bindstring = $xiaomi_exists?"小米账号已关联":"<a href='xm-auth.php'>小米账号未关联</a>";
	}else if($xm_logged_in){
		$bindstring = $jaccount_exists?"Jaccount已关联":"<a href='jaccount-register.php'>Jaccount未关联</a>";
	}else{
		//not logged in. something must be wroong. just return.
//	    $url =  $redirectHost.'index.html';
//	    Header("HTTP/1.1 302 Found");
//	    Header("Location: $url");								
	}
	echo "<h1>$greet ，" . get_my_name() . " ($user_type_string, " . "$bindstring)!</h1>";
	// echo "<table width = '100%'>\n";

	// printf("  <tr bgcolor='#FFA500'>\n");
	// printf("   <td height=2 colspan='3'></td>\n");		
	// printf("  </tr>\n");
	// echo "</table>";

	$match = 0;
	$i = 0;
    $userInfoFileName = "$jdjbzDataPath/$userid/$userid-info.txt";
	$info_file_handle = @fopen($userInfoFileName, "r");
	if ($info_file_handle) {
	    while (($buffer = fgets($info_file_handle, 4096)) !== false) {
  			$match = preg_match("/stepsGoal..(\d+)/",$buffer, $matched_string);
	    	if($match)
	    		break;
	    }
	    fclose($handle);
	}

    if($match)
    	$stepsGoal = $matched_string[1];
    else
    	$stepsGoal = 10000;

 //    echo "<div class='tab_menu'>";
 //    echo "<ul>";
 //    echo "  <li class='on'>今日活动</li>  ";
 //    // echo "  <li>健康</li>  ";
 //    echo "  <li>今日饮食</li>  ";
 //    echo "</ul>";
 //    echo "</div>";

  
 //    echo "<div class='tab_box'>";
 //    echo "<div class='box1'>";
    echo "<table width=100%>";         //add
	echo "<tr width = '100%'>";
	echo "<td width = '20%'>运动</td>";
    echo "<td width = '40%'>步数</td>";

    echo "<td width = '40%'>$steps 步 (<a href='manual_update.php'>手动更新</a>&dagger;)</td>";  
	echo "</tr>";
	echo "</table>";

	$percent = 100*$steps/$stepsGoal;
	if($percent > 100) {
		$color = "#FFA500";
		$percent = 100;
	}
	else
		$color = "#008000";

    echo "<table width=100%>";
    printf("  <tr bgcolor='#FFA500'>\n");
	printf("   <td height=1 colspan='3'></td>\n");		
	printf("  </tr>\n");
   	echo "<tr width = '100%'>";
   	echo "<td width = '20%'></td>";
   	echo "<td width = '40%'>单位排名</td>";

	if($num_players_school != 0)
	    $percent = round($my_position_school/$num_players_school*100);
	else
	    $percent = 0;
	if($percent < 1 && $percent > 0)
	    $percent = 1;

    echo "          <td width = '40%'>$my_position_school/$num_players_school (Top $percent%)</td>\n";
	echo "	</tr>\n";

	echo "	<tr width = '100%'>\n";
	echo "		<td width = '20%'></td>\n";
	echo "		<td width = '40%'>学校排名</td>\n";

	if($num_players != 0)
	    $percent = round($my_position/$num_players*100);
	else
	    $percent = 0;

	if($percent < 1 && $percent > 0)
	    $percent = 1;
	echo "		<td width = '40%'>$my_position/$num_players (Top $percent%)</td>\n";
	echo "	</tr>\n";
	echo "</table>";

    echo "<table width=100%>";
	printf("  <tr bgcolor='#FFA500'>\n");
	printf("   <td height=1 colspan='3'></td>\n");		
	printf("  </tr>\n");
       echo "  <tr>\n";
    echo "          <td width = '20%'>睡眠</td>\n";
    echo "          <td width = '40%'>入睡时间</td>\n";
    echo "          <td width = '40%'>" . date('H:i:s', $sleepStartTime) . "</td>\n";
    echo "  </tr>\n";
    
    echo "  <tr width = '100%'>\n";
    echo "          <td width = '20%'></td>\n";
    echo "          <td width = '40%'>醒来时间</td>\n";
    echo "          <td width = '40%'>" . date('H:i:s', $sleepEndTime) . "</td>\n";
    echo "  </tr>\n";

	$sleep_min = $deepSleepTime%60;
	$sleep_hour = ($deepSleepTime - $sleep_min)/60;

    echo "  <tr width = '100%'>\n";
    echo "          <td width = '20%'></td>\n";
    echo "          <td width = '40%'>深睡时长</td>\n";
    echo "          <td width = '40%'>$sleep_hour"."时".$sleep_min."分</td>\n";
    echo "  </tr>\n";

	$sleep_min = $shallowSleepTime%60;
	$sleep_hour = ($shallowSleepTime - $sleep_min)/60;
    echo "  <tr width = '100%'>\n";
    echo "          <td width = '20%'></td>\n";
    echo "          <td width = '40%'>浅睡时长</td>\n";
    echo "          <td width = '40%'>$sleep_hour"."时".$sleep_min."分</td>\n";
    echo "  </tr>\n";
	
    echo "</table>";

    $query_diet="SELECT passphyid FROM jaccount_table WHERE jaccount='$jaccount' ";

	// $query_diet="SELECT jaccount_table.passphyid FROM jaccount_xm_assoc INNER JOIN jaccount_table WHERE jaccount_xm_assoc.xiaomiid=$userid AND jaccount_xm_assoc.jaccount=jaccount_table.jaccount";
	require_once('/var/www/jdjbz/mysql_connect.php');
	$query_diet_result=mysql_query($query_diet);
    if(!$query_diet_result) {
            die('Error fetching diet info: ' . mysql_error()); 
    }
    $diet_row=mysql_fetch_array($query_diet_result);
    if(!$diet_row){
		// echo "Error fetching data for diet of $userid<br />";
		echo "你还没有绑定jaccount账号";
		return;
	}
	$user=$diet_row[0];     //用户的物理卡号，对应excel中的id


	$file="/var/www/jdjbz/data/diet/".$user.".txt";

	// $file="/var/www/jdjbz/data/diet/56220108.txt";
	// $fp=fopen($file,"r")or die("unable to open file");
	if($fp=fopen($file,"r")){
	$lines=0;
	$first_line=get_line($file,1);
	$columns_first_line=explode(' ', $first_line);
	if($columns_first_line[0]!=$date){
		echo "<table width='100%'>";
		echo "<tr bgcolor='#FFA500'>";
		echo "<td height=1 colspan='4'></td>";
		echo "</tr>"; 
		echo "</table>"; 
		echo "<p>无今日就餐记录</p>";
	}
	else{
		echo "<table width='100%'>";


while(!feof($fp)){
	$line=fgets($fp);               //逐行读取文件
	$columns=explode(' ',$line);
	if($columns[0]==$date){        //找到当日的那行数据
		
		// $diet_amount=count($columns)-4;

			echo "<tr width='100%'>";
			echo "<td width='20%'>$columns[2]</td>";
			// echo "<td width='25%'></td>";
			echo "<td width='40%'>菜品</td>";
			echo "<td width='40%'>份数</td>";
			echo "</tr>";
			echo "<tr bgcolor='#FFA500'>";
			echo "<td height=1 colspan='4'></td>";
			echo "</tr>";  
        	for($i=4;$i<count($columns);$i++){
        	 	$dishes=explode('*',$columns[$i]);
        	 	$file2="/var/www/jdjbz/data/diet/dish_list.txt";
				$fp2=fopen($file2,"r")or die("unable to open file2");
        	
        	while(!feof($fp2)){
        		$line2=fgets($fp2);
        		$columns2=explode(' ',$line2);
        	
        		if($columns2[0]==$dishes[0])
        		{
        			$dishes[0]=$columns2[1];     			
        		}
        	}
        	fclose($fp2);      
        	if($lines%2==0){
        		echo "<tr width='100%' bgcolor='#FFE4A4'>";
        	}
        	else{
        		echo "<tr width='100%'>";
        	}
        	$lines++;
			echo "<td width='20%'></td>";
	
			echo "<td width='40%'>$dishes[0]</td>";
			echo "<td width='40%'>$dishes[1]</td>";
			echo "</tr>";
        }
        continue;
	}
}


}
fclose($fp);
echo "</table>";
}
else{
	echo "<table width='100%'>";
	echo "<tr bgcolor='#FFA500'>";
	echo "<td height=1 colspan='4'></td>";
	echo "</tr>"; 
	echo "</table>"; 
	echo "<p>无今日就餐记录</p>";

}


?>

?>