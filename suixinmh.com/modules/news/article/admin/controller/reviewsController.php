<?php 
/** 
 * 小说连载->书评管理 * @copyright   Copyright(c) 2014 
 * @author            liujilei* @version     1.0 
 */ 
class reviewsController extends Admin_controller {
		public $template_name = 'reviews';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallreview');//权限验证
        } 
		
        public function main($params = array()) {
			 $modelObj = $this->model('reviews');
             $this->display($modelObj->main($params));
        } 
		
		//根据ID 删除评论，并删除该评论的回复，并减少相应的积分
		public function batchDel($params = array()){
			 $modelObj = $this->model('reviews');
             //$modelObj->delReview($params);
			 //$this->main($params);
			  $this->display($modelObj->batchDel($params));
		}
		
		//管理书评
	    public function manageReview($params = array())
	   {
			$this->addConfig(JIEQI_MODULE_NAME, 'power');
			$jieqiPower['power'] = $this->getConfig(JIEQI_MODULE_NAME,'power');
			$canedit = $this->checkpower($jieqiPower['power']['manageallreview'], $this->getUsersStatus(), $this->getUsersGroup(), true );
			if($canedit && isset($params['action']) && !empty($params['rid']))
			{
				$modelObj = $this->model('reviews');
				$reviewId = $modelObj->manageReview($params);
			}
			$this->main($params);
	   }
	   //显示书评及回复
	   public function showReplies($params = array()){
			header('Content-Type:text/html;charset=gbk');//用于评论刷新时消除乱码
	   		$dataObj = $this->model('reviews');
			$data = $dataObj->showReplies($params);
			$this->display($data,'showreplies');
	   }
	   //删除回复
	   public function delReply($params = array()){//print_r($params);exit();
	   		$dataObj = $this->model('reviews');
			$dataObj->delReply($params);
	   }
}

?>