<?php
/**
 * 任务模型类
 * @author chengyuan
 *
 */
class taskModel extends Model{
	
	// 存储当前过滤的参数组
	protected $_params = array();
	// 当前模型操作类型
	protected $_taskType = '';
	// 这里定义所有任务列表
	protected $_types = array(
		'sign'			=> '签约任务',
		'consume'		=> '上月消费任务',
		'vipup'			=> '会员升级任务',
		'signin'		=> '累计签到任务',
		'applogin'		=> '客户端登录',
		'recharge'		=> '充值任务',
		'quest'			=> '抽奖任务'
	);
	// 设置不能重复任务的类型
	protected $_no_type = array('vipup', 'signin');
	
	/**
	 * 任务列表
	 * @param unknown $params
	 */
	public function main($params) {
		$data = $this->getTaskList('main', $params['page']);
		return $data;
	}
	
	/**
	 * 异步获得任务规则
	 */
	public function getTaskRule($params, $isAjax = true) {
		$this->addLang('task','task');
		$jieqiLang['task'] =  $this->getLang('task');
		
		if (!isset($params['type']))
			$this->printfail($jieqiLang['task']['need_task_type']);
		$thisType = trim($params['type']);
		if (!array_key_exists($thisType, $this->_types))
			$this->printfail($jieqiLang['task']['task_type_error']);
		$reHtml = '';
		$taskLib = $this->load($thisType.'task', 'task');
		$reHtml .= $taskLib->getRules(true);
		$reHtml .= $taskLib->getRewards(true);
		if ($reHtml == '')
			$this->printfail($jieqiLang['task']['need_define_rule']);
		if ($isAjax) {
			$this->msgbox('', $reHtml);
		} else {
			return $reHtml;
		}
	}
	
	/**
	 * 添加一个任务的默认方法
	 */
	public function addTask($params) {
		$this->addLang('task','task');
		$jieqiLang['task'] =  $this->getLang('task');
		
		if (!isset($params['type']))
			$this->printfail($jieqiLang['task']['need_task_type']);
		$thisType = trim($params['type']);
		// 字段验证
		if (trim($params['taskname'])=='' || mb_strlen($params['taskname'])>20)
			$this->printfail('任务名称不能为空或超过20个字');
		if (trim($params['description'])=='' || mb_strlen($params['description'])>200)
			$this->printfail('任务描述不能为空或超过200个字');
		// 签到任务仅允许存在一条
		if (in_array($thisType, $this->_no_type)) {
			$this->db->init('task', 'taskid', 'task');
			$this->db->setCriteria(new Criteria('type', $thisType, '='));
			$res = $this->db->lists();
			if (!empty($res))
				$this->printfail($this->_types[$thisType].'不允许重复设置');
		}
		if (!array_key_exists($thisType, $this->_types))
			$this->printfail($jieqiLang['task']['task_type_error']);
		$data = array();
		$taskLib = $this->load($thisType.'task', 'task');
		$data = $taskLib->getTaskForm($params);
		$this->db->init('task', 'taskid', 'task');
		$this->db->add($data);
		$this->msgbox('', array('msg'=>LANG_DO_SUCCESS));
	}
	
	/**
	 * 编辑一个任务的默认方法
	 */
	public function editTask($params) {//$this->dump($params);
//		$uid = $this->_userCheck();
		$this->addLang('task','task');
		$jieqiLang['task'] =  $this->getLang('task');
		
		// 类型验证
		if (!isset($params['type']))
			$this->printfail($jieqiLang['task']['need_task_type']);
		$thisType = trim($params['type']);
		// 字段验证
		if (trim($params['taskname'])=='' || mb_strlen($params['taskname'])>20)
			$this->printfail('任务名称不能为空或超过20个字');
		if (trim($params['description'])=='' || mb_strlen($params['description'])>200)
			$this->printfail('任务描述不能为空或超过200个字');
		// 签到任务仅允许存在一条
		if (in_array($thisType, $this->_no_type)) {
			$this->db->init('task', 'taskid', 'task');
			$this->db->setCriteria(new Criteria('type', $thisType, '='));
			$res = $this->db->lists();
			if ($params['taskid']!=$res[0]['taskid'])
				$this->printfail($this->_types[$thisType].'不允许重复设置');
		}
		if (!array_key_exists($thisType, $this->_types))
			$this->printfail($jieqiLang['task']['task_type_error']);
		// 必须包含taskid
		if (!isset($params['taskid']) || intval($params['taskid'])==0) 
			$this->printfail($jieqiLang['task']['need_taskid']);
		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria(new Criteria('taskid', intval($params['taskid'])));
		// 验证是否存在当前taskid的内容
		if (!$this->db->getCount())
			$this->printfail($jieqiLang['task']['task_not_exists']);
		$data = array();
		$taskLib = $this->load($thisType.'task', 'task');
		$data = $taskLib->getTaskForm($params, false);
		$this->db->edit($params['taskid'], $data);
		$this->msgbox('', array('msg'=>LANG_DO_SUCCESS));
	}
	
	/**
	 * 删除一个任务的默认方法
	 */
	public function delTask($params) {
		$this->addLang('task','task');
		$jieqiLang['task'] =  $this->getLang('task');
		
		// 必须包含taskid
		if (!isset($params['taskid']) || intval($params['taskid'])==0) 
			$this->printfail($jieqiLang['task']['need_taskid']);
		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria(new Criteria('taskid', intval($params['taskid'])));
		// 验证是否存在当前taskid的内容
		if (!$this->db->getCount())
			$this->printfail($jieqiLang['task']['task_not_exists']);
		$this->db->delete(intval($params['taskid']));
		$this->msgbox('', array('msg'=>LANG_DO_SUCCESS));
	}
	
	
	/**
	 * 获得任务列表的默认方法
	 * @param	string	: $methodName 	- 当前控制器名称，用于生成page跳转地址
	 * @param	string	: $page 			- 当前页码
	 * @param	string	: $pageNum 		- 从页数，可手动设置，默认取系统值
	 */
	public function getTaskList($methodName, $page = 1, $pageNum = 0) {
		// 引入分页设置
		$this->addConfig('article','configs');
    		$jieqiConfigs['article'] = $this->getConfig('article', 'configs');
		$pagenum = $jieqiConfigs['article']['pagenum'];
		// 初始化当前页面
		$thisPage = intval($page)<=0 ? 1 : $page;
		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria();
		$this->db->criteria->setSort('createtime');
        $this->db->criteria->setOrder('DESC');
		$thisController = ($_REQUEST['controller']) ? $_REQUEST['controller'] : 'taskController';
		$data['lists'] = $this->db->lists($pagenum, $thisPage, JIEQI_PAGE_TAG);
		if (empty($data)) 
			return $data;
		$reData = $data;
		foreach ($data['lists'] as $k => $v) {
			$reData['lists'][$k]['rule'] = json_decode($v['rule'], true);
			$reData['lists'][$k]['rewards'] = json_decode($v['rewards'], true);
		}
		$reData['url_jumppage'] = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME, $thisController,'evalpage=0','SYS=method='.$methodName));
		return $reData;
	}
	
	/**
	 * 获得一条记录
	 */
	public function getOneTask($params, $isAjax = true) {
		$this->addLang('task','task');
		$jieqiLang['task'] =  $this->getLang('task');
		
		if (intval($params['tid'])<=0)
			$this->printfail($jieqiLang['task']['need_taskid']);
		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria(new Criteria('taskid', intval($params['tid'])));
		$res = $this->db->lists();
		if (empty($res))
			$this->printfail($jieqiLang['task']['task_not_exists']);
		$reData = array(
			'taskid'		=> $res[0]['taskid'],
			'type'			=> $res[0]['type'],
			'taskname'		=> $res[0]['taskname'],
			'description'	=> $res[0]['description'],
			'rule'			=> json_decode($res[0]['rule'], true),
			'rewards'		=> json_decode($res[0]['rewards'], true),
			'createtime'	=> $res[0]['createtime'],
			'starttime'		=> $res[0]['starttime'],
			'endtime'		=> $res[0]['endtime'],
			'isshow'		=> $res[0]['isshow']
		);
		if ($isAjax) {
			// 处理规则显示格式
			$taskModel = $this->load($reData['type'].'task', 'task');
			$reData['ruleForm'] = $taskModel->getRuleHtml($reData['rule']);
			$reData['rewardsForm'] = $taskModel->getRewardsHtml($reData['rewards']);//print_r($reData);
			$this->msgbox('', $reData);
		} else {
			return $reData;
		}
	}
	
	/**
	 * 获得当前所有任务列表
	 */
	public function getTypesLists() {
		return $this->_types;
	}
}
?>