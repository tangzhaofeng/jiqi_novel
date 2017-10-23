<?php 
/** 
 * base控制器
 * @copyright   Copyright(c) 2014 
 * @author      huliming * 
 * @version     1.0 
 */ 
class pay_controller extends Controller {
	//默认模板
	public $template_name = 'main';
	public $caching = false;
	/**
	 * pay根控制器
	 */
	public function __construct() {
	    header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); 
		parent::__construct ();
		$this->addConfig('article','power');header('location:http://m.ishufun.net/pay');
		$this->assign('iswriter',$this->checkpower ($this->getConfig('article','power','newarticle'), $this->getUsersStatus (), $this->getUsersGroup (), true));
	} 	
} 
?>