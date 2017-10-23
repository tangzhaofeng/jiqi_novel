<?php 
/** 
 * 测试控制器 * @copyright   Copyright(c) 2014 
 * @author      liujilei* @version     1.0 
 */ 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class reviewsController extends chief_controller
 { 
	public $caching = false;
	public $theme_dir = false;
	//查询并显示书评
	public function main($params = array())
	{
		$modelObj = $this->model('reviews','article');
		$data = $modelObj->main($params);//print_r($data);
		//$dataObj = $this->model('articleinfo','article');
		//$data1 = $dataObj->articleinfoView($params);
		//$data['reviewrows'] = $data1['reviewrows'];
//		$this->dump($data);
	 	$this->display($data); 
	}
	
	//根据评论（主题）ID 查询评论
	public function showReplies($params = array())
	{
//		$this->dump($GLOBALS);
//		$this->template_name = 'reviewshow';
		$modelObj = $this->model('reviews','article');
		$data = $modelObj->showReplies($params);

		$revieModel = $this->model('reviews3g', '3g');
		$data['review'] = $revieModel->reviewbyid($params);//print_r($data);
		$data['replyrows'] = $revieModel->addFace($data['replyrows']);
//		$data['review'] = $revieModel->addFace($data['review'], false);
//		$this->dump($data);
	 	$this->display($data);
	}
	
	public function addReplies($params = array())
	{	$auth = $this->getAuth();
	    if (empty($auth['uid'])){
			header('location:'.$this->geturl(JIEQI_MODULE_NAME, 'login'));exit;
		}
		if($this->submitcheck()){
			$modelObj = $this->model('reviews','article');
			$modelObj->addReplies($params);
		}
	}
	
	//新增书评
	public function add($params = array())
	{ 	
		$auth = $this->getAuth();
		if (empty($auth['uid'])){
			header('location:'.$this->geturl(JIEQI_MODULE_NAME, 'login'));exit;
		}
		if($this->submitcheck()){
			$modelObj = $this->model('reviews','article');
			$modelObj->add($params);
		}
	}
 }
?>