<?php
/**
 * 包月书包模型：书包信息控制模型
 * @auther by: liuxiangbin
 * @createtime : 2014-12-10
 */

class bookpackageModel extends Model {
    
    /**
     * 获取包月书包导航页列表的方法
     * @param type $params
     * @param type $power - true验证权限（目前暂不需要）
     */
    public function getBookpackageList($params = array(), $isAdmin = false) {
        // 查询书包列表
        $data = array();
        $this->db->init('bookpackage', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->setFields('*');
        // 搜索设置
        $this->db->criteria->add(new Criteria('putaway', 1, '='));
        if ($isAdmin) {
            if (isset($params['showbookpackage']) && in_array(intval($params['showbookpackage']), array(1, 2))) {
                $this->db->criteria->add(new Criteria('showbookpackage', $params['showbookpackage'], '='));
            }
        } else {
            $this->db->criteria->add(new Criteria('showbookpackage', 1, '='));
        }
        $data['putaway'] = $params['putaway'];
        if (isset($params['keyword']) && isset($params['searchkey'])) {
            if ($params['searchkey'] == 'name') {
                $this->db->criteria->add(new Criteria('name', '%'.trim($params['keyword']).'%', 'LIKE'));
            }
            if ($params['searchkey'] == 'articleid') {
                $this->db->criteria->add(new Criteria('books', '%articleid":"'.trim($params['keyword']).'",%', 'LIKE'));
            }
            $data['keyword'] = $params['keyword'];
            $data['searchkey'] = $params['searchkey'];
        }
        if ($isAdmin) {
            if (isset($params['siteid']) && intval($params['siteid']>=0)) {
                $this->db->criteria->add(new Criteria('siteid', $params['siteid'], '='));
                $data['siteid'] = $params['siteid'];
            }
        }
        // 前端频道信息（目前包含男频和女频）
        if ($isAdmin === 0 || $isAdmin === 100) {
        	$this->db->criteria->add(new Criteria('siteid', $isAdmin, '='));
        }
        $thisPage = isset($params['page']) ? $params['page'] : 1;
        $this->db->criteria->setSort('createtime');
        $this->db->criteria->setOrder('DESC');
        $bpcount = $this->db->getCount($this->db->criteria);
        // 前后台分页不同
        if (!$isAdmin) {
            $pagenum = 6;
		} else if($isAdmin === 'wap') {
			$pagenum = 5;
        } else {
            // 引入配置项的分页配置
            $this->addConfig('article','configs');
            $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
            $pagenum = $jieqiConfigs['article']['pagenum'];
        }
        $this->db->criteria->setLimit($pagenum);
        $this->db->criteria->setStart(($thisPage-1)*$pagenum);
        $data['bplist'] = $this->db->lists($pagenum, $thisPage, JIEQI_PAGE_TAG);
        // 前台书包增加封面地址封装
        if (!$isAdmin) {
            // 重组封面，仅前台需要重组
            $this->addConfig('article', 'configs');
            $configs = $this->getConfig('article', 'configs');
            $showPath = jieqi_uploadurl($configs['bpimagedir']) . '/';
            foreach ($data['bplist'] as $k => $v) {
                $data['bplist'][$k]['coverimg'] = $showPath  . $v['id'] . '.jpg';
            }
        }
        foreach ($data['bplist'] as $k => $v) {
            $data['bplist'][$k]['initinalprice'] += $this->_get_bpinitinalprice($v['books']);
            $data['bplist'][$k]['totalsize'] += $this->_onebp_fields($v['books'], 'size');
        }
        // 获取当前控制器方法名
        $thisControler = $_REQUEST['controller']==''?'bookpackage':$_REQUEST['controller'];
        $data['url_jumppage'] = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME, $thisController,'evalpage=0','SYS=method=main'));
        $data['bpcount'] = $bpcount;
		$data['bppage'] = ceil($data['bpcount']/$pagenum);
        return $data;
    }
    
    /**
     * 前台方法：获得某个频道的数据
     * @param type $params
     * @param type $site : 非负
     * @return type
     */
    public function siteBookpackageList($params = array(), $site = 0) {
        $data = array();
        $this->db->init('bookpackage', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('siteid', $site, '='));
        $this->db->criteria->setFields('*');
        $this->db->criteria->add(new Criteria('showbookpackage', 1, '='));
        $this->db->criteria->add(new Criteria('putaway', 1, '='));
        $this->addConfig('article','configs');
        // 配置中读取显示页数
        $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
        $thisPage = isset($params['page']) ? $params['page'] : 1;
        $pagenum = 6;
        $this->db->criteria->setLimit($pagenum);
        $this->db->criteria->setStart(($thisPage-1)*$pagenum);
        $this->db->criteria->setSort('createtime');
        $this->db->criteria->setOrder('DESC');
		$data['bpcount'] = $this->db->getCount($this->db->criteria);
        $data['lists'] = $this->db->lists($pagenum, $thisPage, JIEQI_PAGE_TAG);
		$data['bppage'] = ceil($data['bpcount']/$pagenum);
        // 重组封面，仅前台需要重组
        $this->addConfig('article', 'configs');
        $configs = $this->getConfig('article', 'configs');
        $showPath = jieqi_uploadurl($configs['bpimagedir']) . '/';
        foreach ($data['lists'] as $k => $v) {
           $data['lists'][$k]['initinalprice'] += $this->_get_bpinitinalprice($v['books']);
           $data['lists'][$k]['totalsize'] += $this->_onebp_fields($v['books'], 'size');
           $data['lists'][$k]['coverimg'] = $showPath  . $v['id'] . '.jpg';
        }
        $data['url_jumppage'] = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME,'bookpackage','evalpage=0','SYS=method=main&siteis='.$site));
        return $data;
    }
    
    /**
     * 书包专区专用合并书包总字数方法
     * @param type $booksparams
     */
    private function _onebp_fields($booksparams, $fields) {
        $returnSum = 0;
        $books = array();
        if (is_array($booksparams)) {
            $books = $booksparams;
        } else {
            $books = json_decode($booksparams, true);
        }
        foreach ($books as $k => $v) {
            $this->db->init('chapter', 'chapterid', 'article');
            $this->db->setCriteria();
            $this->db->criteria->add(new Criteria('articleid', $v['articleid'], '='));
            $returnSum += $this->db->getSum($fields);
        }
        return ($fields=='size')?$this->_to_bigsize(ceil($returnSum/2)):$returnSum;
    }
    
    /**
     * 推荐书包获取方法
     * @param type $params
     */
    public function commendsBookpackageList($params = array(), $addLess = false, $isWap = false) {
        $data = array();
        $this->db->init('bookpackage', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('commends', 0, '>'));
        $this->db->criteria->add(new Criteria('showbookpackage', 1, '='));
        $this->db->criteria->add(new Criteria('putaway', 1, '='));
        $this->db->criteria->setFields('id,name,price,pricetype,books,description,booknumber');
        $this->db->criteria->setSort('commends');
        $data = $this->db->lists();
        // 重组封面，仅前台需要重组
        $this->addConfig('article', 'configs');
        $configs = $this->getConfig('article', 'configs');
        $showPath = jieqi_uploadurl($configs['bpimagedir']) . '/';
		// 替换3GWAP地址
		if ($isWap) 
			$showPath = str_replace('3gwap/', 'article/', $showPath);
		if (count($data)!=0) {
			foreach ($data as $k => $v) {
	           $data[$k]['initinalprice'] = $this->_onebp_fields($v['books'], 'saleprice');
	           $data[$k]['coverimg'] = $showPath  . $v['id'] . '.jpg';
	        }
		}
        // 补全每组四个内容
        $lessNum = 4 - count($data)%4;
        if ($addLess) {
        	if (count($data)==0 || $lessNum != 4) {
        		for ($i=1;$i<=$lessNum;$i++) {
	                $data[] = array(
	                    'id'                => 0,
	                    'name'              => '敬请期待',
	                    'price'             => ' ',
	                    'coverimg'          => JIEQI_URL . '/images/bookpackagedefault.jpg',
	                    'initinalprice'     => ' '
	                );
	            }
        	}
        }
		$data[0]['comcounts'] = count($data);
        return $data;
    }
    
    /**
     * 添加一个书包
     * @param type $params
     */
    public function add_new_bp($params) {
//      $auth = $this->getAuth();
        // 仅主编和系统管理员可对书包进行操作
//      if (!in_array($auth['groupid'], array(2, 9, 10)))
//              return false;
        // 表单验证
        if ($_FILES['size']>2048)
            return false;
        if ($params['name']=='' || mb_strlen($params['name'], 'GBK')>20)
                return false;
        if ($params['description']=='' || mb_strlen($params['description'], 'GBK')>120)
                return false;
        $books = explode(',', rtrim($params['booklists'], ','));
        if (empty($books) || count($books)!=intval($params['booknumber']))
                return false;
        $timeNow = time();
        $bookstr = array();
        foreach ($books as $k => $v) {
            // 验证书包内书籍是否已存在销售中
            $this->_check_book_exist($v);
            $bookstr[$k]['articleid'] = $v;
            $bookstr[$k]['create_time'] = $timeNow;
        }
        // 组合写入数据库数组
        $addData = array(
            'name'              => $params['name'],
            'description'       => $params['description'],
            'booknumber'        => $params['booknumber'],
            'price'             => intval($params['price']),
            'pricetype'         => 1,
            'siteid'            => intval($params['siteid']),
            'books'             => json_encode($bookstr),
            'createtime'        => $timeNow,
            'updatetime'        => $timeNow-(30*24*3600),
            'obpid'             => 0,
            'showbookpackage'   => intval($params['showbookpackage']),
            'putaway'           => 1,
            'commends'          => intval($params['commends'])
        );
        $this->db->init('bookpackage', 'id', 'article');
        $bpidVal = $this->db->add($addData);
        if (!$bpidVal)
            return false;
        // 图片处理
        $res = $this->_upload_img($bpidVal, $_FILES);
        if (!$res['status']) {
            // 图片上传失败时的回滚操作
            $this->db->init('bookpackage', 'id', 'article');
            $this->db->delete($bpidVal);
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * 修改一个书包内容
     * @param type $params
     * @return boolean
     */
    public function update_one_bp($params) {
//      $auth = $this->getAuth();
        // 仅主编和系统管理员可对书包进行操作
//      if (!in_array($auth['groupid'], array(2, 10)))
//              return false;
        // 表单验证
        if ($_FILES['size']>2048)
            return false;
        if ($params['name']=='' || mb_strlen($params['name'], 'GBK')>20)
                return false;
        if ($params['description']=='' || mb_strlen($params['description'], 'GBK')>120)
                return false;
        if ($_FILES['faceimg']['error'] == 0) {
            $res = $this->_upload_img($params['id'], $_FILES);
            if (!$res['status']) {
                return false;
            }
        }
        // 组合写入数据库数组
        $updateData = array(
            'name'              => $params['name'],
            'description'       => $params['description'],
            'siteid'            => intval($params['siteid']),
            'obpid'             => 0,
            'showbookpackage'   => intval($params['showbookpackage']),
            'commends'          => intval($params['commends'])
        );
        $this->db->init('bookpackage', 'id', 'article');
        $this->db->setCriteria(new Criteria('id', $params['id'], '='));
        $this->db->criteria->setFields('showbookpackage');
        $res = $this->db->lists();
        if ($params['showbookpackage']==2 && $res[0]['showbookpackage']==1) {
            $updateData['updatetime'] = time();
        }
        $result = $this->db->edit($params['id'], $updateData);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 获取包月书包详情的方法
     * @param type $params
     */
    public function getBookpackageInfo($params = array(), $isAdmin = false, $isWap = false) {
        if (!isset($params['bpid']) || intval($params['bpid'])==0)
            $this->jumppage($this->getAdminurl().'&method=main', LANG_DO_FAILURE, '网络不给力，请重试:(');
        $data = array();
        $this->db->init('bookpackage', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('id', $params['bpid'], '='));
        if (!$isAdmin) {
            $this->db->criteria->add(new Criteria('showbookpackage', 1, '='));
        }
        $this->db->criteria->add(new Criteria('putaway', 1, '='));
        if ($isAdmin) {
            $this->db->criteria->setFields('*');
        } else {
           $this->db->criteria->setFields('id,name,description,booknumber,price,pricetype,siteid,books,updatetime,obpid,coverimg'); 
        }
        $this->db->criteria->setSort('updatetime');
        $this->db->criteria->setOrder('DESC');
        $tempData = $this->db->lists();
        // 重组封面，仅前台需要重组
        $this->addConfig('article', 'configs');
        $configs = $this->getConfig('article', 'configs');
        $showPath = jieqi_uploadurl($configs['bpimagedir']) . '/';
		// 替换3GWAP地址
		if ($isWap) 
			$showPath = str_replace('3gwap/', 'article/', $showPath);
		if (!$isAdmin) 
            $tempData[0]['coverimg'] = $showPath  . $params['bpid'] . '.jpg';
		$showData = array();
        $showData = $tempData;
		$init_price = 0;
        // 重组书包内包含文章详情
        foreach ($tempData as $k => $v) {
            $tempval = array();
            $tempval = json_decode($v['books'], true);
            foreach ($tempval as $key => $val) {
                $showData[$k]['book'][$key] = $this->_get_article_info($val['articleid']);
                $showData[$k]['book'][$key]['create_time'] = $val['create_time'];
				$init_price += intval($showData[$k]['book'][$key]['saleprice']);
            }
        }
        // 计算书包合计内容
        $showData[0]['totalsize'] = $this->_onebp_fields($tempData[0]['books'], 'size');
		$showData[0]['initinalPrice'] = $init_price;
        $data['data'] = $showData;
        return $data['data'];
    }
    
    /**
     * 查询一个书包内书籍明细（需进行权限认证）
     * @param type $params
     */
    public function get_one_bpinfo($params, $isTiny = false) {
//      $auth = $this->getAuth();
        // 仅主编和系统管理员可对书包进行操作
//      if (!in_array($auth['groupid'], array(2, 10)))
//              return false;
        $this->db->init('bookpackage', 'id', 'article');
        $this->db->setCriteria(new Criteria('id', $params['id'], '='));
        $this->db->criteria->setFields('books');
        $res = $this->db->lists();
        if (!$res)
            return false;
        $result = json_decode($res[0]['books'], true);
//        $articleLib = $this->load('article', 'article');
        $this->addConfig('article', 'sort');
        $sourtes = $this->getConfig();
        $data = array();
        if ($isTiny) {
            foreach ($result as $k => $v) {
                $data[$k] = $this->_get_article_info($v['articleid']);
                $data[$k]['createtime'] = $v['create_time'];
            }
        } else {
            foreach ($result as $k => $v) {
                $data[$k] = $this->_get_article_info($v['articleid']);
                $data[$k]['sortname'] = $sourtes['article']['jieqiSort'][$data[$k]['sortid']]['shortname'];
                $data[$k]['size'] = $data[$k]['size'];
                $data[$k]['createtime'] = date('Y-m-d', $v['create_time']);
            }
        }
        return $data;
    }
    
    /**
     * 异步删除书包操作，num为真则删除多个书包记录
     * @param type $params
     * @param type $num
     */
    public function del_bpinfo($params, $num = false) {
//      $auth = $this->getAuth();
        // 仅主编和系统管理员可对书包进行操作
//      if (!in_array($auth['groupid'], array(2, 10))) {
//              return array('status'=>'300', 'msg'=>'非法请求');
//      }
        $this->db->init('bookpackage', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('id', intval($params['id']), '='));
        $res = $this->db->lists();
        if (empty($res)) {
            return array('status'=>'400', 'msg'=>'非法请求1');
        }
        // 在售书包不允许删除
        if ($res[0]['showbookpackage'] == 1) {
            return array('status'=>'300',  'msg'=>'在售书包不允许删除:(');
        }
        // 控制必须在隐藏销售30天后才可以下架（删除）书包记录
        $nowTime = time();
        if ($nowTime <= $res[0]['updatetime']+30*24*3600) {
            $value = ($res[0]['updatetime']+30*24*3600 - $nowTime)/(24*3600);
            return array('status'=>'300', 'msg'=>'【书包删除】操作需在该书包暂停销售30天进行:(<br/>当前书包'.  floor($value).'天后可以删除');
        }
        $result = $this->db->edit($params['id'], array('putaway'=>2));
        if ($result) {
            return array('status'=>'200');
        } else {
            return array('status'=>'400',  'msg'=>'非法请求2');
        }
    }
    
    /**
     * 异步查找需要添加的书籍列表，返回字段格式:$data['书籍id'] = '书籍name'
     * @param type $params
     */
    public function get_book_ids($params) {
//      $auth = $this->getAuth();
        // 仅主编和系统管理员可对书包进行操作
//      if (!in_array($auth['groupid'], array(2, 9, 10)))
//              return false;
        $this->db->init('article', 'articleid', 'article');
        $this->db->setCriteria();
        $this->db->criteria->setTables(jieqi_dbprefix('article_article').' aa LEFT JOIN '.jieqi_dbprefix('article_chapter').' ac ON aa.articleid=ac.articleid');
        $this->db->criteria->setFields('aa.articleid,aa.articlename');
        if ($params['keytype'] == '1') {
            $wheres = new Criteria('ac.articlename', '%'.$params['keywords'].'%', 'LIKE');
        } else {
            $wheres = new Criteria('ac.articleid', intval($params['keywords']), '=');
        }
        $this->db->criteria->add($wheres);
        // 完本开关、vip章节、显示章节
        $this->db->criteria->add(new Criteria('aa.fullflag', 1, '='));
        $this->db->criteria->add(new Criteria('ac.isvip', 1, '='));
        $this->db->criteria->add(new Criteria('ac.display', 0, '='));
        $this->db->criteria->setGroupby('aa.articleid');
        $result = $this->db->lists();
        if (empty($result)) {
            $this->printfail('');
        } else {
            $reData = array();
            foreach ($result as $k => $v) {
                $reData[$v['articleid']] = $v['articlename'];
            }
            $this->msgbox('', $reData);
        }
//        return $reData;
    }
    
    // 【内部封装方法】
    /**
     * 获得单独文章信息的方法
     * @param type $aritcleid
     * @return type
     */
    private function _get_article_info($aritcleid) {
        $data = array();
        $tmep = '';
        $this->db->init('article', 'articleid', 'article');
        $this->db->setCriteria();
        $this->db->criteria->setTables(jieqi_dbprefix('article_article').' aa LEFT JOIN '.jieqi_dbprefix('article_chapter').' ac ON aa.articleid=ac.articleid');
        $this->db->criteria->add(new Criteria('aa.articleid', $aritcleid, '='));
        $this->db->criteria->add(new Criteria('ac.display', 0, '='));
        $this->db->criteria->add(new Criteria('ac.isvip', 1, '='));
        $this->db->criteria->setFields('aa.articleid,aa.articlename,aa.author,aa.sortid,aa.intro,SUM(ac.saleprice) as saleprice,aa.size as size,aa.imgflag');
        $this->db->criteria->setGroupby('aa.articleid');
        $data = $this->db->lists();
        $temp = $this->_to_bigsize($data[0]['size']);
		$data[0]['coverimg'] = jieqi_geturl('article', 'cover', $data[0]['articleid'], 's', $data[0]['imgflag']);
        $data[0]['size'] = $temp;
        return $data[0];
    }
    
    /**
     * 获得书包的合计售价
     * @param type $bpid
     */
    private function _get_bpinitinalprice($books) {
        $data = '';
        if (!is_array($books)) {
            $tempData = json_decode($books, true);
        } else {
            $tempData[0]['articleid'] = $books;
        }
        foreach ($tempData as $k => $v) {
            $this->db->init('chapter', 'chapterid', 'article');
            $this->db->setCriteria();
            $this->db->criteria->add(new Criteria('articleid', $v['articleid'], '='));
            $this->db->criteria->add(new Criteria('isvip', 1, '='));
            $this->db->criteria->add(new Criteria('display', 0, '='));
            $data += $this->db->getSum('saleprice');
        }
        return $data;
    }
    
    /**
     * 获得书包的总字数
     * @param type $books
     * @return type
     */
    private function _get_bptotalsize($books) {
        $data = '';
        if (!is_array($books)) {
            $tempData = json_decode($books, true);
        } else {
            $tempData[0]['articleid'] = $books;
        }
		$this->db->init('chapter', 'chapterid', 'article');
        $this->db->setCriteria(new Criteria('articleid', $v['articleid'], '='));
        foreach ($tempData as $k => $v) {
            $tmpSum = $this->db->getSum('size');
            $data += intval($tmpSum);
        }
        return $data;
    }
    
    /**
     * 修改字数的函数
     * @param type $str
     */
    private function _to_bigsize($str) {
        $num = intval($str)/20000;
        $returnStr = '';
        if ($num > 1) {
            $returnStr = sprintf('%.2f', $num) . '万字';
        } else {
            $returnStr = $str . '字';
        }
        return $returnStr;
    }
    
    /**
     * 图片上传处理函数
     * @param type $id
     * @param type $files
     */
    private function _upload_img($id, $files) {
        $this->addConfig('article', 'configs');
        $configs = $this->getConfig('article', 'configs');
        $from = $files['faceimg']['tmp_name'];
        $to_dir = jieqi_uploadpath($configs['bpimagedir']);
        $to = $to_dir . '/' . $id . '.jpg';
        // 检查目录是否存在
        jieqi_checkdir($to_dir);
        // 如果文件已存在则删除
        if (is_file($to)) {
            jieqi_delfile($to);
        }
        $result = jieqi_copyfile($from, $to);
        if ($result) {
            return array('status'=>true, 'imgurl'=>$to);
        } else {
            return array('status'=>false);
        }
    }
    
    /**
     * 判断书籍是否已经添加到在售书包的公共方法
     * @param type $bookid
     */
    public function _check_book_exist($bookid) {
//        echo 111;die;
        $count = '';
        $this->db->init('bookpackage', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('books', '%"articleid":"'.$bookid.'",%', 'LIKE'));
        $count = $this->db->getCount();
        if ($count > 0) {
            return array('status'=>400,'msg'=>'编号为：'.$bookid.'的书籍已存在在售或停售的书包中，请重新设置书包内容:(');
        } else {
            return array('status'=>200);
        }
    }
}