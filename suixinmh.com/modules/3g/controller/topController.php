<?php 
/** 
 * 排行榜控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class topController extends chief_controller
 { 
	public $caching = true;
	
	/**
	 * 排行榜导航页
	 */
	public function main($params = array())
	{
		$this->display(); 
	}
	
	/**
	 * 排行榜页面
	 */
	public function toplist($params = array())
	{
		$params['listnum'] = 15;
		$this->setCacheid(md5(serialize($params)));
		if(!$this->is_cached()){
			$dataObj = $this->model('top','article');
			// 增加每页显示文章数量
			$data = $dataObj->toplist($params);//print_r($data);
			$data['midname'] = $data['midname'].'榜';
		}
		$this->display($data);
	}
 }
?>