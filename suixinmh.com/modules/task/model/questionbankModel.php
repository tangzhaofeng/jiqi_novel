<?php
/**
 * 任务操作模型：前台用户操作模型
 * @auther by: liuxiangbin
 * @createtime : 2015-02-04
 */

class questionbankModel extends Model {
	
	protected $_params = array();
		
	/**
	 * 后台模型：获得所有题库数据
	 */
	public function getAllData($params) {
		$reData = array();
		$this->db->init('quests', 'qid', 'task');
		$this->db->setCriteria();
		$this->db->criteria->setTables(jieqi_dbprefix('task_quests').' tq LEFT JOIN '.jieqi_dbprefix('article_article').' aa ON tq.aid=aa.articleid');
		$this->db->criteria->setFields('tq.*,aa.articlename');
		$this->db->criteria->add(new Criteria('qflag', 99, '<>'));
		// 搜索条件
		if (isset($params['keyword']) && trim($params['keyword']) != '') {
			$this_field = trim($params['searchkey']);
			if (in_array($this_field, array('question','aid'))) {
				$this->db->criteria->add(new Criteria('tq.'.$this_field, '%'.trim($params['keyword']).'%', 'LIKE'));
			} else if ($this_field == 'articlename') {
				$this->db->criteria->add(new Criteria('aa.'.$this_field, '%'.trim($params['keyword']).'%', 'LIKE'));
			} else {
				$this->printfail('非法参数');
			}
		}
		// 排序
		$this->db->criteria->setSort('tq.createtime');
		$this->db->criteria->setOrder('DESC');
		// 引入分页设置
		$this_count = $this->db->getCount($this->db->criteria);
		$this->addConfig('article','configs');
    		$jieqiConfigs['article'] = $this->getConfig('article', 'configs');
		$pagenum = $jieqiConfigs['article']['pagenum'];
		$thisPage = isset($params['page']) ? intval($params['page']) : 1;
		$reData['lists'] = $this->db->lists($pagenum, $thisPage, JIEQI_PAGE_TAG);
        include_once(HLM_ROOT_PATH.'/lib/html/page.php');
        $jumppage = new JieqiPage($this_count, $pagenum, $thisPage);
        $jumppage->setlink('', true, true);
		$reData['url_jumppage'] = $jumppage->whole_bar();
		$reData['qbcount'] = $this_count;
		return $reData;
	}
	
	/**
	 * 后台模型：设置一个问题
	 */
	public function setOneData($params, $this_act = 'add') {
		$this->paramsFilter($params, $this_act);
		$this->db->init('quests', 'qid', 'task');
		$setData = array(
			'aid'				=> $this->_params['aid'],
			'question'			=> $this->_params['question'],
			'options'			=> json_encode($this->_params['options']),
			'rightoption'		=> $this->_params['rightoption'],
			'point'				=> ''
		);
		if ($this_act == 'add') {
			$setData['createtime'] = JIEQI_NOW_TIME;
			$this->db->add($setData);
			$this->msgbox('');
			die;
		} elseif ($this_act == 'edit') {
//			$this->dump($setData);
			$this->db->edit($this->_params['qid'], $setData);
			$this->msgbox('');
			die;
		}
		$this->printfail('处理失败');
	}
	
	/**
	 * 后台模型：删除问题
	 */
	public function delData($params, $isBatching = false) {
		$this->paramsFilter($params, 'del');
		$data = array();
		$this->db->init('quests', 'qid', 'task');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('qid', $this->_params['qid'], '='));
		$res = $this->db->lists();
		if (empty($res))
			$this->printfail('参数有误');
		$data['qflag'] = 99;
		$this->db->edit($this->_params['qid'], $data);
		$this->msgbox('');
		die;
	}
	
	/**
	 * 后台模型：展示一条数据
	 */
	public function viewOneData($params) {
		$this->paramsFilter($params, 'del');
		$data = array();
		$this->db->init('quests', 'qid', 'task');
		$this->db->setCriteria();
		$this->db->criteria->setTables(jieqi_dbprefix('task_quests').' tq LEFT JOIN '.jieqi_dbprefix('article_article').' aa ON tq.aid=aa.articleid');
		$this->db->criteria->setFields('tq.*,aa.articlename');
		$this->db->criteria->add(new Criteria('tq.qid', $this->_params['qid'], '='));
		$res = $this->db->lists();
		if (empty($res))
			$this->printfail('参数有误');
		$data['qid'] 			= $res[0]['qid'];
		$data['aid'] 			= $res[0]['aid'];
		$data['question'] 		= $res[0]['question'];
		$data['rightoption'] 	= $res[0]['rightoption'];
		$data['point'] 			= $res[0]['point'];
		$data['createtime'] 		= $res[0]['createtime'];
		$data['qflag'] 			= $res[0]['qflag'];
		$data['articlename'] 	= $res[0]['articlename'];
		$temp = json_decode($res[0]['options'], true);
		foreach ($temp as $k => $v) {
			$data['options'][$k] = iconv('utf-8','gb2312',$v);
		}
		$this->msgbox('', $data);
	}
	
	/**
	 * 后台模型：根据AID获得文章的名称
	 */
	public function getArticleName($params) {
		if (intval($params['aid'])<=0)
			$this->printfail('参数错误');
		$this->db->init('article', 'articleid', 'article');
		$this->db->setCriteria(new Criteria('articleid', intval($params['aid']), '='));
		$res = $this->db->lists();
		if (empty($res))
			$this->printfail('没有找到任何书籍');
		$this->msgbox('', $res[0]['articlename']);
	}
	
	/**
	 * 后台模型：内部方法 - 表单数据过滤
	 * @param : $this_act - add-添加;edit-编辑;del-删除（仅验证qid）
	 */
	protected function paramsFilter($params, $this_act = 'add') {
//		$this->dump($params);
		if ($this_act != 'del') {
			$this->_params['aid'] = isset($params['aid']) ? intval($params['aid']) : $this->printfail('缺少参数');
			$this->_params['question'] = isset($params['question']) ? trim($params['question']) : $this->printfail('缺少参数');
			if (!isset($params['options'])) $this->printfail('缺少参数');
			$this->_params['rightoption'] = isset($params['rightoption']) ? intval($params['rightoption']) : $this->printfail('缺少参数');
			// 提示内容暂不处理
	//		$this->_params['point'] = isset($params['point']) ? intval($params['point']) : $this->printfail('缺少参数');
			// 验证规则
			if ($this->_params['aid'] <= 0)
				$this->pintfail('书籍ID输入有误');
			if ($this->_params['question']=='' || mb_strlen($this->_params['question'], 'gbk')>60)
				$this->printfail('问题字数必须大于0小于60个字');
			if ($this->_params['rightoption'] <= 0)
				$this->printfail('正确选项输入有误');
			$options_sum = 0;
			foreach ($params['options'] as $k =>$v) {
				if (trim($v) != '') {
					$this->_params['options'][$k] = iconv('gb2312','utf-8',$v);
					$options_sum++;
				}
			}
			if ($options_sum < 2)
				$this->printfail('至少需要有两个选项');
		}
		// 编辑方式针对qid单独验证
		if ($this_act == 'edit' || $this_act == 'del') {
			$this->_params['qid'] = isset($params['qid']) ? intval($params['qid']) : $this->printfail('缺少参数');
			if ($this->_params['qid'] <= 0)
				$this->printfail('参数错误');
		}
	}
	
	/**
	 * 前台方法：获得随机题目内容
	 * @param : $num - 返回题目数量
	 */
	public function getQuestions($params, $num = 5) {
		$data = array();
		$this->db->init('quests', 'qid', 'task');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('qid', $this->_params['qid'], '='));
		$res = $this->db->lists();
		if (empty($res))
			$this->printfail('参数有误');
		$data['qid'] 			= $res[0]['qid'];
		$data['aid'] 			= $res[0]['aid'];
		$data['question'] 		= $res[0]['question'];
		$data['rightoption'] 	= $res[0]['rightoption'];
		$data['point'] 			= $res[0]['point'];
		$data['createtime'] 		= $res[0]['createtime'];
		$data['qflag'] 			= $res[0]['qflag'];
		$data['articlename'] 	= $res[0]['articlename'];
		$temp = json_decode($res[0]['options'], true);
		foreach ($temp as $k => $v) {
			$data['options'][$k] = iconv('utf-8','gb2312',$v);
		}
		return $data;
	}
	
	/**
	 * 获取随机题目
	 * 默认5题
	 * 需要有文章的aid字段（如果没有则全部随机)
	 */
	public function getRadomQuestion($aid, $num = 5) {
		$this->db->init('quests', 'qid', 'task');
		$this->db->setCriteria();
		$this->db->criteria->setTables(jieqi_dbprefix('task_quests').' tq LEFT JOIN '.jieqi_dbprefix('article_article').' aa ON tq.aid=aa.articleid');
		$this->db->criteria->setFields('tq.qid');
		$this->db->criteria->add(new Criteria('tq.aid', intval($aid), '='));
		$this->db->criteria->add(new Criteria('tq.qflag', 99, '<>'));
		$qid_arr = $this->db->lists();
		// 如果所需题目大于题库书目则使用所需题目数量
		$ques_num = count($qid_arr)>=$num ? $num : count($qid_arr);
		$qids = array();
		foreach ($qid_arr as $k => $v) {
			$qids[$k] = $v['qid'];
		}
		// 获得随机数组并进行sql语句重组
		$this_random = array_rand($qids, $ques_num);
		$where_str = '';
		// 如果获取1条则进行处理
		if ($ques_num==1) {
			$where_str = $this_random;
		} else {
			foreach ($this_random as $k => $v) {
				$where_str .= $qids[$v] . ',';
			}
			$where_str = rtrim($where_str, ',');
		}
		$this->db->init('quests', 'qid', 'task');
		$res = $this->db->execute('SELECT tq.*,aa.articlename FROM '.jieqi_dbprefix('task_quests').' tq LEFT JOIN '.jieqi_dbprefix('article_article').' aa ON tq.aid=aa.articleid WHERE tq.qid IN('.$where_str.')');
		// 处理结果集到一个数组
		$data = array();
		$tmp_data = array();
		while ($row = mysql_fetch_array($res)) {
			$tmp_data[] = $row;
		}
		foreach ($tmp_data as $k => $v) {
			$data[$k]['qid'] = $v['qid'];
			$data[$k]['aid'] = $v['aid'];
			$data[$k]['question'] = $v['question'];
			$data[$k]['rightoption'] = $v['rightoption'];
			$data[$k]['options'] = iconv('gb2312', 'utf-8', $v['options']);
		}
//		$this->dump($data);
//		return $data;
		$this->msgbox('', $data);
	}
}















	