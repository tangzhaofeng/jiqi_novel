<?php
/**
 * 用户中心控制器
 * @author chengyuan  2015-3-31
 *
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class userhubController extends chief_controller {
	public $theme_dir = false;
	public $caching = false;
	/**
	 * 用户信息
	 */
	public function userinfo($params = array()){
		$this->display();
	}

	/**
	 * 更新密码
	 * @param unknown $param
	 */
	public function updatePwd($param){
		if($this->submitcheck()){
			$dataObj = $this->model('passedit','system');
			$dataObj->passedit($param,'userinfo');
		}
	}
	/**
	 * 退出登录
	 */
	public function logout(){
		$dataObj = $this->model('logout','system');
		$dataObj->logout();
	}
	/**
	 * 收件箱
	 */
	public function inbox($param){
		$dataObj = $this->model('message','system');
		$data = $dataObj->inbox($param);
		$this->display($data);
	}
	/**
	 * 我的发表的书评
	 */
	public function comment($param){
		$reviewsLib = $this->load ( 'reviews', 'article' );
		$auth = $this->getAuth();
		$url = $this->getUrl('article','userhub','SYS=method=comment');
		$data = $reviewsLib->queryReviews(array('uid'=>$auth['uid'],'page'=>$param['page'],'ispage'=>1,'url'=>$url));
		$data['limit'] = 10;
		$data['pageurl'] = $this->getUrl('3gwap','userhub','SYS=method=comment&page=1');//print_r($data);
		if (empty($param['ajax_request'])){
			$this->display($data);
		}else{
			$this->display($data,'commentAjax');
		}
		
	}
	/**
	 * 用户中心首页
	 */
	public function main($param){
        //刷新用户信息
		$users_handler =  $this->getUserObject();
		$auth = $this->getAuth();
		if($auth['uid']){//鏇存柊鐢ㄦ埛SESSIO锛岄槻姝㈠嚭鐜板厖鍊煎埌璐︽湭鏄剧ず鐨勬儏鍐?
			if($users = $users_handler->get($auth['uid'])){
				$users->saveToSession();
			}
		}
		$this->display();
	}
	/**
	 * 财务中心-充值
	 */
/*	public function cwView($param){
		$data = array();
		$this->display($data,'caiwuhub');
	}*/
	/**
	 * 充值记录
	 */
	public function czView($param){//print_r($param);
		$dataObj = $this->model('finance','system');
		$data = $dataObj->rechargeLog($param);
		$this->display($data,'userhub_czView');
	}
	/**
	 * 消费记录
	 */
	public function xfView($param){
		$dataObj = $this->model('finance','system');
		$data = $dataObj->pay($param);
		$dat = $dataObj->xiaofei();
		$data['xfegold'] = $dat['xfegold'];
		$data['xfnum'] = $dat['xfnum'];
		$this->display($data);
	}

	public function usermember($params = array()){
		$this->display();
	}

	public function uservip($param){
		$this->display();
	}
}
?>