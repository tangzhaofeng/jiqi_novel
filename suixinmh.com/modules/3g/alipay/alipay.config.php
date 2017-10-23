<?php
/* *
 * 配置文件
 * 版本�?.3
 * 日期�?012-07-19
 * 说明�?
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编�?并非一定要使用该代码�?
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考�?
	
 * 提示：如何获取安全校验码和合作身份者id
 * 1.用您的签约支付宝账号登录支付宝网�?www.alipay.com)
 * 2.点击“商家服务�?https://b.alipay.com/order/myorder.htm)
 * 3.点击“查询合作者身�?pid)”、“查询安全校验码(key)�?
	
 * 安全校验码查看时，输入支付密码后，页面呈灰色的现象，怎么办？
 * 解决方法�?
 * 1、检查浏览器配置，不让浏览器做弹框屏蔽设�?
 * 2、更换浏览器或电脑，重新登录查询�?
 */
 
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓�?

//合作身份者id，以2088开头的16位纯数字
$alipay_config['partner']		= '2088421922211664';
//如果签名方式设置为“MD5”时，请设置该参�?
$alipay_config['key']			= '3puedwv3qqkkyt063v47dnk76m2del1a';
//收款支付宝账�?
$alipay_config['seller_id']	= $alipay_config['partner'];

//商户的私钥（后缀�?pen）文件相对路�?
$alipay_config['private_key_path']	= 'alipay/key/rsa_private_key.pem';

//支付宝公钥（后缀�?pen）文件相对路�?
$alipay_config['ali_public_key_path']= 'alipay/key/rsa_public_key.pem';


//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑�?


//签名方式 不需修改
$alipay_config['sign_type']    = strtoupper('MD5');//'MD5';//

//字符编码格式 目前支持 gbk �?utf-8
$alipay_config['input_charset']= strtolower('utf-8');

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录�?
$alipay_config['cacert']    = getcwd().'/alipay/cacert.pem';
//echo $alipay_config['cacert'];
//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    = 'http';

//服务器异步通知页面路径
$notify_url = "http://".JIEQI_HTTP_HOST."/pay/alipay_notify";
//需http://格式的完整路径，不允许加?id=123这类自定义参�?

//页面跳转同步通知页面路径
$call_back_url = "http://".JIEQI_HTTP_HOST."/pay/checkalipay";
//需http://格式的完整路径，不允许加?id=123这类自定义参�?

//操作中断返回地址
$merchant_url = "http://".JIEQI_HTTP_HOST."/pay/alipay";
?>