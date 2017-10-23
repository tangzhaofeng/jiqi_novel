<?php
include_once ($GLOBALS['jieqiModules']['task']['path'] . '/class/TaskBase.php');

/**
 * 上月消费返还模块：继承自TaskBase抽象类
 * @author zhangxue
 * @version 0.1
 * 目前只限VIP升级
 */
 class MyVipuptask extends TaskBase {
 	
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
		// 格式可以自定义，只要自己能看懂
//		'score' => array(
//			'rk2' => 2,
//			'rk3' => 3,
//			'rk4' => 4,
//			'rk5' => 5,
//			'rk6' => 6,
//			'rk7' => 7,
//			'rk8' => 8,
//			'rk9' => 9,
//			'rk10' => 10,
//			'rk11' => 11,
//			'rk12' => 12,
//			'rk13' => 13,
//			'rk14' => 14,
//			'rk15' => 15,
//			'rk16' => 16,
//			'rk17' => 17,
//			'rk18' => 18,
//			'rk19' => 19,
//			'rk20' => 20
//		),
		'isvip' => array(
			'v1' => 50,
			'v2' => 250,
			'v3' => 1000,
			'v4' => 2000,
			'v5' => 3500,
			'v6' => 5000
		)
	);
	
	/**
	 * 奖励完成标记
	 */
	protected function finishGroup($params) {//print_r($params);exit;
		if(empty($params['uid']))
			$this->getReward($params);//获取奖励参数
		$reData = array(
			'from'		=> $this->_params['from'],
			'to'			=> $this->_params['to']
		);
		return json_encode($reData);
	}
 	
	/**
	 * 获取提升对应等级应得的数量
	 */
 	private function rewardSum($from = 0, $to = 0, $tid) {
 		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria(new Criteria('taskid', $tid));
		$this->db->criteria->setFields('rewards');
		$tmp = $this->db->lists();
		$thisRewards = json_decode($tmp[0]['rewards'], true);
		
		$sum = 0;
		for($i = $from+1; $i <= $to; $i++){
			$sum += $thisRewards['v'.$i];
		}
		return $sum;
 	}
	/**
	 * 获取奖励参数
	 */
 	private function getReward($params) {//print_r($params);exit;
		$auth = $this->getAuth();
		$vipgrade =  str_replace('VIP','',$auth['vipgrade']);//用户目前VIP等级	
		$uid = $auth['uid'];
		
		$addesilver = 0;
		
		if($vipgrade > 0){
			$this->db->init('complete', 'tcid', 'task');
			$this->db->setCriteria(new Criteria('userid', $uid));
			$this->db->criteria->add(new Criteria('taskid', $params['tid']));
			$this->db->criteria->setFields('finish');
			$finish = $this->db->lists();
			
			if (!empty($finish)) {
				$maxrewardvip = 0;
				foreach($finish as $k=>$v){
					$tmp = json_decode($v['finish'], true);
					$maxrewardvip = $tmp['to']>$maxrewardvip? $tmp['to']:$maxrewardvip;
				}
				if($vipgrade > $maxrewardvip){
					$addesilver = $this->rewardSum($maxrewardvip, $vipgrade, $params['tid']);
					$this->_params['from'] = $maxrewardvip;
					$this->_params['to'] = $vipgrade;
				}else
					$addesilver = 0;
			}else{
				$addesilver = $this->rewardSum(0, $vipgrade, $params['tid']);
				$this->_params['from'] = 0;
				$this->_params['to'] = $vipgrade;
			}
		}else 
			$addesilver = 0; //VIP0不可完成
		$this->_params['addesilver'] = $addesilver;//$this->dump($this->_params);
 	}
		
	/**
	 * 增加奖励
	 */
 	protected function addReward($params) {//$this->dump($this->_params);
 		if(isset($params['uid']))
			$uid = $params['uid'];
		else
			$uid = $this->_userCheck();

		$addesilver = $this->_params['addesilver'];//$this->dump($this->_params);
		if($addesilver > 0){
			$this->db->init('users','uid','system');
			$this->db->setCriteria(new Criteria('uid', $uid));
			$user=$this->db->get($this->db->criteria);
			$esilver = $user->getVar('esilver') + $addesilver;
			$this->db->edit($uid, array('esilver'=>$esilver));			
		}
 	}
	
	/**
	 * 发站内信
	 */
 	protected function addRewardAfter($params) {//$this->dump($params);
 		if($this->_params['addesilver'] > 0){
			$this->addLang('task','msg');
			$this->jieqiLang['task'] =  $this->getLang('task'); 
			if(isset($params['uid'])){
				$users_handler = $this->getUserObject();
				$jieqiUsers = $users_handler->get($params['uid']);//print_r($jieqiUsers);exit;
				$uid = $jieqiUsers->getVar('uid');
				$uname = $jieqiUsers->getVar('uname', 'n');
			}else{
				$auth = $this->getAuth();
				$uid = $auth['uid'];
				$uname = $auth['useruname'];
			}
				
			$this->db->init('message','messageid','system');
			$newMessage = array();
			$newMessage['siteid']= JIEQI_SITE_ID;
			$newMessage['postdate']= JIEQI_NOW_TIME;
			$newMessage['fromid']= 6;
			$newMessage['fromname']= '系统管理员';
			$newMessage['toid']= $uid;
			$newMessage['toname']= $uname;
			$newMessage['title']= $this->jieqiLang['task']['vip_update_title'];
			$newMessage['content']= sprintf($this->jieqiLang['task']['vip_update_text'],$this->_params['from'],$this->_params['to'],$this->_params['addesilver']);
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
	
	/**
	 * 实现方法：用户是否可完成任务
	 */
	protected function haveAchevable($uid, $tid) {
		$this->addConfig('system', 'vipgrade');
		$jieqiVipgrade = $this->getConfig('system', 'vipgrade');//print_r($jieqiVipgrade);
		$users_handler = $this->getUserObject();
		$jieqiUsers = $users_handler->get($uid);//print_r($jieqiUsers);exit;
		$userscore = $jieqiUsers->getVar('isvip');
		
		$vipgrade = jieqi_gethonorid($userscore, $jieqiVipgrade)-1;//VIP等级数组中的下标，比真实等级大1
		
		if($vipgrade > 0){
			$this->db->init('complete', 'tcid', 'task');
			$this->db->setCriteria(new Criteria('userid', $uid));
			$this->db->criteria->add(new Criteria('taskid', $tid));
			$this->db->criteria->setFields('finish');
			$finish = $this->db->lists();
			
			if (!empty($finish)) {
				$maxrewardvip = 0;
				foreach($finish as $k=>$v){
					$tmp = json_decode($v['finish'], true);
					$maxrewardvip = $tmp['to']>$maxrewardvip? $tmp['to']:$maxrewardvip;
				}
				if($vipgrade > $maxrewardvip)
					return TRUE;
				else
					return FALSE;
			}else
				return TRUE;			
		}else 
			return FALSE; //VIP0不可完成			
	}
	
	/**
	 * 判断是否已完成，即结合当前等级、历史奖励，判断是否有未领取的升级奖励
	 */
	protected function haveFinished($uid, $tid) {
		$this->addConfig('system', 'vipgrade');
		$jieqiVipgrade = $this->getConfig('system', 'vipgrade');//print_r($jieqiVipgrade);
		$users_handler = $this->getUserObject();
		$jieqiUsers = $users_handler->get($uid);//print_r($jieqiUsers);exit;
		$userscore = $jieqiUsers->getVar('isvip');
		
		$vipgrade = jieqi_gethonorid($userscore, $jieqiVipgrade)-1;//VIP等级数组中的下标，比真实等级大1
		
		if($vipgrade > 0){
			$this->db->init('complete', 'tcid', 'task');
			$this->db->setCriteria(new Criteria('userid', $uid));
			$this->db->criteria->add(new Criteria('taskid', $tid));
			$this->db->criteria->setFields('finish');
			$finish = $this->db->lists();
			
			if (!empty($finish)) {
				$maxrewardvip = 0;
				foreach($finish as $k=>$v){
					$tmp = json_decode($v['finish'], true);
					$maxrewardvip = $tmp['to']>$maxrewardvip? $tmp['to']:$maxrewardvip;
				}
				if($vipgrade > $maxrewardvip)
					return FALSE;
				else
					return TRUE;
			}else
				return FALSE;
		}else 
			return FALSE; //VI0P0不可完成
	}

	/**
	 * 获得html格式的规则(重写方法)
	 */
	public function getRuleHtml($params) {
//		$htmls = '';
//		$htmls .= '<tr class="sign_option"><th class="td_title">依据字段</th>';
//		$htmls .= '<td class="td_contents">'.$params['field'].'</td>';
//		$htmls .= '<td class="td_span"><span>*依据该字段判断升级</span></td></tr>';//echo $htmls;
//		return $htmls;	
	}
	
	/**
	 * 获得html格式的奖励(重写方法)
	 */
	public function getRewardsHtml($params) {//print_r($params);
		$htmls = '';
		$htmls .= '<tr class="sign_option"><th class="td_title">任务奖励</th><td class="td_contents">';
		foreach ($params as $k => $v) {//echo '1';print_r($v);
			$htmls .= $k.'&nbsp;&nbsp;<input class="text" type="text" name="rewards['.$k.']" value="'.$v.'" /><br/>';
		}
		$htmls .= '</td><td class="td_span"><span>*填写奖励方式</span><input type="hidden" name="rule" value=""/></td></tr>';
		return $htmls;
	}
	
	/**
	 * 获得html格式的规则(重写方法)
	 */
	public function setRuleHtml() {
//		$htmls = '';
//		$htmls .= '<tr class="sign_option"><th class="td_title">判断依据</th><td class="td_contents"><select name="rule[field]" id="grade_type">';
//		foreach ($this->_rules['field'] as $k => $v) {
//			$htmls .= '<option value="'.$k.'">'.$v.'</option>';
//		}
//		$htmls .= '</select></td><td class="td_span"><span>*</span></td></tr>';
//		return $htmls;
	}
	
	/**
	 * 获得html格式的奖励(重写方法)
	 */
	public function setRewardsHtml() {//print_r($this->_rewards);
//		$this->addConfig('system', 'vipgrade');
//		$jieqiVipgrade = $this->getConfig('system', 'vipgrade');//print_r($jieqiVipgrade);
		
		$htmls = '';
		$htmls .= '<tr class="sign_option"><th class="td_title">任务奖励</th><td class="td_contents">';
//		$htmls .= '<p id="isvip">';
		foreach ($this->_rewards['isvip'] as $k => $v) {//echo '1';print_r($v);
			$htmls .= $k.'&nbsp;&nbsp;<input class="text" type="text" name="rewards['.$k.']" value="'.$v.'" /><br/>';
		}
//		$htmls .= '</p><p style="display:none;" id="score">';
//		foreach ($this->_rewards['score'] as $k => $v) {//echo '1';print_r($v);
//			$htmls .= '<span style="width:25%;">'.$k.'&nbsp;<input class="text" style="width:40px;" type="text" name="rewards['.$k.']" value="'.$v.'" /></span>';
//		}
//		$htmls .= '</p>';
		$htmls .= '</td><td class="td_span"><span>*填写奖励方式</span><input type="hidden" name="rule" value=""/></td></tr>';
		return $htmls;
	}
	protected function recordsGroup($params){
		$this->addLang('task','msg');
		$this->jieqiLang['task'] =  $this->getLang('task'); 
		return sprintf($this->jieqiLang['task']['vip_update_text'],$this->_params['from'],$this->_params['to'],$this->_params['addesilver']);
	}
	/*
	 * 
	 */
	public function isFinish($uid, $isvip) {
//		print_r($this);
		$params['tid'] = $this->_getTid();
		if(!$params['tid']) return FALSE;
		$params['uid'] = $uid;
		
		$this->addConfig('system', 'vipgrade');
		$jieqiVipgrade = $this->getConfig('system', 'vipgrade');
		
		$vipgrade = jieqi_gethonorid($isvip, $jieqiVipgrade)-1;//VIP等级数组中的下标，比真实等级大1
		
		if($vipgrade > 0){
			$this->db->init('complete', 'tcid', 'task');
			$this->db->setCriteria(new Criteria('userid', $uid));
			$this->db->criteria->add(new Criteria('taskid', $params['tid']));
			$this->db->criteria->setFields('finish');
			$finish = $this->db->lists();
			
			if (!empty($finish)) {
				$maxrewardvip = 0;
				foreach($finish as $k=>$v){
					$tmp = json_decode($v['finish'], true);
					$maxrewardvip = $tmp['to']>$maxrewardvip? $tmp['to']:$maxrewardvip;
				}
				if($vipgrade > $maxrewardvip){
					$addesilver = $this->rewardSum($maxrewardvip, $vipgrade, $params['tid']);
					$this->_params['from'] = $maxrewardvip;
					$this->_params['to'] = $vipgrade;
				}else
					$addesilver = 0;
			}else{
				$addesilver = $this->rewardSum(0, $vipgrade, $params['tid']);
				$this->_params['from'] = 0;
				$this->_params['to'] = $vipgrade;
			}		
		}else 
			$addesilver = 0; //VIP0不可完成			
		
		if($addesilver > 0){
			$this->_params['addesilver'] = $addesilver;
			$thisAddTask = array(
				'userid' 		=> $uid,
				'taskid'			=> $params['tid'],
				'finish'			=> $this->finishGroup($params),
				'createtime'		=> JIEQI_NOW_TIME,
				'records'		=> $this->recordsGroup($params)
			);
			$this->db->init('complete', 'tcid', 'task');
			$this->db->add($thisAddTask);
			$this->addReward($params);
			$this->addRewardAfter($params);			
		}
//		print_r($this);
		$this->db->init( 'paylog', 'payid', 'pay' );
//		print_r($this);
	}
	/**
	 * 根据taskid获取当前任务类型的私有方法
	 */
	private function _getTid($type = 'vipup') {
		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria(new Criteria('type', $type));
		$res = $this->db->lists();
		if (empty($res)) {
			return FALSE;
		} else {
			return $res[0]['taskid'];
		}
	}
}
?>
 
 
 
 
 
 
 
 
 