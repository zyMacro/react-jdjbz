<?php
require_once("xiaomi.inc.php");

session_start();

function getXmData($access_token, $fromdate, $todate){
	global $clientId, $third_appid, $third_app_secret;
	//set url
	$url='https://hm.xiaomi.com/huami.api.getUserSummaryData.json';
	//set post fields
	$fields=array(
		'appid'=>$clientId,
		'third_appid'=>$third_appid,
		'third_appsecret'=>$third_app_secret,
		'call_id'=>time(),
		'access_token'=>$access_token,
		'fromdate'=>$fromdate,
		'todate'=>$todate,
		'v'=>'1.0',
		'l'=>'english'
		);

//	var_dump($fields);

	//init curl
	$ch=curl_init();
	//set options of curl
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HEADER,0);
	//get result of json
	$json=curl_exec($ch);
	//close curl
	curl_close($ch);

	return $json;
}

function getHMData($access_token, $mac_key, $fromdate, $todate){
	global $clientId, $third_appid, $third_app_secret;
	//set url
	$url='https://hmservice.mi-ae.com.cn/user/summary/getData';
//	$url='https://hm.xiaomi.com/huami.api.getUserSummaryData.json';

	//set post fields
	if ($mac_key == "")
		$mac_key = "mac_key";
	
	$fields=array(
		'appid'=>$clientId,
		'third_appid'=>$third_appid,
		'third_appsecret'=>$third_app_secret,
		'call_id'=>time(),
		'access_token'=>$access_token,
		'mac_key'=>$mac_key,
		'fromdate'=>$fromdate,
		'todate'=>$todate,
		'v'=>'1.0',
		'l'=>'english'
		);

	$params = http_build_query($fields);
//	var_dump($fileds);

	//init curl
	$ch=curl_init();
	//set options of curl
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HEADER,0);
	//get result of json
	$json=curl_exec($ch);
	//close curl
	curl_close($ch);

	return $json;
}

function getHMInfo($access_token, $mac_key){
	global $clientId, $third_appid, $third_app_secret;
	//set url
	$url='https://hmservice.mi-ae.com.cn/user/info/getData';
//	$url='http://health.sjtu.edu.cn/getData.php';
	//set post fields
	if ($mac_key == "")
		$mac_key = "mac_key";
	
	$fields = array(
		'appid'=>$clientId,
		'call_id'=>time(),
		'third_appid'=>$third_appid,
		'third_appsecret'=>$third_app_secret,
		'access_token'=>$access_token,
		'v'=>'1.0',
		'l'=>'english',
		'mac_key'=>$mac_key
	);

	$params = http_build_query($fields);

//	var_dump($params);

	//init curl
	$ch=curl_init();
	//set options of curl
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//get result of json
	$json=curl_exec($ch);

	//close curl
	curl_close($ch);

	return $json;
}


function getHMRunningData($access_token, $mac_key, $fromdate, $todate){
	global $clientId, $third_appid, $third_app_secret;
	//set url
	$url='https://hmservice.mi-ae.com.cn/user/sport/getData';
//	$url='https://hm.xiaomi.com/huami.api.getUserSummaryData.json';

	//set post fields
	if ($mac_key == "")
		$mac_key = "mac_key";
	
	$fields=array(
		'appid'=>$clientId,
		'third_appid'=>$third_appid,
		'third_appsecret'=>$third_app_secret,
		'call_id'=>time(),
		'access_token'=>$access_token,
		'mac_key'=>$mac_key,
		'fromdate'=>$fromdate,
		'todate'=>$todate,
		'v'=>'1.0',
		'l'=>'english'
		);

	$params = http_build_query($fields);
//	var_dump($fileds);

	//init curl
	$ch=curl_init();
	//set options of curl
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HEADER,0);
	//get result of json
	$json=curl_exec($ch);
	//close curl
	curl_close($ch);

	return $json;
}

?>
