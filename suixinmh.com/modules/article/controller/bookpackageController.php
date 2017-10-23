<?php
/**
 * 包月书包控制器：书包列表
 * @author by: liuxiangbin
 * @createtime : 2014-12-10
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class bookpackageController extends Controller {
    
    public $template_name = 'bookpackagelist';
    // 缓存相关内容
    public $caching = false;
//  public $theme_dir = false;
//  public $cacheid = 'snow_bookpackage';
//    public $cachetime = 5;
    
    /**
     * 包月书包列表（默认方法）
     * @param type $params
     */
    public function main($params = array()) {
        $data = array();
        $page = $this->getRequest('page');
        if (!$page)
            $page = 1;
        $this->setCacheid($page);
        if (!$this->is_cached()) {
            $bpinfoModel = $this->model('bookpackage');
            $bpsaleModel = $this->model('bookpackagesale');
            $data = $bpinfoModel->getBookpackageList($params, false);
            $data['commends'] = $bpinfoModel->commendsBookpackageList($params, true);
            $data['rankbp'] = $bpsaleModel->getRankBp();
            // 男频数据
            $temp = $bpinfoModel->siteBookpackageList($params, 0);
            $data['bpmalelist'] = $temp['lists'];
            $data['male_jumppage'] = $temp['url_jumppage'];
            // 女频数据
            $temp = $bpinfoModel->siteBookpackageList($params, 100);
            $data['bpfemalelist'] = $temp['lists'];
            $data['female_jumppage'] = $temp['url_jumppage'];
        }
        $this->display($data);
    }
    
    /**
     * 包月书包详情展示页
     * @param type $params
     */
    public function showbookpackage($params = array()) {
        if (!isset($params['bpid'])) {
            jieqi_printfail('对不起，该书包不存在！', $this->geturl('article', 'bookpackage', 'SYS=method=main'));
        }
        $bpinfoModel = $this->model('bookpackage');
        $bpsaleModel = $this->model('bookpackagesale');
        $temp = $bpinfoModel->getBookpackageInfo($params);
        $data['bpinfo'] = $temp[0];
        $data['commends'] = $bpinfoModel->commendsBookpackageList();
        $data['rankbp'] = $bpsaleModel->getRankBp();
        // 引入书籍分类
        $this->addConfig('article', 'sort');
        $data['sortname'] = $this->getConfig('article', 'sort');
//		$this->dump($data);
        $this->display($data, 'bookpackageinfo');
    }
    
    /**
     * 书包包月购买记录【我的书包】
     * @param type $params
     */
    public function mybookpackage($params = array()) {
        $bpsaleModel = $this->model('bookpackagesale');
        $data['records'] = $bpsaleModel->getMyBookpackage($params);
        $this->display($data, 'bookpackagebuy');
    }
    
    /**
     * 购买一个书包
     * @param type $params
     */
    public function buybookpackage($params = array()) {
//        $this->checklogin();
        $bpsaleModel = $this->model('bookpackagesale');
        $bpsaleModel->buy_one_bp($params);
    }
    
    /**
     * 获取用户账户信息
     * @param type $params
     */
    public function get_user_info($params = array()) {
        $this->checklogin();
        $bpsaleModel = $this->model('bookpackagesale');
        // 异步返回数据
        $bpsaleModel->get_user_money($params);
    }
}