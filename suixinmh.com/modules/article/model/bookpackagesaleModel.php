<?php
/**
 * 包月书包购买模型：书包购买信息控制模型
 * @auther by: liuxiangbin
 * @createtime : 2014-12-10
 */

class bookpackagesaleModel extends Model {
    
    /**
     * 获得【我的包月书包】的方法
     * @param type $params
     */
    public function getMyBookpackage($params = array()) {
        $auth = $this->getAuth();
        if (!isset($auth['uid']) || $auth['uid']<1) {
            $this->printfail('请先登录', '', geturl('system', 'login'));
        }
        $users_handler =  $this->getUserObject();
        $users = $users_handler->get($auth['uid']);
        $user = array(
            'uid'               => $users->getVar('uid'),
            'egold'             => $users->getVar('egold'),
            'esilver'           => $users->getVar('esilver')
        );
        // TODO::获得我的包月书包
        $thisPage = isset($params['page']) ? $params['page'] : 1;
        $this->db->init('bookpackagesale', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->setTables(jieqi_dbprefix('article_bookpackagesale').' sa LEFT JOIN '.jieqi_dbprefix('article_bookpackage').' a ON sa.bpid=a.id');
        $this->db->criteria->add(new Criteria('sa.accountid', $user['uid'], '='));
        // 过期记录查询
        if (isset($params['isover']) && intval($params['isover'])==1) {
            $this->db->criteria->add(new Criteria('sa.endtime', JIEQI_NOW_TIME, '<'));
        } else {
            $this->db->criteria->add(new Criteria('sa.endtime', JIEQI_NOW_TIME, '>='));
        }
        $this->db->criteria->setFields("sa.bpid,sa.buy_time,sa.endtime,sa.saleprice,a.name,a.booknumber,sa.bookid");
        // 引入配置项的分页配置
        $this->addConfig('article','configs');
        $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
        $this->db->criteria->setLimit($jieqiConfigs['article']['pagenum']);
        $thisPage = isset($params['page']) ? $params['page'] : 1;
        $this->db->criteria->setStart(($thisPage-1)*$jieqiConfigs['article']['pagenum']);
        $this->db->criteria->setSort('sa.buy_time');
        $this->db->criteria->setOrder('DESC');
        $temp = $this->db->lists($jieqiConfigs['article']['pagenum'], $thisPage, JIEQI_PAGE_TAG);
        // 处理数据用户view层显示
        foreach ($temp as $k => $v) {
            $temp[$k]['book'] = $this->_show_user_books($v['bookid']);
            unset($temp[$k]['bookid']);
        }
        $data['user'] = $user;
        $data['salelist'] = $temp;
        $data['url_jumppage'] = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME,JIEQI_CONTROLLER_NAME,'evalpage=0','SYS=method=main'));
        return $data;
    }

    /**
     * 获取单独书包内容的私有方法
     * @param type $params
     */
    private function _show_user_books($books) {
        // TODO::获得用户读书记录和书包内容
        $data = array();
        if (is_array($books)) {
            $book = $books;
        } else {
            $book = json_decode($books, true);
        }
        $auth = $this->getAuth();
        $uid = $auth['uid'];
//        $this->dump($book, 0);
//        $this->dump($uid);
        foreach ($book as $k => $v) {
            $this->db->init('article','articleid', 'article');
            $this->db->setCriteria();
            $this->db->criteria->setFields('articlename,sortid,author');
            $this->db->criteria->add(new Criteria('articleid', $v['articleid'], '='));
            $article = $this->db->get($this->db->criteria);
            $data[$k]['id'] = $v['articleid'];
            $data[$k]['articlename'] = $article->getVar('articlename', 'n');
            $data[$k]['sortid'] = $article->getVar('sortid', 'n');
            $data[$k]['author'] = $article->getVar('author', 'n');
            $data[$k]['create_time'] = date('Y-m-d', $v['create_time']);
            // 这里添加cookie中的阅读记录
            
        }
        return $data;
    }

    /**
     * 管理员获取数据列表
     * @param type $params
     */
    public function getAllBp($params = array()) {
        // TODO::获取所有书包，需要进行管理员权限验证
        $auth = $this->getAuth();
        // 仅主编和系统管理员可对书包进行操作
        if (!in_array($auth['groupid'], array(2, 10)))
            return false;
        $thisPage = isset($params['page']) ? $params['page'] : 1;
        $this->db->init('bookpackagesale', 'id', 'article');
        $this->db->setCriteria();
        // 搜索条件
        if (isset($params['keyword']) && isset($params['searchkey']) && trim($params['keyword'])!='') {
            if (trim($params['searchkey']) == 'bpname') {
                $this->db->criteria->add(new Criteria('bpname', '%'.trim($params['keyword']).'%', 'LIKE'));
            } elseif (trim($params['searchkey']) == 'account') {
                $this->db->criteria->add(new Criteria('account', '%'.trim($params['keyword']).'%', 'LIKE'));
            } elseif (trim($params['searchkey']) == 'articleid') {
                $this->db->criteria->add(new Criteria('bookid', '%articleid":"'.trim($params['keyword']).'",%', 'LIKE'));
            }
        }
        if (isset($params['start']) && $params['start']!='') {
            $this->db->criteria->add(new Criteria('buy_time', strtotime($params['start']), '>='));
        }
        if (isset($params['end']) && $params['end']!='') {
            $this->db->criteria->add(new Criteria('buy_time', strtotime($params['end']), '<='));
        }
        if (isset($params['overtime']) && intval($params['overtime'])!=0) {
//            $this->dump($params['overtime']);
            if ($params['overtime'] == 1) {
//                $this->dump($params);
                $this->db->criteria->add(new Criteria('endtime', JIEQI_NOW_TIME, '>'));
            } elseif ($params['overtime'] == 2) {
                $this->db->criteria->add(new Criteria('endtime', JIEQI_NOW_TIME, '<='));
            }
        }
        // 引入配置项的分页配置
        $this->addConfig('article','configs');
        $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
        $this->db->criteria->setLimit($jieqiConfigs['article']['pagenum']);
        $thisPage = isset($params['page']) ? $params['page'] : 1;
        $this->db->criteria->setStart(($thisPage-1)*$jieqiConfigs['article']['pagenum']);
        $this->db->criteria->setSort('buy_time');
        $this->db->criteria->setOrder('DESC');
        $bpcount = $this->db->getCount($this->db->criteria);
        // 计算销售增额
        $saleSum = $this->db->getSum('saleprice');
        $temp = $this->db->lists($jieqiConfigs['article']['pagenum'], $thisPage, JIEQI_PAGE_TAG);
        // 处理数据用户view层显示
        $tempData = $temp;
//        $this->dump($temp);
        foreach ($temp as $k => $v) {
            $tempData[$k]['number'] = $k + 1;
            $tempData[$k]['days'] = date('Y-m-d', $v['buy_time']);
            $tempData[$k]['times'] = date('H:i:s', $v['buy_time']);
            $tempData[$k]['end_time'] = date('Y-m-d H:i:s', $v['endtime']);
            $tempData[$k]['isend'] = (JIEQI_NOW_TIME<=$v['endtime'])?'1':'2';
        }
        // 添加分页类
        include_once(HLM_ROOT_PATH.'/lib/html/page.php');
        $jumppage = new JieqiPage($bpcount,$jieqiConfigs['article']['pagenum'],$thisPage);
        $jumppage->setlink('', true, true);
        $data['salelist'] = $tempData;
        $data['url_jumppage'] = $jumppage->whole_bar();
        $data['count'] = $bpcount;
        $data['totalprice'] = $saleSum;
//        $this->dump($params, 0);
//        $this->dump($data);
        return $data;
    }
    
    /**
     * 获取所有阅读记录的方法
     * @param type $params
     */
    public function get_all_click ($params) {
        $auth = $this->getAuth();
//        $this->dump($auth);
        // 仅主编和系统管理员可对书包进行操作
        if (!in_array($auth['groupid'], array(2, 10)))
            return false;
        $data = array();
        $this->db->init('bookpackagestat', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->setTables(jieqi_dbprefix('article_bookpackagestat') . ' s LEFT JOIN ' . jieqi_dbprefix('article_bookpackage') . ' a ON s.bpid=a.id LEFT JOIN ' . jieqi_dbprefix('article_bookpackagesale') . ' sa ON s.bpsaleid=sa.id LEFT JOIN ' . jieqi_dbprefix('article_article') . ' ar ON s.articleid=ar.articleid');
        $this->db->criteria->setFields('s.bpsaleid as saleid,s.bpid as bpid,a.name as bpname,s.articleid as articleid,ar.articlename as bookname,ar.author as authorname,count(chapterid) as clicks,a.price as price,a.pricetype as pricetype,sa.endtime as end_time');
        $this->db->criteria->setGroupby('s.bpsaleid,s.bpid,s.articleid');
        // 搜索条件：
        $this->db->criteria->add(new Criteria('a.putaway', 1, '='));
        if (isset($params['keyword']) && isset($params['searchkey']) && trim($params['keyword'])!='') {
            if (trim($params['searchkey']) == 'bpname') {
                $this->db->criteria->add(new Criteria('a.name', '%'.trim($params['keyword']).'%', 'LIKE'));
            } elseif (trim($params['searchkey']) == 'bookname') {
                $this->db->criteria->add(new Criteria('ar.articlename', '%'.trim($params['keyword']).'%', 'LIKE'));
            } elseif (trim($params['searchkey']) == 'authorname') {
                $this->db->criteria->add(new Criteria('ar.author', '%'.trim($params['keyword']).'%', 'LIKE'));
            }
        }
        if (isset($params['start']) && trim($params['start'])!='') {
            $this->db->criteria->add(new Criteria('sa.buy_time', strtotime($params['start']), '>='));
        }
        if (isset($params['end']) && trim($params['end'])!='') {
            $this->db->criteria->add(new Criteria('sa.buy_time', strtotime($params['end']), '<='));
        }
        if (isset($params['overtime']) && intval($params['overtime'])!=0) {
            if ($params['overtime'] == 1) {
                $this->db->criteria->add(new Criteria('sa.endtime', JIEQI_NOW_TIME, '>'));
            } elseif ($params['overtime'] == 2) {
                $this->db->criteria->add(new Criteria('sa.endtime', JIEQI_NOW_TIME, '<='));
            }
        }
        // 引入配置项的分页配置
        $this->addConfig('article','configs');
        $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
        $this->db->criteria->setLimit($jieqiConfigs['article']['pagenum']);
        $thisPage = isset($params['page']) ? $params['page'] : 1;
        $this->db->criteria->setStart(($thisPage-1)*$jieqiConfigs['article']['pagenum']);
        $this->db->criteria->setSort('sa.endtime');
        $this->db->criteria->setOrder('DESC');
        // QUESTION::三个字段分组后查询总数的方法
        $count = $this->db->getCount($this->db->criteria->setGroupby('s.articleid'));
//        $this->dump($count);
        $tempData = $this->db->lists();
//        echo 111;
//        $this->dump($tempData);
        // 添加分页类
        include_once(HLM_ROOT_PATH.'/lib/html/page.php');
        $jumppage = new JieqiPage($count,$jieqiConfigs['article']['pagenum'],$thisPage);
        $jumppage->setlink('', true, true);
        $data['url_jumppage'] = $jumppage->whole_bar();
        $data['count'] =$count;
        $data['salelist'] = $tempData;
        return $data;
    }
    
    /**
     * 异步返回单独书包阅读量详情的方法
     * @param type $params
     */
    public function get_one_clicks($params) {
//        $this->dump($params, 0);
        $auth = $this->getAuth();
        // 仅主编和系统管理员可对书包进行操作
        if (!in_array($auth['groupid'], array(2, 10)))
            return false;
        if (!isset($params['saleid']))
            return false;
        $saleid = intval($params['saleid']);
        if ($saleid == 0)
            return false;
        $data = array();
//        echo 111;die;
        $this->db->init('bookpackagestat', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->setTables(jieqi_dbprefix('article_bookpackagestat') . ' s LEFT JOIN ' . jieqi_dbprefix('article_bookpackage') . ' bp ON s.bpid=bp.id LEFT JOIN ' . jieqi_dbprefix('article_article') . ' a ON s.articleid=a.articleid');
        $this->db->criteria->setFields('s.articleid as articleid, a.articlename as articlename, a.author as author, count(s.chapterid) as clicks');
        $this->db->criteria->setGroupby('s.bpsaleid,s.bpid,s.articleid');
        $this->db->criteria->add(new Criteria('bp.putaway', 1, '='));
        $this->db->criteria->add(new Criteria('s.bpsaleid', $saleid, '='));
        $result = $this->db->lists();
        $total = 0;
        foreach ($result as $k => $v) {
            $total += $v['clicks'];
        }
        $result['total'] = $total;
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    
    /**
     * 购买一个书包的操作方法
     * @param type $params
     */
    public function buy_one_bp($params = array()) {
//        echo 111;die;
        $this->addLang ( 'article', 'article' );
        $notice ['article'] = $this->getLang ( 'article' );
        if (!isset($params['bpid']) || intval($params['bpid'])==0) 
            $this->printfail ('书包编号不能为空，请刷新重试');
        // 用户身份验证
        $auth = $this->getAuth();
        $users_handler =  $this->getUserObject();
        $users = $users_handler->get($auth['uid']);
        if(!is_object($users) || $users->getVar('groupid')==1) 
            $this->printfail($notice['article']['need_user_login']);
//        echo 111;die;
        // 书包验证
        $this->db->init('bookpackage', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('id', $params['bpid'], '='));
        $bpinfo = $this->db->get($this->db->criteria);
        if (!is_object($bpinfo) || $bpinfo->getVar('putaway', 'n')!=1)
            $this->printfail ('书包不存在或已下架');
//        echo 111;die;
        // 验证书包是否已购买
        if ($this->isMyBookpackage($params['bpid'], $users->getVar('uid'))) {
            $contents = array(
                'owner'             => 'OK',
                'msg'               => '您已购买包月书包《'.$bpinfo->getVar('name', 'n').'》，并且该书包未过期，无需二次购买。'
            );
            $this->msgbox('', $contents);
            die;
        }  
        // 余额不足处理
        if ($users->getVar('egold', 'n')<$bpinfo->getVar('price', 'n')) {
            $this->msgbox('', $contents);
        }
        $addData = array(
            'siteid'                => $bpinfo->getVar('siteid', 'n'),
            'accountid'             => $auth['uid'],
            'account'               => $users->getVar('name', 'n'),
            'buy_time'              => JIEQI_NOW_TIME,
            'bpid'                  => $params['bpid'],
            'bpname'                => $bpinfo->getVar('name', 'n'),
            'bookid'                => $bpinfo->getVar('books', 'n'),
            'saleprice'             => $bpinfo->getVar('price', 'n'),
            'pricetype'             => 0,// 目前仅支持书海币购买包月书包
            'endtime'               => JIEQI_NOW_TIME + 30*24*3600,
            'flag'                  => 0
        );
        $this->db->init('bookpackagesale', 'id', 'article');
        if ($this->db->add($addData)) {
            // TODO::数据库操作成功，扣费处理
            if($users_handler->payout($users->getVar('uid', 'n'), $bpinfo->getVar('price', 'n'))) {
                // 异步返回成功提示
                $this->msgbox();
                die;
            } else {
                $this->printfail($jieqiLang['article']['add_buyinfo_failure']);
                // 回滚处理
            }
        } else {
            // TODO::数据库操作失败
            $this->printfail($jieqiLang['article']['add_buyinfo_failure']);
        }
    }
    
    /**
     * 判断用户是否已购买当前书包
     * @param type $params
     */
    public function isMyBookpackage($bpid, $uid) {
        // TODO::TRUE-已购买并在有效期；FALSE-未购买或超过期限
        $this->db->init('bookpackagesale', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('bpid', $bpid, '='));
        $this->db->criteria->add(new Criteria('accountid', $uid, '='));
        $this->db->criteria->add(new Criteria('endtime', JIEQI_NOW_TIME, '>='));
        $this->db->criteria->setFields('id');
        $res = $this->db->lists();
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
    }
    
    // 【书包销售状态】私有方法
    
    /**
     * 内部方法：获取书包的销售状态
     * @param type $bpid
     * @param type $isBoo - 返回布尔值/字符串开关：默认返回布尔值
     */
    private function _bp_sale_status($bpid, $isBoo = true) {
        if (!isset($bpid))
            jieqi_printfail ();
        $this->db->init('bookpackage', 'id', 'article');
        $this->db->setCriteria(new Criteria('id', $bpid, '='));
        $this->db->criteria->setFields('putaway');
        $temp = $this->db->lists();
        if (empty($temp))
            jieqi_printfail ('书包不存在');
        $returnData = $temp[0];
        if ($isBoo)
            return $returnData;
        else 
            return ($returnData==1) ? '销售中' : '已下架';
    }
    
    /**
     * 获取书包销售排行
     * @param type $params
     */
    public function getRankBp($params = array()) {
        $data = array();
        $this->db->init('bookpackagesale', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->setTables(jieqi_dbprefix('article_bookpackagesale').' sa LEFT JOIN '.jieqi_dbprefix('article_bookpackage').' a ON sa.bpid=a.id');
        $this->db->criteria->setLimit(10);
        $this->db->criteria->setFields('sa.bpid,a.name,count(*) as counts');
        $this->db->criteria->setGroupby('sa.bpid');
        $this->db->criteria->setSort('counts');
        $this->db->criteria->setOrder('DESC');
        $data = $this->db->lists();
        return $data;
    }
    
    /**
     * 获取当前用户资产信息
     * @param type $params
     */
    public function get_user_money($params) {
        $this->addLang ( 'article', 'article' );
        $notice ['article'] = $this->getLang ( 'article' );
        // 用户身份验证
        $auth = $this->getAuth();
        // 获取当前用户对象
        $users_handler =  $this->getUserObject();
        $users = $users_handler->get($auth['uid']);
        if(!is_object($users) || $users->getVar('groupid')==1) 
            $this->printfail($notice['article']['need_user_login']);
        $data = array(
            'uid'               => $users->getVar('uid', 'n'),
            'money'             => $users->getVar('egold', 'n'),
            'name'              => $users->getVar('name', 'n')
        );
        // 返回数据jsonp处理
        $this->msgbox('', $data);
        die;
    }
}