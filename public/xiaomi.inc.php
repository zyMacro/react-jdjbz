<?php
    $jdjbzFilePath = '/var/www/jdjbz';

    $redirectHost = 'http://health.sjtu.edu.cn/react-jdjbz/dist/';

    $clientId = 2882303761517396518;
    $clientSecret = 'IGiYI7vSFyuHIw+bs6AbeQ==';

    $third_appid = 2860421236;
    $third_app_secret = '4727761daaf76815f94cbc7bb283769e';

    $redirectHost = 'http://health.sjtu.edu.cn/react-jdjbz/dist/';

    require_once("$jdjbzFilePath/php-sdk/utils/XMUtil.php");
    require_once("$jdjbzFilePath/php-sdk/utils/AccessToken.php");
    require_once("$jdjbzFilePath/php-sdk/httpclient/XMHttpClient.php");
    require_once("$jdjbzFilePath/php-sdk/httpclient/XMOAuthClient.php");
    require_once("$jdjbzFilePath/php-sdk/httpclient/XMApiClient.php");

    // api
    $userProfilePath = '/user/profile';
?>