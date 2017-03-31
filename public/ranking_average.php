<?php
$jdjbzDataPath = '/var/www/jdjbz/data/';

    function getDataOnDate($userid, $ondate)
    {
    	global $jdjbzDataPath;
        $pathname = $jdjbzDataPath."/".$userid;

        if(!file_exists($pathname)) {
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
        	$match = preg_match("/^\d+\s\d+\s\d+\s\d+\s\d+\s(\d+)\s\d+\s\d+\s\d+\s\d+\s\d+\s\d+$/", $line, $datas) ;

        	if ($match == 1) {
            		return $datas[1];
        	} else {
            		$match = preg_match("/^\d+:\d+:\d+\s\d+\s\d+\s\d+\s\d+\s\d+\s(\d+)\s\d+\s\d+\s\d+\s-?\d+\s-?\d+\s\d+$/", $line, $datas) ;
        	}
		$i--;
		if($i < 0)
			break;
      	}
		if($match)		
			return $datas[1];
		else
			return 0;
}

// require_once('basic_data.php');
require('/var/www/jdjbz/mysql_connect.php');
session_start();
if(empty($_SESSION['userId']) || $_SESSION['userId'] == 123456 ||  $_SESSION['userId'] == -1){
	    $url =  $redirectHost.'index.html';
	    Header("HTTP/1.1 302 Found");
	    Header("Location: $url");
	}
	$myname = $_SESSION['name'];
	$school = $_SESSION['school'];
	
	$scope = $_GET['s'];
	 if($scope == NULL || $scope == 0 || !is_numeric($scope))
		$query = "SELECT userid, name, school_id FROM user_table";
    else
		$query = "SELECT userid, name FROM user_table WHERE school_id = $scope";

	$queryresult = mysql_query($query);

	if(!$queryresult) {
	   die('Error while fetching data from DB: ' . mysql_error()); 
	}

	$ranklist = array();
	$namelist = array();
	$schoollist = array();
	$numdays = array();
	$total_steps = array();

	$num_rows = mysql_num_rows($queryresult);
	for($i=0; $i<$num_rows; $i++) {
	    $userid = mysql_result($queryresult, $i, 0);
	    $name = mysql_result($queryresult, $i, 1);
		$school = mysql_result($queryresult, $i, 2);

        $pathname = $jdjbzDataPath.$userid;
	    $datafiles = scandir($pathname, 1);
//	    var_dump($datafiles);
	    $numfiles = count($datafiles);

//	    echo "$pathname<br />files: $numfiles <br />";
	    $num_days = 0;
	    for($j=0; $j<$numfiles; $j++){
	        $filename = $datafiles[$j];
	        $match = preg_match("/^(\d+-\d+-\d+).txt$/", $filename, $date) ;  
	        if($match){
	        	$this_day = $date[1];
	            $steps = getDataOnDate($userid, $this_day);
	            if($steps > 0){
//			        echo "$name: $this_day, $steps <br />\n";
		        	$num_days ++;
		            $total_steps[$userid] += $steps;
	            }
	        }
	    }

	    if($num_days > 0){
	    	$ranklist[$userid] = round($total_steps[$userid]/$num_days);
	    }
	    else
	    	$ranklist[$userid] = 0;	

		$numdays[$userid] = $num_days;
	    $namelist[$userid] = $name;
	    $schoollist[$userid] = $school;
	}
    $fp = file("/var/www/jdjbz/danwei.txt");
    arsort($ranklist);
	mysql_close($link);
	if($queryresult != NULL) 
	{
	    mysql_free_result($queryresult);
	}
 $data=array('numdays'=>$numdays,'nameList'=>$namelist,'schoolList'=>$schoollist,'rankList'=>$ranklist);
 $data=json_encode($data);
 echo $data;

     // $i = 0;
   //       while (list($key, $val) = each($ranklist))
  	// {
  	// 	if(strlen($namelist[$key]) <= 2 || $numdays[$key] == 0){
  	// 		continue;
  	// 	}

        // if($i == 0)
        //         printf("        <tr bgcolor='#FFD700'>\n");
        // else if($i == 1)
        //         printf("        <tr bgcolor='#C0C0C0'>\n");
        // else if($i == 2)
        //         printf("        <tr bgcolor='#F4A460'>\n");
        // else if($i %2 == 0)
        //         printf("  <tr>\n");
        // else
        //     printf("  <tr bgcolor='#EEEEEE'>\n");

  //       if($namelist[$key] == $myname)
		// 	printf("   <td width='10%%'><table cellspacing='0' cellborder='0' bgcolor='#00FF00' width='20%%'><tr><td><div id='tableintable'>%d</div></td></tr></table></td>\n", $i+1);
		// else 
		// 	printf("   <td width='10%%'>%d</td>\n",$i+1);
	    
		// printf("   <td valign = 'center'  width='15%%'><a href='show_user.php?u=%d'>%s</a></td>\n",$key, $namelist[$key]);
	    // if($scope == NULL){
	    // 	if($schoollist[$key] != 0)
	    // 		$myschool = $fp[$schoollist[$key]];
	    // 	else
	    // 		$myschool = "*未设定*";
	    // }else if(is_numeric($scope))
	    // 	$myschool = $fp[$scope];

	    // if($myschool == NULL)
	    // 	$myschool = "*未设定*";

		// printf("   <td valign = 'center' width='50%%'>%s</td>\n",$myschool);		
		// printf("   <td valign = 'center' width='10%%'>%d 天</td>\n",$numdays[$key]);		
		// $steps = $val;


  //       if($steps > 20000)
  //           echo "      <td width='25%'><div id='over20K'>$steps</div></td>\n";
  //       else if($steps > 10000)
  //           echo "      <td width='25%'><div id='over10K'>$steps</div></td>\n";
  //       else
  //           echo "      <td width='25%'><div id='below10K'>$steps</div></td>\n";

		// printf("  </tr>\n");
		// $i ++;
	// }



?>