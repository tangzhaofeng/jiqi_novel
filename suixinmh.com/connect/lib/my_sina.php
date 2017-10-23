<?php
include_once (JIEQI_ROOT_PATH.'/connect/lib/my_connect.php');
/**
 * article业务类继承了老版本的JieqiPackage类
 * 
 * @copyright Copyright(c) 2014
 * @author chengyuan
 * @version 1.0
 */
class MySina extends MyConnect {





//	function api($url, $params, $method='GET'){
//		
//		$response=file_get_contents('https://api.weibo.com/2/users/show.json?uid=2653523061&source=4116974926&format=json');
//		echo $response;
//		exit;
//		
//		//$_params['uid']=$params['openid'];
//		//$_params['access_token']=$params['access_token'];
//		//$_params['oauth_consumer_key']=$params['openid'];
//		$params['format']='json';
//		if($method=='GET'){
////		print_r($url.'?'.http_build_query($params));exit;
//			$result_str=$this->http($url.'?'.http_build_query($params));
//		}else{
//			$result_str=$this->http($url, http_build_query($params), 'POST');
//		}
//		$result=array();
//		if($result_str!='')$result=json_decode($result_str, true);
//		return $result;
//	}





function http($url, $postfields='', $method='GET', $headers=array()){//echo $url.'  '.$postfields;exit;
//		$ci=curl_init();
//		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); 
//		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE);
//		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
//		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
//		if($method=='POST'){
//			curl_setopt($ci, CURLOPT_POST, TRUE);
//			if($postfields!='')curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
//		}
//		$headers[]="User-Agent: qqPHP(piscdong.com)";
//		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
//		curl_setopt($ci, CURLOPT_URL, $url);
//		$response=curl_exec($ci);

		$arr = explode('&', $postfields);
		foreach($arr as $k=>$v){
			$tmp = explode('=', $v);
			$data[$tmp[0]] = urldecode($tmp[1]);
		}

		if($method=='POST'){
			$data['url'] = $url;
			$data['submit'] = 'submit';
			
			$response = $this->Post($url, $data);
		}else{
			$response=file_get_contents($url);
		}
//      print_r($response);
//		curl_close($ci);

//		$data['url'] = $url;
//		if($method!='GET') $data['submit'] = 'submit';
//
//		$response = $this->Post($url, $data, $method);
//		$reg = '#[\'"](http:(//|\\/\\/)t\.cn((/|\\/)([^\'"/]+)(/|\\/)?|(/|\\/)))[\'"]#';
//		preg_match_all($reg, $response, $match);
//		var_dump($match);
	
		return $response;
	}
	
	function Post($url, $post = null) {
	    if (is_array($post)) {
	        ksort($post);
	        $content = http_build_query($post);
	        $content_length = strlen($content);
	        $options = array(
	            'http' => array(
	                'method' => 'POST',
	                'header' => 
	                "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: ".$content_length."\r\n",
	                'content' => $content
	            )
	        );
	        return file_get_contents($url, false, stream_context_create($options));
	    }
	}

}
?>