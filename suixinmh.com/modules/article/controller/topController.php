<?php 
/** 
 * ÅÅÐÐ°ñ¿ØÖÆÆ÷ * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class topController extends Controller
 { 
  	public $template_name = 'top';//ranking
	public $caching = false;
	public function main($params = array())
	{
		$this->display(); 
	}
	public function toplist($params = array())
	{
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		$dataObj = $this->model('top');
			$this->display($dataObj->toplist($params),'top_list');
	}
 }
?>