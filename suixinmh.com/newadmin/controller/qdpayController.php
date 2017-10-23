<?php 
/** 
 * 后台首页控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */
class qdpayController extends Admin_controller {
    public $template_name = 'qdpay';
    public $theme_dir = false;

    public function main($param)
    {
        $dataObj = $this->model('pay');
        $data = $dataObj->qdpay_total_daily($param);
        $auth = $this->getAuth();
        $data['groupid'] = $auth['groupid'];
        $this->display($data);
    }

    public function paytotal($param) {
        $dataObj = $this->model('pay');
        $data=$dataObj->pay_total_daily($param);
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,"paytotal");
    }

    public function paylist($param) {
        $dataObj = $this->model('pay');
        $data=$dataObj->paylist($param);
        $auth=$this->getAuth();
        $data['groupid']=$auth['groupid'];
        $this->display($data,"paylist");
    }

    public function qdtotal($param)
    {
        $dataObj = $this->model('pay');
        $data = $dataObj->qdtotal($param);
        $auth = $this->getAuth();
        $data['groupid'] = $auth['groupid'];
        $this->display($data,"qdtotal");
    }
}
?>