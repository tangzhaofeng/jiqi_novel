<?php 
/**
 * 书评控制器
 * @author chengyuan 2015-6-11 上午9:10:15
 */ 
class reviewsController extends Controller
 { 
	public $caching = false;
	//查询并显示书评
	public function main($params = array())
	{
		header('Content-Type:text/html;charset=gbk');//用于评论刷新时消除乱码
		$modelObj = $this->model('reviews');
		$data = $modelObj->main($params);
	 	$this->display($data); 
	}
	
	//根据评论（主题）ID 查询评论
	public function showReplies($params = array())
	{	
		$this->template_name = 'reviewshow';
		$modelObj = $this->model('reviews');
		$data = $modelObj->showReplies($params);
		
	 	$this->display($data);
	}
	
	public function addReplies($params = array())
	{	
		
		if($this->submitcheck()){
			$modelObj = $this->model('reviews');
			$modelObj->addReplies($params);
		}
	}
	
	//编辑帖子
	public function editReply($params=array())
	{
		$this->template_name = 'reviewedit';
		$modelObj = $this->model('reviews');
		$data = $modelObj->editReply($params);
		$this->display($data);
	}
	//管理帖子回复
	public function manageReplies ($params=array())
	{
		if($this->submitcheck()){
			$params['dosubmit'] = true;
		}else{
			$params['dosubmit'] = false;
		}
		$modelObj = $this->model('reviews');
		$modelObj->manageReplies($params);
		
		//查询并转发
		$this->showReplies($params);
	}
	
	//新增书评
	public function add($params = array())
	{ 	
			$modelObj = $this->model('reviews');
			$modelObj->add($params);
	}
	
	//管理书评
	public function manageReview($params = array())
	{
		if(isset($params['action']) && !empty($params['rid']))
		{
			$modelObj = $this->model('reviews');
			$modelObj->manageReview($params); 
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	}
	public function dianzan($params = array())
	{
		$modelObj = $this->model('reviews');
		$modelObj->manageReplies($params);
	}
	/**
	 * 申请版主
	 * @author chengyuan 2015-6-8 下午1:49:50
	 * @param unknown $params
	 */
	public function asyncApplyModerator($params = array()){
		$modelObj = $this->model('reviews');
		$modelObj->applyModerator($params['aid']);
	}
 }
?>