<?php 
/** 
 * 系统管理->收(发)件箱 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class messageModel extends Model{
	//http://www.mvc.com/admin/?controller=message&method=inbox
	//发件箱
	public function outbox($params = array()){
		global $jieqiModules;

		$this->addLang('system', 'message');
		$jieqiLang['system'] = $this->getLang('system');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		if(!isset($params['box']) || $params['box'] != 'outbox') $params['box']='outbox';
		//页码
		if (empty($params['page']) || !is_numeric($params['page'])) $params['page']=1;

		//处理批量删除
		if(isset($params['checkaction']) && $params['checkaction'] == 1 && is_array($params['checkid']) && count($params['checkid'])>0){
			$this->db->init('message','messageid','system');
			$where='';
			foreach($params['checkid'] as $v){
				if(is_numeric($v)){
					$v=intval($v);
					if(!empty($where)) $where.=' OR ';
					$where.='messageid ='.$v;
				}
			}
			if(!empty($where)){
				$sql='UPDATE '.jieqi_dbprefix('system_message').' SET fromdel=1 WHERE fromid=0 AND todel=0 AND ('.$where.')';
				$this->db->query($sql);
				$sql='DELETE FROM '.jieqi_dbprefix('system_message').' WHERE fromid=0 AND todel=1 AND ('.$where.')';
				$this->db->query($sql);
			}
			$params['checkaction']=0;
		}elseif(isset($params['checkaction']) && $params['checkaction'] == 2){
			//删除全部
			$sql='UPDATE '.jieqi_dbprefix('system_message').' SET fromdel=1 WHERE fromid=0 AND todel=0';
			$this->db->query($sql);
			$sql='DELETE FROM '.jieqi_dbprefix('system_message').' WHERE fromid=0 AND todel=1';
			$this->db->query($sql);
			$_GET['checkaction']=0;
			$params['checkaction']=0;
		}
		
		if(isset($_GET['checkaction'])) unset($_GET['checkaction']);
		if(isset($_POST['checkaction'])) unset($_POST['checkaction']);
		
		$data = array();
		$data['checkall'] = '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">';
		$data['box'] = $params['box'];
		$data['url_action'] = $this->getAdminurl('message', 'method='.$params['box']);
		$data['url_delete'] = $this->getAdminurl('message', 'method='.$params['box'].'&checkaction=2');
		
		$messagerows=array();
		$data['boxname'] = $jieqiLang['system']['message_send_box'];
		$data['usertitle'] = $jieqiLang['system']['table_message_receiver'];
		$this->db->init('message','messageid','system');
		$this->db->setCriteria(new Criteria('fromid', 0));
		$this->db->criteria->add(new Criteria('fromdel', 0));
		$this->db->criteria->setSort('messageid');
		$this->db->criteria->setOrder('DESC');
		$this->db->criteria->setLimit($jieqiConfigs['system']['messagepnum']);
		$this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['system']['messagepnum']);
		$this->db->queryObjects();
		$k=0;
		while($v = $this->db->getObject()){
			//处理删除
			if(isset($params['delid']) && $params['delid']==$v->getVar('messageid')){
				if($v->getVar('todel')>0){
					$this->db->delete($params['delid']);
				}else{
					$this->db->edit($params['delid'], array('fromdel'=>1));
				}
			}else{
				$messagerows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('messageid').'">';
				if($v->getVar('toid')>0){
					$messagerows[$k]['userid']=$v->getVar('toid');
					$messagerows[$k]['username']=$v->getVar('toname');
				}else{
					$messagerows[$k]['userid']=0;
					$messagerows[$k]['username']=$jieqiLang['system']['message_site_admin'];
				}
				$messagerows[$k]['messageid']=$v->getVar('messageid');
				$messagerows[$k]['title']=$v->getVar('title');
				$messagerows[$k]['postdate']=$v->getVar('postdate');
				$messagerows[$k]['date']=date(JIEQI_DATE_FORMAT, $v->getVar('postdate'));
			}
			$k++;
		}
		$data['messagerows'] = $messagerows;
		//处理页面跳转
		include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($this->db->getCount($this->db->criteria),$jieqiConfigs['system']['messagepnum'],$params['page']);
		$data['url_jumppage'] = $jumppage->whole_bar();
		return $data;
	}

	//http://www.mvc.com/admin/?controller=message&method=inbox
	//收件箱
	public function inbox($params = array()){
		global $jieqiPower, $jieqiModules;
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
		$jieqiLang['system'] = $this->getLang('system');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		if(!isset($params['box']) || $params['box'] != 'outbox') $params['box']='inbox';
		//页码
		if (empty($params['page']) || !is_numeric($params['page'])) $params['page']=1;
		//处理批量删除
		if(isset($params['checkaction']) && $params['checkaction'] == 1 && is_array($params['checkid']) && count($params['checkid'])>0){
			$this->db->init('message','messageid','system');
			$where='';
			foreach($params['checkid'] as $v){
				if(is_numeric($v)){
					$v=intval($v);
					if(!empty($where)) $where.=' OR ';
					$where.='messageid'.'='.$v;
				}
			}
			if(!empty($where)){
				$sql='UPDATE '.jieqi_dbprefix('system_message').' SET todel=1 WHERE toid=0 AND fromdel=0 AND ('.$where.')';
				$this->db->query($sql);
				$sql='DELETE FROM '.jieqi_dbprefix('system_message').' WHERE toid=0 AND fromdel=1 AND ('.$where.')';
				$this->db->query($sql);
			}
			$params['checkaction']=0;
		}elseif(isset($params['checkaction']) && $params['checkaction'] == 2){
			//删除全部
			$sql='UPDATE '.jieqi_dbprefix('system_message').' SET todel=1 WHERE toid=0 AND fromdel=0';
			$this->db->query($sql);
			$sql='DELETE FROM '.jieqi_dbprefix('system_message').' WHERE toid=0 AND fromdel=1';
			$this->db->query($sql);
			$_GET['checkaction']=0;
			$params['checkaction']=0;
		}
		
		if(isset($_GET['checkaction'])) unset($_GET['checkaction']);
		if(isset($_POST['checkaction'])) unset($_POST['checkaction']);
		
		$data = array();
		$data['checkall'] = '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">';
		$data['box'] = $params['box'];
		$data['url_action'] = $this->getAdminurl('message', 'method='.$params['box']);
		$data['url_delete'] = $this->getAdminurl('message', 'method='.$params['box'].'&checkaction=2');
		
		$messagerows=array();
		$data['boxname'] = $jieqiLang['system']['message_receive_box'];
		$data['usertitle'] = $jieqiLang['system']['table_message_sender'];
		$this->db->init('message','messageid','system');
		$this->db->setCriteria(new Criteria('toid', 0));
		if(isset($params['typeid'])) $this->db->criteria->add(new Criteria('typeid', $params['typeid']));
		$this->db->criteria->add(new Criteria('todel', 0));
		$this->db->criteria->setSort('messageid');
		$this->db->criteria->setOrder('DESC');
		$this->db->criteria->setLimit($jieqiConfigs['system']['messagepnum']);
		$this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['system']['messagepnum']);
		$this->db->queryObjects();
		$k=0;
		while($v = $this->db->getObject()){
			//处理删除
			if(isset($params['delid']) && $params['delid']==$v->getVar('messageid')){
				if($v->getVar('fromdel')>0){
					$this->db->delete($params['delid']);
				}else{
					$this->db->edit($params['delid'], array('todel'=>1));
				}
			}else{
				$messagerows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('messageid').'">';
				
				if($v->getVar('fromid')>0){
					$messagerows[$k]['userid']=$v->getVar('fromid');
					$messagerows[$k]['username']=$v->getVar('fromname');
				}else{
					$messagerows[$k]['userid']=0;
					$messagerows[$k]['username']=$jieqiLang['system']['message_site_admin'];
				}
				$messagerows[$k]['messageid']=$v->getVar('messageid');
				$messagerows[$k]['title']=$v->getVar('title');
				$messagerows[$k]['postdate']=$v->getVar('postdate');
				$messagerows[$k]['date']=date(JIEQI_DATE_FORMAT, $v->getVar('postdate'));
				if($v->getVar('isread')) $messagerows[$k]['isread']=1;
				else $messagerows[$k]['isread']=0;
			}
			$k++;
		}
		$data['messagerows'] = $messagerows;
		//处理页面跳转
		include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($this->db->getCount($this->db->criteria),$jieqiConfigs['system']['messagepnum'],$params['page']);
		$data['url_jumppage'] = $jumppage->whole_bar();
		return $data;
		//处理短消息提示
		if(isset($_SESSION['jieqiNewMessage']) && $_SESSION['jieqiNewMessage'] > 0){
			$_SESSION['jieqiNewMessage'] = 0;
			$jieqi_user_info=array();
			if(!empty($_COOKIE['jieqiUserInfo'])) $jieqi_user_info=jieqi_strtosary($_COOKIE['jieqiUserInfo']);
			else $jieqi_user_info=array();
			if(isset($jieqi_user_info['jieqiNewMessage']) && $jieqi_user_info['jieqiNewMessage']>0) $jieqi_user_info['jieqiNewMessage']=0;
			if(!empty($jieqi_user_info['jieqiUserPassword'])) $cookietime=JIEQI_NOW_TIME + 22118400;
			else $cookietime=0;
			@setcookie('jieqiUserInfo', jieqi_sarytostr($jieqi_user_info), $cookietime, '/',  JIEQI_COOKIE_DOMAIN, 0);
		}
	}
	
} 
?>