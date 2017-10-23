<?php

/**
 * 后台首页控制器 * @copyright   Copyright(c) 2014
 * @author      huliming* @version     1.0
 */
class qdController extends Admin_controller
{
    public $template_name = 'qdlist';
    public $theme_dir = false;

    public function main($param)
    {
        $dataObj = $this->model('qd');
        $data = $dataObj->main($param);
        $data['menu']='qd';
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data);
    }
    public function addgroupview($param)
    {
        $dataObj = $this->model('qd');
        $data = $dataObj->addgroupview($param);
        $data['menu']='qd';
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,"qd_group_add");
    }
    public function addgroup($param)
    {
        $dataObj = $this->model('qd');
        $param['menu']='qd';
        $auth=$this->getAuth();
        $param['groupid']=$auth['groupid'];
        $dataObj->addgroup($param);
    }
    public function delgroup($param)
    {
        $dataObj = $this->model('qd');
        $data = $dataObj->delgroup($param);

    }
    public function group($param)
    {
        $dataObj = $this->model('qd');
        $data = $dataObj->group_list($param);
        $data['menu']='qd';
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,"qd_group_list");
    }

    public function addview($param)
    {
        $dataObj = $this->model('qd');
        $data = $dataObj->addview($param);
        $data['menu']='qd';
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,"qd_add");
    }
    public function add($param)
    {
        $dataObj = $this->model('qd');
        $dataObj->add($param);
    }

}

?>