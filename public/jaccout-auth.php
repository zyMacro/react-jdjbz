<?php
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
	require_once("mcrypt.inc.php");

	function storeJaccountInfo(){
		global $clientId, $authorize_endpoint, $clientSecret, $token_endpoint, $redirect_uri;
		$xiaomiid = $_SESSION['userId'];
		$jaccount = $_SESSION['jaccount'];

		if($xiaomiid == NULL && $jaccount == NULL){
			return;
		}
	
		$clientConfig = new ClientConfig(
		    array(
		        'authorize_endpoint' => $authorize_endpoint,
		        'client_id' => $clientId,
		        'client_secret' => $clientSecret,
		        'token_endpoint' => $token_endpoint,
		        'use_array_scope' => 1,
		        'redirect_uri' => $redirect_uri,
		    )
		);

		$tokenStorage = new SessionStorage();
		$httpClient = new Guzzle3Client();
		$api = new Api('health-sjtu', $clientConfig, $tokenStorage, $httpClient);
		$context = new Context('health-sjtu', array("basic","essential","profile","card_info"));
		$accessToken = $api->getAccessToken($context);
		if (false === $accessToken) {
			//not logged in, do nothing and return;
			return;
		}

		$json = getJaccountProfile($accessToken->getAccessToken());
		$myprofile_json = json_decode($json, true);	

		$json = getJaccountCardInfo($accessToken->getAccessToken());
		$mycard_json = json_decode($json, true);

		$mycardnumber = $mycard_json['entities'][0]['cardNo'];
		$myphycardnumber = $mycard_json['entities'][0]['cardId'];

		$myprofile_json['entities'][0]['passId'] 	= $mycardnumber;
		$myprofile_json['entities'][0]['passPhyId'] = $myPhycardnumber;
		$myprofile = new JaccountUser($myprofile_json['entities'][0]);

		$jaccount = $myprofile->getAccount();
		$name = $myprofile->getName();
		$code = $myprofile->getCode(); //学号、工号等
		$userType = $myprofile->getUserType();
		$organize = $myprofile->getOrganize();
		$birthday = $myprofile->getBirthday();
		$gender = $myprofile->getGender();
		$email = $myprofile->getEmail();
		
		$cardType = $myprofile->getCardType();
		$passid = $myprofile->getPassId();
		$passPhyid = $myprofile->getPassPhyId();
		$expireDate = $myprofile->getExpireDate();
		$status = $myprofile->getStatus();

		$mobile = $myprofile->getMobile();
		$mobileenc = encrypt_info($mobile);
		
		$card = $myprofile->getCardNo(); //身份证或者其它证件
		$cardenc = encrypt_info($card);
		
/*
		if($xiaomiid == NULL){
			//not a xiaomi user
			//just store what we know about this user into the system
			//1. store into user_table

			$query = "INSERT user_table (name, userid, staffid, dept_id, school_id, last_login_time, type) VALUES ('$name', $code, $code, '$organize', '$organize', '$cur_date', '10001')";
			require_once('mysql_connect.php');
			$queryresult = mysql_query($query);
			if(!$queryresult) {
			   die('Invalid query: ' . mysql_error()); 
			}

			if($_SESSION['userid'] == NULL){
				//set the user as logged in with userid
				$xiaomiid = $_SESSION['userId'] = $code;
				$_SESSION['name'] = $name;
			}
		}
*/
	    /*
			require("mysql_pdo_connect.php");
			$query = $db->prepare($querystring);
			$query->execute();
		*/

		$querystring = "INSERT jaccount_table  (jaccount, name, code, userType, organize, gender, email, mobile, cardNo, 
												cardType, passid, passphyid, expireDate, birthday, status)
						 			   VALUES ('$jaccount', '$name', '$code', '$userType', '$organize', '$gender', '$email', '$mobileenc', '$cardenc', 
						 					   '$cardType', '$passid', '$passPhyid','$expireDate', '$birthday', '$status')";
	    $queryresult = mysql_query($querystring);
	    if(!$queryresult) {
	       die("While insert into jaccount_xb_table:" . mysql_error()); 
	    }
	}

	function updateJaccountInfo(){
		global $clientId, $authorize_endpoint, $clientSecret, $token_endpoint, $redirect_uri;
		$userid = $_SESSION['userId'];
		$jaccount = $_SESSION['jaccount'];

		if($userid == NULL && $jaccount == NULL){
			return;
		}

		$querystring = "SELECT xiaomiid FROM jaccount_xm_assoc WHERE jaccount=\"" ."$jaccount". "\"";

		require("mysql_pdo_connect.php");
		$query = $db->prepare($querystring);
		$query->execute();
		$rows = $query->fetchAll();
		$olduserid = $rows[0]['xiaomiid'];

		if($olduserid != $userid && $userid != NULL){
			$querystring = "UPDATE jaccount_xm_assoc SET xiaomiid = $userid WHERE jaccount=\"" ."$jaccount". "\"";
			require("mysql_pdo_connect.php");
			$query = $db->prepare($querystring);
			$query->execute();			
		}
	//	var_dump($rows);
		unset($db);


		$clientConfig = new ClientConfig(
		    array(
		        'authorize_endpoint' => $authorize_endpoint,
		        'client_id' => $clientId,
		        'client_secret' => $clientSecret,
		        'token_endpoint' => $token_endpoint,
		        'use_array_scope' => 1,
		        'redirect_uri' => $redirect_uri,
		    )
		);

		$tokenStorage = new SessionStorage();
		$httpClient = new Guzzle3Client();
		$api = new Api('health-sjtu', $clientConfig, $tokenStorage, $httpClient);
		$context = new Context('health-sjtu', array("basic","essential","profile","card_info"));
		$accessToken = $api->getAccessToken($context);
		if (false === $accessToken) {
			//not logged in, do nothing and return;
			return;
		}

		$json = getJaccountProfile($accessToken->getAccessToken());
		$myprofile_json = json_decode($json, true);	

		$json = getJaccountCardInfo($accessToken->getAccessToken());
		$mycard_json = json_decode($json, true);

		$mycardnumber = $mycard_json['entities'][0]['cardNo'];
		$myphycardnumber = $mycard_json['entities'][0]['cardId'];

		$myprofile_json['entities'][0]['passId'] 	= $mycardnumber;
		$myprofile_json['entities'][0]['passPhyId'] = $myphycardnumber;

		$myprofile = new JaccountUser($myprofile_json['entities'][0]);		
		$jaccount = $myprofile->getAccount();
		$name = $myprofile->getName();
		$code = $myprofile->getCode(); //学号、工号等
		$userType = $myprofile->getUserType();
		$organize = $myprofile->getOrganize();
		$birthday = $myprofile->getBirthday();
		$gender = $myprofile->getGender();
		$email = $myprofile->getEmail();

		$card = $myprofile->getCardNo(); //身份证或者其它证件
		$cardenc = encrypt_info($card); //加密

		$mobile = $myprofile->getMobile();
		$mobileenc = encrypt_info($mobile);
		
		$cardType = $myprofile->getCardType();
		$passid = $myprofile->getPassId();
		$passPhyid = $myprofile->getPassPhyId();
		$expireDate = $myprofile->getExpireDate();
		$status = $myprofile->getStatus();

		require("mysql_connect.php");
		$querystring = "UPDATE jaccount_table  
						SET 	name='$name', code='$code',
								userType='$userType', organize='$organize', gender='$gender',
								email='$email', mobile='$mobileenc', cardNo='$cardenc', 
								cardType='$cardType', passid='$passid', passphyid='$passPhyid',
								expireDate='$expireDate', birthday='$birthday', status='$status' 
	 			   		WHERE 	jaccount='$jaccount' ";
	    $queryresult = mysql_query($querystring);
	    if(!$queryresult) {
	       die("While insert into jaccount_table:" . mysql_error()); 
	    }
	}

	session_start();

	$responseType = 'code';
	$sess_id=session_id();
	$xiaomi_id = $_SESSION['userId'];

    $redirectHost = 'http://health.sjtu.edu.cn/';

    $clientId = "2Z4nkiC0g2611BinKXE0L5ne";
    $clientSecret = '67DA85EA6758EC7AD68C91BF5EC18C70759BC2C454C7867F';

	$redirect_uri = $redirectHost."jaccount-callback.php?s=".$sess_id;

	$authorize_endpoint = "https://jaccount.sjtu.edu.cn/oauth2/authorize";
	$token_endpoint = "https://jaccount.sjtu.edu.cn/oauth2/token";

	$clientConfig = new ClientConfig(
	    array(
	        'authorize_endpoint' => $authorize_endpoint,
	        'client_id' => $clientId,
	        'client_secret' => $clientSecret,
	        'token_endpoint' => $token_endpoint,
	        'use_array_scope' => 1,
	        'redirect_uri' => $redirect_uri,
	    )
	);
	$tokenStorage = new SessionStorage();
	$httpClient = new Guzzle3Client();
	$api = new Api('health-sjtu', $clientConfig, $tokenStorage, $httpClient);
	$context = new Context('health-sjtu', array("basic","essential","profile","card_info"));
	$accessToken = $api->getAccessToken($context);
	if (false === $accessToken) {
	    /* no valid access token available, go to authorization server */
	    header('HTTP/1.1 302 Found');
	    header('Location: '.$api->getAuthorizeUri($context));
	    exit;
	}

	//the user has been authorized by jaccount, handover to jaccount.
	$json = getJaccountProfile($accessToken->getAccessToken());
	$myprofile_json = json_decode($json, true);

	$myprofile = new JaccountUser($myprofile_json['entities'][0]);

	$account = $myprofile->getAccount();
	$name = $myprofile->getName();
	$code = $myprofile->getCode();
	$_SESSION['jaccount'] = $account;
	$_SESSION['name'] = $name;
	$_SESSION['employeeid'] = '$code';
    $_SESSION['login'] = 'JA';

	$filename = "/var/log/jdjbz/login.log";
    if(!file_put_contents ($filename,  date("Y-m-d H:i:s") . " " . $account. "(". $code. ")" . " logged in through jaccount\n", FILE_APPEND | LOCK_EX))
		error_log ("Error write data into file for user $account\n");

    $query = "SELECT name FROM jaccount_table WHERE jaccount = \"$account\"";
    require_once('mysql_connect.php');
    $queryresult = mysql_query($query);

    if(!$queryresult) {
       die("While fetching info for [$xm_userId] with $query:" . mysql_error()); 
    }
    $num_rows = mysql_num_rows($queryresult);

    if($num_rows == 0){
		try{			
			storeJaccountInfo();	
		}catch(Exception  $e){
			echo "Jaccount error: ".$e;
		}
    }else{
		try{
			updateJaccountInfo();
		}catch(Exception  $e){
			echo "Update jaccount error: ".$e;
		}
    }
//    echo $_SESSION['jaccount'] . "<br />";

	$url =  $redirectHost.'myhome.php?s='.$sid;
	Header("HTTP/1.1 302 Found");
	Header("Location: $url");
?>