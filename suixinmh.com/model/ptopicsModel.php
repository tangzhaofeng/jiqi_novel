<?php 
/** 
 * 测试模型 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class ptopicsModel extends Model{ 
	//收件箱
	function main(){
		global $jieqiModules;
		$_REQUEST = $this->getRequest();
		if(!isset($_REQUEST['uid']) && isset($_REQUEST['oid'])) $_REQUEST['uid'] = $_REQUEST['oid'];
		if($_REQUEST['uid']=='self') $_REQUEST['uid']=intval($_SESSION['jieqiUserId']);
		if(empty($_REQUEST['uid']) && empty($_REQUEST['oname'])){
			if(!empty($_SESSION['jieqiUserId'])) $_REQUEST['uid']=$_SESSION['jieqiUserId'];
			else jieqi_printfail(LANG_ERROR_PARAMETER);
		}
		$this->addConfig('system','power');
		$jieqiPower['system'] = $this->getConfig('system','power');
		$this->addLang('system', 'parlar');
		$jieqiLang['system'] = $this->getLang('system');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		if(jieqi_checkpower($jieqiPower['system']['parlorpost'], $jieqiUsersStatus, $jieqiUsersGroup, true)) $enablepost=1;
		else $enablepost=0;

		if(!empty($_REQUEST['pcontent']) && $enablepost){
			//检查发表评论权限
			if(!$enablepost) jieqi_printfail($jieqiLang['system']['parlor_noper_post']);
			//检查发表评论需要积分
			if(!empty($jieqiConfigs['system']['ppostneedscore']) && $_SESSION['jieqiUserScore']<intval($jieqiConfigs['system']['ppostneedscore'])) jieqi_printfail(sprintf($jieqiLang['system']['parlor_score_limit'], intval($jieqiConfigs['system']['ppostneedscore'])));
		}
		
		//载入主题处理函数
		include_once(JIEQI_ROOT_PATH.'/include/funpost.php');

		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		if(!empty($_REQUEST['uid'])) $owneruser=$users_handler->get($_REQUEST['uid']);
		else $owneruser=$users_handler->getByname($_REQUEST['oname'], 2);
		if(!$owneruser) jieqi_printfail($jieqiLang['system']['owner_not_exists']);
		$_REQUEST['uid']=$owneruser->getVar('uid','n');
		$owner_group = $owneruser->getVar('groupid', 'n');
		$owner_status =  $owner_group == JIEQI_GROUP_ADMIN ? JIEQI_GROUP_ADMIN : JIEQI_GROUP_USER;
		if(!jieqi_checkpower($jieqiPower['system']['haveparlor'], $owner_status, $owner_status, true)) jieqi_printfail($jieqiLang['system']['owner_no_parlor']);
		
		include_once(JIEQI_ROOT_PATH.'/class/ptopics.php');
		$ptopics_handler =& JieqiPtopicsHandler::getInstance('JieqiPtopicsHandler');
		//检查是否有管理评论权力
		jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
		$canedit=jieqi_checkpower($jieqiPower['system']['manageallparlor'], $jieqiUsersStatus, $jieqiUsersGroup, true);
		if(!$canedit && !empty($_SESSION['jieqiUserId'])){
			//除了斑竹，会客室主人可以管理
			$tmpvar=$_SESSION['jieqiUserId'];
			if($tmpvar>0 && $owneruser->getVar('uid')==$tmpvar)	$canedit=true;
		}
		//处理置顶、置后、加精、去精、删除










































		//jieqi_checklogin();
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1; //页码
	}
} 
?>