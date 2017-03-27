<?php
	require_once('xiaomi.inc.php');
	session_start();

	$responseType = 'code';
	$sess_id=session_id();
	$redirectUri = $redirectHost."xmlogin.php?s=".$sess_id;
	print $redirectUri;
	$oauthClient = new XMOAuthClient($clientId, $clientSecret );
	$oauthClient->setRedirectUri($redirectUri);
	$state = 'state';
	$url = $oauthClient->getAuthorizeUrl($responseType, $state);

	Header("HTTP/1.1 302 Found");
	Header("Location: $url");
?>
