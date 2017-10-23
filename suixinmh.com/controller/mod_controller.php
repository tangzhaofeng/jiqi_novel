<?php
/**
 * base控制器
 * @copyright   Copyright(c) 2014
 * @author      chengyuan *
 * @version     1.0
 */
class chief_controller extends Controller {
	//默认模板
	public $template_name = 'main';
	public $caching = false;
	/**
	 * article根控制器，統一處理登陸后臺管理等的權限驗證
	 */
	public function __construct() {
	    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		parent::__construct ();
		if(!in_array($this->getRequest('method'), array('userinfo','popuser','usermember','uservip','zuozhe','logout'))){
			//print_r($this->getRequest('method'));exit;
			//检查登陆
			$this->checklogin();
		}
		//检查发表新书的权限
		$this->addConfig('article','power');
		$iswriter = $this->checkpower ($this->getConfig('article','power','newarticle'), $this->getUsersStatus (), $this->getUsersGroup (), true);
		if($iswriter){
			//作者有没有创建过书
			$article =  $this->model('article','article');
			$this->assign('hasCreate', $article->createArticle());
		}
		$this->assign('iswriter',$iswriter);
	}
}
?>