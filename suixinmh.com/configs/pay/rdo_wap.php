<?php
//星启天微信支付相关配置
//cpcode=qwyo
//渠道代码=M21I0023
$jieqiPayset['rdo_wap']['cpCode']='qwyo';
$jieqiPayset['rdo_wap']['channelCode']='M21I0023';
$jieqiPayset['rdo_wap']['payUrl']='http://121.43.234.27:10002/payplat_api/huinengrdo/cmwap';
$jieqiPayset['rdo_wap']['noticeurl']='http://m.huandie.com/pay/wechat_notify'; //异步通知地址
$jieqiPayset['rdo_wap']['backurl']='http://m.huandie.com/pay/checkwechat';  //回调地址

$jieqiPayset['rdo_wap']['remarks']=array( //商户自定义信息
    '1000'=>JIEQI_EGOLD_NAME.'10元充值',
    '1500'=>JIEQI_EGOLD_NAME.'15元充值',
    '2000'=>JIEQI_EGOLD_NAME.'20元充值',
    '2500'=>JIEQI_EGOLD_NAME.'25元充值',
	'3000'=>JIEQI_EGOLD_NAME.'30元充值'
	);

$jieqiPayset['rdo_wap']['feeCode']=array( //商户自定义信息
    '1000'=>'72000010',
    '1500'=>'72000015',
    '2000'=>'72000020',
    '2500'=>'72000025',
    '3000'=>'72000030'
);



//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
//$jieqiPayset['rdo_wap']['paylimit']=array('1'=>'0.01', '2000'=>'20', '5000'=>'50', '10000'=>'100', '20000'=>'200', '50000'=>'500', '100000'=>'1000');
$jieqiPayset['rdo_wap']['paylimit']=array(
    '1000'=>'10',
    '1500'=>'15',
	'2000'=>'20',
    '2500'=>'25',
	'3000'=>'30'
);
//支付增加积分
//$jieqiPayset['rdo_wap']['payscore']=array('2000'=>'1000', '5000'=>'2500', '10000'=>'5000', '20000'=>'10000', '50000'=>'25000', '100000'=>'50000');

$jieqiPayset['rdo_wap']['moneytype']='0';  //0 人民币 1表示美元

$jieqiPayset['rdo_wap']['paysilver']='0';  //0 表示冲值成金币 1表示银币

?>