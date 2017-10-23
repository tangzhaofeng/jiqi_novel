<?php

/**
 * 后台首页控制器 * @copyright   Copyright(c) 2014
 * @author      huliming* @version     1.0
 */
class articleController extends Admin_controller
{
    public $theme_dir = false;

    public function main($param)
    {
        $dataObj = $this->model('article');
        $data = $dataObj->main($param);
        $data['menu']='qd';
        //print_r($data);
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,'readertotal');
    }

    public function blocklist($param) {
        $dataObj = $this->model('article');
        $data = $dataObj->blocklist($param);
        $data['menu']='qd';
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,'blocklist');
    }
    public function addblock($param) {
        $dataObj = $this->model('article');
        $data = $dataObj->addblock($param);
        $data['menu']='qd';
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,'blockadd');
    }
    public function delblock($param) {
        $dataObj = $this->model('article');
        $dataObj->delblock($param);
    }

    public function booktotal($param) {
        $dataObj = $this->model('article');
        $data = $dataObj->booktotal($param);
        $data['menu']='qd';
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,'booktotal');
    }

}

?>