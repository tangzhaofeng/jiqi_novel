<?php
include_once ($GLOBALS['jieqiModules']['task']['path'] . '/class/TaskBase.php');

/**
 * 上月消费返还模块：继承自TaskBase抽象类
 * @author liuxiangbin
 * @version 0.1
 */
 class MyConsumetask extends TaskBase {
 	
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
		return date("Ym", JIEQI_NOW_TIME);
	}
 	
	/**
	 * 增加奖励
	 */
 	protected function addReward($params) {//print_r($params);
   		$uid = $this->_userCheck();
   		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria(new Criteria('taskid', $params['tid']));
		$this->db->criteria->setFields('rewards');
		$tmp = $this->db->lists();
		$thisRewards = json_decode($tmp[0]['rewards'], true);//print_r($thisRewards);//exit;
		$this->_params['type'] = $thisRewards[0]['reward'];
		$consumeegold = $this->haveAchevable($uid, $params['tid']);
		$this->_params['consumeegold'] = $consumeegold;
		$addesilver = round($consumeegold*$thisRewards[0]['percentage'],2);
		$this->_params['addesilver'] = $addesilver;
		
 		$this->db->init('users', 'uid', 'system');
		$this->db->setCriteria(new Criteria('uid', $uid, '='));
		$res_uadd = $this->db->lists();
		// 循环重组奖励规则
		$user_adds = array();
//		foreach ($thisRewards as $k => $v) {
			$user_adds[$this->_params['type']] = $res_uadd[0][$this->_params['type']] + $addesilver;
//		}
		$this->db->edit($uid, $user_adds);
 	}
	
	/**
	 * 奖励成功后发站内信
	 */
 	protected function addRewardAfter($params) {//$this->dump($params);
		$this->addLang('task','msg');
		$this->jieqiLang['task'] =  $this->getLang('task'); 
		$auth = $this->getAuth();	
		if($this->_params['type']=='esilver'){
			$type = '书券';
		}else{
			$type = '书海币';
		}
			
		$this->db->init('message','messageid','system');
		$newMessage = array();
		$newMessage['siteid']= JIEQI_SITE_ID;
		$newMessage['postdate']= JIEQI_NOW_TIME;
		$newMessage['fromid']= 6;
		$newMessage['fromname']= '系统管理员';
		$newMessage['toid']= $auth['uid'];
		$newMessage['toname']= $auth['useruname'];
		$newMessage['title']= $this->jieqiLang['task']['premonth_xf_title'];
		$newMessage['content']= sprintf($this->jieqiLang['task']['premonth_xf_text'],$this->_params['consumeegold'],$this->_params['addesilver']);
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
//		$this->db->init('task', 'taskid', 'task');
//		$this->db->setCriteria(new Criteria('taskid', $tid));
//		$this->db->criteria->setFields('rule');
//		$tmpRule = $this->db->lists();
//		$rules = json_decode($tmpRule[0]['rule'], true);
		
		$monthstart=$this->getTime('month');
		$premonth = $this->getTime('premonth');
		
		$saleegold = $rewardegold = 0;
		
		//查询当前用户订阅总金额
		$this->db->init( 'sale', 'saleid', 'article' );
		$this->db->setCriteria(new Criteria('accountid', $uid));
		$this->db->criteria->add(new Criteria( 'buytime', $premonth, '>=' ));
		$this->db->criteria->add(new Criteria( 'buytime', $monthstart, '<' ));
		$this->db->criteria->add(new Criteria( 'pricetype', 0 ));//不包括纯书券订阅
		$saleegold = $this->db->getSum('saleprice');
		
		//查询当前用户打赏总金额
		$this->db->init( 'statlog', 'statlogid', 'article' );
		$this->db->setCriteria(new Criteria('uid', $uid));
		$this->db->criteria->add(new Criteria( 'mid', 'reward'));				
		$this->db->criteria->add(new Criteria( 'addtime', $premonth, '>=' ));
		$this->db->criteria->add(new Criteria( 'addtime', $monthstart, '<' ));
		$this->db->criteria->setFields("uid,username,sum(stat) as sum");
		$rewardegold = $this->db->getSum('stat');
		
		$totalegold = bcadd($saleegold, $rewardegold, 2);
		if($totalegold>0) return $totalegold;
		else return false;
	}
	
	/**
	 * 判断是否已完成，结合完成记录的创建时间，判断本月是否已领过上月消费返还
	 */
	protected function haveFinished($uid, $tid) {
		$monthstart=$this->getTime('month');//echo $monthstart.' ';
		//下月初
		$nextmonth = explode('-',date('Y-m-d',strtotime("+1 month")));
		$nextmonthstart = mktime(0,0,0,(int)$nextmonth[1],1,(int)$nextmonth[0]);

		$thismonth = date('Ym', JIEQI_NOW_TIME);
		
		$this->db->init('complete', 'tcid', 'task');
		$this->db->setCriteria(new Criteria('userid', $uid));
		$this->db->criteria->add(new Criteria('taskid', $tid));
//		$this->db->criteria->add(new Criteria('finish', '%'.$thismonth.'%', 'LIKE'));
		
		$this->db->criteria->add(new Criteria('createtime', $monthstart, '>='));
		$this->db->criteria->add(new Criteria('createtime', $nextmonthstart, '<'));//本月有记录，即已领取上月消费返还
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
		$this->getReward($params);
		if($this->_params['type']=='esilver') $type = '书券';
		else $type = '书海币';
		$msg = date('Y年m月',$this->getTime('premonth')).'累计消费'.$this->_params['consumeegold'].'书海币，获赠'.$this->_params['addesilver'].$type.'。';
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
}
?>
 
 
 
 
 
 
 
 
 