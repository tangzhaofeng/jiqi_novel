<?php 
/** 
 * ²âÊÔ¿ØÖÆÆ÷ * @copyright   Copyright(c) 2014 
 * @author      liujilei* @version     1.0 
 */ 
class searchController extends Controller
 { 
  	public $template_name = 'searchresult';
	public $caching = false;
	public function main($params = array())
	{   
	    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		if($params['searchkey']){
		    $params['searchkey'] = iconv('utf-8','gbk',$params['searchkey']);
			$dataObj = $this->model('search');
			$data = $dataObj->search($params);
			
			if ($data['articlerows'] == 1){
			$url_articleinfo=$this->geturl('article', 'articleinfo', 'SYS=aid='.$data['aid']);
			header('location:'.$url_articleinfo);
			}
		}
		$this->display($data);
	}
	public function searchhome($params = array())
	{
		$data = array();
		$this->theme_dir = false;
		$this->display($data,'search_home');
	}
 }
?>