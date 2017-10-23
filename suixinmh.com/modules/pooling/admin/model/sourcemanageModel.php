<?php
/**
 * 充值分成渠道管理
 * @author chengyuan  2014-6-12
 *
 */
class sourcemanageModel extends Model{
		
	/**
	 * 添加一条记录
	 */
	public function setData($params, $setType = 'add'){
		// 如果为修改操作则对sid进行初始化
		if ($setType == 'edit') {
			if (!isset($params['sid']) || intval($params['sid'])==0) {
				$this->printfail('缺少参数');
			} else {
				$this_sid = intval($params['sid']);
			}
		}
		$setData = array();
		// 表单验证
		if (trim($params['sname'])=='' || mb_strlen($params['sname'], 'GBK')>25) 
			$this->printfail('提交信息错误1');
		if (trim($params['markname'])=='' || mb_strlen($params['markname'], 'GBK')>25) 
			$this->printfail('提交信息错误2');
		if (trim($params['name'])=='' || mb_strlen($params['name'], 'GBK')>25) 
			$this->printfail('提交信息错误3');
		if (intval($params['compos'])<0 || intval($params['compos'])>99) 
			$this->printfail('提交信息错误4');
		$setData = array(
			'sname'				=> trim($params['sname']),
			'markname'			=> trim($params['markname']),
			'name'				=> trim($params['name']),
			'compositor'			=> intval($params['compos']),
			'locked'				=> intval($params['locked'])
		);
		// 操作数据库(需要缓存操作)
		$cacheLib = $this->load('cachetable', 'system');
		$cacheLib->init('sources', 'sid', 'pooling', 'compositor');
		if ($setType == 'add') {
			$setData['password'] = $this->createCode();
			$setData['createtime'] = JIEQI_NOW_TIME;
			// 设置缓存
			$cacheLib->add($setData);
			$this->msgbox('');
			die;
		} elseif ($setType == 'edit') {
			$cacheLib->edit($this_sid, $setData);
			$this->msgbox('');
			die;
		} else {
			$this->printfail('模型参数错误');
		}
	}
	
	/**
	 * 获得一条记录
	 */
	public function getOneData($params){
		// 参数验证
		if (!isset($params['sid']) || intval($params['sid'])==0) {
			$this->printfail('缺少参数');
		} else {
			$this_sid = intval($params['sid']);
		}
		$this->db->init('sources', 'sid', 'pooling');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('sid', $this_sid, '='));
		$res = $this->db->lists();
		if (empty($res))
			$this->printfail('查无数据');
		return $res[0];
	}
	
	/**
	 * 删除一条记录
	 * @param			: Boo	默认假-locked设置，真-删除数据
	 */
	public function delData($params, $true_del = false){
		// 参数验证
		if (!isset($params['sid']) || intval($params['sid'])==0) {
			$this->printfail('缺少参数');
		} else {
			$this_sid = intval($params['sid']);
		}
		$cacheLib = $this->load('cachetable', 'system');
		$cacheLib->init('sources', 'sid', 'pooling', 'compositor');
		// 判断是否为删除数据库数据（建议使用标记删除）
		if ($true_del) {
			$cacheLib->delete($this_sid);
			$this->msgbox('');
			die;
		} else {
			$setData['locked'] = 99;
			$cacheLib->edit($this_sid, $setData);
			$this->msgbox('');
			die;
		}
		// 如果没有任何操作则提示失败
		$this->printfail('运行错误');
	}
	
	/**
	 * 获得数据列表
	 */
	public function getDataList($params){
		// 引入分页设置
		$this->addConfig('article','configs');
    		$jieqiConfigs['article'] = $this->getConfig('article', 'configs');
		$pagenum = $jieqiConfigs['article']['pagenum'];
		$thisPage = intval($params['page'])<=0 ? 1 : $params['page'];
		$this->db->init('sources', 'sid', 'pooling');
		$this->db->setCriteria();
		// 屏蔽标记删除的内容
		$this->db->criteria->add(new Criteria('locked', 99, '<>'));
		// 搜索条件
		if (isset($params['keyword']) && trim($params['keyword'])!='') {
			$this->db->criteria->add(new Criteria($params['searchkey'], '%'.$params['keyword'].'%', 'LIKE'));
		}
		$this->db->criteria->setSort('compositor');
//		$this->db->criteria->setOrder('ASC');
//		$this->db->criteria->setFields('sid,sname,markname,name,password,compositor,createtime');
		$thisController = ($_REQUEST['controller']) ? $_REQUEST['controller'] : 'sources';
		$data['lists'] = $this->db->lists($pagenum, $thisPage, JIEQI_PAGE_TAG);
		$data['url_jumppage'] = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME, $thisController,'evalpage=0','SYS=method='.$methodName));
		return $data;
	}
	
	/**
	 * 设置排序
	 */
	public function setOrder($params) {
		// 设置缓存
		$cacheLib = $this->load('cachetable', 'system');
		$cacheLib->init('sources', 'sid', 'pooling', 'compositor');
		foreach ($params as $k => $v) {
			if (preg_match('/^sid_/', $k)) {
				$this_key = array_pop(array_filter(explode('_', $k)));
				$this_val = (intval($v)>=0||intval($v)<=99) ? intval($v) : 0;
				$cacheLib->edit($this_key, array('compositor'=>$this_val));
			}
		}
		$this->msgbox('');
	}
	
	/**
	 * 生成随机码
	 * @param			: String 获得几位的登陆码
	 */
	protected function createCode($codeNumber = 8) {
		$chars = array(
            'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',
            'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',
            '/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',
            'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',
            '?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',
            '=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',
            'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*'
        );
        $strlen = count($chars) - 1;
        $tmpstr = '';
        for ($i = 0; $i < 32; $i++) {
            $tmpstr .= $chars[mt_rand(0, $strlen)];
            $tmpstr .= $tokenstr;
        }
        $tmpstr .= sha1(microtime());
        $tmpstr = md5($tmpstr . time());
        $strnumber = strlen($tmpstr);
        $randnum = mt_rand(0, $strnumber-$codeNumber);
        $code = substr($tmpstr, $randnum, $codeNumber);
        return $code;
	}
}
?>
	