<?php 
/** 
 * 小说连载->回帖管理 * @copyright   Copyright(c) 2014 
 * @author            liujilei* @version     1.0 
 */ 
class repliesController extends Admin_controller {
		public $template_name = 'replies';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallreview');//权限验证
        } 
		
        public function main($params = array()) {
		
			 $modelObj = $this->model('replies');
             $this->display($modelObj->main($params));
        } 
		
		//根据ID并删除该评论的回复，并减少相应的积分
	public function delReply($params = array())
	{
		 $modelObj = $this->model('replies');
         $this->display($modelObj->delReply($params));
	}
	
	//根据ID 删除评论，并删除该评论的回复，并减少相应的积分
	public function batchDel($params = array()){
		 $modelObj = $this->model('replies');
		 $this->display($modelObj->batchDel($params));
	}
	
}
?>