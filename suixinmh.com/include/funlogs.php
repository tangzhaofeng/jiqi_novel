<?php
/**
 * 日志处理类函数
 *
 * 日志处理类函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: hlm $
 */

/**
 * 传入主题实例对象，返回适合模板赋值的主题信息数组
 * 
 * @param      object      $ary 日志内容数组
 * @access     public
 * @return     array
 */
 
     function jieqi_logs_set($ary){
	     if(is_array($ary)){
				include_once(JIEQI_ROOT_PATH.'/class/userlog.php');
				//记录日志
				$userlog_handler = JieqiUserlogHandler::getInstance('JieqiUserlogHandler');
				$newlog=$userlog_handler->create();
				$newlog->setVar('siteid', JIEQI_SITE_ID);
				$newlog->setVar('logtime', JIEQI_NOW_TIME);
				$newlog->setVar('fromid', $_SESSION['jieqiUserId']);
				$newlog->setVar('fromname', $_SESSION['jieqiUserName']);
				$newlog->setVar('toid', $ary['toid']);
				$newlog->setVar('toname', $ary['toname']);
				$newlog->setVar('reason', $ary['reason']);
				$newlog->setVar('chginfo', jieqi_userip().$ary['chginfo'].$_SERVER['HTTP_REFERER']);
				$newlog->setVar('chglog', serialize($ary['chglog']));
				$newlog->setVar('isdel', $ary['isdel'] ?$ary['isdel'] :0);
				$newlog->setVar('userlog', $ary['userlog']);
				if($userlog_handler->insert($newlog)) return true;
				else return true;
		 }
		 return false;
     }
?>