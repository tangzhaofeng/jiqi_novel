<?php
//$jieqiPayset['unicom']['Productsid']=527;//定义商户号

$jieqiPayset['unicom']['orderkey']='a1b2c3';//key
$jieqiPayset['unicom']['callbackkey']='q1w2e3';//key

$jieqiPayset['unicom']['paytype']='联通手机充值';  //确认支付类型

$jieqiPayset['unicom']['payurl']='http://ctucard.800617.com:8002/GetUnicom.asp'; //下单地址

$jieqiPayset['unicom']['yanurl']='http://ctucard.800617.com:8002/VerificationUnicom.asp'; //验证码提交地址

//$jieqiPayset['unicom']['return_page']='http://www.shuhai.com/pay/checkunicom';  //支付成功返回

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的金钱也按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['unicom']['paylimit']=array(
//		'38'=>1,
		'400'=>10,
		'800'=>20//,
//		'1200'=>30
);
$jieqiPayset['unicom']['product_id']=array(
		'400'=>7110,
		'800'=>7120//,
//		'1200'=>527003
);
$jieqiPayset['unicom']['moneytype']='0';  //0 人民币 1表示美元
$jieqiPayset['unicom']['paysilver']='0';  //0 表示冲值成金币 1表示冲值成银币

//$logName	= "paypal.log";
?>