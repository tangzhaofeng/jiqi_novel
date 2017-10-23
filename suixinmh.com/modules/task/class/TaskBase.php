<?php
/**
 * 任务管理模块抽象类：所有任务管理自定义类的顶级类
 * 使用说明：在具体实现时，必须实现抽象方法
 * @Create_Time: 2015-01-12 10:19:30
 * @Author:liuxiangbin
 */
abstract class TaskBase extends JieqiObject{
	
	// 内部使用的参数容器
	protected $_params = array();
	// 开始时间判断标记
	protected $_stime = false;
	// 结束时间判断标记
	protected $_etime = false;
	// 显示/隐藏开关标记
	protected $_isshow = false;
	// 当前规则数组(必须实现方法)
	protected $_rules = array();
	// 当前奖励数组(必须实现方法)
	protected $_rewards = array();
	
	/**
	 * 构造函数初始化一个db数据库操作模型
	 */
	public function __construct() {
		if (! is_object ( $this->db )) {
			$this->db = Application::$_lib ['database'];
		}
	}
	
	/**：前台调用方法**/
	/**
	 * 判断用户是否可完成任务
	 * @param	: $uid		用户id
	 * @param	: $tid		任务id
	 */
	public function isAchevable($tid) {
		// 检查用户是否登录
		$uid = $this->_userCheck();
		// 调用具体规则
		$thisRule = $this->haveAchevable($uid, $tid);
		if ($thisRule) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 设置用户完成任务
	 * @param	: $params	完成任务参数
	 */
	public function setFinish($params, $is_add = false) {
		$this->addLang('task','task');
		$jieqiLang['task'] =  $this->getLang('task');
		if(isset($params['uid']))
			$uid = $params['uid'];
		else 
			$uid = $this->_userCheck();
		// 判断用户是否可以完成任务
		if (!$this->isAchevable($params['tid']))
			return array('status'=>'ER01', 'msg'=>$jieqiLang['task']['task_not_complete']);
		// 验证该任务是否已经存在完成记录
		$userIsFinished = $this->isFinished($params['tid']);
		if ($userIsFinished) 
			return array('status'=>'ER02', 'msg'=>$jieqiLang['task']['task_has_complete']);
		// 写入用户任务完成记录
		$thisAddTask = array(
			'userid' 		=> $uid,
			'taskid'			=> $params['tid'],
			'finish'			=> $this->finishGroup($params),
			'createtime'		=> JIEQI_NOW_TIME,
			'records'		=> $this->recordsGroup($params)
			
		);
		$this->db->init('complete', 'tcid', 'task');
		$this->db->setCriteria(new Criteria('userid', $uid, '='));
		$this->db->criteria->add(new Criteria('taskid', $params['tid'], '='));
		$res_com = $this->db->lists();
		// 如果记录存在且为当月记录则更新完成状态，否则新建一条记录
		if (!empty($res_com) && date('Ym', JIEQI_NOW_TIME)==date('Ym', $res_com[0]['createtime'])) {
			// 如果存在强制新增complete记录的标识，则强制insert一条新纪录
			if ($is_add) {
				$this->db->add($thisAddTask);
			} else {
				$update = array('finish'=>$thisAddTask['finish'], 'records'=>$thisAddTask['records']);
				$this->db->edit($res_com[0]['tcid'], $update);
			}
		} else {
			$this->db->add($thisAddTask);
		}
		$this->addReward($params);
		$this->addRewardAfter($params);
		return array('status'=>'OK');
	}

	/**
	 * 判断用户是否已完成任务
	 * @param	: $uid		用户id
	 * @param	: $tid		任务id
	 */
	public function isFinished($tid) {
		// 检查用户是否登录
		$uid = $this->_userCheck();
		// 调用任务完成记录表并进行验证
		$userFinished = $this->haveFinished($uid, $tid);
		if ($userFinished) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 获取当前自定义类的规则数组
	 */
	public function getRules($isHtml = false, $htmlType = 1) {
		if (empty($this->_rules))
			return false;
		// 返回html格式
		if ($isHtml) {
			return $this->setRuleHtml($htmlType);
		}
		return $this->_rules;
	}
	
	/**
	 * 获取当前自定义类的奖励数组
	 */
	public function getRewards($isHtml = false, $htmlType = 1) {
		if (empty($this->_rewards))
			return false;
		// 返回html格式
		if ($isHtml) {
			return $this->setRewardsHtml($htmlType);
		}
		return $this->_rewards;
	}
	
	/**
	 * 返回当前自定义类的时间判断状态
	 */
	public function getTimeRule() {
		return array('stime'=>$this->_stime, 'etime'=>$this->_etime);
	}
	
	
	/**
	 * 按照自定义规则过滤和重组数据
	 */
	public function getTaskForm($params, $isAdd = true) {
		$reData = array();
		$this->filterTask($params);
		$reData = array(
			'type'			=> $this->_params['type'],
			'taskname'		=> $this->_params['taskname'],
			'description'	=> $this->_params['description'],
			'rule'			=> json_encode($this->_params['rule']),
			'rewards'		=> json_encode($this->_params['rewards']),
			'isshow'			=> $this->_params['isshow']
		);
		if ($this->_stime == true) 
			$reData['starttime'] = $this->_params['starttime'];
		if ($this->_etime == true)
			$reData['endtime'] = $this->_params['endtime'];
		if ($isAdd)
			$reData['createtime'] = time();
		return $reData;
	}
	
	/**：内部封装方法**/
	/**
	 * 验证用户权限
	 * @param	: $uid		用户id
	 * @param	: $isAdmin	后台权限则为true
	 */
	protected function _userCheck($isAdmin = false) {
		// TODO::验证前台用户（非管理员）登陆权限
		$auth = $this->getAuth();
		if (!isset($auth['uid']) || intval($auth['uid'])<=0) 
			$this->checkLogin();
		$uid = $auth['uid'];
		if (!$isAdmin) {
			return $uid;
		} else {
			// TODO::后台管理返回权限组
		}
	}
	
	/**
	 * 过滤方法，默认使用htmlspecialchars和trim过滤，如有其他方式则集成并添加规则即可
	 */
	protected function filterTask($params = array()) {
		if (empty($params)) 
			return true;
		// 过滤规则（默认规则，可重写）
		foreach ($params as $k => $v) {
			if (!is_array($v)) {
				$this->_params[$k] = htmlspecialchars(trim($v));
			} else {
				$this->_params[$k] = $v;
			}
		}
	}
	
	/**
	 * 返回一个html格式的方法。
	 * 扩展：$type参数可以自定义，根据type不同从而调用不同的html格式
	 */
	public function setRuleHtml($type = '') {
		// TODO::需要自定义
		return $this->_rules;
	}
		
	/**
	 * 返回一个html格式的方法。
	 * 扩展：$type参数可以自定义，根据type不同从而调用不同的html格式
	 */
	public function setRewardsHtml($type = '') {
		// TODO::需要自定义
		return $this->_rewards;
	}

	/**
	 * 用户完成任务之后的事件，如果不定义则什么也不做
	 */
	protected function addRewardAfter($params = array()) {
		// TODO::默认什么都不做
	}
	
	
	// *************必须重写的抽象方法*****************
	
	// 用户完成任务后增加奖励(只允许本类的子类调用)
	abstract protected function addReward($params);
	// 获得任务规则并验证当前用户是否可完成（返回真/假）
	abstract protected function haveAchevable($uid, $tid);
	// 在用户任务表中写入具体任务完成格式，最后json_encode压缩
	abstract protected function finishGroup($params);
	// 在描述表中写入具体的描述文字内容存入数据库(字符串)
	abstract protected function recordsGroup($params);
	// 用户是否已完成具体任务的规则会有不同，必须重写
	abstract protected function haveFinished($uid, $tid);
}











