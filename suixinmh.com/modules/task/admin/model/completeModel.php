<?php
/**
 * 任务完成记录表操作模型
 * @author liuxiangbin
 *
 */
class completeModel extends Model{
	
	/**
	 * 获取数据列表
	 */
	public function getAllData($params) {
//		$this->dump($params);
		$thisPage = isset($params['page']) ? intval($params['page']) : 1;
		$data = array();
		$this->db->init('complete', 'sid', 'task');
		$this->db->setCriteria();
		$this->db->criteria->setTables(jieqi_dbprefix('task_complete').' tc LEFT JOIN '.jieqi_dbprefix('task_task').' tt ON tc.taskid=tt.taskid LEFT JOIN '.jieqi_dbprefix('system_users').' su ON tc.userid=su.uid');
		$this->db->criteria->setFields('tc.*,su.uname,tt.taskname,tt.type,tt.isshow');
		// 查询条件
		if (isset($params['keyword']) && trim($params['keyword']) != '') {
			$this_field = trim($params['searchkey']);
			if (in_array($this_field, array('uname','name'))) {
				$this->db->criteria->add(new Criteria('su.'.$this_field, '%'.trim($params['keyword']).'%', 'LIKE'));
			} else if ($this_field == 'taskname') {
				$this->db->criteria->add(new Criteria('tt.'.$this_field, '%'.trim($params['keyword']).'%', 'LIKE'));
			} else {
				$this->printfail('非法参数');
			}
		}
		if (isset($params['tasktype']) && $params['tasktype'] != 'all') {
			$this->db->criteria->add(new Criteria('tt.type', $params['tasktype'], '='));
		}
		if (isset($params['start']) && intval($params['start'])!=0) {
			$this->db->criteria->add(new Criteria('tc.createtime', strtotime($params['start']), '>='));
		}
		if (isset($params['end']) && intval($params['end'])!=0) {
			$this->db->criteria->add(new Criteria('tc.createtime', strtotime($params['end']), '<='));
		}
		// 排序
		$this->db->criteria->setSort('tc.createtime');
        $this->db->criteria->setOrder('DESC');
		// 引入分页设置
		$this_count = $this->db->getCount($this->db->criteria);
		$this->addConfig('article','configs');
    		$jieqiConfigs['article'] = $this->getConfig('article', 'configs');
		$pagenum = $jieqiConfigs['article']['pagenum'];
		$data['lists'] = $this->db->lists($pagenum, $thisPage, JIEQI_PAGE_TAG);
        include_once(HLM_ROOT_PATH.'/lib/html/page.php');
        $jumppage = new JieqiPage($this_count, $pagenum, $thisPage);
        $jumppage->setlink('', true, true);
		$data['url_jumppage'] = $jumppage->whole_bar();
		return $data;
	}
}