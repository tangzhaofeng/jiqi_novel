<?php
//易宝yeepay支付相关参数

$jieqiPayset['yeepay']['payid']='10012466170';  //商户编号10011326468

$jieqiPayset['yeepay']['paykey']='9h0q544oyoS5ko53o47403sy4138G11756859rLqHQbxJSM96l9Ur86WWK82';  //密钥值JzG3ZGP40Z01t9L7n8H7208i6RM9BUZAoHz84I6954Z152p5M8789y9922Gt

$jieqiPayset['yeepay']['payurl']='https://www.yeepay.com/app-merchant-proxy/node';  //提交到对方的网址

$jieqiPayset['yeepay']['payreturn']='http://www.mmread.com/pay/checkyeepay';  //接收返回的地址 (www.domain.com 是指你的网址)

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
if($_REQUEST['method']=='cardpay'){//手机充值卡
	$jieqiPayset['yeepay']['paylimit']=array('800'=>'10', '1600'=>'20', '2400'=>'30', '4000'=>'50', '8000'=>'100', '16000'=>'200', '24000'=>'300', '40000'=>'500', '80000'=>'1000');
}elseif($_REQUEST['method']=='gcardpay'){//游戏点卡
	$jieqiPayset['yeepay']['paylimit']=array('400'=>'5', '800'=>'10', '1200'=>'15', '1600'=>'20', '2000'=>'25','2400'=>'30', '2800'=>'35', '3600'=>'45','4000'=>'50', '8000'=>'100', '16000'=>'200', '24000'=>'300', '28000'=>'350', '40000'=>'500', '80000'=>'1000');
}elseif($_REQUEST['method']=='qcardpay'){//QQ卡
	$jieqiPayset['yeepay']['paylimit']=array('400'=>'5', '800'=>'10', '1200'=>'15', '1600'=>'20', '2400'=>'30', '4000'=>'50', '4800'=>'60', '8000'=>'100', '16000'=>'200');
}else{
	$jieqiPayset['yeepay']['paylimit']=array('2000'=>'20', '3500'=>'30', '6000'=>'50', '11500'=>'100', '22000'=>'200', '55000'=>'500', '112000'=>'1000');
}
//支付增加积分
//$jieqiPayset['yeepay']['payscore']=array('1000'=>'100', '2000'=>'200', '3000'=>'300', '5000'=>'500', '10000'=>'1000');

$jieqiPayset['yeepay']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['yeepay']['paysilver']='0';  //0 表示冲值成金币 1表示银币



$jieqiPayset['yeepay']['addressFlag']='0';  //需要填写送货信息 0：不需要  1:需要

$jieqiPayset['yeepay']['messageType']='Buy';  //业务类型

$jieqiPayset['yeepay']['cur']='CNY';  //货币单位

$jieqiPayset['yeepay']['productId']='';  //商品名

$jieqiPayset['yeepay']['productDesc']='';  //商品描述

$jieqiPayset['yeepay']['productCat']='';  //商品种类

$jieqiPayset['yeepay']['sMctProperties']='';  //附加参数
$jieqiPayset['yeepay']['frpId']='';  //附加参数

$jieqiPayset['yeepay']['needResponse']='1';  //是否需要应答机制，默认或"0"为不需要,"1"为需要

$jieqiPayset['yeepay']['payfrom']=array(
'1000000-NET'   => '易宝会员支付',
'SZX'           => '神州行支付卡',
'ABC-NET'       => '中国农业银行',
'BCCB-NET'      => '北京银行',
'BOCO-NET'      => '交通银行',
'CCB-NET'       => '建设银行',
'CIB-NET'       => '兴业银行',
'CMBCHINA-NET'  => '招商银行',
'CMBCHINA-PHONE'=> '招行电话银行',
'CMBC-NET'      => '中国民生银行总行',
'CMBC-PHONE'    => '民生电话银行',
'ICBC-NET'      => '中国工商银行',
'JUNNET-NET'    => '骏网一卡通',
'SNDACARD-NET'    => '盛大卡',
'SZX-NET'    => '神州行',
'ZHENGTU-NET'    => '征途卡',
'QQCARD-NET'    => 'Q币卡',
'UNICOM-NET'    => '联通卡',
'JIUYOU-NET'    => '久游卡',
'YPCARD-NET'    => '易宝e卡通',
'NETEASE-NET'    => '网易卡',
'WANMEI-NET'    => '完美卡',
'SOHU-NET'    => '搜狐卡',
'TELECOM-NET'    => '电信卡',
'ZONGYOU-NET'    => '纵游一卡通',
'TIANXIA-NET'    => '天下一卡通',
'TIANHONG-NET'    => '天宏一卡通',
'LIANHUAOKCARD-NET'=>'联华OK 卡',
'POST-NET'      => '中国邮政',
'SDB-NET'       => '深圳发展银行',
'SHTEL-NET'     => '电信聚信卡',
'SPDB-NET'      => '上海浦东发展银行',
'TONGCARD-NET'  => '积分支付(通卡)'
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
'POST-NET'      => 'yeepay-other',
'SDB-NET'       => 'yeepay-bank',
'SHTEL-NET'     => 'yeepay-other',
'SPDB-NET'      => 'yeepay-bank',
'JUNNET-NET'    => 'yeepay-other',
'SNDACARD-NET'    => 'yeepay-other',
'SZX-NET'    => 'yeepay-other',
'ZHENGTU-NET'    => 'yeepay-other',
'QQCARD-NET'    => 'yeepay-other',
'UNICOM-NET'    => 'yeepay-other',
'JIUYOU-NET'    => 'yeepay-other',
'YPCARD-NET'    => 'yeepay-other',
'NETEASE-NET'    => 'yeepay-other',
'WANMEI-NET'    => 'yeepay-other',
'SOHU-NET'    => 'yeepay-other',
'TELECOM-NET'    => 'yeepay-other',
'ZONGYOU-NET'    => 'yeepay-other',
'TIANXIA-NET'    => 'yeepay-other',
'TIANHONG-NET'    => 'yeepay-other',
'LIANHUAOKCARD-NET'=>'yeepay-other',
'TONGCARD-NET'  => 'yeepay-other'
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

?>