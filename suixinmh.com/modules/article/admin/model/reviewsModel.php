<?php 
/** 
 * 小说连载->书评管理 * @copyright   Copyright(c) 2014 
 * @author      liujilei* @version     1.0 
 */ 

class reviewsModel extends Model{
	public function main($params = array()){
		//加载配置参数
		global $jieqiModules;
		$muteService = $this->model('mute','article');
		 $this->addConfig('article','configs');
		 $jieqiConfigs['article'] = $this->getConfig('article','configs');
		//构造查询条件
		$this->db->init ( 'reviews', 'topicid', 'article' );
		$this->db->setCriteria();		
		//$this->db->criteria->setTables($this->dbprefix('article_reviews')." AS r LEFT JOIN ".$this->dbprefix('article_article')." AS a ON r.ownerid=a.articleid ");
		$sqlStr = $this->dbprefix('article_reviews')." AS ar  LEFT JOIN ".$this->dbprefix('article_article')." AS a ON  ar.ownerid = a.articleid";
		$this->db->criteria->setTables($sqlStr);
		$this->db->criteria->setFields("ar.*,a.articlename,a.articleid");
		$this->db->criteria->setSort('ar.topicid');
		$this->db->criteria->setOrder('DESC');
		
		if(!empty($params['display'])){
		 switch ($params['display']){
			case 'isgood':
				$this->db->criteria->add(new Criteria('ar.isgood',1, '='));
				break;
			default:
				$params['display']='';
				break;
		   }
		 }
		 if(!empty($params['keyword'])){
			if($params['keytype']==1) $this->db->criteria->add(new Criteria('articlename', '%'.$params['keyword'].'%', 'like'));
			else $this->db->criteria->add(new Criteria('ar.poster', $params['keyword'], '='));
		 } 
		//分页查询
		 $data = $this->db->lists($jieqiConfigs['article']['toppagenum'],$params['page']);
		 $article_dynamic_url = empty($jieqiConfigs['article']['dynamicurl']) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		 foreach($data as $k=>$v){
		 		//书评操作
				$v['url_top'] = $article_dynamic_url.'/admin/?controller=reviews&method=manageReview&aid='.$v['articleid'].'&action=top&rid='.$v['topicid'];
			    $v['url_untop'] = $article_dynamic_url.'/admin/?controller=reviews&method=manageReview&aid='.$v['articleid'].'&action=untop&rid='.$v['topicid'];
			    $v['url_good'] = $article_dynamic_url.'/admin/?controller=reviews&method=manageReview&aid='.$v['articleid'].'&action=good&rid='.$v['topicid'];
			    $v['url_normal'] = $article_dynamic_url.'/admin/?controller=reviews&method=manageReview&aid='.$v['articleid'].'&action=normal&rid='.$v['topicid'];
			    $v['url_del'] =  $article_dynamic_url.'/admin/?controller=reviews&method=manageReview&action=del&rid='.$v['topicid'];
			    //发表评论的用户有没有禁言，单本禁言|全站禁言
			    if($muteService->userState($v['posterid'],$v['articleid'])){
			    	$v['url_mute'] = "";//禁言
			    }else{
			    	$v['url_mute'] =  $article_dynamic_url.'/admin/?controller=reviews&method=manageReview&aid='.$v['articleid'].'&action=mute&rid='.$v['topicid'].'&posterid='.$v['posterid'].'&poster='.$v['poster'];
			    }
		 	    $data[$k] = $v;
		 }
		 
		 return array(
		      'reviewrows'=>$data,
			  'article_static_url'=>(empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'],
			  'article_dynamic_url'=>$article_dynamic_url,
			  'url_jumppage'=>$this->db->getPage()
		 );
	}
	
	//根据ID 删除评论，并删除该评论的回复，并减少相应的积分
	/*public function delReview($params = array())
	{
		$this->db->init('reviews','topicid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('topicid', $params['rid'], '='));
		$this->db->delete($this->db->criteria);
		return $this->main($params);
	}*/
	
	//根据ID 删除评论，并删除该评论的回复，并减少相应的积分
	public function batchDel($params = array())
	{
		//print_r($params['checkid']);
		$params['rid'] = $params['checkid'];
		$arr = $params['checkid'];
		$ids = $this->arrayToStr($arr);
		$this->db->init('reviews','topicid','article');
		$this->db->setCriteria(new Criteria('topicid','('.$ids.')', 'in'));
		$this->db->delete($this->db->criteria);
		
		$this->db->init('replies ', 'postid', 'article');
		$this->db->setCriteria();
		$this->db->setCriteria(new Criteria('topicid','('.$ids.')', 'in'));
		$this->db->delete($this->db->criteria);
		return $this->main($params);
	}
	
	//管理书评
	public function manageReview(&$params)
	{
		$reviewnewLib = $this->load ( 'reviews', 'article' );
		$reviewnewLib->manageReview($params);
		//$rul =  $this->getAdminurl($_REQUEST['controller'], 'page='.$params['page'], JIEQI_MODULE_NAME);
		jieqi_jumppage();
	}
	//显示书评及回复
	public function showReplies($params = array()){//print_r($params);
		$this->db->init ( 'replies', 'postid', 'article' );
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('topicid', $params['rid']));
		$this->db->criteria->add(new Criteria('istopic', 1));
		$this->db->queryObjects();
		$reviewrow = array ();
		if( $review = $this->db->getObject () ) {
//			$reviewrow['postid'] = $review->getVar ( 'postid' );
//			$reviewrow['istopic'] = $review->getVar ( 'istopic');
//			$reviewrow['poster'] = $review->getVar ( 'poster' );
//			$reviewrow['posttime'] = date("Y-m-d H:i:s",$review->getVar ( 'posttime' ));
			$reviewrow['posttext'] = $review->getVar ( 'posttext' );
		}//print_r($replyrows);
		$data['reviewrow'] = $reviewrow;
		unset($this->db->criteria);
		
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('topicid', $params['rid']));
		$this->db->criteria->add(new Criteria('istopic', 0));
        $this->db->criteria->setSort('posttime');
		$this->db->criteria->setOrder('DESC');
		$this->db->queryObjects();
		$count = $this->db->getCount();
		$data['count'] = $count;
		$replyrows = array ();
		$k = 0;
		while ( $reply = $this->db->getObject () ) {//print_r($reply);
			$replyrows [$k] ['postid'] = $reply->getVar ( 'postid' );
			$replyrows [$k] ['istopic'] = $reply->getVar ( 'istopic');
			$replyrows [$k] ['poster'] = $reply->getVar ( 'poster' );
			$replyrows [$k] ['posttime'] = date("Y-m-d H:i:s",$reply->getVar ( 'posttime' ));
			$replyrows [$k] ['posttext'] = $reply->getVar ( 'posttext' );
			$k ++;
		}//print_r($replyrows);
		$data['replyrows'] = $replyrows;
		$data['article_dynamic_url'] = $this->getAdminurl();
		if($params['nowid']) $data['nowid'] = $params['nowid'];
		return $data;
	}
	//删除回复
	public function delReply($params = array()){//print_r($params);exit();
	    $this->addConfig('article','configs');
		$this->addConfig('article', 'power');
		$jieqiPower['power'] = $this->getConfig('article','power');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		//$canedit = $this->checkpower($jieqiPower['power']['newreview'], $this->getUsersStatus(), $this->getUsersGroup(), true );
		//减少书评积分
		if(!empty($jieqiConfigs['article']['scorereply'])){
			$users_handler = $this->getUserObject();
			$users_handler->changeScore(intval($params['pid']), $jieqiConfigs['article']['scorereply'], false);
		
		}
		//删除回帖
		$this->db->init('replies ', 'postid', 'article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('postid', $params['pid']));
		$this->db->queryObjects();
		$reply = $this->db->getObject();
		$topicid = $reply->getVar('topicid');
		$this->db->delete($this->db->criteria);
		//回复减1
		$this->db->init('reviews ', 'topicid', 'article');
		$review = $this->db->get($topicid);//print_r($review);exit();
		$replies = $review['replies']-1;
		$this->db->edit($topicid,array('replies'=>$replies));
		exit('success');
	}
}
?>