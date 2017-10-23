<?php
//威富通wftpay支付相关配置

$jieqiPayset['wftpay']['service']='pay.weixin.scancode';	//接口类型
$jieqiPayset['wftpay']['version']='1.1';				//版本号
$jieqiPayset['wftpay']['mch_id']='008702901170001';  //商户号
$jieqiPayset['wftpay']['body']=array(
	'1000'=>'书海币10元充值', 
	'2000'=>'书海币20元充值', 
	'5000'=>'书海币50元充值', 
	'10000'=>'书海币100元充值', 
	'22500'=>'书海币200元充值', 
	'55800'=>'书海币500元充值', 
	'112000'=>'书海币1000元充值'
	);
$jieqiPayset['wftpay']['notify_url']='http://www.shuhai.com/pay/checkwftpay'; //通知地址
$jieqiPayset['wftpay']['payurl']='https://pay.swiftpass.cn/pay/gateway';  //请求url
$jieqiPayset['wftpay']['sign']='93982e32f55dc73f9065425fde749570';  //密钥值



//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['wftpay']['paylimit']=array('1000'=>'10', '2000'=>'20', '5000'=>'50', '10000'=>'100', '22500'=>'200', '55800'=>'500', '112000'=>'1000');

//支付增加积分
$jieqiPayset['wftpay']['payscore']=array('1000'=>'500','2000'=>'1000', '5000'=>'2500', '10000'=>'5000', '22500'=>'10000', '55800'=>'25000', '112000'=>'50000');

$jieqiPayset['wftpay']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['wftpay']['paysilver']='0';  //0 表示冲值成金币 1表示银币

?>