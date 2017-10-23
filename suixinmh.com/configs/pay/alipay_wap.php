<?php
//WAP支付宝alipay_wap支付相关参数
$jieqiPayset['alipay_wap']['subject']=array(
	'3000'=>'$30',
	'5500'=>'$50',
	'11500'=>'$100',
	'23000'=>'$200',
	'60000'=>'$500',
	'125000'=>'$1000'
//	'3300'=>'$30',
//	'6300'=>'$50',
//	'13200'=>'$100',
//	'28000'=>'$200',
//	'75000'=>'$500',
//	'155000'=>'$1000'
	);


//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['alipay_wap']['paylimit']=array(
	'3000'=>'30',
	'5500'=>'50',
	'11500'=>'100',
	'23000'=>'200',
	'60000'=>'500',
	'125000'=>'1000'
//	'3300'=>'30',
//	'6300'=>'50',
//	'13200'=>'100',
//	'28000'=>'200',
//	'75000'=>'500',
//	'155000'=>'1000'
	);

//支付增加积分
$jieqiPayset['alipay_wap']['payscore']=array(
	'3000'=>'1500',
	'5500'=>'2500',
	'11500'=>'5000',
	'23000'=>'10000',
	'60000'=>'25000',
	'125000'=>'50000'
//	'3300'=>'1500',
//	'6300'=>'2500',
//	'13200'=>'5000',
//	'28000'=>'10000',
//	'75000'=>'25000',
//	'155000'=>'50000'
	);

$jieqiPayset['alipay_wap']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['alipay_wap']['paysilver']='0';  //0 表示冲值成金币 1表示银币

?>