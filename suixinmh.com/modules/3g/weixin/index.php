<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 15/12/27
 * Time: ÏÂÎç4:37
 */
include_once("../../../global.php");
include_once("wechat_pinshu.class.php");
$wechat = new PinshuWechat(array(
    'token' => $token,
    'aeskey' => $encodingAesKey,
    'appid' => $appId,
    'debug' => $debugMode
));
$wechat->run();
//include_once("../../article/model/searchModel.php");
//$dataObj = new searchModel();
//$searchkey = "×Ü²Ã";
//$data = $dataObj->search(array("searchkey"=>$searchkey));
//print_r($data);