<?php
include_once ($GLOBALS['jieqiModules']['task']['path'] . '/class/TaskBase.php');

/**
 * 客户端登陆模块：继承自TaskBase抽象类
 * @author zhangxue
 * @version 0.1
 */
 class MyApplogintask extends TaskBase {
 	
	// 实现当前自定义类的规则数组
	protected $_rules = array(
		// key为规则对应的名称，value为默认值
//		'field'	=> array(
//			'isvip' => 'VIP等级',
//			'score' => '称号等级'
//		)
	);
	
	// 实现当前自定义类的奖励规则数组
	protected $_rewards = array(
	);
	
	/**
	 * 奖励完成标记
	 */
	protected function finishGroup($params) {
		return 'finished';
	}
 	
	/**
	 * 增加奖励
	 */
 	protected function addReward($params) {//print_r($params);只有tid
 		$uid = $params['uid'];
 		$this->db->init('users', 'uid', 'system');
		$this->db->setCriteria(new Criteria('uid', $uid));
		$res_uadd = $this->db->lists();
		// 循环重组奖励规则
		$user_adds = array();
		$user_adds['egold'] = $res_uadd[0]['egold'] + 200;
		$this->db->edit($uid, $user_adds);
 	}
	
	/**
	 * 奖励成功后发站内信
	 */
 	protected function addRewardAfter($params) {//$this->dump($params);只有tid
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
		$newMessage['title']= '登录书海手机客户端送礼';
		$newMessage['content']= '书海迎来三周年庆，手机客户端送礼啦。您在活动期间登录书海手机客户端，获赠200书海币，请注意查收。';
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
	 * 实现方法：用户是否可完成任务
	 */
	protected function haveAchevable($uid, $tid) {
		$stime = 1422720000;
		$etime = 1423584000;
		if(JIEQI_NOW_TIME>=$stime && JIEQI_NOW_TIME<$etime) return true;
		else return false;
	}
	
	/**
	 * 判断是否已完成，判断是否已领过
	 */
	protected function haveFinished($uid, $tid) {
		$this->db->init('complete', 'tcid', 'task');
		$this->db->setCriteria(new Criteria('userid', $uid));
		$this->db->criteria->add(new Criteria('taskid', $tid));
		$finish = $this->db->lists();//print_r($finish);
		if (!empty($finish)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 获得html格式的规则(重写方法)
	 */
	public function getRuleHtml() {
		$htmls = 'qqqq';
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
		$htmls .= '<tr class="sign_option"><th class="td_title">任务奖励</th><td class="td_contents"><select name="rewards[0][reward]">';
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
		$htmls = '';
//		$htmls .= '<tr class="sign_option"><td class="td_title">任务认证组</td><td class="td_contents"><select name="rule[groupid]">';
//		foreach ($groups as $k => $v) {
//			$htmls .= '<option value="'.$k.'">'.$v.'</option>';
//		}
//		$htmls .= '</select></td><td class="td_span"><span>*可完成任务的用户组</span></td></tr>';
		return $htmls;
	}
	/*
	 * 完成记录的备注字段
	 */
	protected function recordsGroup($params){
		$msg = '活动期间登录书海手机客户端，获赠200书海币。';
		return $msg;
	}
	/**
	 * 客户端登陆调用
	 */
	 public function isFinish($uid, $uname){
		if(JIEQI_NOW_TIME>=1422720000 && JIEQI_NOW_TIME<1423584000){
			$this->db->init('task', 'taskid', 'task');
			$this->db->setCriteria(new Criteria('type', 'applogin'));
			$task = $this->db->get($this->db->criteria);//print_r($task);exit;
			$tid = $task->getVar('taskid');
			
			$userFinished = $this->haveFinished($uid, $tid);
			if ($userFinished) {
				
			} else {
				$thisAddTask = array(
					'userid' 		=> $uid,
					'taskid'			=> $tid,
					'finish'			=> $this->finishGroup($params),
					'createtime'		=> JIEQI_NOW_TIME,
					'records'		=> $this->recordsGroup($params)
				);
				$this->db->init('complete', 'tcid', 'task');
				$this->db->add($thisAddTask);
				
				$users_handler =  $this->getUserObject();
				$ret=$users_handler->income($uid, 200);	//改造，不用income
				if($ret){
					$this->db->init('message','messageid','system');
					$newMessage = array();
					$newMessage['siteid']= JIEQI_SITE_ID;
					$newMessage['postdate']= JIEQI_NOW_TIME;
					$newMessage['fromid']= 6;
					$newMessage['fromname']= '系统管理员';
					$newMessage['toid']= $uid;
					$newMessage['toname']= $uname;
					$newMessage['title']= '登录书海手机客户端送礼';
					$newMessage['content']= '书海迎来三周年庆，手机客户端送礼啦。您在活动期间登录书海手机客户端，获赠200书海币，请注意查收。';
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
			}
		}
	 }
}
?>
 
 
 
 
 
 
 
 
 