<?php
/**
 * 常用字符串函数
 *
 * 常用字符串函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: textfunction.php 324 2009-01-20 04:47:10Z juny $
 */

/**
 * 获取首字母
 * 
 * @param      string      $str
 * @access     public
 * @return     string
 */
function jieqi_getinitial($str)
{
	$asc=ord(substr($str,0,1));
	if ($asc<160) //非中文
	{
		if ($asc>=48 && $asc<=57){
			return '1';  //数字
		}elseif ($asc>=65 && $asc<=90){
			return chr($asc);   // A--Z
		}elseif ($asc>=97 && $asc<=122){
			return chr($asc-32); // a--z
		}else{
			return '~'; //其他
		}
	}
	else   //中文
	{
		$asc=$asc*1000+ord(substr($str,1,1));
		//获取拼音首字母A--Z
		if ($asc>=176161 && $asc<176197){
			return 'A';
		}elseif ($asc>=176197 && $asc<178193){
			return 'B';
		}elseif ($asc>=178193 && $asc<180238){
			return 'C';
		}elseif ($asc>=180238 && $asc<182234){
			return 'D';
		}elseif ($asc>=182234 && $asc<183162){
			return 'E';
		}elseif ($asc>=183162 && $asc<184193){
			return 'F';
		}elseif ($asc>=184193 && $asc<185254){
			return 'G';
		}elseif ($asc>=185254 && $asc<187247){
			return 'H';
		}elseif ($asc>=187247 && $asc<191166){
			return 'J';
		}elseif ($asc>=191166 && $asc<192172){
			return 'K';
		}elseif ($asc>=192172 && $asc<194232){
			return 'L';
		}elseif ($asc>=194232 && $asc<196195){
			return 'M';
		}elseif ($asc>=196195 && $asc<197182){
			return 'N';
		}elseif ($asc>=197182 && $asc<197190){
			return 'O';
		}elseif ($asc>=197190 && $asc<198218){
			return 'P';
		}elseif ($asc>=198218 && $asc<200187){
			return 'Q';
		}elseif ($asc>=200187 && $asc<200246){
			return 'R';
		}elseif ($asc>=200246 && $asc<203250){
			return 'S';
		}elseif ($asc>=203250 && $asc<205218){
			return 'T';
		}elseif ($asc>=205218 && $asc<206244){
			return 'W';
		}elseif ($asc>=206244 && $asc<209185){
			return 'X';
		}elseif ($asc>=209185 && $asc<212209){
			return 'Y';
		}elseif ($asc>=212209){
			return 'Z';
		}else{
			return '~';
		}
	}
}

/**
 * 限制一行宽度，自动换行
 * 
 * @param      string      $str 要转换的字符串
 * @param      int         $width 一行几个字节
 * @param      int         $start 开始位数
 * @access     public
 * @return     string
 */
function jieqi_limitwidth($str='', $width=80, $start=0)
{
	$tmpstr='';
	$strlen=strlen($str);
	$point=$start;
	for($i=0; $i<$strlen; $i++) {
		if($point>$width){
			$tmpstr.="\n";
			$point=0;
		}
		if (ord($str[$i]) > 0x80) {
			$tmpstr .= $str[$i].$str[++$i];
			$point+=2;
		} else{
			$tmpstr .= $str[$i];
			if($str[$i]=="\n") $point=0;
			else $point+=1;
		}
	}
	return $tmpstr;
}

/**
 * 检查字符串是否有影响网页的字符
 * 
 * @param      string      $str
 * @access     public
 * @return     bool
 */
function jieqi_safestring($str)
{
	$len=strlen($str);
	for($i=0; $i<$len; $i++){
		$tmpvar=ord($str[$i]);
		if ($tmpvar > 0x80) {
			$i++;
		}else{
			//if($tmpvar<48 || ($tmpvar>57 && $tmpvar<65) || ($tmpvar>90 && $tmpvar<97) || $tmpvar>122) return false;
			if($tmpvar==34 || $tmpvar==38 || $tmpvar==39 || $tmpvar==44 || $tmpvar==47 || $tmpvar==59 || $tmpvar==60 || $tmpvar==62 || $tmpvar==92 || $tmpvar==124) return false;
		}
	}
	return true;
}

/**
 * 普通字符串转换为preg的参数
 * 
 * @param      string      $str
 * @access     public
 * @return     string
 */
function jieqi_pregconvert($str){
	$from=array(' ', '/', '\*','\!','~','\$','\^');
	$to=array('\s', '\/', '.*','[^\>\<]*','[^\<\>\'"]*','[\d]*','[^\<\>\d]*');
	$str=preg_quote($str);
	$str=str_replace($from, $to, $str);
	return $str;
}

/**
 * 半角转换成全角
 * 
 * @param      string      $str
 * @access     public
 * @return     string
 */
function jieqi_sbcstr($str){
	$repary=array(' '=>'　', '"'=>'＂', '&'=>'＆', '\''=>'＇', ','=>'，', '/'=>'／', ';'=>'；', '<'=>'＜', '>'=>'＞', '\\'=>'＼');
	$len=strlen($str);
	$ret='';
	for($i=0; $i<$len; $i++){
		$tmpvar=ord($str[$i]);
		if ($tmpvar > 0x80) {
			$ret.=$str[$i].$str[$i+1];
			$i++;
		}else{
			$ret .= isset($repary[$str[$i]]) ? $repary[$str[$i]] : $str[$i];
		}
	}
	return $ret;
}

/**
 * 全角转换成半角
 * 
 * @param      string      $str
 * @access     public
 * @return     string
 */
function jieqi_dbcstr($str){
	$from=array('　', '＂', '＆', '＇', '，', '／', '；', '＜', '＞', '＼');
	$to=array(' ', '"', '&', '\'', ',', '/', ';', '<', '>', '\\');
	$str=str_replace($from, $to, $str);
	return $str;
}

/**
 * html代码还原成文本
 * 
 * @param      string      $str
 * @access     public
 * @return     string
 */
function jieqi_textstr($str, $unclickable=false){
    //$str = jieqi_unescape($str);
	if($unclickable){
		$search = array('/<img[^\<\>]+src=[\'"]?([^\<\>\'"\s]*)[\'"]?/is', '/<a[^\<\>]+href=[\'"]?([^\<\>\'"\s]*)[\'"]?/is', '/on[a-z]+[\s]*=[\s]*"[^"]*"/is', '/on[a-z]+[\s]*=[\s]*\'[^\']*\'/is');
		$replace = array("\\1<br>\\0", "\\1<br>\\0", "", "");
		$str=preg_replace($search, $replace, $str);
	}

	$search = array("/([\r\n])[\s]+/", "/\<br[^\>]*\>/i", "/\<[\s]*\/p[\s]*\>/i", "/\<[\s]*p[\s]*\>/i", "/\<script[^\>]*\>.*\<\/script\>/is", "/\<[\/\\!]*[^\<\>]*\>/is","/&(quot|#34);/i", "/&(amp|#38);/i", "/&(lt|#60);/i", "/&(gt|#62);/i", "/&(nbsp|#160);/i", "/&#(\d+);/", "/&([a-z]+);/i");
	$replace = array("\r\n", "\r\n", "", "\r\n\r\n", "", "", "\"", "&", "<", ">", " ", "-", "");
	$str=preg_replace($search, $replace, $str);
	$str=strip_tags($str);
	return $str;
}
/**
 * 文本中关键词替换成图片
 * 
 * @param      string      $str
 * @access     public
 * @return     string
 */
function jieqi_strimg($str, $search = array(), $replace = array(), $module = 'system', $tag = 'img'){
     if(!is_array($search) || !is_array($search) || $str=='') return $str;
	 if(count($search)>0){
		 //组织查找替换内容数组
	     if(count($replace)>0 && count($search)==count($replace)){
			foreach($search as $k=>$filterstr){
				$repfrom[]='/'.$filterstr.'/i';
		    }
		    foreach($replace as $k=>$restr){
			    $repto[] = $restr;
			}
		 }else{
			 //组织查找关键词数组
			 foreach($search as $k=>$filterstr){
				$repfrom[]='/'.$filterstr.'/i';
				$repto[]=$tag=='img' ? '<img src=\"'.JIEQI_ROOT_PATH.'/include/strimg.php?k='.$k.'\" align=\'absmiddle\'>':'~'.$k.'~';
			 }
		 }
	 }else{
	     jieqi_getconfigs($module, 'replace');
		 global $jieqiReplace;
		 if(isset($jieqiReplace[$module]['keyimgwords'])){
		     $keyimgurl = $jieqiReplace[$module]['keyimgurl'] ?$jieqiReplace[$module]['keyimgurl'] :JIEQI_URL.'/include/strimg.php?k=';
		     foreach($jieqiReplace[$module]['keyimgwords'] as $k=>$filterstr){
				$repfrom[]='/'.$filterstr.'/i';
				$repto[]=$tag=='img' ? '<img src="'.$keyimgurl.$k.'" align=\'absmiddle\'>':'~'.$k.'~';
			 }
		 }else{
		     return $str;
		 }
	 }
	 return preg_replace($repfrom, $repto, $str);
}
/**
 * 获取远程网页内容
 * 
 * @param      string      $url 网址
 * @param      array       $params 相关参数
 * @access     public
 * @return     string
 */
function jieqi_urlcontents($url, $params=array()){
    global $Version160;
    if(!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');
	$ret='';
	$count=0;
	if(is_numeric($params)) $params=array('repeat'=>$params);
	if(!isset($params['repeat']) || !is_numeric($params['repeat'])) $params['repeat']=1;
	if(!isset($params['delay'])) $params['delay']=0;
	if(!isset($params['charset'])) $params['charset']='auto';

	while(empty($ret) && $count<$params['repeat']){
		$count++;
		if($count>1 && $params['delay']>0) sleep($params['delay']);
        if(!empty($params['wget']) && in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM', 'DELUXE'))){
		    //定义临时文件temp
			srand((double)microtime()*1000000);
			$rand_number= rand();
			$TEMP = JIEQI_ROOT_PATH.'/files/'.$rand_number.'_'.$params['siteid'].'_'.$_SESSION['jieqiUserId'];
			@exec("wget -O $TEMP $url");
			$ret = @file_get_contents($TEMP);
			@unlink($TEMP);
		}elseif((!empty($params['proxy_host']) && !empty($params['proxy_port'])) || !empty($params['referer']) || !empty($params['cookiefile'])){
			//socket采集
			if(!defined('LIB_REQUEST_INCLUDE')){
				include_once(JIEQI_ROOT_PATH.'/lib/net/client.php');
				define('LIB_REQUEST_INCLUDE', 1);
			}
			$client = new JieqiClient();
			$client->enableHistory(false);
			//User-Agent
			if(!empty($params['useragent'])) $client->setDefaultHeader('User-Agent', $params['useragent']);
			//referer设置
			if(!empty($params['referer']) && substr($params['referer'], 0, 4)=='http') $client->setDefaultHeader('Referer', $params['referer']);
			//代理采集设置
			if(!empty($params['proxy_host']) && !empty($params['proxy_port'])){
				$client->setRequestParameter('proxy_host',$params['proxy_host']);
				$client->setRequestParameter('proxy_port',$params['proxy_port']);
				if(!empty($params['proxy_user'])){
					$client->setRequestParameter('proxy_user',$params['proxy_user']);
					$client->setDefaultHeader('Proxy-Authorization', 'Basic ' . base64_encode($params['proxy_user'] . ':' . $params['proxy_pass']));
				}
				if(!empty($params['proxy_pass'])) $client->setRequestParameter('proxy_pass',$params['proxy_pass']);
			}
			//检查cookie
			$jieqiCollectCookies=array();
			if(!empty($params['cookiefile']) && preg_match('/^[\w\.\/\\\:]+$/', $params['cookiefile']) && is_file($params['cookiefile']) && preg_match('/\.php$/i', @realpath($params['cookiefile'])) && JIEQI_NOW_TIME - filemtime($params['cookiefile']) < $params['cookielife']){
				include_once($params['cookiefile']);
				$client->setDefaultCookies($jieqiCollectCookies);
			}

			$client->get($url);
			$res=$client->currentResponse();
			$ret='';
			if($res['code']=='200' && !empty($res['body'])){
				$ret=$res['body'];
				if(!empty($params['cookiefile'])){
					//更新cookie
					$jieqiCollectCookies=$client->getDefaultCookies();
					$filedata=jieqi_extractvars('jieqiCollectCookies', $jieqiCollectCookies);
					$filedata="<?php\r\n".$filedata."\r\n?>";
					jieqi_writefile($params['cookiefile'], $filedata);
				}
			}
			unset($client);
		}else{
			//普通采集
			$ret=@file_get_contents($url);
		}
	}
	if(!empty($ret) && in_array($params['charset'],array('auto','gb2312','gbk','gb','big5','utf8','utf-8'))){
		if($params['charset']=='auto'){
			preg_match('/\<meta[^\<\>]*content[\s]*=[\s]*(\'|")?[^\/;]*\/[^\/;]*;[\s]*charset[\s]*=[\s]*(gb2312|gbk|big5|utf-8)(\'|")?[^\<\>]*\>/is', $ret, $matches);
			if(!empty($matches[2]))	$pagecherset=strtolower(trim($matches[2]));
			else $pagecherset=strtolower(JIEQI_SYSTEM_CHARSET);
		}else{
			$pagecherset=$params['charset'];
		}
		$defaultcharset=strtolower(JIEQI_SYSTEM_CHARSET);
		$charsetary=array('gb2312'=>'gb', 'gbk'=>'gb', 'gb'=>'gb', 'big5'=>'big5', 'utf-8'=>'utf8', 'utf8'=>'utf8');
		//需要编码转换
		if($pagecherset != $defaultcharset && isset($charsetary[$pagecherset]) && isset($charsetary[$defaultcharset])){
			include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
			$funname='jieqi_'.$charsetary[$pagecherset].'2'.$charsetary[$defaultcharset];
			if(function_exists($funname)) $ret=call_user_func($funname, $ret);
		}
	}
	return $ret;
}

/**
 * 生成随机字符串
 * 
 * @param      int         $length 字符串长度
 * @param      int         $mode 字符串模式，mode由低到高每一位表示 数字、小写字母、大写字母、下划线、特殊字母，默认前三项
 * @access     public
 * @return     string
 */
function jieqi_randstr($length = 6, $mode = 7){
	$str1 = '1234567890';
	$str2 = 'abcdefghijklmnopqrstuvwxyz';
	$str3 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$str4 = '_';
	$str5 = '`~!@#$%^&*()-+=\\|{}[];:\'",./?';
	$str = '';
	if (($mode & 1)>0) $str.=$str1;
	if (($mode & 2)>0) $str.=$str2;
	if (($mode & 4)>0) $str.=$str3;
	if (($mode & 8)>0) $str.=$str4;
	if (($mode & 16)>0) $str.=$str5;
	$result = '';
	$l = strlen($str)-1;
	srand((double) microtime() * 1000000);
	for($i = 0;$i < $length; $i++){
		$num = rand(0, $l);
		$result .= $str[$num];
	}
	return $result;
}
//PHP实现gb2312、UTF-8等字符和unicode间的编码转换及PHP版unescape
function jieqi_unescape($str){
        if(!$str) return;
        @preg_match_all("/%u.{4}|&#x.{4};|&#\\d+;|&#\\d+?|.+/U",$str,$r);
        $ar = $r[0];
		if(count($ar)>0){
			foreach($ar as $k=>$v) {
					if(substr($v,0,2) == "%u")
					{
							$ar[$k] = iconv("UCS-2","GBK",@pack("H4",substr($v,-4)));
					} elseif(substr($v,0,3) == "&#x") {
							$ar[$k] = iconv("UCS-2","GBK",@pack("H4",substr($v,3,-1)));
					} elseif(substr($v,0,2) == "&#") {
							$ar[$k] = iconv("UCS-2","GBK",@pack("n",preg_replace("/[^\\d]/","",$v)));
					}
			}
			$str = @join("",$ar);
		}
        return $str;
}


//提交的变量转成保存的变量
function collectptos($str){
	$str=trim($str);
	$middleary=array('****', '!!!!', '~~~~', '^^^^', '$$$$');
	while(list($k, $v) = each($middleary)){
		if(strpos($str, $v)!==false){
			$tmpary=explode($v, $str);
			return array('left'=>strval($tmpary[0]), 'right'=>strval($tmpary[1]), 'middle'=>$v);
		}
	}
	return $str;
}

//保存的变量转成显示的变量
function collectstop($str){
	if(is_array($str))return $str['left'].$str['middle'].$str['right'];
	else return $str;
}

//将内容标记转换成preg标记
function collectmtop($str){
	switch($str){
		case '!!!!':
			return '([^\>\<]*)';
			break;
		case '~~~~':
			return '([^\<\>\'"]*)';
			break;
		case '^^^^':
			return '([^\<\>\d]*)';
			break;
		case '$$$$':
			return '([\d]*)';
			break;
		case '****':
		default:
			return '(.*)';
			break;
	}
}

//将定义的采集规则转换成执行的
function collectstoe($str){
	if(is_array($str)){
		$pregstr='/'.jieqi_pregconvert($str['left']).collectmtop($str['middle']).jieqi_pregconvert($str['right']).'/is';
	}else{
		$pregstr=trim($str);
		if(strlen($pregstr) > 0 && substr($pregstr,0,1) != '/') $pregstr='/'.str_replace(array(' ', '/'), array('\s', '\/'), preg_quote($pregstr)).'/is';
	}
	return $pregstr;
}

//匹配一个结果
function cmatchone($pregstr, $source){
	$matches=array();
	preg_match($pregstr, $source, $matches);
	if(!is_array($matches) || count($matches)==0){
		return false;
	}else{
		return $matches[count($matches)-1];
	}
}

// 匹配多个结果
function cmatchall($pregstr, $source, $flags=0){
	$matches=array();
	if($flags == PREG_OFFSET_CAPTURE) preg_match_all($pregstr, $source, $matches, PREG_OFFSET_CAPTURE + PREG_SET_ORDER);
	else preg_match_all($pregstr, $source, $matches, PREG_SET_ORDER);
	if(!is_array($matches) || count($matches)==0){
		return false;
	}else{
		$ret=array();
		foreach($matches as $v){
			if(is_array($v)) $ret[]=$v[count($v)-1];
			else $ret[]=$v;
		}
		return $ret;
	}
}
?>