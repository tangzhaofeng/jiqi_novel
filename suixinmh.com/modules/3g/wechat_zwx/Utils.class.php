<?php

/***
 * Class Utils　　对 XML 进行拼装解析
 */
class Utils{
    public static function to($array){
        $xml = '<xml>';
        forEach($array as $k=>$v){
            $xml.='<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
        }
        $xml.='</xml>';
        return $xml;
    }

    public static function parse($xmlSrc){
        if(empty($xmlSrc)){
            return false;
        }
        $array = array();
        $xml = simplexml_load_string($xmlSrc);
        $encode = Utils::getEncode($xmlSrc);
        if($xml && $xml->children()) {
			foreach ($xml->children() as $node){
				if($node->children()) {
					$k = $node->getName();
					$nodeXml = $node->asXML();
					$v = substr($nodeXml, strlen($k)+2, strlen($nodeXml)-2*strlen($k)-5);
					
				} else {
					$k = $node->getName();
					$v = (string)$node;
				}
				
				if($encode!="" && $encode != "UTF-8") {
					$k = iconv("UTF-8", $encode, $k);
					$v = iconv("UTF-8", $encode, $v);
				}
				$array[$k] = $v;
			}
		}
        return $array;
    }

    //获取xml编码
	public static function getEncode($xml) {
		$ret = preg_match ("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
		if($ret) {
			return strtoupper ( $arr[1] );
		} else {
			return "";
		}
	}

	public static  function createSign($parameters,$key) {
		$signPars = "";
		ksort($parameters);
		foreach($parameters as $k => $v) {
			if("" != $v && "sign" != $k) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $key;
		$sign = strtoupper(md5($signPars));
		$parameters['sign']=$sign;
		return $parameters;
	}

	public static function checkSign($data,$key) {
		$signPars = "";
		ksort($data);
		foreach($data as $k => $v) {
			if("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $key;

		$sign = strtolower(md5($signPars));

		$signOrigin  = strtolower($data["sign"]);

		return $sign == $signOrigin;

	}
}
?>