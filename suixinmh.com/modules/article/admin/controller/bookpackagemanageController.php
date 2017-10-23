<?php
/**
 * 包月书包后台管理控制器：
 * @auther by: liuxiangbin
 * @createtime : 2014-12-12
 */
class bookpackagemanageController extends Admin_controller {
    public $template_name = 'bookpackagemanage';
    
    public function __construct() {
        parent::__construct();
//        echo 'start';die;
        $this->checkpower('manageallarticle');
    }
    
    /**
     * 包月书包管理页面入口
     * @param type $params
     */
    public function main($params = array()) {
//        $this->dump($_REQUEST['method']);
        // 分页类中设置当前页面
//        $params['thismethod'] = 'main';
//        $this->dump($params, 0);
        $data = array();
        $bpinfoModel = $this->model('bookpackage', 'article');
        $data = $bpinfoModel->getBookpackageList($params, true);
        // 添加频道列表
        $package = $this->load('article', 'article');
        $tempData = $package->getSources();
        $data['channel'] = $tempData['channel'];
        $this->display($data);
    }
    
    /**
     * 添加新书包的控制器
     * @param type $params
     */
    public function add_bp($params = array()) {
        $data = array();
        // 添加频道列表
        $package = $this->load('article', 'article');
        $tempData = $package->getSources();
        $data['channel'] = $tempData['channel'];
        $sortModel = $this->model('bookpackagesort', 'article');
        $data['bpsort'] = $sortModel->get_bosort($params);
        $this->display($data, 'addbookpackage');
    }
    
    /**
     * 添加新书包的控制器
     * @param type $params
     */
    public function add_newbp($params = array()) {
        $data = array();
        $bpinfoModel = $this->model('bookpackage', 'article');
        if ($bpinfoModel->add_new_bp($params)) {
            $this->jumppage($this->getAdminurl().'&method=main', LANG_DO_SUCCESS, '书包添加成功:)');
        } else {
            $this->jumppage($this->getAdminurl().'&method=add_bp', LANG_DO_FAILURE, '书包添加失败:(');
        }
    }
    
    /**
     * 编辑书包控制器
     * @param type $params
     */
    public function edit_bp($params = array()) {
//        $this->dump($params);
        $data = array();
        $bpinfoModel = $this->model('bookpackage', 'article');
        $temp = $bpinfoModel->getBookpackageInfo($params, true);
        $data['bpinfo'] = $temp[0];
        // 添加频道列表
        $package = $this->load('article', 'article');
        $tempData = $package->getSources();
        $data['channel'] = $tempData['channel'];
        $sortModel = $this->model('bookpackagesort', 'article');
        $data['bpsort'] = $sortModel->get_bosort($params);
//        $this->dump($temp);
        $this->display($data, 'editbookpackage');
    }
    
    /**
     * 更新书包内容的中间控制器
     * @param type $parasm
     */
    public function edit_bpinfo($params = array()) {
        $data = array();
        $bpinfoModel = $this->model('bookpackage', 'article');
        if ($bpinfoModel->update_one_bp($params)) {
            $this->jumppage($this->getAdminurl().'&method=main', LANG_DO_SUCCESS, '书包更新成功:)');
        } else {
            $this->jumppage($this->getAdminurl().'&method=edit_bp&id='.$params['id'], LANG_DO_FAILURE, '书包更新失败:(');
        }
    }
    
    /**
     * 异步方式返回一个书包的书籍明细
     * @param type $params
     */
    public function show_on_bp($params) {
        $data = array();
        $bpinfoModel = $this->model('bookpackage', 'article');
        $result = $bpinfoModel->get_one_bpinfo($params);
        if (!$result) {
            $data['status'] = '400';
        } else {
            $data['status'] = '200';
            $data['list'] = $result;
        }
        echo $this->json_encode($data);
        die;
    }
    
    /**
     * 异步方式删除一个书包
     * @param type $params
     */
    public function del_on_bp($params) {
        $data = array();
        $bpinfoModel = $this->model('bookpackage', 'article');
        $result = $bpinfoModel->del_bpinfo($params);
        echo $this->json_encode($result);
        die;
    }
    
    /**
     * 异步查询添加书籍列表
     * @param type $params
     */
    public function search_book_id($params = array()) {
        $data = array();
        $bpinfoModel = $this->model('bookpackage', 'article');
        $result = $bpinfoModel->get_book_ids($params);
//        $this->msgbox('', $result);
    }
    
    /**
     * 判断书籍是否可以添加
     * @param type $params
     * @return type
     */
    public function check_exist_book($params = array()) {
        $bpinfoModel = $this->model('bookpackage', 'article');
        $result = $bpinfoModel->_check_book_exist($params['bookid']);
        echo $this->json_encode($result);
        die;
    }
    
    /**
     * 包月书包销售统计页面入口
     * @param type $params
     */
    public function bpsalecount($params = array()) {
        $data = array();
        $bpsaleModel = $this->model('bookpackagesale', 'article');
        $data = $bpsaleModel->getAllBp($params);
        $this->display($data, 'bookpackagecount');
    }
    
    /**
     * 异步处理单条记录中点击量的方法
     * @param type $params
     */
    public function search_click($params = array()) {
        $data = array();
        $bpsaleModel = $this->model('bookpackagesale', 'article');
        $data = $bpsaleModel->get_one_clicks($params);
        if ($data) {
            jieqi_msgbox('', $data);
        } else {
            jieqi_printfail();
        }
    }
    
    /**
     * 阅读点击量的控制方法
     * @param type $params
     */
    public function bpclick($params = array()) {
        $data = array();
        $bpsaleModel = $this->model('bookpackagesale', 'article');
        $data = $bpsaleModel->get_all_click($params);
        $this->display($data, 'bookpackageclick');
    }
}
