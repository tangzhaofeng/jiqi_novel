<?php

/**
 *类名：alipay_service
 *功能：支付宝Wap服务接口控制
 *详细：该页面是请求参数核心处理文件，不需要修改
 *版本：2.0
 *日期：2011-09-01
 '说明：
 '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 '该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
*/

require_once ("alipay_function.php");

class alipay_service {
	var $gateway_paychannel="https://mapi.alipay.com/cooperate/gateway.do?";
	var $gateway = "http://wappaygw.alipay.com/service/rest.htm?";	//网关地址
	
	var $_key;				//安全校验码
	var $mysign;			//签名结果
	var $sign_type;			//签名类型 相当于config文件中的sec_id
	var $parameter;			//需要签名的参数数组
	var $format;			//字符编码格式
	var $req_data='';		//post请求数据

	/**构造函数
	 */
	function alipay_service() {
	}

	/**
	 * 创建mobile_merchant_paychannel接口
	 */
	function mobile_merchant_paychannel($parameter, $key, $sign_type) {
		$this->_key			= $key;																		//MD5校验码
		$this->sign_type	= $sign_type; 																//签名类型，此处为MD5
		$this->parameter	= para_filter($parameter); 													//除去数组中的空值和签名参数
		$sort_array			= arg_sort($this->parameter); 												//得到从字母a到z排序后的签名参数数组
		$this->mysign		= build_mysign($sort_array, $this->_key, $this->sign_type); 				//生成签名
		$this->req_data		= create_linkstring($this->parameter).'&sign='.urlencode($this->mysign).'&sign_type='.$this->sign_type;	//配置post请求数据，注意sign签名需要urlencode

		//模拟get请求方法
		$result = $this->get($this->gateway_paychannel);
		//调用处理Json方法
		$alipay_channel = $this->getJson($result);
		return $alipay_channel;
	}

	/**
	 * 验签并反序列化Json数据
	 */
	function getJson($result)
	{
		//获取返回的Json
		$json = getDataForXML($result,'/alipay/response/alipay/result');
		//拼装成待签名的数据
		$data = "result=" . $json . $this->_key;

		//输出json
		//echo $json;

		//$json="{\"payChannleResult\":{\"supportedPayChannelList\":{\"supportTopPayChannel\":{\"name\":\"储蓄卡快捷支付\",\"cashierCode\":\"DEBITCARD\",\"supportSecPayChannelList\":{\"supportSecPayChannel\":[{\"name\":\"农行\",\"cashierCode\":\"DEBITCARD_ABC\"},{\"name\":\"工行\",\"cashierCode\":\"DEBITCARD_ICBC\"},{\"name\":\"中信\",\"cashierCode\":\"DEBITCARD_CITIC\"},{\"name\":\"光大\",\"cashierCode\":\"DEBITCARD_CEB\"},{\"name\":\"深发展\",\"cashierCode\":\"DEBITCARD_SDB\"},{\"name\":\"更多\",\"cashierCode\":\"DEBITCARD\"}]}}}}}";

		//获取返回sign
		$aliSign = getDataForXML($result,'/alipay/sign');

		//转换待签名格式数据，因为此mapi接口统一都是用GBK编码的，所以要把默认UTF-8的编码转换成GBK，否则生成签名会不一致
		$data_GBK = mb_convert_encoding($data, "GBK", "UTF-8");
		
		//生成自己的sign
		$mySign = sign($data_GBK,$this->sign_type);
		
		//判断签名是否一致
		if($mySign==$aliSign){
			//签名相同
			//echo "签名相同";

			//php读取json数据
			return json_decode($json);
		}
		else{
			//验签失败
			//echo "验签失败";
			return "验签失败";
		}
	}

	/**
	 * 模拟https的get请求，并返回默认utf-8的数据返回
	 */
/*
	function get($gateway_url){
		$url = $gateway_url . $this->req_data;
		$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
		//https 的get请求需要以下额外2句代码
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // this line makes it work under https
		
		//获取html返回结果
		echo $result=curl_exec ($ch);echo "<hr>";
        echo(curl_error($ch));exit;
		curl_close ($ch);
		return $result;
	}
*/
function get($gateway_url, $input_charset = '', $time_out = "60") {
	$url = $gateway_url . $this->req_data;
	$urlarr     = parse_url($url);
	$errno      = "";
	$errstr     = "";
	$transports = "";
	$responseText = "";
	if($urlarr["scheme"] == "https") {
		$transports = "ssl://";
		$urlarr["port"] = "443";
	} else {
		$transports = "tcp://";
		$urlarr["port"] = "80";
	}
	$fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
	if(!$fp) {
		die("ERROR: $errno - $errstr<br />\n");
	} else {
		if (trim($input_charset) == '') {
			fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
		}
		else {
			fputs($fp, "POST ".$urlarr["path"].'?_input_charset='.$input_charset." HTTP/1.1\r\n");
		}
		fputs($fp, "Host: ".$urlarr["host"]."\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $urlarr["query"] . "\r\n\r\n");
		while(!feof($fp)) {
			$responseText .= @fgets($fp, 1024);
		}
		fclose($fp);
		$responseText = trim(stristr($responseText,"\r\n\r\n"),"\r\n");
		
		return $responseText;
	}
}
	/**
	 * 创建alipay.wap.trade.create.direct接口
	 */
	function alipay_wap_trade_create_direct($parameter, $key, $sign_type) {
		$this->_key			= $key;																		//MD5校验码
		$this->sign_type	= $sign_type; 																//签名类型，此处为MD5
		$this->parameter	= para_filter($parameter); 													//除去数组中的空值和签名参数
		$this->req_data		= $parameter['req_data'];
		$this->format		= $this->parameter['format']; 												//编码格式，此处为utf-8
		$sort_array			= arg_sort($this->parameter); 												//得到从字母a到z排序后的签名参数数组
		$this->mysign		= build_mysign($sort_array, $this->_key, $this->sign_type); //echo $this->mysign;				//生成签名
		$this->req_data		= create_linkstring($this->parameter).'&sign='.urlencode($this->mysign);	//配置post请求数据，注意sign签名需要urlencode
		//echo $this->req_data;exit();
		//Post提交请求
		$result	= $this->post($this->gateway);
		
		//调用GetToken方法，并返回token
		return $this->getToken($result);															
	}

	/**
	 * 调用alipay_Wap_Auth_AuthAndExecute接口
	 */
	function alipay_Wap_Auth_AuthAndExecute($parameter,$key) {
		$this->parameter	= para_filter($parameter);
		$sort_array			= arg_sort($this->parameter); 
		$this->sign_type	= $this->parameter['sec_id'];
		$this->_key			= $key;
		$this->mysign		= build_mysign($sort_array, $this->_key, $this->sign_type);
		$RedirectUrl		= $this->gateway . create_linkstring($this->parameter) . '&sign=' . urlencode($this->mysign);
		
		//输出post请求字符串（调试用）
		//echo $RedirectUrl;

		//跳转至该地址
		Header("Location: $RedirectUrl");
	}

	/**
	 * 返回token参数
	 * 参数 result 需要先urldecode
	 */
	function getToken($result)
	{
		$result	= urldecode($result);				//URL转码
		$Arr = explode('&', $result);				//根据 & 符号拆分
		
		$temp = array();							//临时存放拆分的数组
		$myArray = array();							//待签名的数组
		//循环构造key、value数组
		for ($i = 0; $i < count($Arr); $i++) {
			$temp = explode( '=' , $Arr[$i] , 2 );
			$myArray[$temp[0]] = $temp[1];
		}
//print_r($myArray);
		$sign = $myArray['sign'];	//echo ' aaaa'.$sign;exit();											//支付宝返回签名
		$myArray = para_filter($myArray);										//拆分完毕后的数组

		$sort_array = arg_sort($myArray);										//排序数组
		$this->mysign = build_mysign($sort_array,$this->_key,$this->sign_type);	//构造本地参数签名，用于对比支付宝请求的签名

		if($this->mysign == $sign)	//判断签名是否正确
		{
			return getDataForXML($myArray['res_data'],'/direct_trade_create_res/request_token');	//返回token
		}
		else
		{
			echo('签名不正确');		//当判断出签名不正确，请不要验签通过
			return '签名不正确';
		}
	}













	/**
	 * PHP Crul库 模拟Post提交至支付宝网关
	 * 如果使用Crul 你需要改一改你的php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
	 * 返回 $data
	 */
	function post($gateway_url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $gateway_url);				//配置网关地址
		curl_setopt($ch, CURLOPT_HEADER, 0);						//过滤HTTP头
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);							//设置post提交
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->req_data);		//post传输数据
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}


}

?>