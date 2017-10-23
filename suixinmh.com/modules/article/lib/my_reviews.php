<?php
/**
 * 
 * 
 * @copyright Copyright(c) 2014
 * @author chengyuan
 * @version 1.0
 */
class MyReviews extends Model {

     /**
	 * 默认构造器，实例$this->db
	 */
// 	function __construct() {
// 		// echo '__construc';
// 		// $this->getLang('article', 'article');
// 		// jieqi_loadlang('article', 'article');
// 		if (! is_object ( $this->db )) {
// 			$this->db = Application::$_lib ['database'];
// 		}
// 	}
	
	//新增评论
	public function addReview(&$params)
	{
		//校验错误信息数组
		$this->addConfig('article', 'configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$check_errors = array();
		//检查和过滤提交变量
		$post_set = array('module'=>JIEQI_MODULE_NAME, 'ownerid'=>intval($params['aid']), 'topicid'=>0, 'postid'=>0, 'posttime'=>JIEQI_NOW_TIME, 'posttext'=>$params['pcontent'], 'attachment'=>'', 'emptytitle'=>true, 'isnew'=>true, 'istopic'=>1, 'istop'=>0, 'sname'=>'jieqiArticleReviewTime', 'attachfile'=>'', 'oldattach'=>'', 'checkcode'=>$params['checkcode']);
		$this->postcheckvar($post_set, $jieqiConfigs['article'], $check_errors);
		if (empty($check_errors)){
			$this->db->init('reviews', 'topicid', 'article');
			$v = $this->topicnewset($params);
			$rid = $this->db->add($v);
			//添加回复
			$params['rid'] = $rid;
			$params['istopic'] = 1;
			$v = $this->addReply($params);
			return $rid;
		}else{
			$this->printfail(implode('<br />', $check_errors));
		}
	}
	
	//添加回复
	public function addReply(&$params)
	{	
		$this->addConfig('article', 'configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
	    $check_errors = array();
		if (!empty($params['rcontent']))
		{
			$params['pcontent'] = $params['rcontent'];
		}
		$post_set = array('module'=>JIEQI_MODULE_NAME, 'ownerid'=>intval($ownerid), 'topicid'=>intval($params['rid']), 'postid'=>0, 'posttime'=>JIEQI_NOW_TIME,  'posttext'=>$params['pcontent'], 'attachment'=>'', 'isnew'=>true, 'istopic'=>0, 'istop'=>0, 'sname'=>'jieqiArticleReviewTime', 'attachfile'=>'', 'oldattach'=>'', 'checkcode'=>$params['checkcode']);

	    $this->postcheckvar($post_set, $jieqiConfigs['article'], $check_errors);
		if (empty($check_errors))
		{
			$this->db->init('replies ', 'postid', 'article');
			$v = $this->postnewset($params);
			$rid=$this->db->add($v);
			return $rid;
		}else{
			$this->printfail(implode('<br />', $check_errors));
		}
	}
	/**
	 * 查询用户的回复评论
	 * @param unknown $params
	 * @return multitype:unknown NULL
	 */
	public function showRepliesByUid($params)
	{
		$this->db->init ( 'replies', 'postid', 'article' );
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('r.istopic', 0, '='));
		$this->db->criteria->add(new Criteria('u.uid', $params['uid'], '='));
		$this->db->criteria->setTables($this->dbprefix('article_replies')." AS r LEFT JOIN ".$this->dbprefix('system_users')." AS u ON r.posterid=u.uid  LEFT JOIN  ".$this->dbprefix('article_reviews')." AS ar ON r.topicid=ar.topicid LEFT JOIN ".$this->dbprefix('article_article')." AS aa ON aa.articleid=ar.ownerid");
		$this->db->criteria->setFields("r.posttext,r.posttime,ar.posterid,ar.poster,ar.views,ar.replies,aa.articlename,aa.articleid");
		$this->db->criteria->setSort('r.postid');
		$this->db->criteria->setOrder('DESC');
		//获取参数
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$params['limit'] = $jieqiConfigs['article']['reviewnum'];
	
		$rid = $params['rid'];
		if ('isgood' == $params['display'])
		{
			$rid = 'good' + $rid;
		}
// 		$p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showReplies(this,\'{$prepage}\',1)" id="'.$rid.'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar]
// [pnumurl]<A href="javascript:;" onclick="return showReplies(this,\'{$pnumurl}\',1)" id="'.$rid.'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showReplies(this,\'{$nextpage}\',1)" id="'.$rid.'">下一页</a>[/nextpage]';
		$data = $this->db->lists($params['limit'], $params['page'],JIEQI_PAGE_TAG);
		$postcontext = 'posttext';
		//数据格式化
		foreach($data as $k=>$v){
		    //$v = $this->getFormat($v);
			$this->postvars($v, $params['article'], true, $postcontext);
			$data[$k] = $v;
		}
	
		//$arr = $this->getArticleById($params['aid']);
		$pageurl = $this->db->getPage($this->getUrl('article','userhub','evalpage=0','SYS=method=hotcomment'));
		return array(
				'reviewrows'=>$data,
				'url_jumppage'=>$pageurl
				//'title'=>$data[0]['title'],
// 				'totalcount'=>$this->db->getVar('totalcount')
				//'url_addreplies' =>
				//'article' => $arr
		);
	}
	//根据主题查询回复列表
	public function showReplies(&$params)
	{   

		$this->db->init ( 'replies', 'postid', 'article' );
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('r.topicid', $params['rid'], '='));
		$this->db->criteria->add(new Criteria('r.istopic', 0, '='));
		$this->db->criteria->setTables($this->dbprefix('article_replies')." AS r LEFT JOIN ".$this->dbprefix('system_users')." AS u ON r.posterid=u.uid  LEFT JOIN  ".$this->dbprefix('article_reviews')." AS ar ON r.topicid=ar.topicid");
		$this->db->criteria->setFields("r.*,u.isvip");
        $this->db->criteria->setSort('r.postid');
		$this->db->criteria->setOrder('DESC');
		
		//获取参数
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$this->addConfig('system','vipgrade');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$params['limit'] = $jieqiConfigs['article']['notenew'];

		$rid = intval($params['rid']);
		if ('isgood' == $params['display'])
		{
			$rid = 'good'.$rid;
		}
		$p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showReplies(this,\'{$prepage}\',1)" id="'.$rid.'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar]
[pnumurl]<A href="javascript:;" onclick="return showReplies(this,\'{$pnumurl}\',1)" id="'.$rid.'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showReplies(this,\'{$nextpage}\',1)" id="'.$rid.'">下一页</a>[/nextpage]';
		
		if (JIEQI_MODULE_NAME == 'wap'){
			$p = JIEQI_PAGE_TAG;
		}
		$data = $this->db->lists($params['limit'], $params['page'],$p);
		$postcontext = 'posttext';
		//数据格式化
		foreach($data as $k=>$v){
		    //$v = $this->getFormat($v,'s');
			$this->postvars($v, $params['article'], true, $postcontext);
			//$v['ding'] = $this->getUrl('article','reviews','method=dianzan','SYS=pid='.$v['postid'].'&action=dianzan');
			$vipgrade = jieqi_gethonorarray($v['isvip'], $jieqiVipgrade);
			//$v['vipgradeid'] = jieqi_gethonorid($v['isvip'], $jieqiVipgrade);
			$v['vipphoto'] = $vipgrade['photo'];//$jieqiVipgrade[$v['vipgradeid']]['photo'];
			$v['posttext'] = $this->shieldWord($v['posttext']);
			$data[$k] = $v;
		}

		if ('isgood' == $params['display'])
		{
			$pageurl = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME,'reviews','method=showReplies','evalpage=0','SYS=aid='.$params['aid'].'&rid='.$params['rid'].'&display=isgood'));
		}else{
			$pageurl = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME,'reviews','method=showReplies','evalpage=0','SYS=aid='.$params['aid'].'&rid='.$params['rid']));
		}
		$reviw = array();
		$article = array();
		if (JIEQI_MODULE_NAME == 'wap'){
			
			$reviw = $this->getReviwByrid($params['rid']);
			$this->postvars($reviw, $params['article'], true, $postcontext);
			$article = $this->getArticleById($params['aid']);
		}
		
	   
		return array(
		'replyrows'=>$data,
		'reviw'	=>$reviw,
		'article'=>$article,
		'replies_jumppage'=>$pageurl,
		'totalcount'=>$this->db->getVar('totalcount')
		);
	}
	
	//根据rid 获得评论
	public function getRevByRid($rid)
	{
		$this->db->init('reviews', 'topicid', 'article');
		return $this->db->get($rid);
	}
	
	public function getReviwByrid($rid)
	{
		$this->db->init ('replies', 'postid', 'article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('topicid', $rid, '='));
		$this->db->criteria->add(new Criteria('istopic', 1, '='));
		$this->db->queryObjects($this->db->criteria);
		$arr = array();
		while($v = $this->db->getObject()){
			$arr['postid'] = $v->getVar('postid');
			$arr['topicid'] = $v->getVar('topicid');
			$arr['ownerid'] = $v->getVar('ownerid');
			$arr['posttext'] = $v->getVar('posttext','n');
			$arr['posttime'] = $v->getVar('posttime');
			$arr['poster'] = $v->getVar('poster');
		}
		return $arr;
	}
	
	//统计查看数和回复数
	public function editViewsReplies($params,$pattern,$flag=true)
	{	
		$Review = $this->getRevByRid($params['rid']);
		switch($pattern)
		{	
			case  'views':
				if ($flag){
					$Review['views'] += 1;
				}
				else{
					$Review['views'] -= 1;
					if($Review['views'] < 0)
					{
						$Review['views'] = 0;
					}
				}
			break;
			case 'replies': 
				if ($flag){
					$Review['replies'] += 1;
				}
				else{
					$Review['replies'] -= 1;
					if($Review['replies'] < 0){
						$Review['replies'] = 0;
					}
				}
			break;
			default:
		}

		//修改点击数
		$this->db->edit($params['rid'], $Review);
		
	}
	
	//管理书评
	public function manageReview(&$params)
	{
		$this->db->init('reviews', 'topicid', 'article');
		$this->addConfig('article','configs');
		$this->addConfig('article','power');
		$this->addLang( 'article', 'review' );
		$jieqiLang['article'] = $this->getLang ( 'article' );
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$jieqiPower['power'] = $this->getConfig('article','power');
		//检查检查
		if($params['action']=='del'){
		    if(!$this->checkpower('manageallreview', $this->getUsersStatus(), $this->getUsersGroup(), true )){
		    	//@todo 2015-3-31 chengyuan
				//作者可以自己管理自己的书评
				$this->db->init('article','articleid','article');
				$article = $this->db->get($params['aid']);
				$auth = $this->getAuth();
				if($article ['authorid'] != $auth['uid']){
					$this->printfail($jieqiLang['article']['review_notper_delete']);
				}
			}
		}
// 		else{
// 		    if(!$this->canedit($params['aid'], true)){
// 		    	$this->printfail($jieqiLang['article']['review_notper_edit']);
// 		    } 
// 		}
		//$this->printfail($jieqiConfigs['article']['goodreviewpercent']);
		$data = array();
		$actreview = $this->getRevByRid($params['rid']);
		//处理置顶、置后、加精、去精、删除
		switch($params['action']){
			case 'top':
				$data['istop'] = 1;
				$this->db->edit($params['rid'], $data);
				break;
			case 'untop':
				$data['istop'] = 0;
				$this->db->edit($params['rid'], $data);
				break;
			case 'good':
				if($actreview['isgood']==0){
					
					//判断允许加精的比例
					$this->db->setCriteria();
					$this->db->criteria->add(new Criteria('ownerid', $params['aid'], '='));
					$allnum = $this->db->getCount($this->db->criteria);
					$this->db->criteria->add(new Criteria('isgood', 1, '='));
					$goodnum = $this->db->getCount($criteria);
					unset($this->db->criteria);
					$maxnum=ceil($allnum * $jieqiConfigs['article']['goodreviewpercent'] / 100);
					if($goodnum>=$maxnum)
					{
						$this->printfail(sprintf($jieqiLang['article']['review_rate_limit'], $jieqiConfigs['article']['goodreviewpercent']));
					}

					$actreview['isgood'] = 1;
					$this->db->edit($params['rid'],$actreview);
					//精华积分
					if(!empty($jieqiConfigs['article']['scoregoodreview'])){
						include_once(JIEQI_ROOT_PATH.'/class/users.php');
						$users_handler =&JieqiUsersHandler::getInstance('JieqiUsersHandler');
						$users_handler->changeScore($actreview['posterid'], $jieqiConfigs['article']['scoregoodreview'], true);
					}
				}
				break;
			case 'normal':
				if($actreview['isgood'] == 1){
					$actreview['isgood'] = 0;
					$this->db->edit($params['rid'],$actreview);
					//精华积分
					if(!empty($jieqiConfigs['article']['scoregoodreview'])){
						include_once(JIEQI_ROOT_PATH.'/class/users.php');
						$users_handler =&JieqiUsersHandler::getInstance('JieqiUsersHandler');
						$users_handler->changeScore($actreview['posterid'], $jieqiConfigs['article']['scoregoodreview'], false);
					}
				}
				break;
			case 'del':
				$this->delReview($params['rid'],$params['isgood']);
				break;	
			case 'mute'://禁言
				$this->db->init('article','articleid','article');
				$article = $this->db->get($params['aid']);
				$muteService = $this->model('mute','article');//jieqi_article_mute业务模型
				$muteService->mute(
						array("userid"=>$params['posterid'],"username"=>$params['poster']),
						array('articleid'=>$params['aid'],'articlename'=>$article['articlename']),
								$params['rid'],
								$params['area'],$params['mt']);
				break;
			default:
			    $this->printfail(LANG_ERROR_PARAMETER);
				break;
		 }
		 //$rul =  $this->getUrl('article','reviews','SYS=aid='.$params['aid'].'&rid='.$params['rid'].'&display='.$params['display']);
		 //$this->jumppage($rul,LANG_DO_SUCCESS, $jieqiLang['article']['review_edit_success']);
		
	}
	/**
	 * 删除topicid评论和回复，并且减去评论积分
	 * @param unknown $topicid
	 * @param number $isgood	是否精华评论
	 */
	public function delReview($topicid,$isgood=0)
	{
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		//foreach($params['rid'] as $k=>$v)
		//{
			$this->db->init('reviews', 'topicid', 'article');
			$this->db->setCriteria();
			$this->db->criteria->add(new Criteria('topicid', $topicid, '='));
			//删除书评减少积分
			if(!empty($jieqiConfigs['article']['scorereview'])){
				$this->db->queryObjects();
				$posterary=array();
				while($replyobj = $this->db->getObject()){
					$posterid = intval($replyobj->getVar('posterid'));
					if(isset($posterary[$posterid])) 
					{
						$posterary[$posterid] += $jieqiConfigs['article']['scorereview'];
					}
					else 
					{
						$posterary[$posterid] = $jieqiConfigs['article']['scorereview'];
					} 
				}
				
				if($isgood == 1 && !empty($jieqiConfigs['article']['scoregoodreview']))
				{
					$posterid = intval($actreview['posterid']);
					if(isset($posterary[$posterid])) $posterary[$posterid] += $jieqiConfigs['article']['scoregoodreview'];
					else  $posterary[$posterid] = $jieqiConfigs['article']['scoregoodreview'];
				}
				
				$users_handler =$this->getUserObject();
				foreach($posterary as $pid=>$pscore){
				    //$this->printfail($pid);
					$users_handler->changeScore($pid, $pscore, false);
				}
			}
			
			//删除评论
			$this->db->delete($topicid);
			//删除该评论的所以回复
			$this->db->init('replies', 'postid', 'article');
			$this->db->setCriteria();
			$this->db->criteria->add(new Criteria('topicid', $topicid, '='));
			$this->db->delete($this->db->criteria);
		//}	
	}
	
	//查询书评
	public function queryReviews($params)
	{
		$muteService = $this->model('mute','article');
		$this->db->init ( 'replies', 'postid', 'article' );
		$this->db->setCriteria();
		$sqlStr = $this->dbprefix('article_replies')." AS r INNER JOIN ".$this->dbprefix('article_reviews')." AS ar ON  r.topicid = ar.topicid"." INNER JOIN ".$this->dbprefix('article_article')." AS a ON  r.ownerid = a.articleid LEFT JOIN  ".$this->dbprefix('system_users')." AS u  ON  r.posterid = u.uid ";
		$this->db->criteria->setTables($sqlStr);
		$this->db->criteria->add(new Criteria('r.istopic',1, '='));
		$this->db->criteria->setFields("ar.*,r.posttext,a.articlename,a.articleid,a.authorid,u.avatar,u.score,u.isvip");
		$this->db->criteria->setSort('ar.istop DESC, ar.replytime');
		
		//state = 0 禁言中 articleid in (0,a.articleid)
		
		
		//$this->db->criteria->setTables($this->dbprefix('article_reviews')." AS r LEFT JOIN ".$this->dbprefix('article_article')." AS a ON r.ownerid=a.articleid ");
		//$this->db->criteria->setFields("r.*,a.articleid,a.articlename");
		//$this->db->criteria->setSort('r.topicid');
		$this->db->criteria->setOrder('DESC');
		//aid 有值查询该书的全部评论
		//aid 为空时查询全部评论
		if(!empty($params['aid']))
		{	
			$this->db->criteria->add(new Criteria('ar.ownerid',$params['aid'],'='));
		}
		
		if(!empty($params['uid']))
		{	
			$this->db->criteria->add(new Criteria('ar.posterid',$params['uid'],'='));
		}
		
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
			
		if (empty($params['limit']))
		{
			$params['limit'] = $jieqiConfigs['article']['newreviewnum'];
		}

		//查询精华评论
		if(!empty($params['display'])){
		 switch ($params['display']){
			case 'isgood':
				$this->db->criteria->add(new Criteria('ar.isgood',1, '='));
				break;
			default:
				$params['display']='new';
				break;
		   }
		 }
		 
		if ($params['page'] == 0)
		{
			$params['page'] = 1;
		}
		if($params['flag'] && $params['flag'] == 1){
			$params['limit'] = $params['limit']*$params['page'];
			$data = $this->db->lists($params['limit'],1,JIEQI_PAGE_TAG);
		}else{
			//查询
			$data = $this->db->lists($params['limit'],$params['page'],JIEQI_PAGE_TAG);
		}
		
// 		echo $this->db->returnSql($this->db->criteria);
		
		//数据格式化
		$postcontext = 'posttext';
		global $jieqiHonors;
		global $jieqiModules;
		$this->addConfig('system','vipgrade');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$this->addConfig('article','power');
		$jieqiPower['article'] = $this->getConfig('article', 'power');
		foreach($data as $k=>$v){
			//数据格式化
			//$v = $this->getFormat($v);
		    $this->postvars($v, $params['article'], true,$postcontext);
			//$v['url_img'] = 
			$v['url_showreview'] = $this->getUrl(JIEQI_MODULE_NAME,'reviews','method=showReplies','SYS=method=showReplies&aid='.$params['aid'].'&rid='.$v['topicid']."&display=".$params['display']);
			$v['url_top'] = $this->getUrl(JIEQI_MODULE_NAME,'reviews','SYS=method=manageReview&aid='.$params['aid'].'&action=top&rid='.$v['topicid']."&display=".$params['display']."&page={$params['page']}&flag=1");
			$v['url_untop'] = $this->getUrl(JIEQI_MODULE_NAME,'reviews','SYS=method=manageReview&aid='.$params['aid'].'&action=untop&rid='.$v['topicid']."&display=".$params['display']."&page={$params['page']}&flag=1");
			$v['url_good'] = $this->getUrl(JIEQI_MODULE_NAME,'reviews','SYS=method=manageReview&aid='.$params['aid'].'&action=good&rid='.$v['topicid']."&display=".$params['display']."&page={$params['page']}&flag=1");
			$v['url_normal'] = $this->getUrl(JIEQI_MODULE_NAME,'reviews','SYS=method=manageReview&aid='.$params['aid'].'&action=normal&rid='.$v['topicid']."&display=".$params['display']."&page={$params['page']}&flag=1");
			$v['url_del'] = $this->getUrl(JIEQI_MODULE_NAME,'reviews','SYS=method=manageReview&aid='.$params['aid'].'&action=del&rid='.$v['topicid']."&display=".$params['display']."&page={$params['page']}&flag=1");
			//发表评论的用户有没有禁言，单本禁言|全站禁言
			if($muteService->userState($v['posterid'],$params['aid'])){
				$v['url_mute'] = "";//禁言
			}else{
				$v['url_mute'] =$this->getUrl(JIEQI_MODULE_NAME,'reviews','SYS=method=manageReview&aid='.$params['aid'].'&action=mute&rid='.$v['topicid']."&display=".$params['display']."&posterid={$v['posterid']}&poster={$v['poster']}&page={$params['page']}&flag=1");
			}
			$v['honorid'] = jieqi_gethonorid($v['score'], $jieqiHonors);
			$v['honorphoto'] = $jieqiHonors[$v['honorid']]['photo'];
			$v['vipgradeid'] = jieqi_gethonorid($v['isvip'], $jieqiVipgrade);
			$v['vipphoto'] = $jieqiVipgrade[$v['vipgradeid']]['photo'];
			$v['honor'] = $jieqiHonors[$v['honorid']]['caption'];
			$v['vipgrade'] = $jieqiVipgrade[$v['vipgradeid']]['caption'];
			$v['posttext'] = $this->shieldWord($v['posttext']);
			//$v['client'] = $jieqiModules[JIEQI_MODULE_NAME]['caption'];
			$data[$k] = $v;
		}
		$USER = $this->getAuth();
		$power = false;
		$canedit = $this->checkpower($jieqiPower['article']['manageallreview'], $this->getUsersStatus(), $this->getUsersGroup(), true );
		if ($canedit) 
		{
			$power = true;
		}
		//2015-6-10 add chengyuan 登陆用户是否是版主
		if(!$power){
			$moderatorService = $this->load('moderator','article');
			$power = $moderatorService->isModerator($params['aid'],$USER['uid']);
		}
		//2015-5-11 add chengyuan
		$totalPage = $this->db->jumppage->getVar('totalpage');//总页数
		$has_next_page = 1;
		if($totalPage == $params['page']){
			$has_next_page = 0;
		}
		return array(
			'mute' => $muteService->getAuthMuteState($params['aid']),
			'reviewrows' => $data,
			'has_next_page' => $has_next_page,
			'url_jumppage' => $this->db->getPage(),
			'count' => $this->db->getVar('totalcount'),
			'url_loadreview' => $this->getUrl('article', 'reviews', 'SYS=aid=' . $params['aid'] . '&page=' . $ipage),
			'display' => $params['display'],
			'page' => $params['page'],
			'pagecount' => $totalPage,
			'power' => $power,
            'url_3g_next' => $this->getUrl('3g', 'reviews', 'SYS=aid=' . $params['aid'] . '&page=' . ($params['page']+1)),
            'url_3g_prev' => $this->getUrl('3g', 'reviews', 'SYS=aid=' . $params['aid'] . '&page=' . ($params['page']-1))
		);
	}
	/**
	 * <p>
	 * 中文按照2个长度匹配
	 * @author chengyuan 2015-5-14 上午10:17:27
	 */
	private function shieldWord($text){
// 		$text = $text."一二分隔符三四五分隔符六七八九分一一一一符一二三四五六七八九@#$@yiersansi任何人yiersan还有eeeeeeerQQ号：siyibawusiliu再补充一个：1 2 3 4 5 6<br>";
		$msg = preg_replace(array("/[一二三四五六七八九零]{8,}/is","/(yi|er|san|si|wu|liu|qi|ba|jiu|ling){4,}/is","/((\d)|(\d[\s]+)){4,}/is"),array("****","****","****"), $text);
		return $msg;
	}
	//根据aid 获得书的信息
	public function getArticleById($aid)
	{
		//获得书名字
		$this->db->init('article','articleid','article');
		$v = $this->db->get($aid);
		$this->addConfig('article','sort');
		$jieqiSort = $this->getconfig('article', 'sort');
		$v['sort'] = $jieqiSort[$v['sortid']]['caption'];
		$v['class'] = $jieqiSort[$v['sortid']]['class'];
		return $v;
	}
	
	//检查评论权限
	/**
	 * 获取文章的修改权限
	 * 
	 * @param $aid articleid        	
	 * @param $powerGroups 权限数组        	
	 * @return boolean
	 */
	public function canedit($aid, $powerGroups) {
		if (empty ( $aid ) || empty ( $powerGroups ))
		{
			return false;
		}
			
		$canedit = $this->checkpower ( $powerGroups, $this->getUsersStatus (), $this->getUsersGroup (), true );
		if (! $canedit && ! empty ( $_SESSION ['jieqiUserId'] )) {
			// //除了斑竹，作者、发表者和代理人可以管理书评
			$this->db->init ( 'article', 'articleid', 'article' );
			$article = $this->db->get ( $aid );
			$tmpvar = $_SESSION ['jieqiUserId'];
			if ($tmpvar > 0 && ($article ['authorid'] == $tmpvar || $article ['agentid'] == $tmpvar)) {
				$canedit = true;
			}
		}
		return $canedit;
	}
	
	/**
 * 发帖提交后的内容校验
 * 
 * $post_set 相关参数：
 * 'module' - 程序所属模块名
 * 'ownerid' - 论坛或所有者ID
 * 'topicid' - 主题ID
 * 'postid' - 帖子ID
 * 'posttime' - 发帖时间
 * 'title' - 标题在$_POST里面的键名
 * 'content' - 内容在$_POST里面的键名
 * 'checkcode' - 验证码
 * 'attachment' - 附件信息，附件数组serialize后的字符串
 * 'emptytitle' - bool类型，主题贴是都允许不填主题，false-不允许，true-允许
 * 'isnew' - bool类型，true表示发新帖，false表示编辑帖子
 * 'istopic' - bool类型，true表示主题帖子，false表示回复帖子
 * 'istop' - bool类型，是否全区置顶帖子
 * 'sname' - string类型，发帖时间在session里面保存的变量名
 * 'attachfile' - array类型，附件上传信息变量
 * 'oldattach' - array类型，老的附件是否保留信息
 * 
 * $configs 相关参数：
 * 'minposttime' - int类型，发帖最少时间间隔，单位是秒
 * 'badpostwords' - string类型，禁止发表的词语，每个一行
 * 'checkpostrubbish' - bool类型，是否检查灌水贴
 * 'minpostsize' - int类型，帖子内容最少几个字节
 * 'maxpostsize' - int类型，帖子内容最多几个字节
 * 'hidepostwords' - string类型，发表后隐藏的词语，每个一行
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      array       $configs 检查相关参数设置
 * @param      array       $check_errors 错误信息数组
 * @access     public
 * @return     bool
 */
function postcheckvar($post_set, $configs, &$check_errors){

	global $jieqiLang;
	global $jieqiConfigs;
	$this->addLang('system', 'post');
	$this->addConfig('system', 'configs');
	$jieqiLang['system'] = $this->getLang('system');
	$jieqiConfigs['system'] = $this->getConfig('system','configs');
	if(!is_array($check_errors)) $check_errors = array();
	$num_errors = count($check_errors);
	include_once(JIEQI_ROOT_PATH.'/include/checker.php');
	$checker = new JieqiChecker();
	//提交处理
	/*if(isset($jieqiConfigs['system']['posttitlemax'])) $jieqiConfigs['system']['posttitlemax']= intval($jieqiConfigs['system']['posttitlemax']);
	if(empty($jieqiConfigs['system']['posttitlemax']) || $jieqiConfigs['system']['posttitlemax'] <= 10) $jieqiConfigs['system']['posttitlemax']=60;
	$post_set['topictitle'] = jieqi_substr(trim($post_set['topictitle']),0,$jieqiConfigs['system']['posttitlemax'],'...');*/
	//检查时间，是否允许发贴
/*	if(!empty($jieqiConfigs['system']['postintervaltime']) && !empty($post_set['isnew'])){
	    $ckk=$checker->interval_time($jieqiConfigs['system']['postintervaltime'], $post_set['sname'], 'jieqiVisitTime');//$this->printfail($ckk.'jj');
        if(!$ckk) $check_errors[] = sprintf($jieqiLang['system']['post_time_limit'], $jieqiConfigs['system']['postintervaltime']);
	}*/
	
	//检查禁用单词
	if(!empty($jieqiConfigs['system']['postdenywords'])){
		//$matchwords1 = $checker->deny_words($post_set['topictitle'], $jieqiConfigs['system']['postdenywords'], true);
		$matchwords2 = $checker->deny_words($post_set['posttext'], $jieqiConfigs['system']['postdenywords'], true);
		if(is_array($matchwords2)){
			$matchwords=array();
			//if(is_array($matchwords1)) $matchwords = array_merge($matchwords, $matchwords1);
			if(is_array($matchwords2)) $matchwords = array_merge($matchwords, $matchwords2);
			$check_errors[] = sprintf($jieqiLang['system']['post_words_deny'], implode(' ', jieqi_funtoarray('htmlspecialchars',$matchwords)));
		}
	}
	//检查灌水
	if(!empty($jieqiConfigs['system']['postdenyrubbish'])){
		if(!$checker->deny_rubbish($post_set['posttext'],$jieqiConfigs['system']['postdenyrubbish'])) $check_errors[] = $jieqiLang['system']['post_words_water'];
	}
	//检查标题
	/*if(!empty($post_set['istopic']) && $checker->is_required($post_set['topictitle'])==false){
		if($post_set['emptytitle']){
			$post_set['topictitle'] = jieqi_substr(str_replace(array("\r","\n","\t"," "),'',preg_replace('/\[[^\[\]]+\]([^\[\]]*)\[\/[^\[\]]+\]/isU','\\1',$post_set['posttext'])),0,60);
			if(strlen($post_set['emptytitle'])==0) $post_set['emptytitle']='--';
		}else{
			$check_errors[] = $jieqiLang['system']['post_need_title'];
		}
	}*/

	/*//检查内容
	if(!$checker->is_required($post_set['posttext'])) $check_errors[] = $jieqiLang['system']['post_need_content'];
	//检查最少字数
	if(!empty($jieqiConfigs['system']['postminsize']) && !$checker->str_min($post_set['posttext'], $jieqiConfigs['system']['postminsize'])) $check_errors[] = sprintf($jieqiLang['system']['post_min_content'], $jieqiConfigs['system']['postminsize']);
	//检查最多字数
	if(!empty($jieqiConfigs['system']['postmaxsize']) && !$checker->str_max($post_set['posttext'], $jieqiConfigs['system']['postmaxsize'])) $check_errors[] = sprintf($jieqiLang['system']['post_max_content'], $jieqiConfigs['system']['postmaxsize']);*/
	//替换单词

	if(isset($jieqiConfigs['system']['postreplacewords']) && !empty($jieqiConfigs['system']['postreplacewords'])){
		$checker->replace_words($post_set['topictitle'], $jieqiConfigs['system']['postreplacewords']);
		$checker->replace_words($post_set['posttext'], $jieqiConfigs['system']['postreplacewords']);
	}
	//return (count($check_errors) > $num_errors) ? false : true;
}

/**
 * 增加帖子
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      object      $newPost 帖子实例
 * @access     public
 * @return     void
 */
function topicnewset(&$params){
    global $jieqiModules;
	$newTopic = array();
	$USER = $this->getAuth();
	if($params['client']=='yes'){
		$newTopic['siteid'] = $params['siteid'];
	}else{
		if(array_key_exists(JIEQI_MODULE_NAME,$jieqiModules)){
			$newTopic['siteid'] = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];	
		}else{
			$newTopic['siteid'] = $jieqiModules['system']['siteid'];
		}
	}
	//$newPost['istopic']= isset($params['istopic']) ? $params['istopic'] : 0;
	//$newPost['replypid']= 0;
	$newTopic['ownerid']= $params['aid'];
	if($params['client']=='yes'){
		$newTopic['posterid'] = $params['uid'];
		$newTopic['poster'] = $params['username'];
	}else{
		$newTopic['posterid']= $USER['uid'];
		$newTopic['poster']= $USER['username'];
	}
	$newTopic['posttime']= JIEQI_NOW_TIME;
	$newTopic['size']= strlen($params['pcontent']);
	if($params['client']=='yes'){
		$newTopic['title'] = $params['theme'];
	}else{
		$newTopic['title']=  $this->subStr($params['pcontent'],0,56);
	}
	$newTopic['title']=  $this->subStr($params['pcontent'],0,56);
	$newTopic['isgood']= 0;
	$newTopic['islock']= 0;
	$newTopic['views']= 0;
	$newTopic['sortid']= $params['sortid'] ? $params['sortid']: 0;
	$newTopic['posttime'] = JIEQI_NOW_TIME;
	$newTopic['replierid'] = 0;
	if($params['client']=='yes'){
		$newTopic['replier'] = $params['username'];
	}else{
		$newTopic['replier'] = $USER['username'];
	}
	$newTopic['replytime'] = JIEQI_NOW_TIME;
	$newTopic['replies'] = 0;
	$newTopic['istop'] =  0;
	$newTopic['rate'] = 0;
	$newTopic['attachment'] = 0;
	$newTopic['needperm'] = 0;
	$newTopic['needscore'] = 0;
	$newTopic['needexp'] = 0;
	$newTopic['needprice'] = 0;
	//$newTopic['sortid'] = 0;
	$newTopic['iconid'] = 0;
	$newTopic['typeid'] = 0;
	$newTopic['linkurl']= '';
	if($params['client']=='yes'){
		$lastinfo=serialize(array('time'=>JIEQI_NOW_TIME, 'uid'=>$params['uid'], 'uname'=>$params['username']));
	}else{
		$lastinfo=serialize(array('time'=>JIEQI_NOW_TIME, 'uid'=>$USER['uid'], 'uname'=>$USER['username']));
	}
	$newTopic['lastinfo'] = $lastinfo;
	
	return $newTopic;
}

/**
 * 回复帖子
 * 
 * @param      array       $post_set 发帖信息设置
 * @param      object      $newPost 帖子实例
 * @access     public
 * @return     void
 */

function postnewset(&$params){
	global $jieqiModules;
	if (!empty($params['rcontent']))
	{
		$params['pcontent'] = $params['rcontent'];
	}
	$newPost = array();
	//获取登陆者的信息
	$USER = $this->getAuth();
	if($params['client']=='yes'){
		$newPost['siteid'] = $params['siteid'];
	}else{
		
		if(array_key_exists(JIEQI_MODULE_NAME,$jieqiModules)){
			$newPost['siteid'] = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];	
		}else{
			$newPost['siteid'] = $jieqiModules['system']['siteid'];
		}
	}
	$newPost['topicid'] = $params['rid'];
	$istopic = isset($params['istopic']) ? $params['istopic'] : 0;
	$newPost['istopic'] = $istopic;
	$newPost['replypid'] = 0;
	$newPost['ownerid'] = $params['aid'];
	if($params['client']=='yes'){
		$newPost['posterid'] = $params['uid'];
		$newPost['poster'] = $params['username'];
	}else{
		$newPost['posterid'] = $USER['uid'];
		$newPost['poster'] = $USER['username'];
	}
	$newPost['posttime'] = JIEQI_NOW_TIME;
	if($params['client']=='yes'){
		$newPost['posterip'] = $params['ip'];
	}else{
		$newPost['posterip'] =  $this->getIp();
	}
	$newPost['editorid'] = 0;
	$newPost['editor'] =  '';
	$newPost['edittime'] = JIEQI_NOW_TIME;
	$newPost['editorip'] =  '';
	$newPost['editnote'] =  '';
	$newPost['iconid'] = 0;
	$newPost['attachment'] = $params['attachment'];
	if($params['client']=='yes'){
		$newPost['subject'] = $params['theme'];
	}else{
		$newPost['subject'] = $this->subStr($params['pcontent'],0,56);
	}
	$newPost['posttext'] = $params['pcontent'];
	$newPost['size'] = strlen($post_set['rcontent']);
	return $newPost;
}

	/**
 * 传入帖子实例对象，返回适合模板赋值的帖子信息数组
 * 
 * @param      object      $post 帖子实例
 * @param      array       $configs 配置参数
 * @param      array       $addvars 附加赋值变量
 * @param      bool        $enableubb 是否允许解析UBB代码
 * @access     public
 * @return     array
 */
function postvars(&$post, $configs=array(), $enableubb=true, $title = 'posttext'){

	global $jieqiTxtcvt;
	global $jieqiHonors;
	global $jieqiGroups;
	global $jieqiModules;
	$this->addConfig('system','configs');
	if(!isset($jieqiHonors)) jieqi_getconfigs('system', 'honors', 'jieqiHonors');
	if(!defined('JIEQI_SHOW_BADGE')){
		if(!empty($jieqiModules['badge']['publish']) && is_file($GLOBALS['jieqiModules']['badge']['path'].'/include/badgefunction.php')){
			include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
			define('JIEQI_SHOW_BADGE', 1);
		}else{
			define('JIEQI_SHOW_BADGE', 0);
		}
	}
    //$post = $this->getFormat($post);
	$post['subject'] = $this->subStr($post['subject'],0,56);
	$post['attachimages']=array();
	$post['attachfiles']=array();
	

	if($enableubb){
		if(!is_object($jieqiTxtcvt))
		{
			include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
			$jieqiTxtcvt=TextConvert::getInstance('TextConvert');
		}

		$post[$title]=$jieqiTxtcvt->displayTarea($post[$title], 0, 1, 1, 1, 1, 'screen.width*0.75');
	}

	//本贴用户信息
	if($post['userid'] > 0){

		$post['groupname']=$jieqiGroups[$post['groupid']];
		$honorid=intval(jieqi_gethonorid($post['score'], $jieqiHonors));
		$post['honor']=isset($jieqiHonors[$honorid]['name'][intval($post['workid'])]) ? $jieqiHonors[$honorid]['name'][intval($post['workid'])] : $jieqiHonors[$honorid]['caption'];

		//头像图片
		if($post['avatar'] > 0){
			$tmpary = jieqi_geturl('system', 'avatar', $post['userid'], 'a', $post['avatar']);
			$ret['base_avatar'] = $tmpary['d'];
			$ret['url_avatar'] = $tmpary['l'];
			$ret['url_avatars'] = $tmpary['s'];
			$ret['url_avatari'] = $tmpary['i'];
		}
		//徽章图片
		if(JIEQI_SHOW_BADGE == 1){
			$checkfile = (JIEQI_LOCAL_URL == JIEQI_MAIN_URL) ? true : false;
			$checkfile = false;
			//等级徽章
			$post['groupurl']=getbadgeurl(1, $post['groupid'], 0, $checkfile);
			//头衔徽章
			$post['honorurl']=getbadgeurl(2, $honorid, 0, $checkfile);
			//自定义徽章
			$badgeary=unserialize($post['badges']);
			$post['badgerows']=array();
			if(is_array($badgeary)){
				$m=0;
				foreach($badgeary as $badge){
					$post['badgerows'][$m]['imageurl']=getbadgeurl($badge['btypeid'], $badge['linkid'], $badge['imagetype']);
					$post['badgerows'][$m]['caption']=jieqi_htmlstr($badge['caption']);
					$m++;
				}
			}
		}
	}
	//return $ret;
}

}
?>