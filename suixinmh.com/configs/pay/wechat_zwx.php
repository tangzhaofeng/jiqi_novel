<?php
//梓微兴微信支付相关配置
$jieqiPayset['wechat_zwx']['customerid']='15124229';	//商户号
$jieqiPayset['wechat_zwx']['noticeurl']='http://'.JIEQI_HTTP_HOST.'/pay/wechat_zwx_notify'; //异步通知地址
$jieqiPayset['wechat_zwx']['backurl']='http://'.JIEQI_HTTP_HOST.'/pay/checkwechat_zwx';  //回调地址
$jieqiPayset['wechat_zwx']['key']='7ff9318e7d0cd6238ae999d08a11446a';  //密钥
$jieqiPayset['wechat_zwx']['payurl']='https://api.zwxpay.com/pay/unifiedorder';  //微信手机网页 支付请求地址




$jieqiPayset['wechat_zwx']['remarks']=array( //商户自定义信息
	'3000'=>JIEQI_EGOLD_NAME.'30元充值', 
	'5500'=>JIEQI_EGOLD_NAME.'50元充值', 
	'11200'=>JIEQI_EGOLD_NAME.'100元充值', 
	'23000'=>JIEQI_EGOLD_NAME.'200元充值', 
	'60000'=>JIEQI_EGOLD_NAME.'500元充值', 
	'125000'=>JIEQI_EGOLD_NAME.'1000元充值'
	);



$jieqiPayset['wechat_zwx']['paylimit']=array(
	'3000'=>'30', 
	'5500'=>'50', 
	'11200'=>'100', 
	'23000'=>'200', 
	'60000'=>'500',
	'125000'=>'1000'
);

if (date("Y-m-d")>='2016-09-01' && date("Y-m-d")<='2016-10-07'){
    $jieqiPayset['wechat_zwx']['gift'] = array(
        '3000'=>'300',
        '5500'=>'800',
        '11200'=>'2000',
        '23000'=>'5000',
        '60000'=>'15000',
        '125000'=>'30000'
    );
}
else {
    $jieqiPayset['wechat_zwx']['gift'] = array();
}

//支付增加积分
//$jieqiPayset['wechat_zwx']['payscore']=array('2000'=>'1000', '5000'=>'2500', '10000'=>'5000', '20000'=>'10000', '50000'=>'25000', '100000'=>'50000');

$jieqiPayset['wechat_zwx']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['wechat_zwx']['paysilver']='0';  //0 表示冲值成金币 1表示银币

?>