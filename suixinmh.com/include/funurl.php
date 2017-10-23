<?php 
/**
 * 获得url路径相关函数
 *
 * 获得url路径相关函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: funuser.php 243 2008-11-28 02:59:57Z juny $
 */

/**
 * 用户信息相关url
 * 
 * @param      int         $id 用户id
 * @param      string      $type 页面类型 'info' - 个人信息页, 'space' - 个人空间页(默认)
 * @access     public
 * @return     string
 */
function jieqi_url_system_user($id, $type=''){
	global $jieqiModules;
	switch($type){
		case 'info':
			return JIEQI_USER_URL.'/userinfo.php?id='.$id;
			break;
		case 'page':
			return JIEQI_USER_URL.'/userpage.php?uid='.$id;
			break;
		case 'space':
		default:
			return !empty($jieqiModules['space']['publish']) ? $jieqiModules['space']['url'].'/space.php?uid='.$id : JIEQI_USER_URL.'/userpage.php?uid='.$id;
			break;
	}
}
/**
 * 返回用户头像图片url
 * 
 * @param      int         $uid 用户id
 * @param      int         $size 返回类型 'd'=>图片目录，'o'=>原图, 'l'=>大图(默认)，'m'=>中图, 's'=>小图, 'i'=>图标, 'a'=>返回前面几个合并的数组
 * @param      int         $type 图片类型 -1 系统自动判断，0 无头像 1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp'
 * @param      bool        $retdft 无头像是否返回默认头像地址，true-是（默认），false-否
 * @access     public
 * @return     mixed
 */
function jieqi_url_system_avatar($uid, $size = 'l', $type = -1, $retdft = true){
	global $jieqiConfigs;
	global $jieqi_image_type;
	if(!isset($jieqiConfigs['system'])) jieqi_getConfigs('system', 'configs');
	if(empty($jieqi_image_type)) $jieqi_image_type=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
	$base_avatar = '';
	if($uid == 0 || $type == 0 || ($type > 0 && !isset($jieqi_image_type[$type]))){
		if($retdft){
			$base_avatar = JIEQI_USER_URL.'/images';
			$type = 2;
			$uid = 'noavatar';
		}else{
			return false;
		}
	}elseif($type < 0){
		return JIEQI_USER_URL.'/avatar.php?uid='.$uid.'&size='.$size;
		//如果有启用裁剪功能，统一头像图片 .jpg，否则没有赋值头像类型就用程序输出
		//if(function_exists('gd_info') && $jieqiConfigs['system']['avatarcut']) $type = 2;
		//else return JIEQI_USER_URL.'/avatar.php?uid='.$uid.'&size='.$size;
	}
	$prefix = $jieqi_image_type[$type];
	if(empty($base_avatar)) $base_avatar = jieqi_uploadurl($jieqiConfigs['system']['avatardir'], $jieqiConfigs['system']['avatarurl'], 'system').jieqi_getsubdir($uid);
	switch($size){
		case 'd':
			return $base_avatar;
			break;
		case 'o':
			return $base_avatar.'/'.$uid.$prefix;
			break;
		case 'l':
			return $base_avatar.'/'.$uid.'l'.$prefix;
			break;
		case 'm':
			return $base_avatar.'/'.$uid.'m'.$prefix;
			break;
		case 's':
			return $base_avatar.'/'.$uid.'s'.$prefix;
			break;
		//case 'i':
			//return $base_avatar.'/'.$uid.'i'.$prefix;
			//break;
		case 'a':
		default:
			return array('l'=>$base_avatar.'/'.$uid.$prefix, 's'=>$base_avatar.'/'.$uid.'s'.$prefix, 'i'=>$base_avatar.'/'.$uid.'i'.$prefix, 'd'=>$base_avatar);
			break;
	}
	//判断有没有物理文件，没有则使用默认头像

}


/**
 * 返回PATH_INFO伪静态URL
 * 
 * @param      string      $url 默认的动态url
 * @param      string      $prefix 伪静态地址后缀，如 .html，默认为空
 * @access     public
 * @return     string
 */
function jieqi_url_system_pathinfo($url, $prefix=''){
	if(!in_array($prefix, array('.html', '.htm'))) $prefix='';
	$pos=strpos($url, '?');
	if($pos > 0){
		$parmary = explode('&', substr($url, $pos+1));
		$pstr='';
		foreach($parmary as $v){
			$tmpary = explode('=', $v);
			if(isset($tmpary[1])) $pstr.='/'.$tmpary[0].'/'.$tmpary[1];
		}
		return substr($url, 0, $pos).$pstr.$prefix;
	}else{
		return $url;
	}
}
/**
 * 处理模板标签
 * 
 * @param      string        $tag 标签标识
 * @access     public
 * @return     string
 */
 function jieqi_url_system_tags($tag, $return = 'html'){
     global $_SGLOBAL,$_SCONFIG,$_OBJ,$jieqiModules;
	 //if(!defined('IN_JQNEWS')) @define('IN_JQNEWS', TRUE);
	 if(!defined('_ROOT_')) @define('_ROOT_', JIEQI_ROOT_PATH);
	 if(!is_array($tag)) $id = $tag;
	 else $id = $tag['id'];
	 include_once(JIEQI_ROOT_PATH.'/lib/my_position.php');
	 if(!is_object($_OBJ['position'])) $_OBJ['position'] = new MyPosition();
	 if($data = $_OBJ['position']->get($id)){
	     if($param = jieqi_exechars("<{!!!!}>", urldecode($tag['name']), true)){
		     if($param[0]) $data['setting']['vars'] = $param[0];
			 //echo $param[0];
			 if($param[1]) $data['setting']['template'] = $param[1];
		 }
	     $data['setting']['title'] = $data['name'];
		 switch($data['type']){
			 case '0':
				 $data['setting']['vars'] = $data['data'];
				 $data['setting']['side'] = 1;
				 $data['setting']['bid'] = $tag['id'];
				 $data['setting']['module'] = 'news';
				 $data['setting']['filename'] = 'block_commend';
				 $data['setting']['classname'] = 'BlockNewsCommend';
				 $data['setting']['contenttype'] = 1;
				 $data['setting']['custom'] = 0;
				 $data['setting']['publish'] = 3;
				 $data['setting']['hasvars'] = 2;
			 break;
			 case '2':
				 $data['setting']['vars'] = $data['setting']['content'];
				 $data['setting']['side'] = 1;
				 $data['setting']['bid'] = $id;
				 $data['setting']['module'] = 'system';
				 $data['setting']['filename'] = 'block_content';
				 $data['setting']['classname'] = 'BlockContent';
				 $data['setting']['contenttype'] = 1;
				 $data['setting']['custom'] = 0;
				 $data['setting']['publish'] = 3;
				 $data['setting']['hasvars'] = 2;
			 break;
			 default :
				 $data['setting']['side'] = 1;
				 $data['setting']['publish'] = 3;
			 break;
		 }
		 if($return == 'html'){
		     include_once(JIEQI_ROOT_PATH.'/header.php');
		     return jieqi_get_block($data['setting'],1);
		 } else {
		     $temp = '';
		     foreach($data['setting'] as $k=>$v){
			      if($temp) $temp.='&'.$k.'='.urlencode($v);
				  else $temp.=$k.'='.urlencode($v);
			 }
			 return htmlspecialchars_array('<script language="javascript" type="text/javascript" src="'.JIEQI_LOCAL_URL.'/app.php?id='.$id.'"></script>');
		 }
	 }else{
	     return "Data is error[".@urldecode($tag['name'])."]!";
	 }
 }

?>