<?php
//悦蓝 移动 需要短信验证码

$jieqiPayset['mobile']['spid']='999';//Spid

$jieqiPayset['mobile']['cpid']='1111111';//合作者id

$jieqiPayset['mobile']['appid']='222';//应用id

$jieqiPayset['mobile']['ctimid']='500101';//计费项id

$jieqiPayset['mobile']['passid']='333';//通道id

$jieqiPayset['mobile']['channelcode']='444';//渠道code

$jieqiPayset['mobile']['callback']='http://3g.shuhai.com/pay/checkmobile';//同步cp的订单地址

$jieqiPayset['mobile']['forward']='http://3g.shuhai.com/pay/';//返回按钮跳转的地址

$jieqiPayset['mobile']['payurl']='http://pay3.miliroom.com:13579/H5/paymentUrlapi.do'; //下单地址

$jieqiPayset['mobile']['paytype']='移动充值_方式2';  //确认支付类型




//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的金钱也按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['mobile']['paylimit']=array(
		'38'=>1,
		'380'=>10,
		'780'=>20//,
//		'1200'=>30
);

$jieqiPayset['mobile']['service_provider']=array(
		'1'=>'移动',
		'2'=>'联通',
		'3'=>'电信' //暂不支持
);


$jieqiPayset['mobile']['moneytype']='0';  //0 人民币 1表示美元
$jieqiPayset['mobile']['paysilver']='0';  //0 表示冲值成金币 1表示冲值成银币

//$logName	= "paypal.log";
?>