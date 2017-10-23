<?php
include_once ($GLOBALS['jieqiModules']['task']['path'] . '/class/TaskBase.php');
/**
 * 签约任务模块：继承自TaskBase抽象类
 * @author liuxiangbin
 * @version 0.1
 */
class MySigntask extends TaskBase {

	// 实现当前自定义类的规则数组
	protected $_rules = array(
	// key为规则对应的名称，value为默认值
	'groupid' => '0');

	// 实现当前自定义类的奖励规则数组
	protected $_rewards = array(
	// 格式可以自定义，只要自己能看懂
	array('reward' => 'egold', 'name' => '书海币', 'number' => '10'), array('reward' => 'esilver', 'name' => '书券', 'number' => '100'));

	/**
	 * 奖励完成标记
	 */
	protected function finishGroup($params) {
		return 'finished';
	}

	/**
	 * 写入具体的描述文字
	 */
	protected function recordsGroup($params) {
		$this -> addConfig('system', 'groups');
		$groups = $this -> getConfig('system', 'groups');
		// 重组奖励文字
		if ($this -> _params['own_rewards'][0]['reward'] == 'egold') {
			$rewards_name = '书海币';
		} else if ($this -> _params['own_rewards'][0]['reward'] == 'esilver') {
			$rewards_name = '书券';
		}
		$str = '';
		$str .= "完成了签约" . $groups[$this -> _params['own_rules']['groupid']] . '任务，获赠' . $this -> _params['own_rewards'][0]['number'] . $rewards_name . "。";
		return $str;
	}

	/**
	 * 增加奖励
	 */
	protected function addReward($params) {
		if (isset($this -> _params['own_rewards'])) {
			$thisRewards = $this -> _params['own_rewards'];
		} else {
			$this -> db -> init('task', 'taskid', 'task');
			$this -> db -> setCriteria(new Criteria('taskid', $params['tid'], '='));
			$this -> db -> criteria -> setFields('rewards');
			$tmp = $this -> db -> lists();
			$thisRewards = json_decode($tmp[0]['rewards'], true);
		}
		$uid = $this -> _userCheck();
		$this -> db -> init('users', 'uid', 'system');
		$this -> db -> setCriteria(new Criteria('uid', $uid, '='));
		$res_uadd = $this -> db -> lists();
		// 循环重组奖励规则
		$user_adds = array();
		foreach ($thisRewards as $k => $v) {
			$user_adds[$v['reward']] = $res_uadd[0][$v['reward']] + $v['number'];
		}
		$this -> db -> edit($uid, $user_adds);
	}

	/**
	 * 实现方法：用户是否可完成任务
	 */
	protected function haveAchevable($uid, $tid) {
		$this -> db -> init('task', 'taskid', 'task');
		$this -> db -> setCriteria(new Criteria('taskid', $tid, '='));
		$tmpRule = $this -> db -> lists();
		$rules = json_decode($tmpRule[0]['rule'], true);
		$this -> _params['own_rewards'] = json_decode($tmpRule[0]['rewards'], true);
		$this -> _params['own_rules'] = $rules;
		$this -> db -> init('users', 'uid', 'system');
		$this -> db -> setCriteria(new Criteria('uid', $uid, '='));
		$this -> db -> criteria -> setFields('groupid');
		$gid = $this -> db -> lists();
		if (empty($gid)) {
			return false;
		} else if ($gid[0]['groupid'] != $rules['groupid']) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 判断是否已完成
	 */
	protected function haveFinished($uid, $tid) {
		$this->db->init('complete', 'tcid', 'task');
		$this->db->setCriteria(new Criteria('userid', $uid, '='));
		$this->db->criteria->add(new Criteria('taskid', $tid, '='));
		$this->db->criteria->setFields('finish');
		$finish = $this->db->lists();
		if (!empty($finish)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 奖励成功后发站内信
	 */
 	protected function addRewardAfter($params) {
		$this->addLang('task','msg');
		$this->jieqiLang['task'] =  $this->getLang('task'); 
		$auth = $this->getAuth();	
		// 加载群组管理
		$this -> addConfig('system', 'groups');
		$groups = $this -> getConfig('system', 'groups');
		// 重组奖励文字
		if ($this -> _params['own_rewards'][0]['reward'] == 'egold') {
			$rewards_name = '书海币';
		} else if ($this -> _params['own_rewards'][0]['reward'] == 'esilver') {
			$rewards_name = '书券';
		}
		$this->db->init('message','messageid','system');
		$newMessage = array();
		$newMessage['siteid']= JIEQI_SITE_ID;
		$newMessage['postdate']= JIEQI_NOW_TIME;
		$newMessage['fromid']= 6;
		$newMessage['fromname']= '系统管理员';
		$newMessage['toid']= $auth['uid'];
		$newMessage['toname']= $auth['useruname'];
		$newMessage['title']= sprintf($this->jieqiLang['task']['sign_writer_title'], $groups[$this -> _params['own_rules']['groupid']]);
		$newMessage['content']= sprintf($this->jieqiLang['task']['sign_writer_text'], $groups[$this -> _params['own_rules']['groupid']],$this -> _params['own_rewards'][0]['number'], $rewards_name);
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
	 * 获得html格式的规则(重写方法)
	 */
	public function setRuleHtml() {
		$this -> addConfig('system', 'groups');
		$groups = $this -> getConfig('system', 'groups');
		$htmls = '';
		$htmls .= '<tr class="sign_option"><th class="td_title">任务认证组</th><td class="td_contents"><select name="rule[groupid]">';
		foreach ($groups as $k => $v) {
			$htmls .= '<option value="' . $k . '">' . $v . '</option>';
		}
		$htmls .= '</select></td><td class="td_span"><span>*可完成任务的用户组</span></td></tr>';
		return $htmls;
	}

	/**
	 * 获得html格式的奖励(重写方法)
	 */
	public function setRewardsHtml() {
		$htmls = '';
		$htmls .= '<tr class="sign_option"><th class="td_title">任务奖励</th><td class="td_contents"><select name="rewards[0][reward]">';
		foreach ($this->_rewards as $k => $v) {
			$htmls .= '<option value="' . $v['reward'] . '">' . $v['name'] . '</option>';
		}
		$htmls .= '</select>&nbsp;&nbsp;<input class="text" type="text" name="rewards[0][number]" placeholder="例如：5000" />';
		$htmls .= '</td><td class="td_span"><span>*填写奖励方式</span></td></tr>';
		return $htmls;
	}

	/**
	 * 自定义方法：无扩展
	 */
	public function getRuleHtml($params) {
		$this -> addConfig('system', 'groups');
		$groups = $this -> getConfig('system', 'groups');
		$htmls = '';
		$htmls .= '<tr class="sign_option"><th class="td_title">任务认证组</th><td class="td_contents"><select name="rule[groupid]">';
		foreach ($groups as $k => $v) {
			if ($params['groupid'] == $k) {
				$htmls .= '<option value="' . $k . '" selected="selected"">' . $v . '</option>';
			} else {
				$htmls .= '<option value="' . $k . '">' . $v . '</option>';
			}
		}
		$htmls .= '</select></td><td class="td_span"><span>*可完成任务的用户组</span></td></tr>';
		return $htmls;
	}

	/**
	 * 获得html格式的奖励(重写方法)
	 */
	public function getRewardsHtml($params) {
		$htmls = '';
		foreach ($params as $key => $val) {
			$htmls .= '<tr class="sign_option"><th class="td_title">任务奖励</th><td class="td_contents"><select name="rewards[' . $key . '][reward]">';
			foreach ($this->_rewards as $k => $v) {
				if ($val['reward'] == $v['reward']) {
					$htmls .= '<option value="' . $v['reward'] . '" selected="selected" >' . $v['name'] . '</option>';
				} else {
					$htmls .= '<option value="' . $v['reward'] . '">' . $v['name'] . '</option>';
				}
			}
			$htmls .= '</select>&nbsp;&nbsp;<input class="text" type="text" name="rewards[' . $key . '][number]" placeholder="例如：5000" value="' . $params[$key]['number'] . '" />';
			$htmls .= '</td><td class="td_span"><span>*填写奖励方式</span></td></tr>';
		}
		return $htmls;
	}

}
