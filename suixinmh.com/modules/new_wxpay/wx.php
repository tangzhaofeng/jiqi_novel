<?php
ini_set("display_errors",'On');
error_reporting(E_ALL^E_NOTICE);
include 'config.php';

$goods=($_GET['goods']*100).'书币';
$order_id=$_GET['order_id'];
$money=$_GET['money'];
$ymdhis=$_GET['ymdhis'];
$open_id=$_GET['open_id'];

$notify_url='http://'.$_SERVER['HTTP_HOST'].'/notify.php';

include 'lib/WxPay.Config.php';
include 'jsapi/WxPay.JsSdk.php';
$jssdk = new JSSDK(WxPayConfig::APPID, WxPayConfig::APPSECRET);
$signPackage = $jssdk->GetSignPackage();

include 'lib/WxPay.Api.php';
include 'jsapi/WxPay.JsApiPay.php';
$tools=new JsApiPay();
$input=new WxPayUnifiedOrder();
$input->SetBody($goods);
$input->SetAttach($goods);
$input->SetOut_trade_no($order_id);
$input->SetTotal_fee($money);
$input->SetTime_start($ymdhis);
//$input->SetTime_expire(date("YmdHis",time()+600));
$input->SetGoods_tag($goods);
$input->SetNotify_url($notify_url);
$input->SetTrade_type("JSAPI");
$input->SetOpenid($open_id);
$order=WxPayApi::unifiedOrder($input);

$jsApiParameters=json_decode($tools->GetJsApiParameters($order),TRUE);
exit(
    json_encode(
        array(
            'sign'=>$signPackage,
            'jsapi'=>$jsApiParameters,
        )
    )
);
?>