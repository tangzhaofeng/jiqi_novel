<?php
/**
 * 操作标签分类的模型
 * @author liuxiangbin
 * @create 2045-03-24 13:40:24
 */
class positiontypeModel extends Model {
	
	/**
	 * 获取全部数据，含搜索方法
	 */
	public function getAllData($params, $pagenum=20) {
		// 初始化参数和数据库模型
		$data = array();
		$thisController = ($_REQUEST['controller']) ? $_REQUEST['controller'] : 'taskController';
		$thisPage = isset($params['page']) ? intval($params['page']) : 1;
		$this->db->init('positiontype', 'id', 'system');
		$this->db->setCriteria();
		// 加查询条件
		$this->db->criteria->add(new Criteria('flag', 1, '='));
		if (isset($params['name']) && ''!=htmlspecialchars(trim($params['name'])))
			$this->db->criteria->add(new Criteria('name', '%'.htmlspecialchars(trim($params['name'])).'%', 'LIKE'));
		// 获取数据并返回
		$data['lists'] = $this->db->lists($pagenum, $thisPage, JIEQI_PAGE_TAG);
		$data['url_jumppage'] = $this->db->getPage($this->getAdminurl('positiontype'));
		return $data;
	}
	
	/**
	 * 返回一条分类记录
	 */
	public function getOne($params) {
		if (!isset($params['ptid'])) $this->printfail('缺少参数');
		$this->db->init('positiontype', 'id', 'system');
		$this->db->setCriteria(new Criteria('id', intval($params['ptid']), '='));
		$this->db->criteria->add(new Criteria('flag', 1, '='));
		$result = $this->db->lists();
		if (empty($result)) $this->printfail('不存在的页面');
		return $result[0];
	}
	
	/**
	 * 设置一条记录，默认为update
	 * @param $mod-add为新增
	 */
	public function setData($params, $mod = 'edit') {
		// 表单验证
		if ('edit'===$mod && !isset($params['ptid'])) $this->printfail('缺少参数');
		$setName = htmlspecialchars(trim($params['name']));
		if (strlen($setName)<=0 || strlen($setName)>40) $this->printfail('名称长度必须在2~20个中文之间');
		$setModule = htmlspecialchars(trim($params['module']));
		if (!preg_match('/^[a-zA-z1-9]{1,20}$/', $setModule)) $this->printfail('模块只能使用英文切必须在1~20个字母之间');
		$setDescription = htmlspecialchars(trim($params['description']));
		if (strlen($setDescription)>160) $this->printfail('描述文字不能超过80个字');
		// 重组写入数据
		$setData = array(
			'name'			=> $setName,
			'module'		=> $setModule,
			'description'	=> $setDescription
		);
		// 更新数据库数据
		$this->db->init('positiontype', 'id', 'system');
		if ('add'===$mod) {
			$setData['createtime'] = JIEQI_NOW_TIME;
			$this->db->add($setData);
		} else if ('edit'===$mod) {
			$this->db->edit(intval($params['ptid']), $setData);
		} else {
			$this->printfail('不存在的setData方式');
		}
		jieqi_jumppage($this->getAdminurl('positiontype'));
		die;
	}
	
	/**
	 * 删除一条记录，默认为标记删除
	 * @param $mod-del为真实删除
	 */
	public function delData($params, $mod = 'mark') {
		// 参数及使用验证
		if (!isset($params['ptid'])) $this->printfail('缺少参数');
		$this->db->init('position', 'postid', 'system');
		$this->db->setCriteria(new Criteria('ptypeid', intval($params['ptid']), '='));
		$result = $this->db->lists();
		if (!empty($result)) $this->printfail('请先删除所有属于此模块的标签');
		// 进行标记或删除
		$this->db->init('positiontype', 'id', 'system');
		if ('delete'!==$mod) {
			$this->db->edit(intval($params['ptid']), array('flag'=>2));
		} else {
			$this->db->delete(intval($params['ptid']));
		}
		jieqi_jumppage($this->getAdminurl('positiontype'));
		die;
	}
	
	/**
	 * 获得分类列表
	 * @return 一维数组：array(id=>module)
	 */
	public function getSort() {
		$this->db->init('positiontype', 'id', 'system');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('flag', 1, '='));
		$this->db->criteria->setFields('id,name,module');
		$result = $this->db->lists();
		// 重组数据：id为下标的数组
		$data = array();
		foreach ($result as $k=>$v) {
			$data[$v['id']]['name'] = $v['name'];
			$data[$v['id']]['module'] = $v['module'];
		}
		return $data;
	}
}
 
 
 
 
 
 
