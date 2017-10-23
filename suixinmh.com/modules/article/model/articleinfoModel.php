<?php 
/**
 * article模型
 * @author huliming  2014-4-9
 *
 */
class articleinfoModel extends Model{

	public function articleinfoView($params)
	{
        global $jieqiSetting;
        jieqi_getconfigs('article', 'reward_item','jieqiSetting');
        $this->addConfig('article', 'configs');
        $this->addConfig('system', 'configs');
        $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
        $jieqiConfigs['article']['reward_item'] = $this->getConfig('article', 'reward_item');
        $jieqiConfigs['system'] = $this->getConfig('system', 'configs');
        $this->addLang('article', 'article');
        $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
        $params['aid'] = intval($params['aid']);


        $hideflag=0;
        $hide=array();
        $hidefile=JIEQI_ROOT_PATH."/configs/article/hidelist.php";
        if (file_exists($hidefile)) {
            include($hidefile);
            if ($hide[$params['aid']]){
                $hideflag=1;
            }
        }

        $auth = $this->getAuth();
        if (!$auth['uid'] && $hideflag) {
            $readurl = $this->geturl('3g', 'articleinfo', 'aid=' . $params['aid']) . "?" . time();
            $readurl=urlencode($readurl);
            if (get_user_agent() == 1) {
                $url="/wxlogin/?jumpurl=$readurl";
            }
            else {
                $url="/login/?jumpurl=$readurl";
            }
            header("location:$url");
            exit();
        }

        if ($hideflag) {
            $package = $this->load('article', 'article');
            if (!$package->checkArticleReader($params['aid'])) {
                $this->printfail("文章未找到");
            }
        }

        $this->db->init('article', 'articleid', 'article');
        $this->db->setCriteria(new Criteria('a.articleid', $params['aid'], '='));
        $this->db->criteria->setTables($this->dbprefix('article_article') . " AS a LEFT JOIN " . $this->dbprefix('article_statamout') . " AS s ON a.articleid=s.articleid ");
        $this->db->criteria->setFields("a.*,s.visit,s.vote,s.goodnum,s.vipvote,s.reward");
        //$v = $this->db->get($this->db->criteria);
        if (!$v = $this->db->get($this->db->criteria)) {
            $this->printfail($jieqiLang['article']['article_not_exists']);
        }
        $auth = $this->getAuth();
        if ($v->getVar('display')) {
            if ($auth['vip'] == 0 || $params['aid'] > 10000) $this->printfail($jieqiLang['article']['article_not_audit']);
        }
        $package = $this->load('article', 'article');//加载文章处理类
        $data = $package->article_vars($v);//article表字段的格式化
        //非本频道书跳转到相应频道
        //print_r(JIEQI_MODULE_NAME);exit;
        if (!in_array(JIEQI_MODULE_NAME, array('3gwap', 'wap', '3g', 'overseas'))) {
            $p_tmp = parse_url(JIEQI_CURRENT_URL);
            if (!preg_match("/{$p_tmp['host']}/", $data['url_articleinfo'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: {$data['url_articleinfo']}");
                exit;
            }
        }

        /*if ($data['lastchaptervip'] > 0){
            $this->db->init('chapter','chapterid','article');
            $this->db->setCriteria(new Criteria('articleid',$params['aid'], '='));
            $this->db->criteria->add(new Criteria('isvip', 0, '='));
            $this->db->criteria->add(new Criteria('chaptertype', 0, '='));
            $this->db->criteria->add(new Criteria('display', 0, '='));
            $this->db->criteria->setSort('chapterorder');
            $this->db->criteria->setOrder('DESC');
            $this->db->criteria->setLimit(1);
            $this->db->criteria->setStart(0);
            $this->db->queryObjects();
            if ($v = $this->db->getObject()){
                $data['lastchapter_no_vip'] = $v->getVar('chaptername','n');
                $data['lastchapter_no_vipid'] = $v->getVar('chapterid','n');
                $data['url_lastchapter_no_vip'] = $this->geturl($package->getModule($data['sortid']), 'reader', 'aid='.$params['aid'],'cid='.$data['lastchapter_no_vipid']);
                $data['lastupdate_no_vip'] = $v->getVar('lastupdate','n');
            }
        }*/
        $this->db->init('reviews  ', 'topicid', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('ownerid', $params['aid'], '='));
        $data['count'] = $this->db->getCount($this->db->criteria);
        $data['maxpage'] = ceil($data['count'] / $jieqiConfigs['article']['newreviewnum']);
        $this->db->criteria->add(new Criteria('isgood', 1, '='));
        $data['goodcount'] = $this->db->getCount($this->db->criteria);
        $data['gdmaxpage'] = ceil($data['goodcount'] / $jieqiConfigs['article']['newreviewnum']);
        $this->db->init('statlog', 'statlogid', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('mid', 'reward', '='));
        $this->db->criteria->add(new Criteria('articleid', $params['aid'], '='));
        $data['rewardnum'] = $this->db->getCount($this->db->criteria);
        $package->instantPackage($data['articleid']);
        if ($data['lastchapterid'] > 0) $data['lastchapter_con'] = $package->getContent($data['lastchapterid']);
        $moderatorLib = $this->load('moderator', 'article');
        $data['moderators'] = $moderatorLib->moderatorInfo($params['aid']);
        $data['reward_item'] = $jieqiSetting['article']['reward_item'];
        return $data;
	} 
	function vipjump($params){
	        $this->db->init('obook','obookid','obook');
			$this->db->setCriteria(new Criteria('obookid',$params['obookid'], '='));
			$obook = $this->db->get($this->db->criteria);
			if($params['cid']){
				$this->db->init('chapter','chapterid','article');
				$this->db->setCriteria(new Criteria('vchapterid',$params['cid'], '='));
				$this->db->queryObjects();
				if($chapter = $this->db->getObject()){
				    Header("HTTP/1.1 301 Moved Permanently");
					header('location:'.$this->geturl(JIEQI_MODULE_NAME, 'reader', 'aid='.$obook->getVar('articleid'),'cid='.$chapter->getVar('chapterid')));exit;
				}//else print_r($params);exit;
			}
			Header("HTTP/1.1 301 Moved Permanently");
			header('location:'.$this->geturl(JIEQI_MODULE_NAME, 'articleinfo', 'aid='.$obook->getVar('articleid')));exit;
	}
	/**
	 * 查询打赏次数：用于新版wap详情页
	 */
	function votenum($aid){
		$this->db->init('statlog','statlogid','article');
		$this->db->setCriteria(new Criteria('mid','vote'));
		$this->db->criteria->add(new Criteria('articleid',$aid));
		$votenum = $this->db->getCount($this->db->criteria);
		return $votenum;
	}
}
?>
