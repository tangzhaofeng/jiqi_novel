<?php
 /**==============================
  * 行为操作基础类
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 ===============================*/
ACCESS_GRANT != true && exit('forbiden!');
class Action{
    protected $model;
    protected $smarty;
    protected $adm;
    public function __construct(){
        global $mUser, $smarty;
        $this ->model = new comModel;
        $this ->smarty = $smarty;
        $this ->adm = $mUser;

    }    
}