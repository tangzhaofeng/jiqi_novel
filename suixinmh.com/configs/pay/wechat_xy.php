<?php
//星启天微信支付相关配置
$jieqiPayset['wechat_wap']['customerid']='101550023512';	//商户号
$jieqiPayset['wechat_wap']['cardno']='32';	//支付方式：固定值32
$jieqiPayset['wechat_wap']['noticeurl']='http://'.JIEQI_HTTP_HOST.'/pay/wechat_xy_notify'; //异步通知地址
$jieqiPayset['wechat_wap']['backurl']='http://'.JIEQI_HTTP_HOST.'/pay/checkwechat';  //回调地址
$jieqiPayset['wechat_wap']['key']='948853631d42da72ce7d08e140488677';  //密钥
$jieqiPayset['wechat_wap']['payurl']='https://pay.swiftpass.cn/pay/gateway';  //微信手机网页 支付请求地址

$jieqiPayset['wechat_wap']['appid'] = 'wxe40804eefad91598';
$jieqiPayset['wechat_wap']['appkey'] = 'fc03c47bc3346a1855d0e2dd744b520e';


$jieqiPayset['wechat_wap']['remarks']=array( //商户自定义信息
	'30'=>JIEQI_EGOLD_NAME.'30元充值',
	'50'=>JIEQI_EGOLD_NAME.'50元充值',
	'100'=>JIEQI_EGOLD_NAME.'100元充值',
	'200'=>JIEQI_EGOLD_NAME.'200元充值',
	'500'=>JIEQI_EGOLD_NAME.'500元充值',
	'1000'=>JIEQI_EGOLD_NAME.'1000元充值'
	);





$huodong_setting=array(
    'from_time'=>strtotime("2017-01-28 00:00:00"),
    'to_time'=>strtotime("2017-02-05 23:59:59"),
    'notify'=>"迎新年,爱书坊充值送大礼,机会难得不容错过哦,具体规则如下<br>\n 充50元送1000书币+1次抽奖机会，<br>\n充100元送3500书币+2次抽奖机会，<br>\n充200送5000书币+5次抽奖机会，<br>\n充500元送15000书币+15次抽奖机会，<br>\n充1000元送35000书币+35次抽奖机会<br>\n活动时间：2017-1-28至2017-2-5<br><a href='/huodong/newyear/'>查看详细活动规则&gt;&gt;</a><br>\n"
);


if (time()>=$huodong_setting['from_time'] && time()<=$huodong_setting['to_time']) {
    $jieqiPayset['wechat_wap']['paylimit'] = array(
        '100' => '13500',
        '30' => '3000',
        '50' => '6000',
        '200' => '25000',
        '500' => '65000',
        '1000' => '135000'
    );
    $jieqiPayset['wechat_wap']['notify'] = $huodong_setting['notify'];
    $jieqiPayset['wechat_wap']['in_huodong'] = 1;
}
else {
    $jieqiPayset['wechat_wap']['paylimit'] = array(
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