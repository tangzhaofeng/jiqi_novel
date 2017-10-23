<?php
include_once ($GLOBALS['jieqiModules']['task']['path'] . '/class/TaskBase.php');

/**
 * 充值返还模块：继承自TaskBase抽象类
 * @author zhangxue
 * @version 0.1
 */
 class MyRechargetask extends TaskBase {
 	
	// 实现当前自定义类的规则数组
	protected $_rules = array(
		// key为规则对应的名称，value为默认值
//		'groupid'			=> '0'
	);
	
	// 实现当前自定义类的奖励规则数组
	protected $_rewards = array(
		// 格式可以自定义，只要自己能看懂
//		'percentage'	=> 0.05
		array('reward'=>'egold', 'name'=>'书海币', 'percentage'=>0.05),
		array('reward'=>'esilver', 'name'=>'书券', 'percentage'=>0.1)
	);
	
	/**
	 * 奖励完成标记
	 */
	protected function finishGroup($params) {
		return date("Y-m-d H:i:s", JIEQI_NOW_TIME);
	}
 	
	/**
	 * 增加奖励
	 */
 	protected function addReward($params) {//print_r($params);
   		$uid = $params['uid'];
		
 		$this->db->init('users', 'uid', 'system');
		$this->db->setCriteria(new Criteria('uid', $uid));
		$res_uadd = $this->db->lists();
		// 循环重组奖励规则
		$user_adds = array();
		foreach ($thisRewards as $k => $v) {
			$user_adds[$this->_params['type']] = $res_uadd[0][$this->_params['type']] + $addesilver;
		}
		$this->db->edit($uid, $user_adds);
 	}
	
	/**
	 * 奖励成功后发站内信
	 */
 	protected function addRewardAfter($params) {//$this->dump($params);
		$users_handler = $this->getUserObject();
		$jieqiUsers = $users_handler->get($params['uid']);//print_r($jieqiUsers);exit;
		$uid = $jieqiUsers->getVar('uid');
		$uname = $jieqiUsers->getVar('uname', 'n');
			
		$this->db->init('message','messageid','system');
		$newMessage = array();
		$newMessage['siteid']= JIEQI_SITE_ID;
		$newMessage['postdate']= JIEQI_NOW_TIME;
		$newMessage['fromid']= 6;
		$newMessage['fromname']= '系统管理员';
		$newMessage['toid']= $uid;
		$newMessage['toname']= $uname;
		$newMessage['title']= '充值送红包';
		$newMessage['content']= '三周年感恩回馈，充值就送大红包。您本次充值满XX元，获赠'.$value.'书海币、'.$value.'书券，请注意查收。';
		$newMessage['messagetype']= 0;
		$newMessage['isread']= 0;
		$newMessage['fromdel']= 0;
		$newMessage['todel']= 0;
		$newMessage['enablebbcode']= 1;
		$newMessage['enablehtml']= 0;
		$newMessage['enablesmilies']= 1;
		$newMessage['attachsig']=0;
		$newMessage['attachment']= 0;
		$this->db->add($newMessage);	
 	}
	
	/**
	 * 实现方法：用户是否可完成任务，即上月是否订购章节或打赏
	 */
	protected function haveAchevable($uid, $tid) {
//		else return false;
	}
	
	/**
	 * 判断是否已完成
	 */
	protected function haveFinished($uid, $tid) {
		return false;
	}
	
	/**
	 * 获得html格式的规则(重写方法)
	 */
	public function getRuleHtml() {
		$htmls = '';
//		$htmls .= '<tr class="sign_option"><th class="td_title">任务认证组</th>';
//		$htmls .= '<td class="td_contents">sale+reward</td>';
//		$htmls .= '<td class="td_span"><span>*可完成任务的用户组</span></td></tr>';
		return $htmls;	
	}
	
	/**
	 * 获得html格式的奖励(重写方法)
	 */
	public function getRewardsHtml($params) {
		$htmls = '';
//		$htmls .= '<tr class="sign_option"><th class="td_title">奖励比例</th><td class="td_contents">';
//		$htmls .= '<input class="text" type="text" name="rewards[percentage]" value="'.$params['percentage'].'" />';
//		$htmls .= '</td><td class="td_span"><span>*浮点数表示</span></td></tr>';
		foreach ($params as $key => $val) {
			$htmls .= '<tr class="sign_option"><th class="td_title">任务奖励</th><td class="td_contents"><select name="rewards['.$key.'][reward]">';
			foreach ($this->_rewards as $k => $v) {
				if ($val['reward'] == $v['reward']) {
					$htmls .= '<option value="'.$v['reward'].'" selected="selected" >'.$v['name'].'</option>';
				} else {
					$htmls .= '<option value="'.$v['reward'].'">'.$v['name'].'</option>';
				}
			}
			$htmls .= '</select>&nbsp;&nbsp;<input class="text" type="text" name="rewards['.$key.'][percentage]" placeholder="例如：5000" value="'.$params[$key]['percentage'].'" />';
			$htmls .= '</td><td class="td_span"><span>*填写奖励方式</span></td></tr>';
		}
		return $htmls;
	}
	
	/**
	 * 获得html格式的奖励(重写方法)
	 */
	public function setRewardsHtml() {
		$htmls = '';
		$htmls .= '<tr class="sign_option"><th class="td_title">任务奖励1</th><td class="td_contents"><select name="rewards[0][reward]">';
		foreach ($this->_rewards as $k => $v) {
			$htmls .= '<option value="'.$v['reward'].'">'.$v['name'].'</option>';
		}
		$htmls .= '</select>&nbsp;&nbsp;<input class="text" type="text" name="rewards[0][percentage]" value="'.$this->_rewards[0]['percentage'].'" />';
		$htmls .= '</td><td class="td_span"><span>*填写奖励方式</span></td></tr>';
		return $htmls;
	}
	
	/**
	 * 获得html格式的规则(重写方法)
	 */
	public function setRuleHtml() {
//		$this->addConfig('system', 'groups');
//		$groups = $this->getConfig('system', 'groups');
//		$htmls = '';
//		$htmls .= '<tr class="sign_option"><td class="td_title">任务认证组</td><td class="td_contents"><select name="rule[groupid]">';
//		foreach ($groups as $k => $v) {
//			$htmls .= '<option value="'.$k.'">'.$v.'</option>';
//		}
//		$htmls .= '</select></td><td class="td_span"><span>*可完成任务的用户组</span></td></tr>';
//		return $htmls;
	}
	/*
	 * 完成记录的备注字段
	 */
	protected function recordsGroup($params){
		$msg = '本次充值满'.$params['reach'].'元，获赠'.$params['value'].'书海币、'.$params['value'].'书券。';
		return $msg;
	}
	/*
	 * 获取详细奖励信息，用于备注
	 */
	private function getReward($params){
 		$uid = $this->_userCheck();
 		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria(new Criteria('taskid', $params['tid']));
		$this->db->criteria->setFields('rewards');
		$tmp = $this->db->lists();
		$thisRewards = json_decode($tmp[0]['rewards'], true);//print_r($thisRewards);//exit;
		$this->_params['type'] = $thisRewards[0]['reward'];
		$consumeegold = $this->haveAchevable($uid, $params['tid']);//echo $params['tid'];exit;
		$this->_params['consumeegold'] = $consumeegold;
		$addesilver = round($consumeegold*$thisRewards[0]['percentage'],2);
		$this->_params['addesilver'] = $addesilver;
	}
	/**
	 * 充值判断，income之前
	 */
	function judge($uid, $uname, $money){
		if(JIEQI_NOW_TIME>=1422720000 && JIEQI_NOW_TIME<1425139200){
			if($money>=2000 && $money<5000){
				$value = 200;
				$reach = 20;
			}else if($money>=5000 && $money<10000){
				$value = 500;
				$reach = 50;
			}else if($money>=10000){
				$value = 1000;
				$reach = 100;
			}
			if($value >0){
				$this->db->init('task', 'taskid', 'task');
				$this->db->setCriteria(new Criteria('type', 'recharge'));
				$task = $this->db->get($this->db->criteria);//print_r($task);exit;
				$tid = $task->getVar('taskid');
			
				$thisAddTask = array(
					'userid' 		=> $uid,
					'taskid'			=> $tid,
					'finish'			=> $this->finishGroup($params),
					'createtime'		=> JIEQI_NOW_TIME,
					'records'		=> '活动期间充值满'.$reach.'元，获赠'.$value.'书海币、'.$value.'书券。'
				);
				$this->db->init('complete', 'tcid', 'task');
				$this->db->add($thisAddTask);
				
				$users_handler =  $this->getUserObject();
				$users_handler->income($uid, $value);
				$users_handler->income($uid, $value, 1);
//				if($ret){
					$this->db->init('message','messageid','system');
					$newMessage = array();
					$newMessage['siteid']= JIEQI_SITE_ID;
					$newMessage['postdate']= JIEQI_NOW_TIME;
					$newMessage['fromid']= 6;
					$newMessage['fromname']= '系统管理员';
					$newMessage['toid']= $uid;
					$newMessage['toname']= $uname;
					$newMessage['title']= '充值送红包';
					$newMessage['content']= '三周年感恩回馈，充值就送大红包。您本次充值满'.$reach.'元，获赠'.$value.'书海币、'.$value.'书券，请注意查收。';
					$newMessage['messagetype']= 0;
					$newMessage['isread']= 0;
					$newMessage['fromdel']= 0;
					$newMessage['todel']= 0;
					$newMessage['enablebbcode']= 1;
					$newMessage['enablehtml']= 0;
					$newMessage['enablesmilies']= 1;
					$newMessage['attachsig']=0;
					$newMessage['attachment']= 0;
					$this->db->add($newMessage);	
//				}
			}
			$this->db->init( 'paylog', 'payid', 'pay' );
		}
	}
}
?>
 
 
 
 
 
 
 
 
 