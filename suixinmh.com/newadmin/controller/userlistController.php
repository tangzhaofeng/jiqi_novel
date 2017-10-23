<?php

/**
 * 后台首页控制器 * @copyright   Copyright(c) 2014
 * @author      huliming* @version     1.0
 */
define("ADMIN_ONLY",true);
class userlistController extends Admin_controller
{
    public $template_name = 'userlist';
    public $theme_dir = false;

    public function main($param)
    {
        $dataObj = $this->model('userlist');
        $data = $dataObj->main($param);
        $data['menu']='userlist';
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data);
    }

    public function addview($param)
    {
        $dataObj = $this->model('userlist');
        $data = $dataObj->addview($param);
        $data['menu']='userlist';
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,"user_add");
    }

    public function add($param)
    {
        $dataObj = $this->model('userlist');
        $dataObj->add($param);
    }

    public function editview($param)
    {
        $dataObj = $this->model('userlist');
        $data = $dataObj->editview($param);
        $data['menu']='userlist';
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,"user_edit");
    }

    public function edit($param)
    {
        $dataObj = $this->model('userlist');
        $dataObj->edit($param);
    }
}

?>