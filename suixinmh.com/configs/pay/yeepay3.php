<?php
//易宝yeepay支付相关参数
$jieqiPayset['yeepay']['messageType']='Buy';  //支付请求，固定值"Buy"

//$jieqiPayset['yeepay']['payid']='10001126856';  //商户编号      测试用
$jieqiPayset['yeepay']['payid']='10011415194'; 

//$jieqiPayset['yeepay']['paykey']='69cl522AV6q613Ii4W6u8K6XuW8vM1N6bFgyv769220IuYe9u37N4y7rI4Pl';  //密钥merchantKey      测试用
$jieqiPayset['yeepay']['paykey']='o9XYE8mg6Y6K06737u9t3qV5i0A7xpE82365rSw3p32hQ438cgz40u685fCR';   //正式使用

//$jieqiPayset['yeepay']['payurl']='http://tech.yeepay.com:8080/robot/debug.action';   //请求地址       测试用
$jieqiPayset['yeepay']['payurl']='https://www.yeepay.com/app-merchant-proxy/node';  //正式使用
$jieqiPayset['yeepay']['payurl_wap']='http://www.yeepay.com/app-merchant-proxy/wap/controller.action';  //wap正式使用

$jieqiPayset['yeepay']['payreturn']='http://www.shuhai.com/modules/pay/yeepayreturn.php';  //接收返回数据的地址 (www.domain.com 是指你的网址)       正式使用

$jieqiPayset['yeepay']['cur']='CNY';  //货币单位
$jieqiPayset['yeepay']['productId']='virtual money';  //商品名
$jieqiPayset['yeepay']['productDesc']='virtual money';  //商品描述
$jieqiPayset['yeepay']['productCat']='virtual money';  //商品种类
$jieqiPayset['yeepay']['sMctProperties']='shuhai userid';  //附加参数
$jieqiPayset['yeepay']['frpId']='';  //附加参数
$jieqiPayset['yeepay']['addressFlag']='0';  //需要填写送货信息 0：不需要  1:需要
$jieqiPayset['yeepay']['needResponse']='1';  //是否需要应答机制，默认或"0"为不需要,"1"为需要


//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的金钱也按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$jieqiPayset['yeepay']['paylimit']=array('100'=>'1','105'=>'1','1080'=>'10', '1500'=>'15', '2180'=>'20', '3000'=>'30', '5480'=>'50', '6000'=>'60', '11000'=>'100', '22500'=>'200', '30000'=>'300', '55800'=>'500');


//支付增加积分
//$jieqiPayset['yeepay']['payscore']=array('105'=>'10','1080'=>'108','2180'=>'218', '5480'=>'548', '11000'=>'1100', '22500'=>'2250', '55800'=>'5580');

$jieqiPayset['yeepay']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['yeepay']['paysilver']='0';  //0 表示冲值成金币 1表示冲值成银币

$logName	= "YeePay_HTML.log";




$jieqiPayset['yeepay']['payfrom']=array(
'1000000-NET'   => '易宝会员支付',
'SZX'           => '神州行支付卡',
'ABC-NET-B2C'       => '中国农业银行',
'BCCB-NET'      => '北京银行',
'BOCO-NET-B2C'      => '交通银行',
'CCB-NET-B2C'       => '建设银行',
'CIB-NET-B2C'       => '兴业银行',
'CMBCHINA-NET-B2C'  => '招商银行',
'CMBCHINA-PHONE'=> '招行电话银行',
'CMBC-NET-B2C'      => '中国民生银行',
'CMBC-PHONE'    => '民生电话银行',
'ICBC-NET-B2C'      => '中国工商银行',
'BOC-NET-B2C'      => '中国银行',
'JUNNET-NET'    => '骏网一卡通(需要特别开通才可使用)',
'LIANHUAOKCARD-NET'=>'联华OK 卡(需要特别开通才可使用)',
'POST-NET'      => '中国邮政(需要特别开通才可使用)',
'SDB-NET'       => '深圳发展银行',
'SHTEL-NET'     => '电信聚信卡(需要特别开通才可使用)',
'SPDB-NET'      => '上海浦东发展银行',
'TONGCARD-NET'  => '积分支付(通卡)(需要特别开通才可使用)',
'SZX-NET'  => '神州行充值卡(需要特别开通才可使用)',
'UNICOM-NET'  => '联通充值卡',
'TELECOM-NET'  => '电信充值卡',
'ICBC-WAP'  => '中国工商银行WAP通道',
'CMBCHINA-WAP'  => '招商银行WAP通道',
'CCB-WAP'  => '中国建设银行WAP通道',
'QQCARD-NET'  => 'Q币卡'
);

$jieqiPayset['yeepay']['paytype']=array(
'1000000-NET'   => 'yeepay',
'SZX'           => 'yeepay-szx',
'ABC-NET'       => 'yeepay-bank',
'BCCB-NET'      => 'yeepay-bank',
'BOCO-NET'      => 'yeepay-bank',
'CCB-NET'       => 'yeepay-bank',
'CIB-NET'       => 'yeepay-bank',
'CMBCHINA-NET'  => 'yeepay-bank',
'CMBCHINA-PHONE'=> 'yeepay-bank',
'CMBC-NET'      => 'yeepay-bank',
'CMBC-PHONE'    => 'yeepay-bank',
'ICBC-NET'      => 'yeepay-bank',
'JUNNET-NET'    => 'yeepay-other',
'LIANHUAOKCARD-NET'=>'yeepay-other',
'POST-NET'      => 'yeepay-other',
'SDB-NET'       => 'yeepay-bank',
'SHTEL-NET'     => 'yeepay-other',
'SPDB-NET'      => 'yeepay-bank',
'TONGCARD-NET'  => 'yeepay-other',
'SZX-NET'  => 'yeepay-other',
'UNICOM-NET'  => 'yeepay-other',
'TELECOM-NET'  => 'yeepay-other',
'ICBC-WAP'  => 'yeepay-bank-wap',
'CMBCHINA-WAP'  => 'yeepay-bank-wap',
'CCB-WAP'  => 'yeepay-bank-wap',
'QQCARD-NET'  => 'yeepay-other'
);

$jieqiPayset['yeepay']['addvars']=array();  //附加参数

/*
易宝默认支付 /modules/pay/buyegold.php?t=yeepaypay
对应模板 /modules/pay/templates/yeepaypay.html

易宝神州行 /modules/pay/buyegold.php?t=yeeszxpay
对应模板 /modules/pay/templates/yeeszxpay.html

paytype.php 是总的支付种类配置，增加易宝支付的话，在原有配置基础上加上以下内容

$jieqiPaytype['yeepay'] = array('name' => '易宝会员支付', 'shortname' => '易宝会员', 'description'=>'', 'url' => 'http://www.yeepay.com');

$jieqiPaytype['yeepay-szx'] = array('name' => '易宝神州行卡支付', 'shortname' => '易宝神州行', 'description'=>'', 'url' => 'http://www.yeepay.com');

$jieqiPaytype['yeepay-bank'] = array('name' => '易宝银行卡支付', 'shortname' => '易宝银行卡', 'description'=>'', 'url' => 'http://www.yeepay.com');

$jieqiPaytype['yeepay-other'] = array('name' => '易宝其他支付', 'shortname' => '易宝其他', 'description'=>'', 'url' => 'http://www.yeepay.com');
*/
/*
Q币卡：
10,15,20,30,50,60,100,200

移动充值卡：
30 50 100 10 20 200 300 500 1000

联通充值卡：
20 30 50 100 300 500

电信充值卡：
50 100
*/
?>