<?php
include_once ($GLOBALS['jieqiModules']['task']['path'] . '/class/TaskBase.php');

/**
 * 客户端登陆模块：继承自TaskBase抽象类
 * @author zhangxue
 * @version 0.1
 */
 class MyQuesttask extends TaskBase {
 	
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
	 * $params tid
	 */
 	protected function addReward($params) {
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
	 * $params tid
	 */
 	protected function addRewardAfter($params) {
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
		return false;
	}
	
	/**
	 * 判断是否已完成，判断是否已领过
	 */
	protected function haveFinished($uid, $tid) {
		$this->db->init('complete', 'tcid', 'task');
		$this->db->setCriteria(new Criteria('userid', $uid));
		$this->db->criteria->add(new Criteria('taskid', $tid));
		$this->db->criteria->add(new Criteria('createtime', $this->getTime(), '>='));
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
	 * $params tid
	 */
	protected function recordsGroup($params){
		$this->getReward($params);//获取奖励参数
		$msg = '当天参与答题抽奖活动，抽中'.$this->_params['value'];
		if($this->_params['type']=='esilver'){
			$msg .= '书券。';
		}else{
			$msg .= '书海币。';
		}
		return $msg;
	}
	/*
	 * 获取详细奖励信息，用于备注
	 */
	private function getReward($params){
		$items = array(
				array('prob' => 700,'msg'=>'六等奖', 'type'=>'esilver', 'value'=>50, 'data'=>'6'),
				array('prob' => 850,'msg'=>'五等奖', 'type'=>'esilver', 'value'=>200, 'data'=>'5'),
				array('prob' => 909,'msg'=>'四等奖', 'type'=>'esilver', 'value'=>500, 'data'=>'4'),
				array('prob' => 989,'msg'=>'三等奖', 'type'=>'egold', 'value'=>88, 'data'=>'3'),
				array('prob' => 999,'msg'=>'二等奖', 'type'=>'egold', 'value'=>188, 'data'=>'2'),
				array('prob' => 1000,'msg'=>'一等奖', 'type'=>'egold', 'value'=>588, 'data'=>'1')
		);
		$index = $this->getItems($items);
		$this->_params = $items[$index];//echo $value['msg'];
//		return $value;
	}
	private function getItems($items){
		mt_srand((double)microtime()*1000000);
		$randval = mt_rand(1,1000);
		foreach($items as $k => $v){
			$prob = $v['prob'];
			if($randval<=$prob){
				return $k;
			}
		}
	}
	function judge($params = array()){
		if(JIEQI_NOW_TIME<1423929600){
			$this->printfail('活动未开始，请留意具体活动时间！');
		}elseif(JIEQI_NOW_TIME>=1425571200){
			$this->printfail('活动已结束，下次请抓紧哦！');
		}else{
			$auth = $this->getAuth();
			$this->db->init('task', 'taskid', 'task');
			$this->db->setCriteria(new Criteria('type', 'quest'));
			$task = $this->db->get($this->db->criteria);
			$tid = $task->getVar('taskid');
			
			if($this->haveFinished($auth['uid'], $tid)){
				$this->printfail('今天已经抽过奖了，明天再来吧');
			}else{
				$thisAddTask = array(
					'userid' 		=> $auth['uid'],
					'taskid'			=> $tid,
					'finish'			=> $this->finishGroup($params),
					'createtime'		=> JIEQI_NOW_TIME,
					'records'		=> $this->recordsGroup($params)
				);
				$this->db->init('complete', 'tcid', 'task');
				$this->db->add($thisAddTask);
				
				$value = $this->_params;//echo $value['msg'];
				$users_handler =  $this->getUserObject();
				if($value['type']=='egold'){
					$ret = $users_handler->income($auth['uid'],$value['value']);
//					$type = '书海币';
				}else{
					$ret = $users_handler->income($auth['uid'],$value['value'],1);
//					$type = '书券';
				}
				$this->msgbox("", $value['data']);
	//			exit( json_encode(array('status'=>true,'msg'=>$value['data'])));
	//			if($ret){
	//				$this->db->init('message','messageid','system');
	//				$newMessage = array();
	//				$newMessage['siteid']= JIEQI_SITE_ID;
	//				$newMessage['postdate']= JIEQI_NOW_TIME;
	//				$newMessage['fromid']= 6;
	//				$newMessage['fromname']= '系统管理员';
	//				$newMessage['toid']= $auth['uid'];
	//				$newMessage['toname']= $auth['username'];
	//				$newMessage['title']= '登录书海手机客户端送礼';
	//				$newMessage['content']= '书海迎来三周年庆，手机客户端送礼啦。您在'.date('Y年m月d日',JIEQI_NOW_TIME).'参与答题抽奖，抽中'.$value['msg'].'，获赠'.$value['value'].$type.'，请注意查收。';
	//				$newMessage['messagetype']= 0;
	//				$newMessage['isread']= 0;
	//				$newMessage['fromdel']= 0;
	//				$newMessage['todel']= 0;
	//				$newMessage['enablebbcode']= 1;
	//				$newMessage['enablehtml']= 0;
	//				$newMessage['enablesmilies']= 1;
	//				$newMessage['attachsig']=0;
	//				$newMessage['attachment']= 0;
	//				$this->db->add($newMessage);	
	//			}
			}
		}

	}
}
?>
 
 
 
 
 
 
 
 
 