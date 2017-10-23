<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 2016/10/25
 * Time: ÉÏÎç1:55
 */

define('JIEQI_PAY_TYPE', 'wechat_zwx');
if (!defined("JIEQI_HTTP_HOST")) {
    define("JIEQI_HTTP_HOST",$_SERVER['HTTP_HOST']);
}
include_once(dirname(__FILE__)."/../../../configs/pay/wechat_zwx.php");
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

$pay_vars=array(
    'mch_id'=>$jieqiPayset[JIEQI_PAY_TYPE]['customerid'],
    'nonce_str'=>mt_rand(time(),time()+rand()),
    'body'=>$body,
    'detail'=>$detail,
    'attach'=>$attach,
    'out_trade_no'=>$payid,
    'total_fee'=>$money,
    'fee_type'=>'CNY',
    'spbill_create_ip'=>$ip,
    'notify_url'=>$jieqiPayset[JIEQI_PAY_TYPE]['noticeurl'],
    'return_url'=>$jieqiPayset[JIEQI_PAY_TYPE]['backurl']."?payid=$payid",
    'trade_type'=>$trade_type ? $trade_type : 'trade.weixin.jspay'
);


$pay_vars=Utils::createSign($pay_vars,$jieqiPayset[JIEQI_PAY_TYPE]['key']);

$data=Utils::to($pay_vars);

$http_client = new HttpClient();
$http_client->setReqContent($jieqiPayset['wechat_zwx']['payurl'],$data);
if ($http_client->invoke()) {
    $xml = new SimpleXMLElement($http_client->getResContent());
    echo $xml->asXML();
} else {
    echo json_encode(array('status' => 500, 'msg' => 'Response Code:' . $http_client->getResponseCode() . ' Error Info:' . $http_client->getErrInfo()));
}