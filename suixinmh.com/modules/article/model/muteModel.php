<?php 
/**
 * 禁言业务模型
 * @author chengyuan 2015-5-18 下午2:41:57
 */

class muteModel extends Model{

	public $mute_day = array(1,3,7,15,30,0);

	/**
	 * 当前登录用户的对于aid书籍（或全站）的禁言状态
	 * <p>
	 * 判断对于aid单本的禁言状态和全站禁言
	 * <p>
	 * 为登陆则未禁言
	 * @author chengyuan 2015-5-22 上午9:59:30
	 * @param unknown $aid	书号，可空
	 * @return Ambigous <1（禁言）, 0（未禁言）, number>|number
	 */
	public function getAuthMuteState($aid){
		$auth = $this->getAuth();
		if($auth['uid']){//登录
			return $this->userState($auth['uid'],$aid);
		}else{
			return 0;
		}
	}
	/**
	 * 用户中心-禁言列表
	 * @author chengyuan 2015-5-21 下午4:30:07
	 * @param unknown $params
	 * @return unknown
	 */
	public function mute_view($params){
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
			//$data['article']指定书籍的禁言列表
			$this->db->init ( 'mute', 'muteid', 'article' );
			$this->db->setCriteria();
			$this->db->criteria->setTables("jieqi_article_mute as am inner join jieqi_article_reviews as ar on am.topicid = ar.topicid");
			$this->db->criteria->setFields("am.*,ar.title");
			$this->db->criteria->add(new Criteria('am.articleid',$data['article']['articleid']));
			$this->db->criteria->add(new Criteria('am.state',0));
			$this->addConfig('article','configs');
			$jieqiConfigs['article'] = $this->getConfig('article','configs');
			$data['muterows'] = $this->db->lists($jieqiConfigs['article']['newreviewnum'],$params['page'],JIEQI_PAGE_TAG);
			$data['url_jumppage']=$this->db->getPage();
		}
		return $data;
	}
	/**
	 * 解禁，支持单条和批量
	 * @author chengyuan 2015-5-21 下午2:43:47
	 * @param unknown $muteid id或者id数组
	 */
	public function unmute($muteid){
		if(!is_array($muteid)){
			$muteid = array($muteid);
		}
		if(count($muteid) > 0){
			$auth = $this->getAuth();
			$this->db->updatetable ( 'article_mute', array (
					'state' => 1,
					'unmuteuserid'=>$auth['uid'],
					'unmuteusername'=>$auth['username']
			), 'muteid IN ' . '('.implode(',',$muteid).')');
		}
		$this->jumppage('', LANG_DO_SUCCESS,LANG_DO_SUCCESS);
	}
	/**
	 * 书评管理-禁言记录
	 * @author cheangyuan 2015-5-21 下午4:30:43
	 * @param unknown $params
	 * @return unknown
	 */
	public function mute_list($params){
		//$data['article']指定书籍的禁言列表
		$this->db->init ( 'mute', 'muteid', 'article' );
		$this->db->setCriteria();
		$this->db->criteria->setTables("jieqi_article_mute as am inner join jieqi_article_reviews as ar on am.topicid = ar.topicid");
		$this->db->criteria->setFields("am.*,ar.title");
// 		$this->db->criteria->add(new Criteria('am.articleid',$data['article']['articleid']));
		$this->db->criteria->add(new Criteria('am.state',0));
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$data['muterows'] = $this->db->lists($jieqiConfigs['article']['newreviewnum'],$params['page']);
		$data['url_jumppage']=$this->db->getPage();
		return $data;
	}
	/**
	 * 指定的用户对aid书籍（或全站）是否被禁言
	 * <p>
	 * 1:是有否禁言时间已经到的
	 * 2:如果有更改禁言状态为1
	 * 3:在获取指定用户的是否被禁言（单本，全站）
	 * @author chengyuan 2015-5-19 下午1:22:59
	 * @param unknown $uid	用户id
	 * @param unknown $aid	书籍id，为空则判断是否全站禁言
	 * @return 1（禁言）|0（未禁言）
	 */
	public function userState($uid,$aid){
		if(!$uid){
			$this->printfail(LANG_NEED_LOGIN);
		}
//		$muteCache = $this->load('mute', 'article');
//		//到期更新
//		$muteCache->setCriteria(new Criteria('state',0));
//		$muteCache->criteria->add(new Criteria('day',0,"!="));
//		$muteCache->criteria->add(new Criteria('enddate',time(),"<"));
//		if($muteCache->getCount($muteCache->criteria)){
//			//有禁言到期的，自动解禁
//			$muteCache->updatefields("jieqi_article_mute",array("state"=>1,"unmuteuserid"=>0,"unmuteusername"=>""),$muteCache->criteria);
//		}
//		//用户禁言状态
//		$muteCache->setCriteria(new Criteria('state',0));//禁言中
//		$muteCache->criteria->add(new Criteria('userid',$uid));
//		if(isset($aid) && aid){
//			$muteCache->criteria->add(new Criteria('articleid','','in (0,'.$aid.')'));//单本
//		}else{
//			$muteCache->criteria->add(new Criteria('articleid',0));//全站
//		}
//
//		$result = $muteCache->getCount($muteCache->criteria)?1:0;
//		return $result;

		return 0;
	}
	/**
	 * 用户-书籍的一对一禁言的业务流程
	 * @author chengyuan 2015-5-18 下午2:46:42
	 * @param unknown $user		被禁言的用户信息
	 * @param unknown $article	被禁言文章信息
	 * @param unknown $topicid	违规的评论ID
	 * @param unknown $area		区间，单本0，全站1
	 * @param number $day		禁言时间
	 */
	public function mute($user=array(),$article=array(),$topicid,$area=0,$day){
		$day = intval($day);
		if(!$user['userid'] || !$user['username']){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		if(!$article['articleid'] || !$article['articlename']){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		if(!$topicid){//即使同时评论被删除了，顶多算一条无意义数据
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		//默认intval($day)0，永久禁言
		if (!in_array($day,$this->mute_day, TRUE)){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		$mute = array();
		$mute['state']=0;//0开始禁言，1完成
		$mute['userid']=$user['userid'];
		$mute['username']=$user['username'];
		$mute['topicid']=$topicid;
		if($area){//全站
			$mute['articleid']=0;
			$mute['articlename']="";
		}else{//单本
			$mute['articleid']=$article['articleid'];
			$mute['articlename']=$article['articlename'];
		}
		$auth = $this->getAuth();
		$mute['activeuserid']=$auth['uid'];
		$mute['activeusername']=$auth['username'];
		$mute['unmuteuserid']=0;
		$mute['unmuteusername']="";
		$mute['startdate']=time();
		if($day == 0){
			$mute['enddate']=0;//永久禁言
		}else{
			$mute['enddate']=strtotime("+$day days");;
		}
		$mute['day']=$day;
		$muteCache = $this->load('mute', 'article');
		if(!$muteCache->add($mute)){
			$this->printfail(LANG_DO_FAILURE);//唯一索引则已经禁言
		}else{
			//发送站内信通知用户
			$this->expressNotice($user,$article['articlename'],$day,$mute['enddate']);
		}
	}

	private function expressNotice($user,$articlename,$day,$enddate){
		//发送站内信通知用户
		if($enddate == 0){
			$content = "您在《{$articlename}》书评区被永久禁言，暂时不能在该书评区发表新评论！";
		}else{
			$content = "您在《{$articlename}》书评区被禁言{$day}天，解禁时间".date("Y-m-d H:i:s", $enddate)."。禁言期间，暂时不能在该书评区发表新评论！";
		}
		$messageService = $this->model('message','system');
		$messageService->auditApproval($user['userid'],$user['username'],'您已被禁言',$content);
	}
}
?>