<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 2016/10/25
 * Time: ÉÏÎç1:55
 */

define('JIEQI_PAY_TYPE', 'wechat_xy');
if (!defined("JIEQI_HTTP_HOST")) {
    define("JIEQI_HTTP_HOST",$_SERVER['HTTP_HOST']);
}
include_once(dirname(__FILE__)."/../../../configs/pay/wechat_xy.php");
require(dirname(__FILE__).'/Utils.class.php');
require(dirname(__FILE__).'/Props.php');
require(dirname(__FILE__).'/RequestHandler.class.php');
require(dirname(__FILE__).'/ResponseHandler.class.php');
require(dirname(__FILE__).'/HttpClient.php');
require(dirname(__FILE__).'/Log.php');





$payid=intval($_POST['payid']);
$money=intval($_POST['money']);
//$money=1;
$body=trim($_POST['body']);
$detail=trim($_POST['detail']);
$attach=trim($_POST['attach']);
$ip=trim($_POST['ip']);
$trade_type=trim($_POST['trade_type']);
$uid=$_POST['uid'];
$openid=$_POST['openid'];

$pay_vars=array(
    'service'=>'pay.weixin.jspay',
    'mch_id'=>$jieqiPayset["wechat_wap"]['customerid'],
    'out_trade_no'=>$payid,
    'body'=>$body,
    'sub_openid'=>$openid,
    'total_fee'=>$money,
    'mch_create_ip'=>$ip,
    'notify_url'=>$jieqiPayset["wechat_wap"]['noticeurl'],
    'callback_url'=>$jieqiPayset["wechat_wap"]['backurl']."?payid=$payid",
    'nonce_str'=>mt_rand(time(),time()+rand()),
    'attach'=>$attach
);


$pay_vars=Utils::createSign($pay_vars,$jieqiPayset["wechat_wap"]['key']);

//print_r($pay_vars);
//exit();

$data=Utils::to($pay_vars);



$http_client = new HttpClient();
$http_client->setReqContent($jieqiPayset["wechat_wap"]['payurl'],$data);
if ($http_client->invoke()) {
    $xml = new SimpleXMLElement($http_client->getResContent());
    echo $xml->asXML();
} else {
    echo json_encode(array('status' => 500, 'msg' => 'Response Code:' . $http_client->getResponseCode() . ' Error Info:' . $http_client->getErrInfo()));
}