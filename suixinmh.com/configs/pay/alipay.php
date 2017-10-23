<?php
//支付宝alipay支付相关参数

$jieqiPayset['alipay']['payid']='2088911995276793';  //合作伙伴ID

$jieqiPayset['alipay']['paykey']='ww6qwshvrfdeboo26qfanavkliuozoxm';  //密钥值

$jieqiPayset['alipay']['payurl']='https://www.alipay.com/cooperate/gateway.do';  //提交到对方的网址

$jieqiPayset['alipay']['payreturn']='http://www.mmread.com/pay/checkalipay';  //接收返回的地址 ( 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['alipay']['paylimit']=array('2000'=>'20', '3500'=>'30', '6000'=>'50', '11500'=>'100', '22000'=>'200', '55000'=>'500', '112000'=>'1000');

//支付增加积分
$jieqiPayset['alipay']['payscore']=array('2000'=>'1000','3500'=>'1500', '6000'=>'2500', '11500'=>'5000', '22000'=>'10000', '55000'=>'25000', '112000'=>'50000');

$jieqiPayset['alipay']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['alipay']['paysilver']='0';  //0 表示冲值成金币 1表示银币


$jieqiPayset['alipay']['service']='create_direct_pay_by_user';  //交易类型
$jieqiPayset['alipay']['agent']='';  //代理商id
$jieqiPayset['alipay']['_input_charset']='GBK';  //字符集
$jieqiPayset['alipay']['body']='麦麦币';  //商品描述
$jieqiPayset['alipay']['payment_type']='1';  // 商品支付类型 1 ＝商品购买 2＝服务购买 3＝网络拍卖 4＝捐赠 5＝邮费补偿 6＝奖金
$jieqiPayset['alipay']['show_url']='http://www.mmread.com';  //商品相关网站公司
$jieqiPayset['alipay']['seller_email']='2562510780@qq.com';  //卖家邮箱，必填
$jieqiPayset['alipay']['sign_type']='MD5';  //签名方式

$jieqiPayset['alipay']['notify_url']='http://www.mmread.com/pay/checkalipay'; //异步返回信息
$jieqiPayset['alipay']['notifycheck']='http://notify.alipay.com/trade/notify_query.do';  //通知验证地址

$jieqiPayset['alipay']['addvars']=array();  //附加参数
?>