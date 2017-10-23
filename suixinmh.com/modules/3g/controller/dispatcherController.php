<?php 
/** 
 * 分类文章控制器 * @copyright   Copyright(c) 2014 
 * @author      zhangxue* @version     1.0 
 */ 
class dispatcherController extends chief_controller
 { 
// 	public $template_name = 'shuku';
	public $caching = false;
//	public $theme_dir = false;
	// public $cacheid = 'fff';
	// public $cachetime=5;
	public function main($params = array()) {
		//$dataObj = $this->model ( 'sort', 'article' );
		//$data = $dataObj->query ( $params );
		//print_r($data);
		switch($params['type']){
			case 'shuku':
				$this->display ("");
				break;
			case 'top':
				$this->display ("",'top');
				break;
			case 'male':
				$this->display ("",'male');
				break;
			case 'female':
				$this->display ("",'female');
				break;
			case 'quanben':
				$this->display ("",'quanben');
				break;
			case 'free':
				$this->display ("",'free');
				break;
			case 'wenxue':
				$this->display ("",'wenxue');
				break;
			case 'baoyue':
				$this->jumppage(JIEQI_REFER_URL,'建设中……', '敬请期待');
				break;
			case 'download':
				$this->display ("",'download');
				break;
			case 'ment':
				$this->display ("",'ment');
				break;
			default:
				//print_r($params['type']);exit;
				$this->jumppage(JIEQI_REFER_URL,'参数错误……', '小样……');
				break;
	}	
	}
 }
?>