<?php
/**
 * 任务操作模型：前台用户操作模型
 * @auther by: liuxiangbin
 * @createtime : 2015-01-13
 */

class usertaskModel extends Model {
	
	/**
	 * 获取用户当前所有任务
	 */
	public function getUserList($params) {
		// 引入分页设置
		$this->addConfig('article','configs');
    		$jieqiConfigs['article'] = $this->getConfig('article', 'configs');
		$pagenum = $jieqiConfigs['article']['pagenum'];
		// 初始化当前页面
		$thisPage = intval($params['page'])<=0 ? 1 : $params['page'];
		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria();
		$this->db->criteria->setFields('taskid,type,taskname,description,createtime,starttime,endtime');
		// 是否显示隐藏的查询条件
		$this->db->criteria->add(new Criteria('isshow', 1, '='));
		// 不显示签到任务
		$this->db->criteria->add(new Criteria('type', 'signin', '<>'));
		$this->db->criteria->setSort('createtime');
        $this->db->criteria->setOrder('DESC');
		$thisController = ($_REQUEST['controller']) ? $_REQUEST['controller'] : 'taskController';
		$data['lists'] = $this->db->lists($pagenum, $thisPage, JIEQI_PAGE_TAG);
		$data['url_jumppage'] = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME, $thisController,'evalpage=0','SYS=method='.$methodName));
		if (empty($data['lists'])) 
			return $data;
		// 重组是否完成的参数(1-可完成且未完成，2-已完成，3-不可完成)
		foreach ($data['lists'] as $k => $v) {
			// 动态获取每一条任务的判断规则
			$taskLib = $this->load($v['type'].'task', 'task');
			$timeRule = $taskLib->getTimeRule();
			// 开始时间判断
			if ($timeRule['stime'] == true) {
				if (JIEQI_NOW_TIME<$v['starttime']) {
					unset($data['lists'][$k]);
					continue;
				}
			}
			// 结束时间判断
			if ($timeRule['etime'] == true) {
				if (JIEQI_NOW_TIME>$v['endtime']) {
					unset($data['lists'][$k]);
					continue;
				}
			}
			// 动态判断加载完成标记
			if ($taskLib->isFinished($v['taskid'])) {
				$data['lists'][$k]['iscomplete'] = 2;
			} else if ($taskLib->isAchevable($v['taskid'])) {
				$data['lists'][$k]['iscomplete'] = 1;
			} else {
				$data['lists'][$k]['iscomplete'] = 3;
			}
		}
		return $data;
	}
	
	/**
	 * 获取用户完成任务描述列表
	 */
	public function getUsreFinished($params) {
		$auth = $this->getAuth();
		// 引入分页设置
		$this->addConfig('article','configs');
    		$jieqiConfigs['article'] = $this->getConfig('article', 'configs');
		$pagenum = $jieqiConfigs['article']['pagenum'];
		// 初始化当前页面
		$thisPage = intval($params['page'])<=0 ? 1 : $params['page'];
		$this->db->init('complete', 'tcid', 'task');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('userid', $auth['uid'], '='));
		$this->db->criteria->setFields('records,createtime');
		$this->db->criteria->setSort('createtime');
        $this->db->criteria->setOrder('DESC');
		$thisController = ($_REQUEST['controller']) ? $_REQUEST['controller'] : 'taskController';
		$data['lists'] = $this->db->lists($pagenum, $thisPage, JIEQI_PAGE_TAG);
		$data['url_jumppage'] = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME, $thisController,'evalpage=0','SYS=method='.$methodName));
		return $data;
	}
	
	/**
	 * 设置任务完成：异步方式返回
	 */
	public function setComplete($params) {
		$taskType = $this->_getType($params['tid']);
		$taskLib = $this->load($taskType . 'task', 'task');
		if ($taskType == 'vipup') {
			$res = $taskLib->setFinish($params, true);
		} else {
			$res = $taskLib->setFinish($params);
		}
		if ($res['status']=='OK') {
			$this->msgbox('');
		} else {
			$this->printfail($res['msg']);
		}
	}
	
	/**
	 * 根据taskid获取当前任务类型的私有方法
	 */
	private function _getType($tid) {
		if (intval($tid) <= 0) 
			$this->printfail('缺少必要条件');
		$this->db->init('task', 'taskid', 'task');
		$this->db->setCriteria(new Criteria('taskid', $tid, '='));
		$this->db->criteria->setFields('type');
		$res = $this->db->lists();
		if (empty($res)) {
			$this->printfail('任务类型获取失败');
		} else {
			return $res[0]['type'];
		}
	}
}
	