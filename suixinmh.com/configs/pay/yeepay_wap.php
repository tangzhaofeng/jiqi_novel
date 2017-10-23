<?php
//易宝yeepay支付相关参数

$jieqiPayset['yeepay_wap']['callbackurl']='http://m.mmread.com/pay/yeepay_notify';  //商户后台系统回调地址

$jieqiPayset['yeepay_wap']['fcallbackurl']='http://m.mmread.com/pay/checkyeepay';  //商户前台系统回调地址

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['yeepay_wap']['paylimit']=array(
//		'2'=>'0.02', 
		'2000'=>'20', 
		'3500'=>'30',
		'6000'=>'50', 
		'11500'=>'100', 
		'22000'=>'200', 
		'55000'=>'500'
		);
		
$jieqiPayset['yeepay_wap']['subject']=array(
//	'1'=>'1分测试', 
//	'500'=>'5:00', 
	'2000'=>'$20', 
	'3500'=>'$30', 
	'6000'=>'$50', 
	'11500'=>'$100', 
	'22000'=>'$200', 
	'55000'=>'$500'
	);

$jieqiPayset['yeepay_wap']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['yeepay_wap']['paysilver']='0';  //0 表示冲值成金币 1表示银币

$jieqiPayset['yeepay_wap']['paytype']='易宝一键';

$jieqiPayset['yeepay_wap']['addvars']=array();  //附加参数

?>