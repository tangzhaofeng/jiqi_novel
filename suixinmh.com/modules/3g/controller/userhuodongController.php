<?php
/**
 * 用户中心控制器
 * @author chengyuan  2015-3-31
 *
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class userhuodongController extends chief_controller {
	public $theme_dir = false;
	public $caching = false;

	public function sliver($param){
        if($this->checklogin(true)){
            $dataObj = $this->model('userhuodong','3g');
            $data = $dataObj->sliver($param);
            $this->display($data);
        }else{
            header('Location: '.$this->geturl('3g', 'login'));
        }
    }
    public function guoqing($param){
        if($this->checklogin(true)){
            $dataObj = $this->model('userhuodong','3g');
            $data = $dataObj->guoqing($param);
            $this->display($data);
        }else{
            header('Location: '.$this->geturl('3g', 'login'));
        }
    }
}
?>