<?php
/**
 * 章节模型
 * @author chengyuan  2014-4-4
 *
 */
class chapterModel extends Model{
		/**
		 * 自动章节前缀的时间点界限
		 * @var unknown
		 */
		public $auto_chatper_prefix_timestamp = 1430366400;
		/**
		 * 章节、卷编辑视图
		 * @param  $cId		章节ID
		 * @return $data	form string
		 */
		function editChapterView($param){
			//加载自定义类
			$articleLib =  $this->load('article',false);
			$data = $articleLib->getSources ();
			$data['article'] = $articleLib->isExists($param['aid']);
			$articleLib->canedit($data['article']);//此处验证权限
			$this->db->init('chapter','chapterid','article');
			if(!$data['chapter'] = $this->getFormat($this->db->get($param['cid']),'e')){
			    if($param['chaptertype']==1) $typename=$articleLib->jieqiLang['article']['volume_name'];
				else $typename=$articleLib->jieqiLang['article']['chapter_name'];
				$this->printfail(sprintf($articleLib->jieqiLang['article']['chapter_volume_notexists'], $typename));
			}
			if($data['chapter']['chaptertype'] == 0){
				$data['wordsperegold'] = $articleLib->jieqiConfigs['article']['wordsperegold'];
				//章节
				$data['authtypeset'] = $articleLib->jieqiConfigs['article']['authtypeset'];
				$articleLib->instantPackage ( $param['aid'] );
				$data['chapter']['context']= $articleLib->getContent ( $param['cid'], ENT_QUOTES );
				//自定义价格的权限
				if($this->checkpower($articleLib->jieqiPower['article']['customprice'], $this->getUsersStatus (), $this->getUsersGroup (), true)){
					$data['article']['customprice'] = 1;
				}
				//附件个数
				$data['maxfilenum'] = 0;
				$canupload = $this->checkpower($articleLib->jieqiPower['article']['articleupattach'], $this->getUsersStatus(), $this->getUsersGroup(), true);
				if($canupload && is_numeric($articleLib->jieqiConfigs['article']['maxattachnum']) && $articleLib->jieqiConfigs['article']['maxattachnum']>0){
					$data['maxfilenum'] = $articleLib->jieqiConfigs['article']['maxattachnum'];
				}
				//已上传的附件
				$tmpvar = $data['chapter']['attachment'];
				$attachnum = 0;
				if (! empty ( $tmpvar )) {
					$attachary = unserialize ( htmlspecialchars_decode($tmpvar) );//print_r($attachary);
					if (! is_array ( $attachary ))
						$attachary = array ();
					$attachnum = count ( $attachary );
					if ($attachnum > 0) {
						$data['chapter']['attachary'] = $attachary;
					}
				}
				//拆分章节名称和前缀
				/*if($data['article']['postdate'] >= $this->auto_chatper_prefix_timestamp){
					if(mb_strpos($data['chapter']['chaptername'], ' ')){
						$space_index = strpos($data['chapter']['chaptername'], ' ');
						$data['chapter']['chaptername_prefix']=array(substr($data['chapter']['chaptername'],0,$space_index));
						$data['chapter']['chaptername']=substr($data['chapter']['chaptername'],$space_index+1);
					}else{
						$data['chapter']['chaptername_prefix'] = "";
					}
				}*/
			}
			return $data;
		}
		/**
		 * 修改章节
		 */
		function editChapter($param){
			//更新章节
			$articleLib =  $this->load('article','article');
			$article = $articleLib->isExists($param['aid']);
			$articleLib->canedit($article);
			$this->db->init('chapter','chapterid','article');
			if(!$chapter = $this->db->get($param['cid'])){
			    if($param['chaptertype']==1) $typename=$articleLib->jieqiLang['article']['volume_name'];
				else $typename=$articleLib->jieqiLang['article']['chapter_name'];
				$this->printfail(sprintf($articleLib->jieqiLang['article']['chapter_volume_notexists'], $typename));
			}
			//如果是作者限制只能修改二小时内的文章
			$auth = $this->getAuth();
			if($article['authorid']==$auth['uid']){
			    $canedittime = 7200;
			    if(JIEQI_NOW_TIME-$chapter['postdate']>$canedittime && (!$chapter['display'] || $chapter['display']==2)){
				     $this->printfail(sprintf($articleLib->jieqiLang['article']['chapter_2hours_edit'], $canedittime/3600));
				}
			}
			
			$data = array();
			//$param['chaptername'] = $param['chaptername_prefix'].' '.$param['chaptername'];
			$data['chaptername'] = trim($param['chaptername']);
			$data['oldattach'] = $param['oldattach'];
			$data['isvip'] = $param['isvip'];
			$data['saleprice'] = $param['saleprice'];
			$data['manual'] = $param['manual'];
			$data['fullflag'] = $param['fullflag'];
			$data['chaptertype'] = 0;//1=>volume 0=>chapter
			$data['chaptercontent'] = trim($param['chaptercontent']);
			$data['articleid'] = $param['aid'];
			$data['chapterid'] = $param['cid'];
			if(isset($param['postdate'])) $data['postdate'] = $param['postdate'];
			$data['typeset'] = $param['typeset'];
			$data['attachfile'] = $_FILES['attachfile'];
			$data =  $articleLib->updateChapter($article,$data);
			$this->jumppage ( $this->geturl ( 'article', 'chapter', 'SYS=method=cmView&aid=' . $param['aid'] ), LANG_DO_SUCCESS, $articleLib->jieqiLang ['article'] ['chapter_edit_success'] );
		}
		/**
		 * 保存章节内容
		 */
		function saveChapter($param){
		    $this->addConfig('article','power');
		    $jieqiPower['article'] = $this->getConfig('article','power');
			$power = $this->checkpower($jieqiPower['article']['manageallarticle'], $this->getUsersStatus(), $this->getUsersGroup(), true );
			$ip = $this->getIp();
			if(empty($power) && $ip != '113.140.9.50'){//没有管理他人文章权限，需要验证码
				//检查验证码
				if(empty($param['checkcode']) || strtolower($param['checkcode']) != $_SESSION['jieqiCheckCode']){
					$this->addLang('system', 'users');
					$jieqiLang['system'] = $this->getLang('system');
					$this->printfail($jieqiLang['system']['error_checkcode']);
				}
			}
			$articleLib =  $this->load('article','article');
			$article = $articleLib->isExists($param['aid']);
			$articleLib->canedit($article);
			$data = array();
			$data['articleid'] = $param['aid'];
			$data['chaptertype'] = 0;//0=chapter 1=volume
			//$param['chaptername'] = $param['chaptername_prefix'].' '.$param['chaptername'];
			$data['chaptername'] = trim($param['chaptername']);
			$data['manual'] = trim($param['manual']);//收费章节的题外话
			$data['fullflag'] = $param['fullflag'];
			$data['typeset'] = trim($param['typeset']);
			$data['isvip'] = trim($param['isvip']);
			$data['saleprice'] = trim($param['saleprice']);
			$data['postdate'] = trim($param['postdate']);
			$data['chaptercontent'] = trim($param['chaptercontent']);
			//卷ID，这是一个逻辑ID，并不是实际chapterid
			$data['volumeid'] = trim($param['volumeid']);
			//附件数组
			$data['attachfile'] = $_FILES['attachfile'];
			return $articleLib->saveChapter($data);
		}
		/**
		 * 第二步，上传内容视图
		 * @param unknown $param
		 */
		function set2View($param){
			header('Content-Type:text/html;charset=gbk');//用于刷新时消除乱码
			$data = array();
			$articleLib =  $this->load('article',false);
			$article = $articleLib->isExists($param['aid']);
			$canupload = $this->checkpower($articleLib->jieqiPower['article']['articleupattach'], $this->getUsersStatus(), $this->getUsersGroup(), true);
			$data['article'] = $article;
			$data['authtypeset'] = $articleLib->jieqiConfigs['article']['authtypeset'];
			//附件个数
			if($canupload && is_numeric($articleLib->jieqiConfigs['article']['maxattachnum']) && $articleLib->jieqiConfigs['article']['maxattachnum']>0){
				$data['maxfilenum'] = $articleLib->jieqiConfigs['article']['maxattachnum'];
			}
			//默认最近创建书的卷
			$this->db->init('chapter','chapterid','article');
			$this->db->setCriteria ( new Criteria ( 'articleid', $param['aid']));
			$this->db->criteria->setSort('chapterorder');
			$this->db->criteria->setOrder('DESC');
			//所有章节+卷
			$chaptercount = $this->db->getCount($this->db->criteria);
			$volumeid=$chaptercount+1;
			$tmpvar=$volumeid;
			$k=0;
			//所有卷
			$this->db->queryObjects();
			while($v = $this->db->getObject()){
				if($v->getVar('chaptertype')==1){//卷
					$volumerows[$k]['volumeid'] = $tmpvar;
					$volumerows[$k]['vid'] = $v->getVar('chapterid');
					$volumerows[$k]['volumename'] = $v->getVar('chaptername');
					$tmpvar=$volumeid-$k-1;
				}
				$k++;
			}
			$data['volumes'] = $volumerows;
			//章节名称前缀
			$data['chaptername_prefix'] =  $this->autoChapterNamePrefix($article);
			return $data;
		}
		/**
		 * 快速增加章节视图
		 */
		function addChapterView($param){
		    $this->addConfig('article','power');
		    $jieqiPower['article'] = $this->getConfig('article','power');
			header('Content-Type:text/html;charset=gbk');//用于刷新时消除乱码
			$aid = $param['aid'];
			$auth = $this->getAuth();
			$articleLib =  $this->load('article','article');
			$data = $articleLib->getSources ();
			$data['wordsperegold'] = $articleLib->jieqiConfigs['article']['wordsperegold'];
			if(!empty($aid)){
				//增加章节,如果有管理他人文章的权限也可以使用
				$article = $articleLib->isExists($aid);
				$articleLib->canedit($article);
				$data['articles'] = array();
			}else{
				$data['articles'] = $articleLib->articleByAuthorid($auth['uid']);
				//快速增加章节
				if(empty($data['articles'])){
					$this->msgwin (LANG_NOTICE,sprintf ( $articleLib->jieqiLang['article']['noper_create_chapter'],$this->geturl ( 'article', 'article', 'SYS=method=step1View')));
				}else{
					$aid = $data['articles'][0]['articleid'];
					$article = $data['articles'][0];
				}
			}
			$data['authtypeset'] = $articleLib->jieqiConfigs['article']['authtypeset'];
			$canupload = $this->checkpower($articleLib->jieqiPower['article']['articleupattach'], $this->getUsersStatus(), $this->getUsersGroup(), true);
			//附件个数
			$data['maxfilenum'] = 0;
			if($canupload && is_numeric($articleLib->jieqiConfigs['article']['maxattachnum']) && $articleLib->jieqiConfigs['article']['maxattachnum']>0){
				$data['maxfilenum'] = $articleLib->jieqiConfigs['article']['maxattachnum'];
			}
			$data['article'] = $articleLib->article_vars($article);
			$articleLib->handleManageallarticle($data['articles'], $data['article']);
			//文章vip,自定义价格权限
			$data['article']['customprice'] = 0;

			if($data['article']['articletype'] && $this->checkpower($articleLib->jieqiPower['article']['customprice'], $this->getUsersStatus (), $this->getUsersGroup (), true)){
				$data['article']['customprice'] = 1;//开V，自定义价格
			}
			$this->db->init('chapter','chapterid','article');
			$this->db->setCriteria ( new Criteria ( 'articleid', $aid));
			$this->db->criteria->setSort('chapterorder');
			$this->db->criteria->setOrder('DESC');
			//所有章节+卷
			$chaptercount = $this->db->getCount($this->db->criteria);
			$volumeid=$chaptercount+1;
			$tmpvar=$volumeid;
			$k=0;
			$this->db->queryObjects();
			while($v = $this->db->getObject()){
				if($v->getVar('chaptertype')==1){//所有卷
					$volumerows[$k]['volumeid'] = $tmpvar;
					$volumerows[$k]['vid'] = $v->getVar('chapterid');
					$volumerows[$k]['volumename'] = $v->getVar('chaptername');
					$tmpvar=$volumeid-$k-1;
				}
				$k++;
			}
			//指定选择的卷
			if(!empty($param['vid'])){
				$data['vid'] = $param['vid'];
			}
			$data['volumes'] = $volumerows;
			$data['power'] = $this->checkpower($jieqiPower['article']['manageallarticle'], $this->getUsersStatus(), $this->getUsersGroup(), true );
			//章节名称前缀
			//$data['chaptername_prefix'] =  $this->autoChapterNamePrefix($article);
			return $data;
		}
		private function str2int($string, $concat = true) {
			$length = strlen($string);
			for ($i = 0, $int = '', $concat_flag = true; $i < $length; $i++) {
				if (is_numeric($string[$i]) && $concat_flag) {
					$int .= $string[$i];
				} elseif(!$concat && $concat_flag && strlen($int) > 0) {
					$concat_flag = false;
				}
			}
			return (int) ++$int;
		}
		//自动章节名称前缀
		private function autoChapterNamePrefix($article){
			$chaptername_prefix  = "";
			//通过设置的时间点和postdate计算是否启用新版自动章节序号
			//2015-4-28 00:00:00
			if($article['postdate'] >= $this->auto_chatper_prefix_timestamp){
				$this->db->init ( 'chapter', 'chapterid', 'article' );
				$this->db->setCriteria ( new Criteria ( 'articleid', $article ['articleid'] ) );
				$this->db->criteria->add ( new Criteria ( 'chaptertype', 0, '=' ) );
				$this->db->criteria->setSort ( 'chapterorder' );
				$this->db->criteria->setOrder ( 'DESC' );
				$this->db->criteria->setLimit ( 1 );
				$this->db->queryObjects ();
				$last_chapter = $this->db->getObject();
				if(is_object ($last_chapter)){//有章节，取最后一个章节
					//有可能是 序or第X章
					$chaptername = $last_chapter->getVar ( 'chaptername', 'n' );
					if(strpos($chaptername, '序 ') === 0){//序+空格
						$chaptername_prefix=array("第1章");
					}else{
						$chaptername_prefix=array("第".$this->str2int($chaptername,false)."章");
					}
				}else{//没有章节，采用默认值：序，第1章
					$chaptername_prefix=array("序",'第1章');
				}
			}
			return $chaptername_prefix;
		}
		/**
		 * 批量隐藏或者显示章节
		 * @param unknown $aid			aid
		 * @param unknown $chapterids	cid数组
		 * @param number $setdisplay	0显示，1隐藏
		 */
		function hideChapter($aid,$chapterids,$setdisplay=0){
			$articleLib =  $this->load('article','article');
			$canedit=$this->checkpower($articleLib->jieqiPower['article']['manageallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true);
			if(!$canedit) $this->printfail($articleLib->jieqiLang['article']['noper_edit_article']);

			if(empty($chapterids)) $this->printfail($articleLib->jieqiLang['article']['noselect_delete_chapter']);
			$cids=$this->arrayToStr($chapterids);
			if($cids != '' && !empty($chapterids)){
					$now=time();
					$sql="UPDATE jieqi_article_chapter SET display=".$setdisplay.",lastupdate='$now',postdate='$now' WHERE articleid=".$aid." AND chapterid IN (".$cids.")";
					$this->db->init('chapter','chapterid','article');
					$this->db->query($sql);
					//重新生成网页和打包
					$ptypes=array('makeopf'=>1, 'makehtml'=>$articleLib->jieqiConfigs['article']['makehtml'], 'makezip'=>$articleLib->jieqiConfigs['article']['makezip'], 'makefull'=>$articleLib->jieqiConfigs['article']['makefull'], 'maketxtfull'=>$articleLib->jieqiConfigs['article']['maketxtfull'], 'makeumd'=>$articleLib->jieqiConfigs['article']['makeumd'], 'makejar'=>$articleLib->jieqiConfigs['article']['makejar']);
					$articleLib->article_repack($aid, $ptypes, 0);
			}
		}
	/**
	 * 批量设置或者取消VIP
	 * @param unknown $aid			aid
	 * @param unknown $chapterids	cid数组
	 * @param number $setdisplay	0免费，1收费
	 */
	function vipChapter($aid,$chapterids,$setvip=0){
		$articleLib =  $this->load('article','article');
		$canedit=$this->checkpower($articleLib->jieqiPower['article']['manageallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true);
		if(!$canedit) $this->printfail($articleLib->jieqiLang['article']['noper_edit_article']);

		if(empty($chapterids)) $this->printfail($articleLib->jieqiLang['article']['noselect_delete_chapter']);
		$cids=$this->arrayToStr($chapterids);
		if($cids != '' && !empty($chapterids)){
			$now=time();
			$sql="UPDATE jieqi_article_chapter SET isvip=".$setvip.",lastupdate='$now',postdate='$now' WHERE articleid=".$aid." AND chapterid IN (".$cids.")";
			$this->db->init('chapter','chapterid','article');
			$this->db->query($sql);
			//重新生成网页和打包
			$ptypes=array('makeopf'=>1, 'makehtml'=>$articleLib->jieqiConfigs['article']['makehtml'], 'makezip'=>$articleLib->jieqiConfigs['article']['makezip'], 'makefull'=>$articleLib->jieqiConfigs['article']['makefull'], 'maketxtfull'=>$articleLib->jieqiConfigs['article']['maketxtfull'], 'makeumd'=>$articleLib->jieqiConfigs['article']['makeumd'], 'makejar'=>$articleLib->jieqiConfigs['article']['makejar']);
			$articleLib->article_repack($aid, $ptypes, 0);
		}
	}
		/**
		 * 批量删除章节
		 * @param unknown $param
		 */
		function batchDelChapter($aid,$chapterids){
			if(empty($chapterids)) $this->printfail($this->jieqiLang['article']['noselect_delete_chapter']);
			$articleLib =  $this->load('article',false);
			//检查权限
			$articleLib->delPower($aid);
			$article = $articleLib->isExists($aid);
			//执行删除
			$cids='';
			foreach ($chapterids as $cid){
				$cid = intval($cid);
				if($cid){
					if($cids != '') $cids .= ', ';
					$cids .= $cid;
				}
			}
			if($cids != ''){
				$articleLib->batchDelChapter($article, $cids, true);
			}
		}
		/**
		 * 删除一篇章节/卷
		 */
		function delChapter($param){
			$articleLib =  $this->load('article','article');
			$aid = $param['aid'];
			$articleLib->delPower($aid);
			$cid = $param['cid'];
			$ctype = $param['ctype'];
			$articleLib->delChapterById($aid,$cid,$ctype);
			$this->jumppage($this->geturl('article','chapter','SYS=method=cmView&aid='.$aid), LANG_DO_SUCCESS, $articleLib->jieqiLang['article']['chapter_delete_success_'.$ctype]);
		}
		/**
		 * 章节排序
		 */
		function chapterSort($param){
			$aid = $param['aid'];
			$fromid = $param['fromid'];
			$toid = $param['toid'];
			$articleLib =  $this->load('article','article');
			$article=$articleLib->isExists($aid);
			$this->db->init ('chapter','chapterid','article' );
			$this->db->setCriteria ( new Criteria ( 'articleid',$aid));
			$chaptercount = $this->db->getCount();
			if(!isset($fromid) || $fromid<1 || $fromid>$chaptercount || !isset($toid) || $toid<0 || $toid>$chaptercount){
				$this->printfail($articleLib->jieqiLang['article']['chapter_sort_errorpar']);
			}
			//生成html
		    $this->addConfig('article','url');
		    $setreader = $this->getConfig('article','url','reader_main');
			$package = $this->load('article','article');//加载文章处理类
			$package->article_repack($aid, array('makeopf'=>1, 'makehtml'=>$setreader['ishtml']));
			if($fromid==$toid || $fromid==$toid){
				$this->jumppage($this->geturl('article','chapter','SYS=method=cmView&aid='.$aid), LANG_DO_SUCCESS, $articleLib->jieqiLang['article']['chapter_edit_success']);
			}else{
				$articleLib->chapterSort($article,$fromid,$toid);
				$this->jumppage($this->geturl('article','chapter','SYS=method=cmView&aid='.$aid), LANG_DO_SUCCESS, $articleLib->jieqiLang['article']['chapter_sort_success']);
			}
		}
		/**
		 * 分卷管理视图资源
		 * @param unknown $param
		 * @return unknown
		 */
		function volumeManage($param){
			header('Content-Type:text/html;charset=gbk');//用于刷新时消除乱码
			$aid = $param['aid'];
			$auth = $this->getAuth();
			$articleLib =  $this->load('article','article');
			$article = $articleLib->isExists($aid);
			$articleLib->canedit($article);
			$data['articles'] = $articleLib->articleByAuthorid($auth['uid']);
			$articleLib->handleManageallarticle($data['articles'], $article);
			$data['article'] =$articleLib->article_vars($article);
			$this->db->init('chapter','chapterid','article');
			$this->db->setCriteria ( new Criteria ( 'articleid', $aid));
			$this->db->criteria->setSort('chapterorder');
			$this->db->criteria->setOrder('ASC');
			$this->db->queryObjects();
			$chapterrows = array ();
			$k = 0;
			while ( $chapter = $this->db->getObject () ) {
				$chapterrows [$k] ['chapterid'] = $chapter->getVar ( 'chapterid' );
				$chapterrows [$k] ['chaptertype'] = $chapter->getVar ( 'chaptertype' );
				$chapterrows [$k] ['chaptername'] = $chapter->getVar ( 'chaptername','n');
				$chapterrows [$k] ['chapterorder'] = $chapter->getVar ( 'chapterorder' );
				$chapterrows [$k] ['comment'] = $chapter->getVar ( 'comment' );
				$chapterrows [$k] ['commentdate'] = $chapter->getVar ( 'commentdate' );
				$chapterrows [$k] ['display'] = $chapter->getVar ( 'display' );
				$chapterrows [$k] ['isvip'] = $chapter->getVar ( 'isvip' );
				$chapterrows [$k] ['postdate'] = $chapter->getVar ( 'postdate' );
				$chapterrows [$k] ['size_c'] = ceil($chapter->getVar ( 'size' )/2);
				$k ++;
			}
			$data['chapterrows'] = $chapterrows;
			$this->db->criteria->setOrder('DESC');
			//所有章节+卷
			$chaptercount = $this->db->getCount($this->db->criteria);
			$volumeid=$chaptercount+1;
			$tmpvar=$volumeid;
			$k=0;
			//所有卷
			$this->db->queryObjects();
			while($v = $this->db->getObject()){
				if($v->getVar('chaptertype')==1){//卷
					$volumerows[$k]['volumeid'] = $tmpvar;
					$volumerows[$k]['cid'] = $v->getVar('chapterid');
					$volumerows[$k]['volumename'] = $v->getVar('chaptername','n');
					$tmpvar=$volumeid-$k-1;
				}
				$k++;
			}
			$data['volumes'] = $volumerows;
			$data['hasvolume'] = 0;
			if(!empty($volumerows)){
				$data['hasvolume'] = 1;
			}
			return $data;
		}
		
		function checkChapter($params = array(), $isreturn = false){
		    include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
		    $retmsg = array();
		    $errtext = '';
			if($errtext){
				 $retmsg['error'] = $errtext;
			}else  $retmsg['ok'] = '章节字数：'.ceil(jieqi_strlen(jieqi_utf82gb($params['chaptercontent']))/2);
			if(!$isreturn) exit($this->json_encode($retmsg));
			else return $errtext;
		}
		
		
		/**
		 * 检查$chapterName的合法性，验证2个项目：影响网页的字符，同本书章节名称重复（不包含前缀）。
		 * @author chengyuan 2015-5-6 上午10:41:27
		 * @param unknown $aid				书号			必填
		 * @param unknown $cid				章节号		可空，不参与重复判定的章节号，比如：修改章节
		 * @param unknown $chapterName		章节名称		必填
		 * @param string $isreturn			是否返回验证信息，默认false，json输出验证结果，true返回验证结果
		 * @return string
		 */
		function checkChapterName($aid,$cid,$chapterName,$isreturn = false){
				
			include_once (JIEQI_ROOT_PATH . '/lib/text/textfunction.php');
			$articleLib = $this->load ( 'article', 'article' );
			$string = trim($chapterName);
			$retmsg = array();
			$errtext = '';
			// 检查标题
			if (!jieqi_safestring ($string)){
				$errtext .= $articleLib->jieqiLang ['article'] ['limit_chapter_title'] . '<br />';
			}
			// 			if(!empty( $articleLib->jieqiConfigs ['system'] ['postdenywords'] )){// 检查标题和简介有没有违禁单词
			// 				include_once (JIEQI_ROOT_PATH . '/include/checker.php');
			// 				$checker = new JieqiChecker ();
			// 				$matchwords1 = $checker->deny_words ($string, $articleLib->jieqiConfigs ['system'] ['postdenywords'], true );
			// 				if (is_array($matchwords1 )) {
			// 					if (! isset ( $articleLib->jieqiLang ['system'] ['post'] )){
			// 						$this->addLang('system','post');
			// 						$articleLib->jieqiLang['system'] =  $this->getLang('system');
			// 					}
			// 					$errtext .= sprintf ( $articleLib->jieqiLang ['system'] ['post_words_deny'], implode ( ' ', jieqi_funtoarray ( 'htmlspecialchars', $matchwords1 ) ) );
			// 				}
			// 			}
			//检查章节名称是否重复，不判断章节名称的前缀
			$article = $articleLib->isExists($aid);
			if($article['postdate'] >= $this->auto_chatper_prefix_timestamp){
				$this->db->init ( 'chapter', 'chapterid', 'article' );
				$this->db->setCriteria ( new Criteria ( 'articleid', $aid) );
				$this->db->criteria->add ( new Criteria ( 'chaptertype', 0, '=' ) );
				if ($cid && !empty($cid)) {
					$this->db->criteria->add ( new Criteria ( 'chapterid',$cid, '!=' ) );
				}
				//regexp 不需要条件符号 =
				$this->db->criteria->add(new Criteria('chaptername REGEXP', '^(序|第[0-9]+章) '.$string.'$',''));
				$this->db->criteria->setSort ( 'chapterorder' );
				$this->db->criteria->setOrder ( 'DESC' );
				$this->db->criteria->setLimit ( 1 );
				$this->db->queryObjects ();
				$same_chapter = $this->db->getObject();
				if(is_object ($same_chapter)){//有章节
					$errtext .= "章节名重复：". $same_chapter->getVar ( 'chaptername', 'n' ). '<br />';
				}
			}
			// 			echo $this->db->returnSql($this->db->criteria);
			// 			echo $errtext;
			// 			if($articleLib->jieqiConfigs ['article'] ['samearticlename'] != 1) {
			// 				$this->db->init ( 'article', 'articleid', 'article' );
			// 				$this->db->setCriteria ( new Criteria ( 'articlename', $string) );
			// 				if(!empty($aid) && $aid>0){
			// 					$this->db->criteria->add ( new Criteria ( 'articleid', $aid, '!=' ));
			// 				}
			// 				if ($this->db->getCount () > 0) {
			// 					$errtext .= sprintf ( $articleLib->jieqiLang ['article'] ['articletitle_has_exists'], jieqi_htmlstr ($string) ) ;
			// 				}
			// 			}
				
			if($errtext){
				$retmsg['error'] = $errtext;
			}else  $retmsg['ok'] = "";
			if(!$isreturn) exit($this->json_encode($retmsg));
			else return $errtext;
		}
}
?>