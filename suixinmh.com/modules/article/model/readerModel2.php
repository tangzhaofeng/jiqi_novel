<?php
/**
 * 章节阅读模型 * @copyright   Copyright(c) 2014
 * @author      huliming* @version     1.0
 */
class readerModel extends Model{
        //public $package;

	public function main($params = array(),$obj = NULL){
		 $package = $this->load('article','article');
		 if(!$package->loadOPF($params['aid'])){
			 $this->addLang('article','article');
			 $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
			 $this->printfail($jieqiLang['article']['article_not_exists']);
		 }
		 return $package->showChapter($params['cid'],$obj);
	}

	//购买VIP章节
	public function buychapter($params = array()){
	    $this->addLang ( 'article', 'article' );
		$jieqiLang ['article'] = $this->getLang ( 'article' ); // 所有语言包配置赋值
		$this->db->init( 'chapter', 'chapterid', 'article' );
		$this->db->setCriteria();
		$this->db->criteria->setTables($this->dbprefix('article_chapter'). ' as c left join '.$this->dbprefix('article_article'). ' as a on c.articleid=a.articleid');
		$this->db->criteria->setFields('c.*,a.articlename,a.authorid,a.display as adisplay');
		$this->db->criteria->add(new Criteria('chapterid', $params['cid'], '='));
		$chapter = $this->db->get($this->db->criteria);
		if(!$chapter || $chapter->getVar('display') || $chapter->getVar('adisplay')) $this->printfail($jieqiLang['article']['not_in_sale']);//文章或者章节待审

		if($params['readurl']){
			$readurl = $params['readurl'];
		}else{
			$readurl = $this->geturl('article', 'reader', 'aid='.$chapter->getVar('articleid'),'cid='.$params['cid']);
		}
		if(!$chapter->getVar('saleprice')){
		     $package = $this->load('article', 'article');
		     $package->article_repack($chapter->getVar('articleid'), array('makeopf'=>1), 1);
		     $this->jumppage($readurl, LANG_DO_SUCCESS, $jieqiLang['article']['batch_buy_success']);
		}
		$auth = $this->getAuth();
		$users_handler =  $this->getUserObject();//查询用户是否存在
		$users = $users_handler->get($auth['uid']);
		if(!is_object($users) || $users->getVar('groupid')==1) $this->printfail($jieqiLang['article']['need_user_login']);
		$articlename=$chapter->getVar('articlename','n');
		$chaptername=$chapter->getVar('chaptername','n');
		$saleprice=$chapter->getVar('saleprice', 'n');//章节价格
		$useregold=$users->getVar('egold', 'n');//账号余额
		$pricetype=0;//默认金币支付
		//if($useregold >= $saleprice) $pricetype=0;
		//else $pricetype=1;
		//查询折扣
		$this->addConfig('system','vipgrade');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$vipgrade = jieqi_gethonorarray($auth['vip'], $jieqiVipgrade);//VIP等级数组
		if($vipgrade['setting']['dingyuezhekou']>0){
		    $saleprice=$saleprice*$vipgrade['setting']['dingyuezhekou'];
		}
		if($useregold < $saleprice) {
		    $users_handler->saveToSession($users);
		    if (JIEQI_MODULE_NAME == 'wap'){
		    	$url = $this->geturl('wap', 'pay');
		    }else{
		    	$url = $this->geturl('pay', 'home');
		    }
			$this->printfail(sprintf($jieqiLang['article']['chapter_money_notenough'], $articlename, $chaptername, $saleprice.' '.JIEQI_EGOLD_NAME, $useregold.' '.JIEQI_EGOLD_NAME, $url));//余额不足
		}
		if($this->checkChapterIsBuy($params)) $this->printfail(sprintf($jieqiLang['article']['chapter_has_buyed'], $articlename, $chaptername, $readurl));
		$this->db->init('sale','saleid','article');
// 		$osale['siteid'] = JIEQI_SITE_ID;
		global $jieqiModules;
		$osale['siteid'] = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];
		$osale['buytime']  = JIEQI_NOW_TIME;
		$osale['accountid'] = $auth['uid'];
		$osale['account'] = $auth['username'];
		$osale['chapterid'] = $chapter->getVar('chapterid', 'n');
		$osale['chaptername'] = $chaptername;
		$osale['articleid'] = $chapter->getVar('articleid');
		$osale['articlename'] = $articlename;
		$osale['saleprice'] = $saleprice;
		$osale['pricetype'] = $pricetype;
		$osale['paytype'] = 0;
		$osale['payflag'] = 0;
		$osale['paynote'] = '';
		$osale['state'] = 0;
		$osale['flag'] = 0;
		if(!$this->db->add($osale)) $this->printfail($jieqiLang['article']['add_osale_faliure']);
		//记录购买日志
		if(!is_object($package)) $package = $this->load('article', 'article');
		$package->addArticleStat($chapter->getVar('articleid'), $chapter->getVar('authorid', 'n'), 'sale', $saleprice, array('chapterid'=>$chapter->getVar('chapterid', 'n'), 'chaptername'=>$chaptername));
		//扣除虚拟货币
	    if($users_handler->payout($users->getVar('uid', 'n'), $saleprice)) $this->jumppage($readurl, LANG_DO_SUCCESS, $jieqiLang['article']['batch_buy_success']);
		else $this->printfail($jieqiLang['article']['add_buyinfo_failure']);
	}
	/*
	*检查订阅情况，去除已订阅章节ID，并统计出需要订阅的章节ID和所需的金额
	*
	*/
	public function checkChapterIsBuy($params = array()){
	    $this->addConfig('article','power');
		$jieqiPower['article'] = $this->getConfig('article','power');
		if($this->checkpower($jieqiPower['article']['freeread'], $this->getUsersStatus (), $this->getUsersGroup (), true)) return true;
	    $auth = $this->getAuth();
		$this->db->init('sale','saleid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('accountid', $auth['uid'], '='));
		$this->db->criteria->add(new Criteria('chapterid', $params['cid'], '='));
		return $this->db->getCount($this->db->criteria);
	}

	/*
	*检查订阅情况，去除已订阅章节ID，并统计出需要订阅的章节ID和所需的金额
	*
	*/
	public function getAllVip($params)
	{
		$auth = $this->getAuth();
		$params['uid'] = $auth['uid'];
		$saleprice = 0;
		$buyArr = array();
		$j = 0;
		$this->db->init('sale','saleid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('articleid', $params['aid'], '='));
		$this->db->criteria->add(new Criteria('accountid', $params['uid'], '='));
		$this->db->queryObjects($this->db->criteria);

		while($buyinfo = $this->db->getObject()){
			$buyArr[$j] = $buyinfo->getVar('chapterid','n') ;
			$j++;
		}

		$this->db->init('chapter','chapterid','article');
		$this->db->setCriteria();
		$this->db->criteria->setTables($this->dbprefix('article_chapter'). ' as c left join '.$this->dbprefix('article_article'). ' as a on c.articleid=a.articleid');
		$this->db->criteria->setFields('c.*,a.authorid');
		$this->db->criteria->add(new Criteria('c.isvip', 1, '='));
		$this->db->criteria->add(new Criteria('c.display', 0, '='));
		$this->db->criteria->add(new Criteria('c.articleid', $params['aid'], '='));
		$this->db->queryObjects($this->db->criteria);

		$i = 0;
		$arr = array();
		while($v = $this->db->getObject())
		{
			if (!in_array($v->getVar('chapterid'),$buyArr) && $v->getVar('saleprice')){
				$saleprice += $v->getVar('saleprice', 'n');
				$articlename = $v->getVar('articlename','n');
				$authorid = $v->getVar('authorid','n');
				$arr[$i]['chapterid'] = $v->getVar('chapterid');
				$arr[$i]['chaptername'] = $v->getVar('chaptername','n');
				$arr[$i]['articleid'] = $v->getVar('articleid');
				$arr[$i]['articlename'] = $v->getVar('articlename','n');
				$arr[$i]['saleprice'] = $v->getVar('saleprice', 'n');
				$arr[$i]['pricetype'] = $v->getVar('pricetype', 'n');
			    $i++;
			}
		}
		return array('chapter'=>$arr,'saleprice'=>$saleprice,'num'=>$i,'articlename'=>$articlename,'authorid'=>$authorid);
	}
	/**
	 * 七夕特惠
	 * @param unknown $params
	 * 2014-7-31 下午2:10:36
	 */
	public function batchbuychapterqixi($params){
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
	}
	//批量购买章节
	public function batchbuychapter($params)
	{
		$this->addLang ( 'article', 'article' );
		$jieqiLang ['article'] = $this->getLang ( 'article' ); // 所有语言包配置赋值
		$datavip = $this->getAllVip($params);
		$auth = $this->getAuth();
		$readurl = $this->geturl('article', 'reader', 'aid='.$datavip['chapter'][0]['articleid'],'cid='.$datavip['chapter'][0]['chapterid']);
		$users_handler =  $this->getUserObject();//查询用户是否存在
		$users = $users_handler->get($auth['uid']);
		if(!is_object($users) || $users->getVar('groupid')==1) $this->printfail($jieqiLang['article']['need_user_login']);
		$useregold=$users->getVar('egold', 'n');//账号余额

		//查询折扣
		$this->addConfig('system','vipgrade');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$vipgrade = jieqi_gethonorarray($auth['vip'], $jieqiVipgrade);//VIP等级数组
		if($vipgrade['setting']['dingyuezhekou']>0){
		    $datavip['saleprice']=$datavip['saleprice']*$vipgrade['setting']['dingyuezhekou'];
		}

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
	    if($users_handler->payout($users->getVar('uid', 'n'),$datavip['saleprice'])) $this->jumppage($readurl, LANG_DO_SUCCESS, $jieqiLang['article']['batch_buy_success']);
		else $this->printfail($jieqiLang['article']['add_buyinfo_failure']);
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

	/*
	*检查是否自动购买
	*
	*/
	public function checkAutoBuy($params = array()){
	    $auth = $this->getAuth();
		$this->db->init('autobuy','autoid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('uid', $auth['uid'], '='));
		$this->db->criteria->add(new Criteria('articleid', $params['aid'], '='));
		return $this->db->getCount($this->db->criteria);
	}
	public function closebuy($params){
		$auth = $this->getAuth();
		$this->db->init('autobuy','autoid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('uid', $auth['uid'], '='));
		$this->db->criteria->add(new Criteria('articleid', $params['aid'], '='));
		return $this->db->delete($this->db->criteria);
	}
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