<?php
/**
 * 章节阅读模型 * @copyright   Copyright(c) 2014
 * @author      huliming* @version     1.0
 */
class readerModel extends Model{
        //public $package;

    public function main($params = array(), $obj = NULL)
    {
        $package = $this->load('article', 'article');
        if (!$package->loadOPF($params['aid'])) {
            $this->addLang('article', 'article');
            $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
            $this->printfail($jieqiLang['article']['article_not_exists']);
        }
        $package->updateChapterVisit($params['cid']);
        return $package->showChapter($params['cid'], $obj);
    }

	//购买VIP章节
	public function buychapter($params = array()){
	    $this->addLang ( 'article', 'article' );
		$jieqiLang ['article'] = $this->getLang ( 'article' ); // 所有语言包配置赋值
		if(!$params['cid']) $this->printfail(LANG_ERROR_PARAMETER);
		$package = $this->load('article', 'article');
		//获得章节信息
		$this->db->init('article','articleid','article');
		$this->db->setCriteria(new Criteria('articleid',$params['aid'], '='));
		$article = $this->db->get($this->db->criteria);
        $auth = $this->getAuth();

		if(!$article || $article->getVar('display') && $params['aid']>10000) $this->printfail($jieqiLang['article']['not_in_sale']);//文章或者章节待审
		$auth = $this->getAuth();
		$users_handler =  $this->getUserObject();//查询用户是否存在
		$users = $users_handler->get($auth['uid']);
		if(!is_object($users) || $users->getVar('groupid')==1) $this->printfail($jieqiLang['article']['need_user_login']);
		$useregold=$users->getVar('egold', 'n');//账号余额
		$useresilver=$users->getVar('esilver', 'n');//书券余额
		if($params['readurl']){
			$readurl = $params['readurl'];
		}else{
			$readurl = $this->geturl('article', 'reader', 'aid='.$article->getVar('articleid'),'cid='.$params['cid']);
		}
		$datavip = $package->getAllVip($params['aid'],array($params['cid']));
		if(!$datavip['num']) $this->printfail(sprintf($jieqiLang['article']['chapter_has_buyed'], $article->getVar('articlename'), $datavip['chapter'][0]['chaptername'], $readurl));
		$articlename = $article->getVar('articlename');
		$chaptername = $datavip['chapter'][0]['chaptername'];
		//if($package->checkChapterIsBuy($params['cid'])) $this->printfail(sprintf($jieqiLang['article']['chapter_has_buyed'], $article->getVar('articlename'), $datavip['chapter'][0]['chaptername'], $readurl));
/*		if(!$chapter->getVar('saleprice')){
		     $package->article_repack($article->getVar('articleid'), array('makeopf'=>1), 1);
		     $this->jumppage($readurl, LANG_DO_SUCCESS, $jieqiLang['article']['batch_buy_success']);
		}*/
		//验证金额是否足够
		$moneyEnough = true;
        if($datavip['shuquan']){
		     if($useresilver < $datavip['shuquan']) $moneyEnough = false;
		}
        if($datavip['shubi']){
		     if($useregold < $datavip['shubi']) $moneyEnough = false;
		}

//        if (!$moneyEnough) {
//            print_r($datavip);
//            echo "no money";
//            exit();
//        }

		if(!$moneyEnough) {
		    if (JIEQI_MODULE_NAME == 'wap' || JIEQI_MODULE_NAME == '3g'){
		    	$url = $this->geturl(JIEQI_MODULE_NAME, 'pay');
		    }else{
		    	$url = $this->geturl('pay', 'home');
		    }
			$this->printfail(sprintf($jieqiLang['article']['chapter_money_notenough'], $articlename, $chaptername, $datavip['salepriceAf'].' '.JIEQI_EGOLD_NAME, $useregold.' '.JIEQI_EGOLD_NAME, $url));//余额不足
		}
		//$this->printfail($datavip['chapter'][0]['paynote']);
        $sale_tableid=sprintf("%02d",$auth['uid']%100);
        $sale_table="sale_$sale_tableid";
		$this->db->init($sale_table,'saleid','article');
		global $jieqiModules;

		$osale['buytime']  = JIEQI_NOW_TIME;
		$osale['accountid'] = $auth['uid'];
		$osale['chapterid'] = $params['cid'];
		$osale['articleid'] = $article->getVar('articleid');
		$osale['saleprice'] = $datavip['chapter'][0]['salepriceAf'] ? $datavip['chapter'][0]['salepriceAf'] : $datavip['chapter'][0]['shuquan'];
		$osale['pricetype'] = $datavip['chapter'][0]['pricetype'];


		if(!$this->db->add($osale)) {
            $this->printfail($jieqiLang['article']['add_osale_faliure']);
        }
		$osale['siteid'] = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];
		$osale['account'] = $auth['username'];
		$osale['chaptername'] = $chaptername;
		$osale['articlename'] = $articlename;
		$osale['paytype'] = 0;
		$osale['payflag'] = 0;
		$osale['paynote']= $datavip['chapter'][0]['paynote'];
		$osale['state'] = 0;
		$osale['flag'] = 0;
        $this->insert_salelog($osale);
		//记录购买日志
		$package->addArticleStat(
			$article->getVar('articleid'), 
			$article->getVar('authorid', 'n'), 
			'sale', $datavip['salepriceAf'], 
			array('chapterid'=>$params['cid'], 'chaptername'=>$chaptername, 'deduct'=>$datavip['shuquan'])
		);
		if($datavip['shuquan']){
			$package->addArticleStat($article->getVar('articleid'),$article->getVar('authorid', 'n'),'shuquansale', $datavip['shuquan']);
		}
		//扣除虚拟货币
		$retstatu = true;
		if($datavip['shubi']){
		   if(!$users_handler->payout($users->getVar('uid', 'n'), $datavip['shubi'])) return false;
		}
		if($datavip['shuquan']){
		   if(!$users_handler->payout($users->getVar('uid', 'n'), $datavip['shuquan'],1)) return false;
		}
		$_SESSION['buyonechapter'] = 1;
		if(!defined('NOT_PUT_DATA')){
			if($retstatu) {
				header('location:' . $readurl);
				exit;
				//$this->jumppage($readurl, LANG_DO_SUCCESS, $jieqiLang['article']['batch_buy_success']);
			}
			else {
				$this->printfail($jieqiLang['article']['add_buyinfo_failure']);
			}
		}else {
		    header('location:'.$readurl);exit;
		}

	}

	//批量购买章节
	public function batchbuychapter($params)
    {
        $this->addLang('article', 'article');
        $jieqiLang ['article'] = $this->getLang('article'); // 所有语言包配置赋值
        //if(isset($params['cids']) && !$params['cids']) $this->printfail(LANG_ERROR_PARAMETER);
        $package = $this->load('article', 'article');
        //获得章节信息
        $this->db->init('article', 'articleid', 'article');
        $this->db->setCriteria(new Criteria('articleid', $params['aid'], '='));
        $article = $this->db->get($this->db->criteria);
        if (!$article || $article->getVar('display')) $this->printfail($jieqiLang['article']['not_in_sale']);//文章或者章节待审
        $auth = $this->getAuth();
        $users_handler = $this->getUserObject();//查询用户是否存在
        $users = $users_handler->get($auth['uid']);
        if (!is_object($users) || $users->getVar('groupid') == 1) $this->printfail($jieqiLang['article']['need_user_login']);
        $useregold = $users->getVar('egold', 'n');//账号余额
        $useresilver = $users->getVar('esilver', 'n');//书券余额
        $datavip = $package->getAllVip($params['aid'], $params['cids'], $params['cid']);
        //print_r($datavip);
        //exit();
        if (!$datavip['num']) $this->printfail(LANG_ERROR_PARAMETER);
        //$this->printfail($datavip['num']);
        $articlename = $article->getVar('articlename');
        if ($params['readurl'])
            $readurl = $params['readurl'];
        else
            $readurl = $this->geturl('3g', 'reader', 'aid=' . $params['aid'], 'cid=' . $datavip['chapter'][0]['chapterid']);
        //验证金额是否足够
        $moneyEnough = true;
        if ($datavip['shuquan']) {
            if ($useresilver < $datavip['shuquan']) $moneyEnough = false;
        }
        if ($datavip['shubi']) {
            if ($useregold < $datavip['shubi']) $moneyEnough = false;
        }
        if (!$moneyEnough) {
            if (JIEQI_MODULE_NAME == 'wap') {
                $url = $this->geturl('wap', 'pay');
            } else {
                $url = $this->geturl('pay', 'home');
            }
            $this->printfail(sprintf($jieqiLang['article']['chapters_money_notenough'], $articlename, $datavip['num'], $datavip['shubi'] . ' ' . JIEQI_EGOLD_NAME, $useregold . ' ' . JIEQI_EGOLD_NAME, $url));//余额不足
        }

        global $jieqiModules;
        //$this->db->init('sale','saleid','article');
        $sale_tableid = sprintf("%02d", $auth['uid'] % 100);
        $sale_table = "sale_$sale_tableid";
        $this->db->init($sale_table, 'saleid', 'article');
        foreach ($datavip['chapter'] as $k => $v) {
            $osale['buytime'] = JIEQI_NOW_TIME;
            $osale['accountid'] = $auth['uid'];
            $osale['chapterid'] = $datavip['chapter'][$k]['chapterid'];
            $osale['articleid'] = $article->getVar('articleid');
            $osale['saleprice'] = $datavip['chapter'][$k]['salepriceAf'] ? $datavip['chapter'][$k]['salepriceAf'] : $datavip['chapter'][$k]['shuquan'];
            $osale['pricetype'] = $datavip['chapter'][$k]['pricetype'];

            //$osale['siteid'] = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];
            //$osale['account'] = $auth['username'];
            //$osale['chaptername'] = $datavip['chapter'][$k]['chaptername'];
            //$osale['articlename'] = $articlename;//$datavip['chapter'][$k]['articlename']
            //$osale['paytype'] = 0;
            //$osale['payflag'] = 0;
            //$osale['paynote'] = $datavip['chapter'][$k]['paynote'];
            //$osale['state'] = 0;
            //$osale['flag'] = 0;

            if (!$this->db->add($osale)) $this->printfail($jieqiLang['article']['add_osale_faliure']);
        }
        //记录购买日志
        $package->addArticleStat(
            $article->getVar('articleid'),
            $article->getVar('authorid', 'n'),
            'sale', $datavip['salepriceAf'],
            array('chapterid' => 0, 'chaptername' => $datavip['num'] . '个章节', 'deduct' => $datavip['shuquan'])
        );
        if ($datavip['shuquan']) {
            $package->addArticleStat($article->getVar('articleid'), $article->getVar('authorid', 'n'), 'shuquansale', $datavip['shuquan']);
        }
        //扣除虚拟货币
        $retstatu = true;
        if ($datavip['shubi']) {
            if (!$users_handler->payout($users->getVar('uid', 'n'), $datavip['shubi'])) return false;
        }
        if ($datavip['shuquan']) {
            if (!$users_handler->payout($users->getVar('uid', 'n'), $datavip['shuquan'], 1)) return false;
        }
        if ($retstatu) {
            header('location:' . $readurl);
            exit;
            //$this->jumppage($readurl, LANG_DO_SUCCESS, $jieqiLang['article']['batch_buy_success']);
        }
        else
            $this->printfail($jieqiLang['article']['add_buyinfo_failure']);
	}

	public function autobuy($params)
	{
		$auth = $this->getAuth();
		$this->addLang ( 'article', 'article' );
		$jieqiLang ['article'] = $this->getLang ( 'article' ); // 所有语言包配置赋值
		$this->db->init('autobuy','autoid','article');
		$autobuy['uid'] = $auth['uid'];
		$autobuy['username'] = $auth['username'];
		$autobuy['articleid'] = $params['aid'];
		$autobuy['addtime'] = JIEQI_NOW_TIME;
		if (!$this->db->add($autobuy)){
			$this->printfail();
		}else{
			$readurl = $this->geturl('article', 'reader', 'aid='.$params['aid'],'cid='.$params['cid']);
	    	$this->jumppage($readurl, LANG_DO_SUCCESS, $jieqiLang['article']['batch_open_buy_success']);
		}
	}
	
	public function closebuy($params){
		$auth = $this->getAuth();
		$this->db->init('autobuy','autoid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('uid', $auth['uid'], '='));
		$this->db->criteria->add(new Criteria('articleid', $params['aid'], '='));
		return $this->db->delete($this->db->criteria);
	}
	/*
	 * 加入限免
	 * 传入aid，cid
	 */
	public function addFree($params){
		$this->addLang ( 'article', 'article' );
		$jieqiLang ['article'] = $this->getLang ( 'article' ); // 所有语言包配置赋值
		
		$auth = $this->getAuth();
		//查询当前用户VIP等级
		$this->addConfig('system','vipgrade');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$vipgrade = jieqi_gethonorarray($auth['vip'], $jieqiVipgrade);//VIP等级数组
		$limit = $vipgrade['setting']['baodiyuepiao']; //该VIP等级下保底月票数量，等于该等级下每日限免数量
		
		//判断该文章是否已加入限免
		$this->db->init ( 'free', 'freeid', 'article' );
		$this->db->setCriteria(new Criteria('uid', $auth['uid']));
		$this->db->criteria->add(new Criteria('articleid', $params['aid']));
		$count = $this->db->getCount();
		if($count>0) $this->printfail($jieqiLang['article']['has_free']); //该用户已将该文章加入限免
		
		//判断是否已超过今日限免数量
		unset($this->db->criteria);
		$day = $this->getTime();
		$this->db->setCriteria(new Criteria('uid', $auth['uid']));
		$this->db->criteria->add(new Criteria('addtime', $day, '>='));
		$today = $this->db->getCount(); //该用户今日加入限免的文章数
		if($limit<1){ //如果是普通会员，可以限定1本
			$freeaids = array();
			$package = $this->load('article', 'article');
			$freeaids = $package->getFreeLimit();
			if(in_array($params['aid'],$freeaids)){
				$limit = 1;
			}
		}
		if($today<$limit){ //没有超过今日限免数量
			$newFree= array();
			$newFree['addtime']=JIEQI_NOW_TIME;
			$newFree['uid']=$auth['uid'];
			$newFree['username']= $auth['username'];
			$newFree['articleid']= $params['aid'];
			if (!$this->db->add($newFree)){
				$this->printfail($jieqiLang['article']['add_free_fail']);
			}else{
				$readurl = $this->geturl('article', 'reader', 'aid='.$params['aid'],'cid='.$params['cid']);
				$this->jumppage($readurl, LANG_DO_SUCCESS, $jieqiLang['article']['add_free_success']);
			}
		}else{ //超过今日限免数量
			$this->printfail($jieqiLang['article']['over_day_limit']);
		}
	}

    function insert_salelog($data) {
        global $jieqiLang;
        $table="salelog_".date("Ym");
        $this->db->init($table,'saleid','article');
        if(!$this->db->add($data)) {
            if ($this->db->errno() == 1146) {
                if (!$this->check_salelog_table()) {
                    return false;
                }
                $this->db->init($table,'saleid','article');
                $this->db->add($data);
            }
            else {
                $this->printfail($jieqiLang['article']['add_osale_faliure']);
                return false;
            }
        }
        return true;
    }

    function check_salelog_table() {
        $table="jieqi_article_salelog_".date("Ym");

        $sql="CREATE TABLE IF NOT EXISTS `$table` (
  `saleid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `buytime` int(11) unsigned NOT NULL DEFAULT '0',
  `accountid` int(11) unsigned NOT NULL DEFAULT '0',
  `account` varchar(30) NOT NULL DEFAULT '',
  `articleid` int(11) unsigned NOT NULL DEFAULT '0',
  `chapterid` int(11) unsigned NOT NULL DEFAULT '0',
  `articlename` varchar(100) CHARACTER SET gbk COLLATE gbk_bin NOT NULL,
  `chaptername` varchar(100) NOT NULL DEFAULT '',
  `saleprice` double NOT NULL DEFAULT '0',
  `pricetype` tinyint(1) NOT NULL DEFAULT '0',
  `paytype` tinyint(1) NOT NULL DEFAULT '0',
  `payflag` tinyint(1) NOT NULL DEFAULT '0',
  `paynote` varchar(255) NOT NULL DEFAULT '',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`saleid`),
  KEY `accountid` (`accountid`),
  KEY `account` (`account`),
  KEY `articleid` (`articleid`),
  KEY `chapterid` (`chapterid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;";
        return $this->db->query($sql);
    }
	/**
	 * 七夕特惠
	 * @param unknown $params
	 * 2014-7-31 下午2:10:36
	 */
/*	public function batchbuychapterqixi($params){
		$this->addLang ( 'article', 'article' );
		$jieqiLang ['article'] = $this->getLang ( 'article' ); // 所有语言包配置赋值
		$datavip = $this->getAllVip($params);
		$auth = $this->getAuth();
		$readurl = $this->geturl('article', 'index', 'aid='.$datavip['chapter'][0]['articleid']);
		$users_handler =  $this->getUserObject();//查询用户是否存在
		$users = $users_handler->get($auth['uid']);
		if(!is_object($users) || $users->getVar('groupid')==1) $this->printfail($jieqiLang['article']['need_user_login']);
		$useregold=$users->getVar('egold', 'n');//账号余额

		//查询折扣
		$this->addConfig('system','vipgrade');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$vipgrade = jieqi_gethonorarray($auth['vip'], $jieqiVipgrade);//VIP等级数组
		if($vipgrade['setting']['dingyuezhekou']>0){
// 			$datavip['saleprice']=$datavip['saleprice']*$vipgrade['setting']['dingyuezhekou'];
		}
		$datavip['saleprice']=floor($datavip['saleprice']*0.77);

		if($useregold < $datavip['saleprice']) $this->printfail(sprintf($jieqiLang['article']['chapters_money_notenough'], $datavip['articlename'], $datavip['num'], $datavip['saleprice'].' '.JIEQI_EGOLD_NAME, $useregold.' '.JIEQI_EGOLD_NAME, $this->geturl('pay', 'home')));//余额不足
		$this->db->init('sale','saleid','article');
		foreach ($datavip['chapter'] as  $k=>$v){
			$osale['siteid'] = JIEQI_SITE_ID;
			$osale['buytime']  = JIEQI_NOW_TIME;
			$osale['accountid'] = $auth['uid'];
			$osale['account'] = $auth['username'];
			$osale['chapterid'] = $datavip['chapter'][$k]['chapterid'];
			$osale['chaptername'] = $datavip['chapter'][$k]['chaptername'];
			$osale['articleid'] = $datavip['chapter'][$k]['articleid'];
			$osale['articlename'] = $datavip['chapter'][$k]['articlename'];
			$osale['saleprice'] = $datavip['chapter'][$k]['saleprice'];
			$osale['pricetype'] = $datavip['chapter'][$k]['pricetype'];
			$osale['paytype'] = 0;
			$osale['payflag'] = 0;
			$osale['paynote'] = '';
			$osale['state'] = 0;
			$osale['flag'] = 0;
			if(!$this->db->add($osale)) $this->printfail($jieqiLang['article']['add_osale_faliure']);
			//记录购买日志
		}
		$package = $this->load('article', 'article');
		$package->addArticleStat($params['aid'], $datavip['authorid'], 'sale', $datavip['saleprice'], array('chapterid'=>0, 'chaptername'=>$datavip['num'].'个章节'));
		//扣除虚拟货币
// 		if(!$datavip['saleprice']){
// 			return $datavip;
// 		}
		if($users_handler->payout($users->getVar('uid', 'n'),$datavip['saleprice'])) return $datavip;
		else $this->printfail($jieqiLang['article']['add_buyinfo_failure']);
	}*/
        /*function main($params = array()){
		     $this->addConfig('article','configs');
		     $this->addLang('article','article');
			 $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
			 $jieqiConfigs['article'] = $this->getConfig('article','configs');
			 $this->package = $this->load('article','article');
			 if(!$data['article'] = $this->package->getOPF($params['aid'])) $this->printfail($jieqiLang['article']['article_not_exists']);
			 /*$this->init($params['aid']);//实例化
			 $data = $tmp = array();
			 foreach($this->package->metas as $k=>$v){
			      if($k){
				       $tmp = explode(':',$k);
					   $data['article'][strtolower($tmp[1])] = jieqi_htmlstr($v);
				  }
			 }
			 $data['article'] = $this->package->article_vars($data['article']);*\/
			 if(!$data['chapter'] = $this->getChapter($params['cid'])) $this->printfail($jieqiLang['article']['chapter_not_exists']);
			 elseif($data['chapter']==2) $this->printfail($jieqiLang['article']['chapter_not_insale']);
			 //print_r($data);exit;
			return $data;
        } */
		//获取一条章节的数据
		/*function getChapter($cid){
			$i=0;
			$num=count($this->package->chapters);
			while($i<$num){
				$tmpvar=$this->package->getCid($this->package->chapters[$i]['href']);
				if($tmpvar==$cid){
				    //if($this->package->chapters[$i]['display']) return 2;//待审
					//else
					return $this->setChapter($i+1, true);
				}
				$i++;
			}
			return false;
		}
		function setChapter($nowid, $filter=false){
		    if($nowid<=0) return false;
			$chaptercount=count($this->package->chapters);
			if($nowid>$chaptercount) return false;
			$chapter=jieqi_htmlstr($this->package->chapters[$nowid-1]['id']);//章节名
			$void=$nowid-2;
			$volume='';
			while($void>=0 && $this->package->chapters[$void]['content-type']!='volume') $void--;
			if($void>=0) $volume=jieqi_htmlstr($this->package->chapters[$void]['id']);
			$preid=$nowid-2;
			while($preid>=0 && $this->package->chapters[$preid]['content-type']=='volume') $preid--;
			$preid++;
			$nextid=$nowid;
			while($nextid<$chaptercount && $this->package->chapters[$nextid]['content-type']=='volume') $nextid++;
			if($nextid>=$chaptercount) $nextid=0;
			else $nextid++;
			$chapterid=$this->package->getCid($this->package->chapters[$nowid-1]['href']);
			global $jieqiConfigs,$jieqiLang;
			if(!isset($jieqiConfigs)){
				$this->addConfig('article','configs');
				$jieqiConfigs['article'] = $this->getConfig('article','configs');
			}
			if(!isset($jieqiConfigs)){
				$this->addLang('article','article');
			    $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
			}
			$data = array();
			$data['chapter']['chapterid'] = $chapterid;
			$data['chapter']['articleid'] = $this->package->id;
			$data['chapter']['chaptername'] = $chapter;
			$data['chapter']['volume'] = $volume;
			$data['chapter']['title'] = $volume.' '.$chapter;
			$data['chapter']['postdate'] = $this->package->chapters[$nowid-1]['postdate'];
			$data['chapter']['lastupdate'] = $this->package->chapters[$nowid-1]['lastupdate'];
			$data['chapter']['size'] = $this->package->chapters[$nowid-1]['size'];
			$data['chapter']['saleprice'] = $this->package->chapters[$nowid-1]['saleprice'];
			$data['chapter']['isvip'] = $this->package->chapters[$nowid-1]['isvip'];
			$data['chapter']['intro'] = jieqi_htmlstr($this->package->chapters[$nowid-1]['intro']);

			if(!$this->package->chapters[$nowid-1]['display']){
				$tmpvar=@$this->package->getContent($this->package->chapters[$nowid-1]['href']);
				if($tmpvar){
					//内容赋值
					include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
					$ts=TextConvert::getInstance('TextConvert');
					//文字过滤
					if($filter && !empty($jieqiConfigs['article']['hidearticlewords'])){
						$articlewordssplit = (strlen($jieqiConfigs['article']['articlewordssplit'])==0) ? ' ' : $jieqiConfigs['article']['articlewordssplit'];
						$filterary=explode($articlewordssplit, $jieqiConfigs['article']['hidearticlewords']);
						$tmpvar=str_replace($filterary, '', $tmpvar);

					}
					//网址改成可以点击的
					$tmpvar=$ts->makeClickable(jieqi_htmlstr($tmpvar));
				}else{//无内容
					$tmpvar=$jieqiLang['article']['userchap_not_exists'];
				}
			}else $tmpvar=$jieqiLang['article']['chapter_not_insale'];
			//加入文字水印
			if(!empty($jieqiConfigs['article']['textwatermark']) && JIEQI_MODULE_VTYPE != '' && JIEQI_MODULE_VTYPE != 'Free'){
				$contentary=explode('<br />
	<br />', $tmpvar);
				$tmpvar='';
				foreach($contentary as $v){
					if(empty($tmpvar)) $tmpvar.=$v;
					else{
						srand((double) microtime() * 1000000);
						$randstr='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$randlen=rand(10, 20);
						$randtext = '';
						$l = strlen($randstr)-1;
						for($i = 0;$i < $randlen; $i++){
							$num = rand(0, $l);
							$randtext .= $randstr[$num];
						}
						$textwatermark=str_replace('<{$randtext}>', $randtext, $jieqiConfigs['article']['textwatermark']);
						$tmpvar.='<br />
	'.$textwatermark.'<br />'.$v;
					}
				}
			}
			$attachurl = jieqi_uploadurl($jieqiConfigs['article']['attachdir'], $jieqiConfigs['article']['attachurl'], 'article').jieqi_getsubdir($this->package->id).'/'.$this->package->id.'/'.$chapterid;
			if(!$jieqiConfigs['article']['packdbattach']){
				//检查附件(检查文件是否存在)
				$attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article').jieqi_getsubdir($this->package->id).'/'.$this->package->id.'/'.$chapterid;

				if(is_dir($attachdir)){
					$attachimage='';
					$attachfile='';
					$files=array();
					$dirhandle = @opendir($attachdir);
					while ($file = @readdir($dirhandle)) {
						if($file != '.' && $file != '..'){
							$files[] = $file;
						}
					}
					@closedir($dirhandle);
					sort($files);
					$image_code=$jieqiConfigs['article']['pageimagecode'];

					if(empty($image_code) || !preg_match('/\<img/is', $image_code))	$image_code='<div class="divimage"><img src="<{$imageurl}>" border="0" class="imagecontent"></div>';
					foreach($files as $file){
						if (is_file($attachdir.'/'.$file)){
							$url=$attachurl.'/'.$file;
							if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$file)){
								$attachimage.=str_replace('<{$imageurl}>', $url, $image_code);
							}else{
								$attachfile.='<strong>file:</strong><a href="'.$url.'">'.$url.'</a>('.ceil(filesize($attachdir.'/'.$file)/1024).'K)<br /><br />';
							}
						}
					}
					if(!empty($attachimage) || !empty($attachfile)){
						if(!empty($tmpvar)) $tmpvar.='<br /><br />';
						$tmpvar.=$attachimage.$attachfile;
					}
				}
			}else{
				//检查附件-从数据库判断是不是有附件
				//global $package_query;
				$sql="SELECT attachment FROM ".$this->dbprefix('article_chapter')." WHERE chapterid=".intval($chapterid);
				$res=$this->db->execute($sql);
				$row=$this->db->fetchArray($res);
				$attachary=array();
				if(!empty($row['attachment'])){
					$attachary=unserialize($row['attachment']);
				}
				if(is_array($attachary) && count($attachary)>0){
					$attachimage='';
					$attachfile='';
					$image_code=$jieqiConfigs['article']['pageimagecode'];
					if(empty($image_code) || !preg_match('/\<img/is', $image_code))	$image_code='<div class="divimage"><img src="<{$imageurl}>" border="0" class="imagecontent"></div>';
					foreach($attachary as $attachvar){
						$url=$attachurl.'/'.$attachvar['attachid'].'.'.$attachvar['postfix'];
						if($attachvar['class']=='image'){
							$attachimage.=str_replace('<{$imageurl}>', $url, $image_code);
						}else{
							$attachfile.='<strong>file:</strong><a href="'.$url.'">'.$url.'</a>('.ceil($attachvar['size']/1024).'K)<br /><br />';
						}
					}
					if(!empty($attachimage) || !empty($attachfile)){
						if(!empty($tmpvar)) $tmpvar.='<br /><br />';
						$tmpvar.=$attachimage.$attachfile;
					}
				}
			}
			$data['chapter']['content'] = $tmpvar;
			$data['chapter']['index_page'] = $this->geturl('article', 'index', 'aid='.$this->package->id);
			if($preid>0){
				$tmpvar=$this->package->getCid($this->package->chapters[$preid-1]['href']);
				$data['chapter']['preview_page'] = $this->geturl('article', 'reader', 'aid='.$this->package->id,'cid='.$tmpvar);
				$data['chapter']['pre_chapterid'] = $tmpvar;

				$data['chapter']['first_page'] = 0;
			}else{
				$data['chapter']['preview_page'] = $data['chapter']['index_page'];
		        $data['chapter']['first_page'] = 1;
			}

			if($nextid>0){
				$tmpvar=$this->package->getCid($this->package->chapters[$nextid-1]['href']);
				$data['chapter']['next_page'] = $this->geturl('article', 'reader', 'aid='.$this->package->id,'cid='.$tmpvar);
				$data['chapter']['next_chapterid'] = $tmpvar;
				$data['chapter']['last_page'] = 0;
			}else{
			    $data['chapter']['next_page'] = $data['chapter']['index_page'];
		        $data['chapter']['last_page'] = 1;
			}
			return $data['chapter'];
		}*/
/*		//实例化文章对象
		function init($aid){
		     if(!is_object($this->package)){
			      $this->package = $this->load('article','article');//加载文章处理类
			      $this->package->instantPackage($aid);
				  if(!$this->package->loadOPF()){
				      global $jieqiLang;
					  if(!isset($jieqiLang)){
					      $this->addLang('article','article');
						  $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
					  }
					  if(!$this->package->article_repack($aid, array('makeopf'=>1))) $this->printfail($jieqiLang['article']['article_not_exists']);
					  //else $package->loadOPF();
				  }
			 }
		}*/
}

?>