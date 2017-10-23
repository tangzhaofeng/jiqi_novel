<?php 
/** 
 * 系统管理->发送短信->详细信息 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class messagedetailModel extends Model{
	public function main(){
		global $jieqiPower, $jieqiModules, $jieqiLang;
		$_REQUEST = $this->getRequest();
		//检查权限
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}
		$this->addLang('system', 'message');
/******************/
		if(empty($_REQUEST['id'])) jieqi_printfail($jieqiLang['system']['message_no_exists']);
		
		$this->db->init('message','messageid','system');
		$message=$this->db->get($_REQUEST['id']);
		if(!$message) jieqi_printfail($jieqiLang['system']['message_no_exists']);
		if($message['fromid'] != 0 && $message['toid'] != 0) jieqi_printfail($jieqiLang['system']['message_no_exists']);
		$data = array();
		$data['messageid'] = $_REQUEST['id'];
		$data['title'] = $message['title'];

		if($message['fromid']>0){
			$data['fromid'] = $message['fromid'];
			$data['fromname'] = $message['fromname'];
		}else{
			$data['fromid'] = 0;
			$data['fromname'] = '';
		}
		if($message['toid']>0){
			$data['toid'] = $message['toid'];
			$data['toname'] = $message['toname'];
		}else{
			$data['toid'] = 0;
			$data['toname'] = '';
		}
		$data['postdate'] = date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $message['postdate']);
		include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
		$ts=TextConvert::getInstance('TextConvert');
		$data['content'] = $ts->makeClickable($message['content']);
		if($message['toid'] == 0 || $message['toid'] == $_SESSION['jieqiUserId']){
			$box='inbox';
		}else{
			$box='outbox';
		}
		$data['box'] = $box;
		$data['url_reply'] = JIEQI_URL.'/?controller=newmessage&reid='.$_REQUEST['id'];
		$data['url_forward'] = JIEQI_URL.'/?controller=newmessage&fwid='.$_REQUEST['id'];
		$data['url_delete'] = JIEQI_URL.'/?controller=message&box='.$box.'&delid='.$_REQUEST['id'];
		
		//设置已读标志
		if($message['isread'] != 1 && ($message['toid'] == 0 || $message['toid'] == $_SESSION['jieqiUserId'])){
			$message['isread'] = '1';
			$this->db->edit($_REQUEST['id'], $message);	
		}
		return 	$data;	
	}
}