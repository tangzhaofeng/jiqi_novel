<?php
//星启天微信支付相关配置
$jieqiPayset['wechat_wap']['customerid']='154740';	//商户号
$jieqiPayset['wechat_wap']['cardno']='32';	//支付方式：固定值32
$jieqiPayset['wechat_wap']['noticeurl']='http://'.JIEQI_HTTP_HOST.'/pay/wechat_notify'; //异步通知地址
$jieqiPayset['wechat_wap']['backurl']='http://'.JIEQI_HTTP_HOST.'/pay/checkwechat';  //回调地址
$jieqiPayset['wechat_wap']['key']='8d829506f88e650c0087a5bf3017e4e2';  //密钥
$jieqiPayset['wechat_wap']['payurl']='http://www.zhifuka.net/gateway/weixin/wap-weixinpay.asp';  //微信手机网页 支付请求地址


$jieqiPayset['wechat_wap']['remarks']=array( //商户自定义信息
   // '1'=>JIEQI_EGOLD_NAME.'1元充值',
    '30'=>JIEQI_EGOLD_NAME.'30元充值',
    '50'=>JIEQI_EGOLD_NAME.'50元充值',
    '100'=>JIEQI_EGOLD_NAME.'100元充值',
    '200'=>JIEQI_EGOLD_NAME.'200元充值',
    '500'=>JIEQI_EGOLD_NAME.'500元充值',
    '1000'=>JIEQI_EGOLD_NAME.'1000元充值'
);
$huodong_setting=array(
    'from_time'=>strtotime("2017-04-29 00:00:00"),
    'to_time'=>strtotime("2017-05-01 23:59:59"),
    'notify'=>"迎51,微读小说充值送大礼,具体规则如下<br>\n 充50元送600书币<br>\n充100元送5000书币<br>\n充200送12000书币<br>\n充500元送20000书币<br>\n充1000元送50000书币<br>\n活动时间：2017-4-29至2017-5-1<br>\n"
);


if (time()>=$huodong_setting['from_time'] && time()<=$huodong_setting['to_time']) {
    $jieqiPayset['wechat_wap']['paylimit'] = array(
      //  '1'=>'100',
        '30' => '3000',
        '50' => '5600',
        '100' => '15000',
        '200' => '32000',
        '500' => '70000',
        '1000' => '150000'
    );
    $jieqiPayset['wechat_wap']['notify'] = $huodong_setting['notify'];
    $jieqiPayset['wechat_wap']['in_huodong'] = 1;
}
else {
    $jieqiPayset['wechat_wap']['paylimit'] = array(
      //  '1'=>'100',
        '30' => '3000',
        '50' => '5500',
        '100' => '11200',
        '200' => '23000',
        '500' => '60000',
        '1000' => '125000'
    );
    $jieqiPayset['wechat_wap']['in_huodong'] = 0;
}





$jieqiPayset['wechat_wap']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['wechat_wap']['paysilver']='0';  //0 表示冲值成金币 1表示银币




?>