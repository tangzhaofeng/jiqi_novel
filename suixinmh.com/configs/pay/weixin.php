<?php
//微信支付

$jieqiPayset['weixin']['customerid']='154156';//商户在网关系统上的商户号

$jieqiPayset['weixin']['cardno']='32';//固定值：32（微信扫码）

$jieqiPayset['weixin']['key']='f12cd165d28998a4cd981a7534249b83';  //key

$jieqiPayset['weixin']['payurl']='http://www.zhifuka.net/gateway/weixin/weixinpay.asp'; //pc付款url

$jieqiPayset['weixin']['noticeurl']='http://www.mmread.com/pay/noticeweixin';  //回调url,供提供方异步回调

$jieqiPayset['weixin']['backurl'] = 'http://www.mmread.com/user/userview'; //支付成功返回

$jieqiPayset['weixin']['wappayurl']='http://www.zhifuka.net/gateway/weixin/wap-weixinpay.asp'; //wap付款url

$jieqiPayset['weixin']['wapnoticeurl']='http://m.mmread.com/pay/noticeweixinwap';  //wap回调url,供提供方异步回调

$jieqiPayset['weixin']['wapbackurl'] = 'http://m.mmread.com'; //wap支付成功返回

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['weixin']['paylimit']=array('2000'=>'20', '3500'=>'30', '6000'=>'50', '11500'=>'100', '22000'=>'200', '55000'=>'500', '112000'=>'1000');

//支付增加积分
$jieqiPayset['weixin']['payscore']=array('2000'=>'1000','3500'=>'1500', '6000'=>'2500', '11500'=>'5000', '22000'=>'10000', '55000'=>'25000', '112000'=>'50000');


$jieqiPayset['weixin']['moneytype']='0';  //0 人民币 1表示美元
$jieqiPayset['weixin']['paysilver']='0';  //0 表示冲值成金币 1表示冲值成银币


?>