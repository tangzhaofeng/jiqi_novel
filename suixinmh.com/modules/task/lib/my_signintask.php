<?php
include_once ($GLOBALS['jieqiModules']['task']['path'] . '/class/TaskBase.php');
/**
 * 签到任务模块：继承自TaskBase抽象类
 * @author liuxiangbin
 * @version 0.1
 */
 class MySignintask extends TaskBase {
 	// 自定义规则字段
 	protected $_rules = array(7,15,99);
	// 自定义奖励规则字段
 	protected $_rewards = array(
		array('reward'=>'egold', 'name'=>'书海币', 'number'=>'10'),
		array('reward'=>'esilver', 'name'=>'书券', 'number'=>'100'),
		array('reward'=>'score', 'name'=>'积分', 'number'=>'100')
	);
	
	/**
	 * 实现方法：用户是否可完成任务
	 */
	protected function haveAchevable($uid, $tid) {
		// 获取当前任务规则
		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria(new Criteria('taskid', $tid, '='));
//		$this->db->criteria->setFields('rule');
		$tmpRule = $this->db->lists();
		// 写入本次规则数组进行参数传递
		$this->_params['own_rules'] = json_decode($tmpRule[0]['rule'], true);
		$this->_params['own_rewards'] = json_decode($tmpRule[0]['rewards'], true);
		// 获取用户签到记录
		$now_time = time();
		$thisMonth = date('Ym', $now_time);
		$thisDay = date('d', $now_time);
		$this->db->init('signin', 'sid', 'task');
		$this->db->setCriteria(new Criteria('uid', $uid, '='));
		$this->db->criteria->add(new Criteria('month', $thisMonth, '='));
		$this->db->criteria->setFields('days');
		$sign_days = $this->db->lists();	
		if (empty($sign_days)) {
			return false;
		} else {
			// 计算用户本月的签到次数
			$user_sdays = count(array_filter(explode(',',$sign_days[0]['days'])));
			if (in_array($user_sdays, $this->_params['own_rules'])) {
				// 写入变量进行参数传递
				$this->_params['own_signs'] = $user_sdays;
				return true;
			} else {
				// 增加月末最后一天的判断
				$tomorrow = date('d', $now_time+(24*3600));
				if ($thisDay>$tomorrow && $thisDay==$user_sdays+1) {
					$this->_params['own_signs'] = 99;
					// 月末最后一天则返回可执行操作
					return true;
				}
				// 否则不可完成
				return false;
			}
		}
	}

	/**
	 * 判断是否已完成
	 */
	protected function haveFinished($uid, $tid) {
		// 加入语言包
		$this->addLang('task','task');
		$jieqiLang['task'] =  $this->getLang('task');
		// 获得当前已完成记录
		$month_Ym = date('Ym', JIEQI_NOW_TIME);
		$first_day = $this->getTime('month');
		$this->db->init('complete', 'tcid', 'task');
		$this->db->setCriteria(new Criteria('userid', $uid, '='));
		$this->db->criteria->add(new Criteria('taskid', $tid, '='));
		// 只判断本月记录
		$this->db->criteria->add(new Criteria('createtime', $first_day, '>='));
		$tmp = $this->db->lists();
//		$this->dump($tmp, 0);
		if (empty($tmp)) {
			return false;
		} else {
			// 记录赋值给函数变量
			$this->_params['own_records'] = $tmp[0]['records'];
			$user_signs = 0;
			// 如果内部变量含有用户签到记录就直接赋值，如果不存在则查询一次用户签到表
			if (isset($this->_params['own_signs'])) {
				$user_signs = $this->_params['own_signs'];
			} else {
				// 获取用户签到记录
				$this->db->init('signin', 'sid', 'task');
				$this->db->setCriteria(new Criteria('uid', $uid, '='));
				$this->db->criteria->add(new Criteria('month', $month_Ym, '='));
				$this->db->criteria->setFields('days');
				$sign_days = $this->db->lists();	
				$user_sdays = count(array_filter(explode(',',$sign_days[0]['days'])));
				if (empty($sign_days)) {
					// 返回值应该提示错误
					return array('status'=>'ER01', 'msg'=>$jieqiLang['task']['task_not_complete']);
				} else {
					// 如果签到天数等于当月天数则给予最大奖励
					if ($this->getTime('month')==$user_sdays) {
						$user_signs = 99;
					} else {
						$user_signs = $user_sdays;
					}
				}
			}
			// 判断逻辑
			$finish_data = json_decode($tmp[0]['finish'], true);
			$this->_param['own_finish'] = $finish_data;
			if (in_array($user_signs, $finish_data)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * 奖励完成标记
	 */
	protected function finishGroup($params) {
		// 获得当前完成的累计天数
		$total_days = $this->_params['own_signs'];
		// 重组需要写入的完成数组
		$finish_data = $this->_param['own_finish'];
		// 组合完成内容
		$finish_data[] = $total_days;
//		$this->dump($this->_params);
		return json_encode($finish_data);
	}
	
	/**
	 * 写入具体的描述文字
	 */
	protected function recordsGroup($params) {
		$this_month = date('m', JIEQI_NOW_TIME);
		$this_year = date('Y', JIEQI_NOW_TIME);
		// 根据函数变量给日期赋值
		if ($this->_params['own_signs'] == 99) 
			$own_sign_days = cal_days_in_month(CAL_GREGORIAN, intval($this_month), intval($this_year));
		else 
			$own_sign_days = $this->_params['own_signs'];
		if ($this->_params['own_records']=='')
			$str = '';
		else 
			$str = $this->_params['own_records'];
		// 重组奖励文字
		switch ($this->_params['own_rewards'][$own_sign_days]['reward']) {
			case 'esilver'	:
				$rewards_name = '书券';break;
			case 'egold'	:
				$rewards_name = '书海币';break;
			case 'score'	:
				$rewards_name = '积分';break;
		}
		$str .= '我完成了累计签到'.$own_sign_days.'天的任务，获赠'.$this->_params['own_rewards'][$this->_params['own_signs']]['number'].$rewards_name.'。';
		return $str;
	}
	
	/**
	 * 增加奖励的具体数据库操作
	 */
	protected function addReward($params) {
		$uid = $this->_userCheck();
 		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria(new Criteria('taskid', $params['tid'], '='));
		$this->db->criteria->setFields('rewards');
		$tmp = $this->db->lists();
		$thisRewards = json_decode($tmp[0]['rewards'], true);
		$this->db->init('users', 'uid', 'system');
		$this->db->setCriteria(new Criteria('uid', $uid, '='));
		$tmp_user = $this->db->lists();
		$user_sum = $tmp_user[0][$thisRewards[$this->_params['own_signs']]['reward']];
		$add_data = array(
			$thisRewards[$this->_params['own_signs']]['reward'] => $user_sum + $thisRewards[$this->_params['own_signs']]['number']
		);
		$this->db->edit($uid, $add_data);
	}
	
	/**
	 * 获得html格式的规则(重写方法)
	 */
	public function setRuleHtml() {
		$htmls = '';
		$htmls .= '<tr class="sign_option"><th class="td_title">任务规则</th><td class="td_contents">';
		foreach ($this->_rules as $key => $val) {
			$htmls .= '<label>天数:</label><input readonly="true" style="width: 40px" name="rule[]" placeholder="例如:3" value="'.$val.'" />&nbsp;&nbsp;<label>奖励:</label><select name="rewards['.$val.'][reward]">';
			foreach ($this->_rewards as $k => $v) {
				$htmls .= '<option value="'.$v['reward'].'">'.$v['name'].'</option>';
			}
			$htmls .= '</select>&nbsp;&nbsp;<input style="width: 80px" class="text" type="text" name="rewards['.$val.'][number]" placeholder="例如:5000" /><br />';
		}
		$htmls .= '</td><td class="td_span"><span>*99天表示月末最后一天</span></td></tr>';
		return $htmls;
	}
	
	/**
	 * 获得html格式的奖励(重写方法)
	 */
	public function setRewardsHtml() {
		$htmls = '';
		return $htmls;
	}
	
	/**
	 * 自定义方法：无扩展
	 */
	public function getRuleHtml($params) {
		$htmls = '';
		return $htmls;
	}
	
	/**
	 * 获得html格式的奖励(重写方法)
	 */
	public function getRewardsHtml($params) {
		$htmls = '';
		$htmls .= '<tr class="sign_option"><th class="td_title">任务规则</th><td class="td_contents">';
		foreach ($params as $key => $val) {
			$htmls .= '<label>天数:</label><input readonly="true" style="width: 40px" name="rule[]" placeholder="例如:3" value="'.$key.'" />&nbsp;&nbsp;<label>奖励:</label><select name="rewards['.$key.'][reward]">';
			foreach ($this->_rewards as $k => $v) {
				if ($val['reward'] == $v['reward']) {
					$htmls .= '<option value="'.$v['reward'].'" selected="selected">'.$v['name'].'</option>';
				} else {
					$htmls .= '<option value="'.$v['reward'].'">'.$v['name'].'</option>';
				}
			}
			$htmls .= '</select>&nbsp;&nbsp;<input style="width: 80px" class="text" type="text" name="rewards['.$key.'][number]" placeholder="例如:5000" value="'.$val['number'].'" /><br />';
		}
		$htmls .= '</td><td class="td_span"><span>*99天表示完成整月签到</span></td></tr>';
		return $htmls;
	}
 }
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 