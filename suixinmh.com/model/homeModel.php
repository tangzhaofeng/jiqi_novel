<?php 
/** 
 * 测试模型 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class homeModel extends Model{ 
    
        function getArticles(){ 
		     $auth = $this->getAuth();
		     $users_handler = $this->getUserObject();
		     $users = $users_handler->get($auth['uid']);
// 			 print_r($users_handler->getByname($users->getVar('uname'),3));
			 
             $this->db->init('article','articleid','article');
			 //print_r($this->db->get(4));exit;
			 $this->db->setCriteria();
			 //$this->db->criteria->setTables('jieqi_article_article');
			 //$this->db->criteria->add(new Criteria('initial', "D"));
			 $this->db->criteria->setSort('articleid');
	         $this->db->criteria->setOrder('DESC');
			 //print_r($this->db);
			 return array(
			     'data'=>$this->db->lists(20,$_REQUEST['page']),
				 'jumppage'=>$this->db->getPage()
			 );
        } 
		
	function getDynamic($params)
	{	
		$this->db->init('statlog','statlogid','article');
		$this->db->setCriteria();
		$this->db->criteria->setTables($this->dbprefix('article_statlog')." AS s LEFT JOIN ".$this->dbprefix('article_article')." AS a ON s.articleid=a.articleid "." LEFT JOIN ".$this->dbprefix('system_users')." AS u ON u.uid=s.uid");
		$this->db->criteria->setFields('s.*,a.articlename,a.authorid,a.siteid,a.sortid,u.avatar');
		
		//条件
		if(!empty($params['mid'])){
		 switch ($params['mid']){
			case 'reward':
				$this->db->criteria->add(new Criteria('s.mid',$params['mid'], '='));
				break;
				case 'goodnum':
				$this->db->criteria->add(new Criteria('s.mid',$params['mid'], '='));
				break;
				case 'sale':
				$this->db->criteria->add(new Criteria('s.mid',$params['mid'], '='));
				break;
				case 'vote':
				$this->db->criteria->add(new Criteria('s.mid',$params['mid'], '='));
				break;
				case 'my':
				$this->db->criteria->add(new Criteria('s.uid',$params['uid'], '='));
				//$this->db->criteria->add(new Criteria('a.authorid',$params['uid'], '='),'or');
				
				
				break;
		   }
		 }
		$this->db->criteria->setSort('statlogid');
		$this->db->criteria->setOrder('DESC');
		
		//自定义分页
			$p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showDynamic(this,\'{$prepage}\',1)" id="'.$params['mid'].'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="javascript:;" onclick="return showDynamic(this,\'{$pnumurl}\',1)" id="'.$params['mid'].'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showDynamic(this,\'{$nextpage}\',1)" id="'.$params['mid'].'">下一页</a>[/nextpage]<em class="pr10">共{$page}/{$totalpage}页</em>';
		
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		
		$data = $this->db->lists($jieqiConfigs['article']['newreviewnum'],$params['page'],$p);
		$articleLid = $this->load('article','article');
		foreach($data as $k=>$v)
		{
			$fd = $articleLid ->article_statlog_vars($v);
			$data[$k] = $fd;
			//print_r($fd);exit;
		}
		$dynamic_jumppage = $this->db->getPage($this->getUrl('system','userhub','method=myDynamic','evalpage=0','SYS=mid='.$params['mid'].'&uid='.$params['uid']));
		if (!empty($params['mid']) && $params['mid'] == 'my')
		{
			 $auth = $this->getAuth();
			 $objLib = $this->load( 'article', 'article');	
			 $objLib->setSetting($auth['uid'],'jieqiNewTongZhi',true); 
		}
		return array(
		'dynamicrows'=>$data,
		'dynamic_jumppage'=>$dynamic_jumppage
		);
	}
	
	function getDingyue($params){
	    $this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
	 	$this->db->init('autobuy','autoid','article');
		$this->db->setCriteria();
		$this->db->criteria->setTables($this->dbprefix('article_autobuy'). ' as ab left join '.$this->dbprefix('article_article'). ' as a on ab.articleid=a.articleid');
		$this->db->criteria->add(new Criteria('ab.uid',$params['uid'], '='));
		$this->db->criteria->setFields('a.*,ab.addtime');
		$data = $this->db->lists($jieqiConfigs['article']['newreviewnum'],$params['page'],JIEQI_PAGE_TAG);
		$articleLid = $this->load('article','article');
		foreach($data as $k=>$v)
		{
			$article = $articleLid ->article_vars($v);
			$data[$k] = $article;
		}
		
		return array(
		'dingyuecrows'=>$data,
		'url_jumppage'=>$this->db->getPage()
		);
	}
} 
?>