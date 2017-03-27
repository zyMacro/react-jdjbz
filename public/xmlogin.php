<?php
	$page_title = ":::HOME:::"; 
	include ('header.html');
?>
<body>
<?php 
	session_start();

	require_once('xiaomi.inc.php');
	require_once('jdjbz.php');
	require_once('xmdata.php');

	$_SESSION['userId'] = 123456;
	$_SESSION['name'] = "Guest";
	$_SESSION['tokenId'] = "1234567";
	$_SESSION['expiresin'] = 0;
	$_SESSION['refreshToken'] = "";

	$code = $_GET["code"];

	$sid=session_id();
	$redirectUri = $redirectHost.'xmlogin.php?s='.$sid;

	if($code) {
	    $oauthClient = new XMOAuthClient($clientId, $clientSecret );
	    $oauthClient->setRedirectUri($redirectUri);
	    $token = $oauthClient->getAccessTokenByAuthorizationCode($code);
	    if($token) {
	//		var_dump($token);
			if  ($token->isError()) {
			    $errorNo = $token->getError();
			    $errordes = $token->getErrorDescription();
			    print "抱歉，通过小米服务器认证失败，请稍候再试！<br >错误编号 : ".$errorNo. "   错误描述 : ".$errordes."<br>";
			} else {
		//            var_dump($token);
			    // 拿到token id
			    $tokenId = $token->getAccessTokenId();

			    //拿到mac_key
			    $mac_key = $token->getMacKey();

			    $mac_algorithm = $token->getMacAlgorithm();

			    // 创建api client
			    $xmApiClient = new XMApiClient($clientId, $tokenId);
			     
			    // 获取nonce  随机数:分钟
			    $nonce = XMUtil::getNonce();

			    $path = $userProfilePath;
			    $method = "POST";
			    $params = array('token' => $tokenId, "clientId" => $clientId);
			     
			    // 计算签名
			    $sign = XMUtil::buildSignature($nonce, $method,  $xmApiClient->getApiHost(), $path, $params, $token->getMacKey());

			    // 构建header
			    $head =XMUtil::buildMacRequestHead($tokenId, $nonce, $sign);

			    // 访问api
			    $result = $xmApiClient->callApi($userProfilePath, $params, false, $head);

			    // 返回json
			    $result = $xmApiClient->callApiSelfSign($userProfilePath, array(), $token->getMacKey());

			    // 返回json
		  //          var_dump($result);

			    $_SESSION['tokenId'] = $tokenId;
			    $_SESSION['expiresin'] = $token->getExpiresIn();
			    $_SESSION['refreshToken'] = $token->getRefreshToken();
			    $_SESSION['mac_key'] = $mac_key;
			    $_SESSION['mac_algorithm'] = $mac_algorithm;

			    $xm_userId = "";

			    $xm_userId = xm_get_userId($result);
			    $_SESSION['userId'] = $xm_userId;

			    if($xm_userId == -1) {
					echo "<p>Failed to authenticate, please come back later...</p>";
					exit();
			    }
			    
			    $query = "SELECT name, access_token_id, expires_in, refresh_token, dept_id, school_id, 
			    				staffid, mac_key, mac_algorithm FROM user_table WHERE userid = $xm_userId";

			    require_once('mysql_connect.php');
			    $queryresult = mysql_query($query);

			    if(!$queryresult) {
			       die("While fetching info for [$xm_userId] with $query:" . mysql_error()); 
			    }
			    $num_rows = mysql_num_rows($queryresult);

			    if($num_rows == 0){
					$sid=session_id();
					$url =  $redirectHost.'firsttime_login.php?s='.$sid;
					Header("HTTP/1.1 302 Found");
					Header("Location: $url");
			    } else {
					$name = mysql_result($queryresult, 0, 0);
					$accesstoken = mysql_result($queryresult, 0, 1);
					$expiresin = mysql_result($queryresult, 0, 2);
					$refreshtoken = mysql_result($queryresult, 0, 3);
					$dept = mysql_result($queryresult, 0, 4);
					$school = mysql_result($queryresult, 0, 5);
					$employeeid = mysql_result($queryresult, 0, 6);
					$mac_key = mysql_result($queryresult, 0, 7);
					$mac_algorithm = mysql_result($queryresult, 0, 8);

					$_SESSION['dept'] = $dept;
					$_SESSION['school'] = $school;
					$_SESSION['employeeid'] = $employeeid; 
					$_SESSION['name'] = $name;

					//update tokens
					if($accesstoken != $_SESSION['tokenId']) {
					    $accesstoken = $_SESSION['tokenId'];
					    error_log("Updating access_token_id for $name");
					}

					if($expiresin != $_SESSION['expiresin']) {
					    $expiresin = $_SESSION['expiresin'];
					    error_log("Updating expiresin for $name");
					}

					if($refreshtoken != $_SESSION['refreshToken']) {
					    $refreshtoken = $_SESSION['refreshToken'];
					    error_log("Updating expiresin for $name");
					}

					if($mac_key != $_SESSION['mac_key']) {
					    $mac_key = $_SESSION['mac_key'];
					    error_log("Updating mac_key for $name");
					}
					
					if($mac_algorithm != $_SESSION['mac_algorithm']) {
					    $mac_algorithm = $_SESSION['mac_algorithm'];
					    error_log("Updating mac_algorithm for $name");
					}
					
					//update tokens
					$curdate = date('Y-m-d');
					$query = "UPDATE user_table SET last_login_time='$curdate', access_token_id='$accesstoken', expires_in=$expiresin, refresh_token='$refreshtoken', mac_key ='$mac_key', mac_algorithm='$mac_algorithm' WHERE userid = $xm_userId";
					$queryresult = mysql_query($query);
					if(!$queryresult) {
					   die("While update info for [$xm_userId] with $query:" . mysql_error()); 
					}
					if(!empty($_SESSION['returning-URL'])){
					    $url =  $redirectHost.$_SESSION['returning-URL'];
						Header("HTTP/1.1 302 Found");
						Header("Location: $url");
						return;
					}
					
					if(is_numeric($school) && $school!=0)
					    $url =  $redirectHost."myhome.php";
					else
					    $url = $redirectHost.'update-info-first.php';

				    $_SESSION['login'] = 'MI';
					$filename = "/var/log/jdjbz/login.log";
				    if(!file_put_contents ($filename,  date("Y-m-d H:i:s") . " " . $name . " logged in with xiaomi account " . $xm_userId . "\n", FILE_APPEND | LOCK_EX))
						error_log ("Error write data into file for user $account\n");

					Header("HTTP/1.1 302 Found");
					Header("Location: $url");
			    }

			    mysql_close($link);
			
			    if($queryresult != NULL) 
			    {
					mysql_free_result($queryresult);
			    }
			}
	    }else {
		print "Get token Error";
	    }
	} else {
	    print "Sorry!! Get code error : ".  $_GET["error"]. "  error description : ".  $_GET["error_description"];
	}
?>
<?php
	include ('footer.html');
?>
