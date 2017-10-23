<?php
//星启天QQ支付相关配置
$jieqiPayset['qq_wap']['customerid']='153953';	//商户号
$jieqiPayset['qq_wap']['cardno']='36';	//支付方式：固定值36
$jieqiPayset['qq_wap']['noticeurl']='http://m.ishufun.net/pay/qq_notify'; //异步通知地址
$jieqiPayset['qq_wap']['backurl']='http://m.ishufun.net/pay/checkqq';  //回调地址
$jieqiPayset['qq_wap']['key']='96ef7f8e7f5d3260b0457c103c69f36d';  //密钥
$jieqiPayset['qq_wap']['payurl']='http://www.zhifuka.net/gateway/QQpay/QQpay.asp';  //微信手机网页 支付请求地址

$jieqiPayset['qq_wap']['remarks']=array( //商户自定义信息
//	'1'=>JIEQI_EGOLD_NAME.'1分测试', 
//	'2000'=>JIEQI_EGOLD_NAME.'20元充值', 
	'3000'=>JIEQI_EGOLD_NAME.'30元充值',
	'5500'=>JIEQI_EGOLD_NAME.'50元充值',
	'11200'=>JIEQI_EGOLD_NAME.'100元充值',
	'23000'=>JIEQI_EGOLD_NAME.'200元充值',
	'60000'=>JIEQI_EGOLD_NAME.'500元充值',
	'125000'=>JIEQI_EGOLD_NAME.'1000元充值'
);



//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
//$jieqiPayset['qq_wap']['paylimit']=array('1'=>'0.01', '2000'=>'20', '5000'=>'50', '10000'=>'100', '20000'=>'200', '50000'=>'500', '100000'=>'1000');
$jieqiPayset['qq_wap']['paylimit']=array(
//	'1'=>'0.01',
//	'500'=>'5',
//	'2000'=>'20', 
	'3000'=>'30',
	'5500'=>'50',
	'11200'=>'100',
	'23000'=>'200',
	'60000'=>'500',
	'125000'=>'1000'
);
//支付增加积分
//$jieqiPayset['qq_wap']['payscore']=array('2000'=>'1000', '5000'=>'2500', '10000'=>'5000', '20000'=>'10000', '50000'=>'25000', '100000'=>'50000');

$jieqiPayset['qq_wap']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['qq_wap']['paysilver']='0';  //0 表示冲值成金币 1表示银币

?>