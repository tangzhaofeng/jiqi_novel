<?php

$appid = "wx73a2e606e8fae039";
$redirect_uri=urlencode('http://www.ishufun.net/weixin/test.php');
$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
header("Location:".$url);

?>