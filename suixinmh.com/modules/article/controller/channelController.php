<?php 
/** 
 * 分类文章控制器 * @copyright   Copyright(c) 2014 
 * @author      zhangxue* @version     1.0 
 */ 
class channelController extends Controller
 { 
  	public $template_name = 'channel';
	public $theme_dir = false;
	public $caching = false;
	public function main($params = array())
	{
		 $data = array();//global $jieqiConfigs,$jieqiBlocks;
//		 $page = $params['page'];
//		 if(!$page) $page= 1;
//		 $this->setCacheid($page);
//		 if(!$this->is_cached()){//echo ' 执行了缓存中的数据查询代码';
			 //$dataObj = $this->model('channel');
//		 }
         $this->addConfig('article','sort');
		 $jieqiSort['article'] = $this->getConfig ( 'article', 'sort' );	//文章分类配置
		 $sortid = $params['sortid'];
		 $sort = $jieqiSort['article'][$sortid]['shortcaption'];
		 $this->display(array('sortid'=>$sortid,'sort'=>$sort)); 
//		 $this->display($params); 
	}
 }
?>