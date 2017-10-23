<?php
$jieqiPayset['txfpay']['paytype']='短信充值';  //确认支付类型

$jieqiPayset['txfpay']['payurl']='http://pay.tianxiafu.cn/DirectFillAction';//http://pay.tianxiafu.cn/DirectFillAction';  //提交到对方的网址//http://pay.tianxiafu.cn/txf_xezf/DirectFillAction

$jieqiPayset['txfpay']['return_wap']='http://wap.shuhai.com/pay/checktxfpay';  //wap版通知地址

$jieqiPayset['txfpay']['merchant_no_wap']=573;//wap版商户号

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的金钱也按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['txfpay']['paylimit']=array(
//		'76'=>2,
//		'114'=>3,
		'190'=>5,
		'380'=>10,
		'780'=>20,
		'1200'=>30
);
$jieqiPayset['txfpay']['product_id']=array(
//		'76'=>573001,
//		'114'=>573005,
		'190'=>573006,
		'380'=>573002,
		'780'=>573003,
		'1200'=>573004
);
/*$jieqiPayset['txfpay']['payscore']=array(
		'5400'=>'3000',
		'10800'=>'6000',
		'27000'=>'15000', 
		'54000'=>'30000', 
		'108000'=>'60000',  
		'270000'=>'150000'
);
$jieqiPayset['txfpay']['exchange_rate']=6;  //美元对人民币汇率
*/
$jieqiPayset['txfpay']['moneytype']='0';  //0 人民币 1表示美元
$jieqiPayset['txfpay']['paysilver']='0';  //0 表示冲值成金币 1表示冲值成银币

//$logName	= "paypal.log";
?>