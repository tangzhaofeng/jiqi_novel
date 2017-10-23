<?php
$jieqiPayset['zhangwei']['app_id']='13';//商户编号 

$jieqiPayset['zhangwei']['pType']='1';  //类型，固定值

$jieqiPayset['zhangwei']['product_id']=array( //产品编码
		'400'=>140000010,
		'800'=>140000020
);

$jieqiPayset['zhangwei']['cm']='M2040075';//渠道代码

$jieqiPayset['zhangwei']['Notify_Url']='http://3g.shuhai.com/pay/zhangwei_notify'; //异步通知地址

$jieqiPayset['zhangwei']['Return_Url']='http://3g.shuhai.com/pay/checkzhangwei';  //同步跳转地址

$jieqiPayset['zhangwei']['payurl']='http://gateway.zw88.net/v1/Pay/getway'; //下单地址

//页面版式，1：简版，2：彩版，3：触屏版，4：XML 响应，默认为：2 

$jieqiPayset['zhangwei']['access_key']='PKwWJyP1HGrbH8nEiCmrWHExTPgGahcq';//请求接口秘钥

$jieqiPayset['zhangwei']['secret_key']='sKbmys1kG4rk7JUWTZHyHhxwPTXNgzq';//异步通知密钥

$jieqiPayset['zhangwei']['checkurl']='http://gateway.zw88.net/v1/Pay/Verify';//验证地址

$jieqiPayset['zhangwei']['paytype']='移动手机充值';  //确认支付类型





//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的金钱也按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['zhangwei']['paylimit']=array(
		'400'=>10,
		'800'=>20
);

$jieqiPayset['zhangwei']['moneytype']='0';  //0 人民币 1表示美元
$jieqiPayset['zhangwei']['paysilver']='0';  //0 表示冲值成金币 1表示冲值成银币

//$logName	= "paypal.log";
?>