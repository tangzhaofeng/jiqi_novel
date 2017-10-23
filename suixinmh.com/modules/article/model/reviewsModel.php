<?php 
/**
 * Review模型
 * @author 刘吉雷  2014-4-11
 *
 */
class reviewsModel extends Model{

	//查询书评
	public function main($params)
	{
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$this->addConfig('article', 'power');
		$jieqiPower['article'] = $this->getConfig('article','power');
		$canedit = $this->checkpower($jieqiPower['article']['manageallreview'], $this->getUsersStatus(), $this->getUsersGroup(), true );
		$reviewnewLib = $this->load ( 'reviews', 'article');
		$params['limit']=$jieqiConfigs['article']['newreviewnum'];
		$params['ispage'] = false;
		$data = $reviewnewLib->queryReviews($params);
		$data['limit'] = $params['limit'];
		$data['ismaster'] =  $canedit ? 1:0;;
		$data['article']= $reviewnewLib->getArticleById($params['aid']); 
		//全部评论
		$data['url_allreview'] = $this->getUrl('article','reviews','SYS=aid='.$params['aid']."&display=all");
		//精华评论
		$data['url_goodreview'] = $this->getUrl('article','reviews','SYS=aid='.$params['aid']."&display=isgood");
		
		return $data;
	}
	
	//展示回复列表
	public function showReplies($params)
	{	
		$this->addConfig('system','configs');
		$this->addConfig('article','configs');
		$this->addConfig('article', 'power');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$data['postcheckcode'] = $jieqiConfigs['system']['postcheckcode'];
		$jieqiPower['article'] = $this->getConfig('article','power');
		$canedit = $this->checkpower($jieqiPower['article']['manageallreview'], $this->getUsersStatus(), $this->getUsersGroup(), true );
		$params['ismaster'] = $canedit ? 1:0;
		$reviewnewLib = $this->load ( 'reviews',  'article' );
		$params['limit'] = $jieqiConfigs['article']['reviewnew'];
		$data = $reviewnewLib->showReplies($params);
//		$this->dump($data);
		$data['ismaster'] = $canedit ? 1:0;
		$data['rid'] =$params['rid'];
		$data['enablepost'] = 1;//显示发表评论区域
		if ('isgood' == $params['display'])
		{
			$data['url_addreplies'] = $this->getUrl(JIEQI_MODULE_NAME,'reviews','SYS=method=addReplies&aid='.$params['aid']."&rid=".$params['rid']."&display=".$params['display']);
			$data['url_showreplies'] = $this->getUrl(JIEQI_MODULE_NAME,'reviews','method=showReplies','SYS=aid='.$params['aid'].'&rid='.$params['rid']."&display=".$params['display']);
			$data['isgood'] = "good";
		}else{
			$data['url_addreplies'] = $this->getUrl(JIEQI_MODULE_NAME,'reviews','SYS=method=addReplies&aid='.$params['aid']."&rid=".$params['rid']);
			$data['url_showreplies'] = $this->getUrl(JIEQI_MODULE_NAME,'reviews','method=showReplies','SYS=aid='.$params['aid'].'&rid='.$params['rid']);
		}
		//更新点击数
		$reviewnewLib->editViewsReplies($params, 'views');
		return $data;
	}
	
	//添加回复
	public function addReplies($params = array())
	{	
		$this->addConfig('article', 'power');
		$this->addConfig('article', 'configs');
		$this->addLang('article', 'review');
		$jieqiLang['article'] = $this->getLang('article');
		$jieqiPower['power'] = $this->getConfig('article','power');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$canedit = $this->checkpower($jieqiPower['power']['newreview'], $this->getUsersStatus(), $this->getUsersGroup(), true );
        if(!$params['rcontent']) $this->printfail(sprintf($jieqiLang['article']['review_minsize_limit'],$jieqiConfigs['article']['minreviewsize']));
		if ($canedit){
			//载入工具类	
			$USER = $this->getAuth();
			//检查时间，是否允许回贴
			if(!empty($jieqiConfigs['article']['reviewneedscore']) && $USER ['score']<intval($jieqiConfigs['article']['reviewneedscore'])) $this->printfail(sprintf($jieqiLang['article']['review_score_limit'], intval($jieqiConfigs['article']['reviewneedscore'])));
			if(!empty($jieqiConfigs['article']['minreplytime'])){
				include_once(JIEQI_ROOT_PATH.'/include/checker.php');
				$checker = new JieqiChecker();
				$ckk=$checker->interval_time($jieqiConfigs['article']['minreplytime'], 'jieqiArticleReplyTime', 'jieqiVisitTime1');
				if(!$ckk) $this->printfail(sprintf($jieqiLang['article']['review_time_limit'], $jieqiConfigs['article']['minreplytime']));
			}
			//检查发表书评需要积分
			if(!empty($jieqiConfigs['article']['reviewneedscore']) && $USER ['score']<intval($jieqiConfigs['article']['reviewneedscore'])) $this->printfail(sprintf($jieqiLang['article']['review_score_limit'], intval($jieqiConfigs['article']['reviewneedscore'])));
			//判断最小长度
			if($jieqiConfigs['article']['minreviewsize']){
				if(strlen($params['rcontent']) < $jieqiConfigs['article']['minreviewsize']){
					$this->printfail(sprintf($jieqiLang['article']['review_minsize_limit'],$jieqiConfigs['article']['minreviewsize']));
				} 
			}
			if($jieqiConfigs['article']['maxreviewsize']){
				if($jieqiConfigs['article']['maxreviewsize'] && strlen($params['rcontent'])>$jieqiConfigs['article']['maxreviewsize']){
					$this->printfail(sprintf($jieqiLang['article']['review_maxsize_limit'],$jieqiConfigs['article']['maxreviewsize']));
				} 
			}
			$this->db->init('replies ', 'postid', 'article');
			$reviewnewLib = $this->load( 'reviews', 'article' );
			$rid = $reviewnewLib->addReply($params);
			if (!empty($rid)){	
				if(!empty($jieqiConfigs['article']['scorereply'])){
					$users_handler = $this->getUserObject();
					$users_handler->changeScore($USER['uid'], $jieqiConfigs['article']['scorereply'], true);
				}
				//添加回复数
				$reviewnewLib->editViewsReplies($params, 'replies');
				$rul =  $this->getUrl(JIEQI_MODULE_NAME,'reviews','method=showReplies','SYS=aid='.$params['aid'].'&rid='.$params['rid']);
			    $this->jumppage($rul,LANG_DO_SUCCESS, $jieqiLang['article']['review_add_success']);
			}else $this->printfail();
		}else{
			$this->printfail($jieqiLang['article']['review_noper_post']);
	   }
	}

	//all:全部 newest:最新 elite:精华
	//发帖
	public function add(&$params)
	{
		//2015-5-19 ad chengyuan
		//bookinfo模板有缓存时间，这里做是否禁言的判断
		$muteService = $this->model('mute','article');
		if($muteService->getAuthMuteState($params['aid'])){
			$this->printfail("您已被本书版主禁言，暂时不能发表评论！");
		}
		
		
		//载入工具类
		//header('Content-Type:text/html;charset=gbk');
		$this->addConfig('article', 'power');
		$this->addConfig('article', 'configs');
		$this->addLang('article', 'review');
		$this->addLang('system', 'users');
		$jieqiPower['power'] = $this->getConfig('article','power');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$jieqiLang['article'] = $this->getLang('article');
		$jieqiLang['system'] = $this->getLang('system');
		$canedit = $this->checkpower($jieqiPower['power']['newreview'], $this->getUsersStatus(), $this->getUsersGroup(), true );
		$manageallreview = $this->checkpower($jieqiPower['power']['manageallreview'], $this->getUsersStatus(), $this->getUsersGroup(), true );
		$params['ismaster'] = $manageallreview ? 1:0;
		$contentFlag = !empty($params['pcontent']);
		if(!$params['checkflag'] && (strtolower($params['checkcode']) != $_SESSION['jieqiCheckCode'])) {
			$this->printfail($jieqiLang['system']['error_checkcode']);
		}
		//检查时间，是否允许发贴
		if(!empty($jieqiConfigs['article']['minreviewtime'])){
			include_once(JIEQI_ROOT_PATH.'/include/checker.php');
			$checker = new JieqiChecker();
			$ckk=$checker->interval_time($jieqiConfigs['article']['minreviewtime'], 'jieqiArticleReviewTime', 'jieqiVisitTime');
			if(!$ckk) $this->printfail(sprintf($jieqiLang['article']['review_time_limit'], $jieqiConfigs['article']['minreviewtime']));
		}
		$USER = $this->getAuth();
	    //检查发表书评需要积分
		if(!empty($jieqiConfigs['article']['reviewneedscore']) && $USER ['score']<intval($jieqiConfigs['article']['reviewneedscore'])) $this->printfail(sprintf($jieqiLang['article']['review_score_limit'], intval($jieqiConfigs['article']['reviewneedscore'])));
		//判断最小长度
		if($jieqiConfigs['article']['minreviewsize']){
			if(!$contentFlag || strlen($params['pcontent']) < $jieqiConfigs['article']['minreviewsize']){
				$this->printfail(sprintf($jieqiLang['article']['review_minsize_limit'],$jieqiConfigs['article']['minreviewsize']));
			} 
		}
		if($jieqiConfigs['article']['maxreviewsize']){
			if($jieqiConfigs['article']['maxreviewsize'] && strlen($params['pcontent'])>$jieqiConfigs['article']['maxreviewsize']){
				$this->printfail(sprintf($jieqiLang['article']['review_maxsize_limit'],$jieqiConfigs['article']['maxreviewsize']));
			} 
		}
		if ($canedit && $contentFlag)
		{
			$reviewnewLib = $this->load( 'reviews',  'article' );	
		    $rid = $reviewnewLib->addReview($params);
			if(!empty($jieqiConfigs['article']['scorereview']) && !empty($rid)){
			$users_handler = $this->getUserObject();
			$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scorereview'], true);
		    }
			if (!empty($rid)){	
				$contr = JIEQI_MODULE_NAME == 'wap'?'articleinfo':'reviews';
				$rul = $this->getUrl(JIEQI_MODULE_NAME,$contr,'SYS=aid='.$params['aid']);
				//print_r($rul);exit;
				$this->jumppage($rul,LANG_DO_SUCCESS, $jieqiLang['article']['review_add_success']);
			}else{
			 $this->printfail($jieqiLang['article']['review_add_error']);
			}
		}else{
			$this->printfail($jieqiLang['article']['review_noper_post']);
		}

		return $rid;
	}
	
	//根据rid 获得评论
	public function getRevByRid($rid)
	{
		$this->db->init('reviews', 'topicid', 'article');
		return $this->db->get($rid);
	}
	
	//根据回复ID 查询回复
	public function editReply($params)
	{
		$this->db->init('replies ', 'postid', 'article');
		$data =$this->db->get($params['pid']);
		$data['article_dynamic_url'] = $this->getUrl('article','reviews','SYS=method=manageReplies&action=editReply&pid='.$params['pid'].'&aid='.$params['aid']);
		return $data;
	}
	
	//管理回帖
	public function manageReplies (&$params)
	{
		$this->addConfig('article','configs');
		$this->addConfig('article', 'power');
		$jieqiPower['power'] = $this->getConfig('article','power');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$canedit = $this->checkpower($jieqiPower['power']['newreview'], $this->getUsersStatus(), $this->getUsersGroup(), true );
		if ($canedit)
		{
			switch($params['action'])
			{	
				case 'delReply':
					//减少书评积分
					if(!empty($jieqiConfigs['article']['scorereview'])){
						$users_handler =  $this->getUserObject();
						$users_handler->changeScore(intval($params['pid']), $jieqiConfigs['article']['scorereview'], false);
					}
					//删除回帖
					$this->db->init('replies ', 'postid', 'article');
					$this->db->setCriteria();
					$this->db->criteria->add(new Criteria('postid', $params['pid'], '='));
					$this->db->delete($this->db->criteria);
				break;
				case 'editReply':
					if ($params['dosubmit'])
					{
						$this->db->init('replies ', 'postid', 'article');
						$reply['subject'] = $params['ptitle'];
						$reply['posttext'] = $params['pcontent'];
						$this->db->edit($params['pid'],$reply);
					}
					
				break;
				case 'delReview':
				$reviewnewLib = $this->load ( 'reviews',  'article' );
				$reviewnewLib->delReview($params);
				break;
				case 'dianzan':
                                    $dianzanlimit =$_COOKIE['dianzanlimit'+$params['pid']];
				   if($dianzanlimit!=date("Y-m-d", JIEQI_NOW_TIME)){
				        $this->addLang('article','review');
				        $jieqiLang['article'] = $this->getLang('article');
				        $this->db->init('replies ', 'postid', 'article');
				        $sql='UPDATE '.$this->dbprefix('article_replies').' SET dianzan=dianzan + 1 WHERE postid='.$params['pid'];
					if ($this->db->query($sql)){
						@setcookie('dianzanlimit'+$params['pid'], date("Y-m-d", JIEQI_NOW_TIME), JIEQI_NOW_TIME+99999999, '/',  JIEQI_COOKIE_DOMAIN, 0);
						$this->msgwin(LANG_DO_SUCCESS, '');
					}
				   }
				break;
				default:
				$params['dosubmit'] = false;
				break;
			}
		}
	}
	
	//管理书评
	public function manageReview($params)
	{
	    $this->addLang( 'article', 'review' );
		$jieqiLang['article'] = $this->getLang ( 'article' );
		$reviewnewLib = $this->load ( 'reviews','article' );
		$reviewnewLib->manageReview($params);
		$rul =  $this->getUrl(JIEQI_MODULE_NAME,'reviews','SYS=aid='.$params['aid'].'&rid='.$params['rid'].'&display='.$params['display']);
// 		if ($params['siteid']){
// 			$rul =  $this->getUrl($params['siteid'],'reviews','SYS=aid='.$params['aid'].'&rid='.$params['rid'].'&display='.$params['display']);
// 		}
		$this->jumppage($rul.'&page='.$params['page'].'&flag='.$params['flag'],LANG_DO_SUCCESS, $jieqiLang['article']['review_edit_success']);
	}
	
	/**
	 * 申请版主
	 * @author chengyuan 2015-6-8 下午2:07:04
	 * @param unknown $articleid
	 */
	public function applyModerator($articleid){
		if($articleid){
			$moderatorLib = $this->load ( 'moderator','article' );
			$moderatorLib->applyModerator($articleid);
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	
	}
	/**
	 * 移除版主
	 * @author chengyuan 2015-6-9 下午5:24:58
	 * @param unknown $articleid
	 * @param unknown $mid
	 */
	public function removeModerator($articleid,$mid){
		$moderatorLib =  $this->load('moderator','article');
		$url = $this->getUrl('system','userhub','SYS=method=moderatorView&articleid='.$articleid);
		if($mid){
			if($moderatorLib->removeModerator($mid)){
				$this->jumppage($url, LANG_NOTICE,LANG_DO_SUCCESS);
			}else{
				$this->jumppage($url, LANG_NOTICE,LANG_DO_FAILURE);
			}
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	
	}
	/**
	 * 设置为版主
	 * @author chengyuan 2015-6-9 下午3:59:50
	 * @param unknown $articleid	aid
	 * @param unknown $mid			申请记录id
	 */
	public function setModerator($articleid,$mid){
		$moderatorLib =  $this->load('moderator','article');
		$url = $this->getUrl('system','userhub','SYS=method=moderatorView&articleid='.$articleid);
		if($moderatorLib->setModerator($articleid,$mid)){
			$this->jumppage($url, LANG_NOTICE,LANG_DO_SUCCESS);
		}else{
			$this->jumppage($url, LANG_NOTICE,LANG_DO_FAILURE);
		}
	}
	/**
	 * 批量删除申请记录
	 * @author chengyuan 2015-6-9 下午4:57:02
	 * @param unknown $articleid
	 * @param unknown $mid
	 */
	public function delApplyByIds($articleid,$mid){
		$moderatorLib =  $this->load('moderator','article');
		$url = $this->getUrl('system','userhub','SYS=method=moderatorView&articleid='.$articleid);
		if($moderatorLib->delByIds($mid)){
			$this->jumppage($url, LANG_NOTICE,LANG_DO_SUCCESS);
		}else{
			$this->jumppage($url, LANG_NOTICE,LANG_DO_FAILURE);
		}
	}
	/**
	 * 版主申请记录视图
	 * @author chengyuan 2015-6-9 下午4:57:13
	 * @param unknown $params
	 * @return unknown
	 */
	public function moderatorView($params = array()){
		$articleLib =  $this->load('article','article');
		$auth = $this->getAuth();
		//作品信息
		$data['articles'] = $articleLib->articleByAuthorid($auth['uid']);
		//书评信息-默认第一本书
		if(is_array($data['articles']) && count($data['articles']) > 0){
			if($params['articleid']){
				$data['article'] = $articleLib->isExists($params['articleid']);
			}else{
				$data['article'] = $data['articles'][0];
			}
			$moderatorLib =  $this->load('moderator','article');
			$data['moderators'] =  $moderatorLib->moderatorInfo($data['article']['articleid']);
			$applyData = $moderatorLib->applyModeratorInfo($data['article']['articleid'],$params['page']);
			$data += $applyData;
		}
		return $data;
	}
}
?>