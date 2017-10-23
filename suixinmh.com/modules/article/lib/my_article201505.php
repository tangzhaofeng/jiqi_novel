<?php
include_once ($GLOBALS['jieqiModules']['article']['path'] . '/class/package.php');
/**
 * article业务类继承了老版本的JieqiPackage类,此类中封装了article,chapter,volume等等操作的相关方法，可以处理作者专区中的基本功能。
 * @copyright Copyright(c) 2014
 * @author chengyuan
 * @version 1.0
 */
class MyArticle extends JieqiPackage {
// 	public $myMessage = 'test variable';

	/**
	 * 免验证码登陆次数
	 * @var unknown
	 */
	public $free_checkcode_login = 2;
	/**
	 * article模块绝对路径
	 */
	public $apath = '';
	/**
	 * article 配置参数
	 */
	public $jieqiConfigs = array();
	/**
	 * article 语言参数
	 */
	public $jieqiLang = array();
	/**
	 * article 权限参数
	 */
	 public $jieqiPower = array();
	// public $controller;
	// public $lang;
	// public $package;
	// $package=new JieqiPackage($id);
	/**
	 * 默认构造器，实例$this->db
	 */
	function __construct() {
		if (! is_object ( $this->db )) {
			$this->db = Application::$_lib ['database'];
		}
// 		jieqi_getconfigs ( 'system', 'configs' );
		//article模块路径
		$this->apath = $GLOBALS['jieqiModules'] ['article'] ['path'];
		//article config
		$this->addConfig('article','configs');
		$this->jieqiConfigs['article'] = $this->getConfig('article','configs');
		//system config
		$this->addConfig('system','configs');
		$this->jieqiConfigs['system'] = $this->getConfig('system','configs');
		//sort config
		$this->addConfig('article','sort');
		$this->jieqiConfigs['sort'] = $this->getConfig('article','sort');
		//option config
		$this->addConfig('article','option');
		$this->jieqiConfigs['option'] = $this->getConfig('article','option');
		//channel config
// 		$this->addConfig('article','channel');
// 		$this->jieqiConfigs['channel'] = $this->getConfig('article','channel');
		//article lange
		$this->addLang('article','article');
		//$this->jieqiLang['article'] =  $this->getLang('article');
		//jieqi_loadlang('applywriter', JIEQI_MODULE_NAME);
		$this->addLang('article','applywriter');
		$this->jieqiLang['article'] =  $this->getLang('article');
		//power config
		$this->addConfig('article','power');
		$this->jieqiPower['article'] = $this->getConfig('article','power');
	}
	/**
	 * 如果需要使用package必须调用此方法先初始化package
	 *
	 * @param $id 文章id
	 */
	function instantPackage($id) {
		$this->JieqiPackage ( $id );
	}
	/**
	 * 章节上传附件的目录，如果没有则尝试创建，并返回目录。
	 * @param  $aid		articleid
	 * @param  $cid		chapterid
	 */
	private function getAttchDir($aid,$cid){
		if(empty($aid) || empty($cid)){
			$this->printfail ( LANG_ERROR_PARAMETER );
		}
		$attachdir = jieqi_uploadpath ($this->jieqiConfigs ['article'] ['attachdir']).jieqi_getsubdir ($aid).'/' . $aid.'/' . $cid;
		jieqi_checkdir($attachdir,true);
		return $attachdir;
	}
	/**
	 * 判断文章是否存在,如果存在则返回文章数组，不存在则直接提示。
	 *
	 * @param $aid 文章ID
	 * @return $article 文章数组
	 */
	public function isExists($aid) {
		if (empty ( $aid ))
			$this->printfail ( LANG_ERROR_PARAMETER );
		$this->db->init ( 'article', 'articleid', 'article' );
		$article = $this->db->get ( $aid );
		if (! $article)
			$this->printfail ( $this->jieqiLang['article'] ['article_not_exists'] );
		return $article;
	}
	/**
	 * 管理他人文章的权限（作者，发布者，代理），没有权限直接提示。
	 * @param $aid articleid
	 * @param $reutrn 是否返回权限结果，默认false直接提示。array_key_exists
	 * @return boolean
	 */
	public function canedit($article,$reutrn = false, $groups = array('author', 'agent')) {
		if (empty ( $article )) $this->printfail(LANG_ERROR_PARAMETER);
		//管理别人文章权限
		if(!$canedit=$this->checkpower($this->jieqiPower['article']['manageallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true)){
		    $auth = $this->getAuth();
			//除了斑竹，作者、发表者和代理人可以修改文章
			if($auth['uid']>0){
				if((in_array('author', $groups) && $article ['authorid']==$auth['uid']) || (in_array('agent', $groups) && $article ['agentid']==$auth['uid'])){
				    $canedit=true;
				}
			}
		}
		if($reutrn) return $canedit;
		elseif(!$canedit) $this->printfail($this->jieqiLang['article']['noper_edit_article']);
	}
	/**
	 * 判断当前登录用户有没有删除权限,删除权限分为两种，删除自己的|删除别人的，如果没有权限直接提示。
	 */
	public function delPower($aid){
		$auth = $this->getAuth();
		$candel=$this->checkpower($this->jieqiPower ['article'] ['delallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true);
		if(!$candel && !empty($auth['uid'])){
			$this->db->init ( 'article', 'articleid', 'article' );
			$article = $this->db->get ( $aid );
			
// 			if($auth['uid']>0){
// 				if($article ['authorid']==$auth['uid'] || ){
// 					$candel=$this->checkpower($this->jieqiPower['article']['delmyarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true);
// 				}elseif ($article ['agentid']==$auth['uid']){
// 					//$canedit=true;
// 					//090708修改作者删除权限方式
// 					$candel=$this->checkpower($this->jieqiPower['article']['delmyarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true);
// 				}
				
// 			}
			if($auth['uid']>0 && ($article ['authorid']==$auth['uid'] || $article ['agentid']==$auth['uid'])){
				//$canedit=true;
				//090708修改作者删除权限方式
				$candel=$this->checkpower($this->jieqiPower['article']['delmyarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true);
			}
		}
		if(!$candel) $this->printfail($this->jieqiLang['article']['noper_delete_chapters']);
	}
	/**
	 * 检查上传附件权限
	 */
	public function canupload(){
		return $this->checkpower ($this->jieqiPower ['article'] ['articleupattach'], $this->getUsersStatus (), $this->getUsersGroup (), true );
	}
	/**
	 * 获得编辑组
	 */
	public function getAgents(){
		//获取编辑组
		$agents = array();
		if($this->jieqiConfigs['article']['agentgroup']){
			global $jieqiGroups;
			$group_array = explode('|',$this->jieqiConfigs['article']['agentgroup']);
			$groups = array();
			foreach($group_array as $key=>$group){
				$groups[] = array_search($group, $jieqiGroups);
			}
			if(is_array($groups)){
				$this->db->init('users','uid','system');
				$this->db->setCriteria();
				$this->db->criteria->setFields("uid,uname,name,groupid");
				foreach($groups as $k=>$groupid){
					$this->db->criteria->add(new Criteria('groupid', $groupid), 'OR');
				}
				$this->db->criteria->setSort('uid');
				$this->db->criteria->setOrder('ASC');
				$agents = $this->db->lists();
			}
		}
		return $agents;
	}
	/**
	 * 获取uid用户可以管理所有文章，更新时间降序
	 * @param unknown $uid
	 */
	function articleByAuthorid($uid){
		$articles = array();
		$this->db->init ( 'article', 'articleid', 'article' );
		$this->db->setCriteria();
		$this->db->criteria->add ( new Criteria ( 'authorid', $uid, '=' ));
		//$this->db->criteria->add ( new Criteria ( 'posterid', $uid, '=' ), 'OR' );
		//$this->db->criteria->add ( new Criteria ( 'agentid', $uid, '=' ), 'OR' );
		$this->db->criteria->setSort('lastupdate');
		$this->db->criteria->setOrder('DESC');
		$this->db->queryObjects();
		$k = 0;
		while($v = $this->db->getObject()){
			$articles[$k] = $this->article_vars($v);
			$k++;
		}
		return $articles;
	}
	/**
	 * 更新用户所属用户组，并返回更新后的用户对象
	 * @param unknown $uid		需要更新uid
	 * @param unknown $group	用户组ID，详情查看define
	 * @param unknown $bool	 	是否更新session，默认false，不更新。如果是当前登录用户更新用户组，则需要true
	 */
	public function updateGroup($uid,$group,$bool=false){
		global $jieqiGroups;
		$key=array_search($group, $jieqiGroups);
		if($key == false) $this->printfail($this->jieqiLang['article']['no_writer_group']);
		elseif($key == JIEQI_GROUP_ADMIN) $this->printfail($this->jieqiLang['article']['no_writer_admin']);
		else {
			//更新申请的作者所在的用户组
			$users_handler = $this->getUserObject();
			$jieqiUsers = $users_handler->get($uid);
			if(is_object($jieqiUsers)){
				$jieqiUsers->setVar('groupid', $key);
				$users_handler->insert($jieqiUsers);
				//更新当前登录用户session
				if($bool){
					$_SESSION['jieqiUserGroup'] = $key;
				}
				return $jieqiUsers;
			}
		}

	}
	/**
	 * 保存样章，申请作者使用
	 * @param  $applytext 样章内容
	 * @return boolean
	 */
	public function saveSimpleChapter($applytext){
		$auth = $this->getAuth();
		$this->db->init('applywriter', 'applyid', 'article' );
		$bool = false;
		//发表文章权限
		$newApply= array();
		$newApply['siteid']= JIEQI_SITE_ID;
		$newApply['applytime']=JIEQI_NOW_TIME;
		$newApply['applyuid']=$auth['uid'];
		$newApply['applyname']= $auth['username'];
		$newApply['authtime']= 0;
		$newApply['authuid']= 0;
		$newApply['authname']= '';
		$newApply['applytitle']= '';
		$newApply['applytext']= $applytext;
		$newApply['applysize']= strlen($applytext);
		$newApply['authnote']= '';
		if($this->jieqiConfigs['article']['checkappwriter']==1){
			//需要审核
			$newApply['applyflag']= 0;
			$this->db->add($newApply);
			$this->msgwin(LANG_DO_SUCCESS, $this->jieqiLang['article']['apply_submit_success']);
		}else{
			//更新申请者为 专栏作者 用户组
			$userObj = $this->updateGroup($auth['uid'],$this->jieqiConfigs['article']['writergroup'],true);
			if(is_object($userObj)){
				$newApply['applyflag']= 1;
				$this->db->insert($newApply);
				$bool=true;
			}
		}
		return $bool;
	}
	/**
	 * 添加新卷<br>
	 * 调用内部处理方法chapterInsideHandle统一处理章节与卷的变动。
	 * @param unknown $article		文章数组对象
	 * @param unknown $position		上一卷的逻辑位置，第一次新增卷参数为空
	 * @param unknown $volumeName	名称
	 * @param unknown $volumemanual	说明
	 */
	public function saveVolume($article,$position, $volumeName, $manual,$postdate) {
		$chaptercount = $position;
		if(empty($position)){
			//chapters值记录 显示 状态的章节总数量，这里需要查询所有章节数
			$this->db->init ('chapter','chapterid','article' );
			$this->db->setCriteria ( new Criteria ( 'articleid', $article ['articleid']));
			$chaptercount = $this->db->getCount()+1;
		}
		$from_draft = false;
		$chaptertype = 1;
		$volumeid = $chaptercount;
		$chaptercontent = '';
		$attachinfo = '';
		// 章节，卷统一处理方法
		return $this->chapterInsideHandle ( array (
				'article' => $article,
				'chaptertype' => $chaptertype,
				'chaptername' => $volumeName,
				'volumeid' => $volumeid,
				'manual' => $manual,
				'chaptercontent' => $chaptercontent,
				'attachinfo' => $attachinfo,
				'postdate' => $postdate,
				'attachfile' => null,
				'draftid' => null,
				'from_draft' => $from_draft,
				'userchappid' => null,
				'attachary' => null
		) );
	}
	/**
	 * 保存章节，验证数据有效性，通过后调用内部处理函数
	 * @param $data 章节数据
	 */
	public function saveChapter($data) {
		$article = $this->isExists($data['articleid']);
		// 检查上传权限
		$canupload = $this->canupload();
		$volumeid = $data ['volumeid'];
		$errtext = '';
		// 检查标题
		if (strlen ( $data ['chaptername'] ) == 0){
			$errtext .= $this->jieqiLang ['article'] ['need_chapter_title'] . '<br />';
		}
		// 检查附件
		$attachary = array ();
		$infoary = array ();
		$attachnum = 0;
		$attachinfo = '';
		// 检查上传文件
		if ($canupload && is_numeric ( $this->jieqiConfigs ['article'] ['maxattachnum'] ) && $this->jieqiConfigs ['article'] ['maxattachnum'] > 0 && !empty($data ['attachfile'])) {
			$typeary = explode ( ' ', trim ($this->jieqiConfigs ['article'] ['attachtype'] ) );
			foreach ( $typeary as $k => $v ) {
				if (substr ( $v, 0, 1 ) == '.')
					$typeary [$k] = substr ( $typeary [$k], 1 );
			}
			foreach ( $data ['attachfile'] ['name'] as $k => $v ) {
				// 遍历附件
				if (! empty ( $v )) {
					// 非空附件
					$tmpary = explode ( '.', $v );
					$tmpint = count ( $tmpary ) - 1;
					$tmpary [$tmpint] = strtolower ( trim ( $tmpary [$tmpint] ) );
					$denyary = array (
							'htm',
							'html',
							'shtml',
							'php',
							'asp',
							'aspx',
							'jsp',
							'pl',
							'cgi'
					);
					if (empty ( $tmpary [$tmpint] ) || ! in_array ( $tmpary [$tmpint], $typeary )) {
						$errtext .= sprintf ( $this->jieqiLang ['article'] ['upload_filetype_error'], $v ) . '<br />';
					} elseif (in_array ( $tmpary [$tmpint], $denyary )) {
						$errtext .= sprintf ( $this->jieqiLang ['article'] ['upload_filetype_limit'], $tmpary [$tmpint] ) . '<br />';
					}
					if (eregi ( "\.(gif|jpg|jpeg|png|bmp)$", $v )) {
						$fclass = 'image';
						//转换成字节限制大小
						if ($data ['attachfile'] ['size'] [$k] > (intval ( $this->jieqiConfigs ['article'] ['maximagesize'] ) * 1024))
							$errtext .= sprintf ( $this->jieqiLang ['article'] ['upload_filesize_toolarge'], $v, intval ( $this->jieqiConfigs ['article'] ['maximagesize'] ) ) . '<br />';
					} else {
						$fclass = 'file';
						if ($data ['attachfile'] ['size'] [$k] > (intval ( $this->jieqiConfigs ['article'] ['maxfilesize'] ) * 1024))
							$errtext .= sprintf ( $this->jieqiLang ['article'] ['upload_filesize_toolarge'], $v, intval ( $this->jieqiConfigs ['article'] ['maxfilesize'] ) ) . '<br />';
					}
					if (! empty ( $errtext )) {
						//删除php处理附件的临时文件
						jieqi_delfile ( $data ['attachfile'] ['tmp_name'] [$k] );
					} else {
						$attachary [$attachnum] = $k;
						$infoary [$attachnum] = array (
								'name' => $v,
								'class' => $fclass,
								'postfix' => $tmpary [$tmpint],
								'size' => $data ['attachfile'] ['size'] [$k]
						);
						$attachnum ++;
					}
				}
			}
		}
		if(intval($this->jieqiConfigs ['article'] ['maxattachnum']) <= 0 && strlen ( $data ['chaptercontent'] ) == 0){
			//不用判断附件
			$errtext .= $this->jieqiLang ['article'] ['need_chapter_null'] . '<br />';
		}else if($attachnum == 0 && strlen ( $data ['chaptercontent'] ) == 0){
			$errtext .= $this->jieqiLang ['article'] ['need_chapter_content'] . '<br />';
		}
		if (empty ( $errtext )) {
			// 附件入库
			if ($attachnum > 0) {
				$this->db->init ( 'attachs', 'attachid', 'article' );
				foreach ( $attachary as $k => $v ) {
					$newAttach = array ();
					$newAttach ['articleid'] = $data ['articleid'];
					$newAttach ['chapterid'] = 0;
					$newAttach ['name'] = $infoary [$k] ['name'];
					$newAttach ['class'] = $infoary [$k] ['class'];
					$newAttach ['postfix'] = $infoary [$k] ['postfix'];
					$newAttach ['size'] = $infoary [$k] ['size'];
					$newAttach ['hits'] = 0;
					$newAttach ['needexp'] = 0;
					$newAttach ['uptime'] = JIEQI_NOW_TIME;
					$attachid = $this->db->add ( $newAttach );
					if ($attachid) {
						$infoary [$k] ['attachid'] = $attachid;
					} else {
						$infoary [$k] ['attachid'] = 0;
					}
				}
				$attachinfo = serialize ( $infoary );
			}
			// 文字过滤
			if (! empty ( $this->jieqiConfigs ['article'] ['hidearticlewords'] )) {
				$articlewordssplit = (strlen ( $this->jieqiConfigs ['article'] ['articlewordssplit'] ) == 0) ? ' ' : $this->jieqiConfigs ['article'] ['articlewordssplit'];
				$filterary = explode ( $articlewordssplit, $this->jieqiConfigs ['article'] ['hidearticlewords'] );
				$data ['chaptercontent'] = str_replace ( $filterary, '', $data ['chaptercontent'] );
			}
			// 内容排版
			if ($this->jieqiConfigs ['article'] ['authtypeset'] == 2 || ($this->jieqiConfigs ['article'] ['authtypeset'] == 1 && $data ['typeset'] == 1)) {
				include_once (JIEQI_ROOT_PATH . '/lib/text/texttypeset.php');
				$texttypeset = new TextTypeset ();
				$data ['chaptercontent'] = $texttypeset->doTypeset ( $data ['chaptercontent'] );
			}
			// 增加章节
			$from_draft = false;
			$newChapter =  $this->chapterInsideHandle ( array (
					'article' => $article,
					'chaptertype' => $data ['chaptertype'],
					'fullflag' => $data ['fullflag'],
					'chaptername' => $data ['chaptername'],
					'volumeid' => $data ['volumeid'],
					'manual' => $data ['manual'],
					'typeset' => $data ['typeset'],
					'isvip' => $data ['isvip'],
					'postdate' => $data ['postdate'],
					'chaptercontent' => $data ['chaptercontent'],
					'attachinfo' => $attachinfo,
					'attachfile' => $data ['attachfile'],
					'saleprice' => $data['saleprice'],
					'draftid' => null,
					'from_draft' => $from_draft,
					'userchappid' => null,
					'attachary' => $attachary
			) );
			if(empty($newChapter))$this->printfail ($this->jieqiLang ['article'] ['add_chapter_failure']);
			else return $newChapter;
			//$url_manager = $this->geturl ( 'article', 'article', 'SYS=method=articleManage&aid=' . $article ['articleid'] );
			//$url_chpater = $this->geturl ( 'article', 'chapter', 'SYS=method=newChapterView&aid=' . $article ['articleid'] );
			//jieqi_jumppage ( $url_manager, LANG_DO_SUCCESS, sprintf ( $this->jieqiLang ['article'] ['add_chapter_success'], $url_manager, jieqi_geturl ( 'article', 'article', $_REQUEST ['aid'], 'info' ), $url_chpater ) );
		} else {
			$this->printfail( $errtext );
		}
	}


	/**
	 * 数组插入首位，如果没有
	 * <p>如果具有管理别人文章的权限，则把这本书追加到当前管理者所能管理的文章数组中
	 * @param unknown $articles
	 * @param unknown $article
	 */
	public function handleManageallarticle(&$articles,$article){
		if(is_array($articles) && is_array($article)){
			$isExists = false;
			foreach ( $articles as $k => $v ) {
				if($v['articleid'] == $article['articleid']){
					$isExists = true;
					break;
				}
			}
			if($isExists == false){
				array_unshift($articles,$this->article_vars($article));
			}
		}
	}
	/**
	 * 计算文章封面的标识符,如果没有封面图默认返回0
	 * @param unknown $articlelpic	大图数组
	 * @param unknown $articlespic	小图数组
	 */
	private function calculatorImgflag($articlelpic,$articlespic){
		$imgflag = 0;//默认封面
		$imgtary = array (
				1 => '.gif',
				2 => '.jpg',
				3 => '.jpeg',
				4 => '.png',
				5 => '.bmp'
		);
		//大封面
		if(!empty($articlelpic) && !empty ($articlelpic['name'] )){
			$limage_postfix = strrchr ( trim ( strtolower ($articlelpic['name'] ) ), "." );
			$imgflag = $imgflag | 2;
			$tmpvar = intval ( array_search ( $limage_postfix, $imgtary ) );
			if ($tmpvar > 0)
				$imgflag = $imgflag | ($tmpvar * 32);
		}
		$simage = '';
		//小封面
		if(!empty($articlespic) && !empty ($articlespic['name'] )){
			$simage = $articlespic['name'];
		}else if(!empty($articlelpic) && !empty ($articlelpic['name'] )){
			//不使用大封面
			//$simage = $articlelpic['name'];
		}
		if(!empty($simage)){
			$simage_postfix = strrchr ( trim ($simage), "." );
			$imgflag = $imgflag | 1;
			$tmpvar = intval ( array_search ( $simage_postfix, $imgtary ) );
			if ($tmpvar > 0)
				$imgflag = $imgflag | ($tmpvar * 4);
		}
		return $imgflag;
	}
	
// 		public function temp(){
// 			$wordsperegold = ceil ( $this->jieqiConfigs ['article'] ['wordsperegold'] ) * 2; // 2倍整数
// 			$chapters = $this->db->selectsql ( "SELECT * FROM `jieqi_article_chapter` WHERE isvip=1 and saleprice=0 and size>0  and chaptertype=0" );
// 			echo '需要处理的章节总数：'.count($chapters);
// 			exit;
// 			echo "<table border=1><tr><th>序</th><th>原价格</th><th>字节数</th><th>变更后的价格</th><th>更新结果</th></tr>";
// 			$i = 0;
// 			$this->db->init ( 'chapter', 'chapterid', 'article' );
// 			foreach ( $chapters as $c ) {
		
// 				$i ++;
// 				$saleprice = 0;
// 				if($c ['size']>0){
// 					$saleprice = round ( $c['size'] / $wordsperegold ); // 四舍五入
// 				}
// 				if($this->db->edit ( $c ['chapterid'], array ('saleprice' => $saleprice) )){
// 					echo "<tr><td>{$i}</td><td>{$c['saleprice']}</td><td>{$c['size']}</td><td>{$saleprice}</td><td>成功</td></tr>";
// 				}else{
// 					echo "<tr><td>{$i}</td><td>{$c['saleprice']}</td><td>{$c['size']}</td><td>{$saleprice}</td><td>失败</td></tr>";
// 				}
		
	
// 			}
// 			echo "</table>";
// 		}
	/**
	 * 计算开v文章的，vip定价
	 * <p>300汉字（600字节）一个书海币，文章和章节size长度都是按照bytes计算的
	 * @param unknown $article
	 * @param unknown $newChapter	引用传递
	 * @param unknown $chaptersize	字节长度，1个汉字两个字节
	 * @param unknown $saleprice
	 */
	private function calculatorPrice($article,&$newChapter,$chaptersize,$saleprice){
		//isvip,saleprice
			//计算定价
			if(!empty($article) && !empty($newChapter)){// && !empty($saleprice)
				if($saleprice && $saleprice>0 && $this->checkpower($this->jieqiPower['article']['customprice'], $this->getUsersStatus (), $this->getUsersGroup (), true)){
					$newChapter ['saleprice']=intval($saleprice);
				}elseif(is_numeric($this->jieqiConfigs['article']['wordsperegold']) && $this->jieqiConfigs['article']['wordsperegold']>0){
					$wordsperegold=ceil($this->jieqiConfigs['article']['wordsperegold']) * 2;//2倍整数
					if($this->jieqiConfigs['article']['priceround']==1){
						$newChapter ['saleprice']=floor($chaptersize / $wordsperegold);//向下舍入，取整。
					}elseif($this->jieqiConfigs['article']['priceround']==2){
						$newChapter ['saleprice']=ceil($chaptersize / $wordsperegold);//向上舍入，取整。
					}else{
						$newChapter ['saleprice']=round($chaptersize / $wordsperegold);//四舍五入
					}
				}
			}
	}
	/**
	 * 新增章节、卷的内部处理流程
	 * @param unknown $params
	 * @bool
	 */
	private function chapterInsideHandle($params) {
		$auth = $this->getAuth();
		$this->db->init ( 'chapter', 'chapterid', 'article' );
		$this->db->setCriteria(new Criteria('articleid', $params['article']['articleid']));
		$this->db->criteria->add(new Criteria('postdate', $this->getTime('day')-86400, '>='));

		$postnum = 100;//$this->printfail ($this->db->getCount($this->db->criteria));
		if(((int)date('H')<8 && (int)date('H')>=0) || (int)date('H')>=23) $postnum = 15;
		elseif((int)date('H')>=9 && (int)date('H')<=18) $postnum = 100000;
		$ip = $this->getIp();
		if($this->db->getCount($this->db->criteria)>=$postnum && $ip!='113.140.9.50'){    
			$ip = $ip ? $ip : 'allip';
			$cookiename = 'closeip_'.str_replace('.','_',$ip);
			$deniedip = JIEQI_ROOT_PATH.'/deniedip.txt';
			$this->db->init('userlog','logid','system');
			//记录日志
			$data = array();
			$data['siteid'] = JIEQI_SITE_ID;
			$data['logtime'] = JIEQI_NOW_TIME;
			$data['fromid'] = $_SESSION['jieqiUserId'];
			$data['fromname'] = $_SESSION['jieqiUserName'];
			$data['toid'] = 0;
			$data['toname'] = '';
			$data['reason'] = '疑似发布恶意章节';
			$data['chginfo'] = $ip."触发每日发布".$postnum."条章节的限制。";
			$data['chglog'] = '';
			$data['isdel'] = '0';
			$data['userlog'] = '';
			if (!$cc=$this->db->add($data)){
				$this->printfail ();
			}
			//更新用户权限
			$auth = $this->getAuth();
			if($auth['uid']){
				 $users_handler =  $this->getUserObject();
				 $criteria=new CriteriaCompo(new Criteria('uid', $auth['uid']));
		         //$criteria->add();
				 $users_handler->updatefields(array('groupid'=>1), $criteria);
				 $tmpuser = $users_handler->get($auth['uid']);
				 $tmpuser->saveToSession();
				 include_once(JIEQI_ROOT_PATH.'/include/dologout.php');
				 jieqi_dologout();
			}
			@setcookie($cookiename, date("Y-m-d H:i:s", JIEQI_NOW_TIME), JIEQI_NOW_TIME+86400, '/',  JIEQI_COOKIE_DOMAIN, 0);
			$this->swritefile($deniedip, $ip."\r\n","a+");
			//书待审
			$this->db->init ( 'article', 'articleid', 'article' );
			$this->db->edit($params['article']['articleid'], array('display'=>1) );
			$this->article_repack($params['article']['articleid'], array('makeopf'=>1));
			$this->jumppage($this->geturl ( 'article', 'chapter', 'SYS=method=cmView&aid='.$params['article']['articleid']), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
			//header('location:/');exit;
		    //$this->printfail ($params['article']['articleid']);
		}

		$article = $params['article'];
		$isvip = $params['isvip'];
		$fullflag = $params['fullflag'];
		$typeset = $params['typeset'];
		$saleprice = $params['saleprice'];
		$chaptertype = $params['chaptertype'];
		$chaptername = $params['chaptername'];
		$volumeid = $params['volumeid'];
		$manual = $params['manual'];
		//定时发布时间
		$postdate = $params['postdate'];
		$chaptercontent = $params['chaptercontent'];
		$attachinfo = $params['attachinfo'];
		$attachfile = $params['attachfile'];
		$draftid = $params['draftid'];
		$from_draft = $params['from_draft'];
		$userchappid = $params['userchappid'];
		$attachary = $params['attachary'];

		$this->db->init ( 'chapter', 'chapterid', 'article' );
		$newChapter = array ();
		$newChapter ['siteid'] = $article['siteid'];
		$chaptersize = jieqi_strlen ( $chaptercontent );
		$newChapter ['articleid'] = $article ['articleid'];
		$newChapter ['articlename'] = $article ['articlename'];
// 		$newChapter ['volumeid'] = 0;
		$newChapter ['posterid'] = $auth ['uid'];
		$newChapter ['poster'] = $auth ['username'];
// 		$newChapter ['postdate'] = JIEQI_NOW_TIME;
//		$newChapter ['lastupdate'] = JIEQI_NOW_TIME;
		$newChapter ['chaptername'] = $chaptername;
		$newChapter ['size'] = $chaptersize;
		if ($chaptertype == 1) {
			// 卷
			$newChapter ['chaptertype'] = 1;
		} else {
			// 章节
			$newChapter ['chaptertype'] = 0;
		}
		$newChapter['manual'] = $manual;//说明，概览,vip章节题外话
		$newChapter ['attachment'] = $attachinfo;
		$newChapter ['saleprice'] = 0;
		$newChapter ['isvip'] = 0;
		//$this->printfail('dddd');
		//isvip,saleprice
		if($article['articletype'] && $isvip){
			$newChapter ['isvip'] = $isvip;
			//计算定价
			if(!empty($chaptersize)){
				$this->calculatorPrice($article, $newChapter, $chaptersize, $saleprice);
			}
		}
		//display的4种状态
		//0审核完成
		//1未审核
		//2审核完定时
		//9未审核定时
		//发章节是否不需要审核
		$audit = $this->checkpower ($this->jieqiPower ['article'] ['nocheckchapter'], $this->getUsersStatus (), $this->getUsersGroup (), true );
		//审核状态
		$display = 1;
		if(empty($postdate)){//手动发布，审核完成即为发布
			$newChapter ['postdate'] = JIEQI_NOW_TIME;//提交时间
			$display = 1;//未审核
			if($audit){
				$display = 0;//不需要审核，直接显示。
				//$newChapter ['postdate'] = JIEQI_NOW_TIME;
			}
		}elseif(strchr($postdate, '-')){//定时发布，审核完成并且时间到即为发布
			//$postdate大于当前时间
			$newChapter ['postdate'] = $this->handlePostdate($postdate);//定时发布时间
			$display = 9;//定时，未审核
			if($audit){
				//什么操作，将状态更新为0，难道是定时器？-
				$display = 2;//不需要审核，定时。
			}
		}else{//api接口采集的书的postdate
			$newChapter ['postdate'] = $postdate;
			$display = 0;
			if($newChapter ['postdate'] > JIEQI_NOW_TIME){//定时审核
				$display = 2;
			}

		}
		$newChapter ['lastupdate'] = $newChapter ['postdate'];
		$newChapter ['display'] = $display;
		//chapters记录 显示 状态的章节数量，这里查询所有章节数
		$this->db->setCriteria ( new Criteria ( 'articleid', $article ['articleid'] ) );
		$chaptercount = $this->db->getCount($this->db->criteria);
		if (empty ( $volumeid ))
			$volumeid = $chaptercount + 1;
			// 如果是插入章节，则原来章节的序号加一位
		if ($volumeid <= $chaptercount) {
			$this->db->updatetable ( 'article_chapter', array (
					'chapterorder' => '++'
			), 'articleid = ' . $article ['articleid'] . ' and chapterorder >= ' . $volumeid );
		}
		$newChapter ['chapterorder'] = $volumeid;
		$cid = $this->db->add ( $newChapter );
		if (! $cid)
			$this->printfail ( $this->jieqiLang ['article'] ['add_chapter_failure'] );
		else {
			$newChapter ['chapterid'] = $cid;
			if($display == 0){//审核通过更新article
				if ($chaptertype != 1) { // add chapter
					// 增加或插入章节，最新卷可能也会变化
					// 暂时默认插入的章节就是本卷最后章节，否则最新章节可能不是插入的章节
					// 重新开启一个查询条件
					$this->db->setCriteria ( new Criteria ( 'articleid', $article ['articleid'] ) );
					$this->db->criteria->add ( new Criteria ( 'chapterorder', $volumeid, '<' ) );
					$this->db->criteria->add ( new Criteria ( 'chaptertype', 1, '=' ) );
					$this->db->criteria->setSort ( 'chapterorder' );
					$this->db->criteria->setOrder ( 'DESC' );
					$this->db->criteria->setLimit ( 1 );
					$this->db->queryObjects ();
					// 这个是对象，不是数组
					$tmpchapter = $this->db->getObject ();
					if (is_object ( $tmpchapter )) {
						$lastvolume = $tmpchapter->getVar ( 'chaptername', 'n' );
						$lastvolumeid = $tmpchapter->getVar ( 'chapterid', 'n' );
					} else {
						$lastvolume = '';
						$lastvolumeid = 0;
					}
					unset ( $tmpchapter );
					// unset($criteria);

					$article ['lastchapter'] = $chaptername;
					$article ['lastchaptervip'] = $isvip;
					$article ['lastchapterid'] = $cid;
					// 插入章节时，卷也可能变化
					if ($article ['lastvolumeid'] != $lastvolumeid) {
						$article ['lastvolume'] = $lastvolume;
						$article ['lastvolumeid'] = $lastvolumeid;
					}
					//chapters记录display=0的所有章节数,卷不计入章节数和总字数
					$article ['chapters'] = $article ['chapters'] + 1;
					$article ['size'] = $article ['size'] + $chaptersize;
					$article ['lastupdate'] = JIEQI_NOW_TIME;
				} else { // add colume
					// 增加分卷，最新卷可能也会变化
					// 重新开启一个查询条件
					$this->db->setCriteria( new Criteria ( 'articleid', $article ['articleid'] ) );
					$this->db->criteria->add( new Criteria ( 'chapterorder', $volumeid, '>' ) );
					$this->db->criteria->add( new Criteria ( 'chaptertype', 0, '=' ) );
					$this->db->criteria->setSort( 'chapterorder' );
					$this->db->criteria->setOrder( 'DESC' );
					$this->db->queryObjects();
					$tmpchapter = $this->db->getObject();
					if (is_object ( $tmpchapter )) {
						if ($tmpchapter->getVar( 'chapterid', 'n' ) == $article ['lastchapterid']) {
							$article ['lastvolume'] = $chaptername;
							$article ['lastvolumeid'] = $cid;
						}
					}
					unset($tmpchapter);
				}
			}
			//为空，前台是disableds是完结状态，这里不做任何动作。
			if (!empty($fullflag)) {
				$article ['fullflag'] = $fullflag;
			}
			// 更新article
			$this->db->init ( 'article', 'articleid', 'article' );
			$this->db->edit ( $article ['articleid'], $article );
			// 判断是否加水印
			$make_image_water = false;
			if (function_exists ( 'gd_info' ) && $this->jieqiConfigs ['article'] ['attachwater'] > 0 && JIEQI_MODULE_VTYPE != '' && JIEQI_MODULE_VTYPE != 'Free') {
				if (strpos ( $this->jieqiConfigs ['article'] ['attachwimage'], '/' ) === false && strpos ( $this->jieqiConfigs ['article'] ['attachwimage'], '\\' ) === false)
					$water_image_file = $this->apath . '/images/' . $this->jieqiConfigs ['article'] ['attachwimage'];
				else
					$water_image_file = $this->jieqiConfigs ['article'] ['attachwimage'];
				if (is_file ( $water_image_file )) {
					$make_image_water = true;
					include_once (JIEQI_ROOT_PATH . '/lib/image/imagewater.php');
				}
			}
			// 处理上传文件
			if (!empty($attachary)) {
// 				$this->db->init ( 'article', 'articleid', 'article' )
// 				$this->db->query ( "UPDATE " . jieqi_dbprefix ( 'article_attachs' ) . " SET chapterid=" . $cid . " WHERE articleid=" . $article ['articleid'] . " AND chapterid=0" );

				//关联附件与章节
				$this->db->updatetable ( 'article_attachs', array (
						'chapterid' => $cid
				), 'articleid = ' . $article['articleid'] . ' AND chapterid=0'  );

				//附件目录
				$attachdir = $this->getAttchDir($newChapter['articleid' ], $cid);
				//附件信息
				$infoary = unserialize($attachinfo);
				foreach ( $attachary as $k => $v ) {
					$attach_save_path = $attachdir . '/' . $infoary [$k] ['attachid'] . '.' . $infoary [$k] ['postfix'];
					$tmp_attachfile = dirname ( $attachfile ['tmp_name'] [$v] ) . '/' . basename ( $attach_save_path );
					@move_uploaded_file ( $attachfile ['tmp_name'] [$v], $tmp_attachfile );
					// 图片加水印
					if ($make_image_water && eregi ( "\.(gif|jpg|jpeg|png)$", $tmp_attachfile )) {
						$img = new ImageWater ();
						$img->save_image_file = $tmp_attachfile;
						$img->codepage = JIEQI_SYSTEM_CHARSET;
						$img->wm_image_pos = $this->jieqiConfigs ['article'] ['attachwater'];
						$img->wm_image_name = $water_image_file;
						$img->wm_image_transition = $this->jieqiConfigs ['article'] ['attachwtrans'];
						$img->jpeg_quality = $this->jieqiConfigs ['article'] ['attachwquality'];
						$img->create ( $tmp_attachfile );
						unset ( $img );
					}
					jieqi_copyfile ( $tmp_attachfile, $attach_save_path, 0777, true );
				}
			}
			// ////////////////////090712定制功能////////////////////////////////////
			// 更新定制缓存
// 			include_once ($GLOBALS ['jieqiModules'] ['article'] ['path'] . '/include/setarticlecache.php');
// 			jieqi_article_editcache ( 'info', $article ['articleid'] );
			// ///////////////////////////////////////////////////////
			// 保存文章内容和生成html
// 			if($display == 0){
				$this->instantPackage ( $article ['articleid'] );
				$data = array(
						'articleid'=>$article ['articleid'],
						'chapterid'=>$cid,
						'name'=>$chaptername,
						'content'=>$chaptercontent,
						'type'=>$newChapter ['chaptertype'],
						'volumeid'=>$volumeid,
						'display'=>$display,
						'isvip'=>$newChapter ['isvip'],
						'postdate'=>$newChapter ['postdate'],
						'lastupdate'=>$newChapter ['lastupdate'],
						'saleprice'=>$newChapter ['saleprice'],
						'intro' => $manual,
						'size'=>$chaptersize
				);
				$this->addChapter($data);
// 			}

// 			if ($chaptertype == 1) {
// 				//$this->addChapter ( $cid, $chaptername, $volumemanual, 1, $volumeid, $display, $newChapter ['postdate'], $newChapter ['lastupdate'], $chaptersize );
// 			} else {
// 				//$this->addChapter ( $cid, $chaptername, $chaptercontent, 0, $volumeid, $display, $newChapter ['postdate'], $newChapter ['lastupdate'], $chaptersize );
// 			}
			if ($from_draft) {
				$this->db->init ( 'draft', 'draftid', 'article' );
				$this->db->delete ( $draftid );
			}
			$this->changeScoreByPosterId( $auth ['uid'],true);
// 			if ($userchappid > 0 && ! empty ( $this->jieqiConfigs ['article'] ['scoreauthuserchap'] )){
// 				$this->changeScoreByPosterId ( $userchappid, $this->jieqiConfigs ['article'] ['scoreauthuserchap'], true );
// 			}
			return $newChapter;
		}
		return array();
	}
	/**
	 * 获取分类列表key:sort，管理授权key:，授权级别key:，首发状态key:
	 *
	 * @return multitype:$data
	 */
	public function getSources() {
	    static $my_sources;
		if (!isset($my_sources)) {
			global $jieqiModules;
			//$data = array ();
			// 获取配置文件的数据对象或者数据数组
	// 		$sort = $this->jieqiConfigs['sort'];
	// 		foreach ( $sort as $k => $v ) {
	// 			if(JIEQI_MODULE_NAME == 'wenxue'){
	// 				//取文学频道分类
	// 				if($k >= 201 && $k < 301){
	// 					$data ['sortrows'][$k] = $v;
	// 				}
	// 			}else{
	// 				//去主站分类
	// 				if($k >= 1 && $k < 201){
	// 					$data ['sortrows'][$k] = $v;
	// 				}
	// 			}
	// 		}
			$jieqiOption ['article'] = $this->jieqiConfigs['option'];
			foreach ( $jieqiOption ['article'] as $k => $v ) {
				// $jieqiTpl->assign_by_ref($k, $jieqiOption['article'][$k]);
				$my_sources [$k] = $jieqiOption ['article'] [$k];
			}
			//处理模块和渠道的对应关系
			//渠道->模块 one to many || one to one
			$channel = array();
			foreach ( $jieqiModules as $k => $v ) {
				if(array_key_exists($v['siteid'],$channel)){
					$channel[$v['siteid']]['name']='男频';
					$channel[$v['siteid']]['module']='article';
					continue;
				}
				$sort = array();
				$min_sortid = $v['minsortid'];
				$max_sortid = $v['maxsortid'];
				foreach ( $this->jieqiConfigs['sort'] as $kk => $vv ) {
					if($kk >= $min_sortid && $kk <= $max_sortid){
						$sort[$kk] = $vv;//频道对应的分类
					}
				}
				$channel[$v['siteid']] = array('name'=>$v['caption'],'module'=>$k,'minsortid'=>$min_sortid,'maxsortid'=>$max_sortid,'sort'=>$sort);
			}
			$my_sources['sortrows']=$channel[$jieqiModules[JIEQI_MODULE_NAME]['siteid']]['sort'];
			ksort($channel);//排序
			$my_sources['channel'] =$channel;
			$my_sources['module'] =JIEQI_MODULE_NAME;
		}
		return $my_sources;
	}
	/**
	 * 处理定时发布的时间格式,将任何英文文本的日期时间描述解析为 Unix 时间戳,解析失败返回0
	 * @param unknown $postdate
	 */
	private function handlePostdate($postdate = 0){
		if($postdate){
			$postdate = strtotime( $postdate);
		}
		if(!$postdate) return JIEQI_NOW_TIME;
		else return $postdate;
	}
	/**
	 * 更新章节
	 *
	 * @param $article 章节所属文章对象
	 * @param $data 章节信息数组
	 */
	public function updateChapter($article, $data) {
	    global $jieqi_file_postfix;
		// 检查上传权限
		$canupload = $this->canupload();
		$errtext = '';
		// 检查标题
		if (strlen ( $data ['chaptername'] ) == 0)
			$errtext .= '章节' . $this->jieqiLang ['article'] ['need_chapter_title'] . '<br />';
			// 检查附件
		$attachary = array ();
		$infoary = array ();
		$attachnum = 0;
		$attachinfo = '';
		$this->db->init ( 'chapter', 'chapterid', 'article' );
		$oldChapter = $this->db->get ( $data ['chapterid'] );
		// 检查上传文件
		if ($canupload && is_numeric ( $this->jieqiConfigs ['article'] ['maxattachnum'] ) && $this->jieqiConfigs ['article'] ['maxattachnum'] > 0) {
			$maxfilenum = intval ( $this->jieqiConfigs ['article'] ['maxattachnum'] );
			$typeary = explode ( ' ', trim ( $this->jieqiConfigs ['article'] ['attachtype'] ) );
			if($data ['attachfile']['name']){
				foreach ( $data ['attachfile']['name'] as $k => $v ) {
					if (! empty ( $v )) {
						$tmpary = explode ( '.', $v );
						$tmpint = count ( $tmpary ) - 1;
						$tmpary [$tmpint] = strtolower ( trim ( $tmpary [$tmpint] ) );
						$denyary = array (
								'htm',
								'html',
								'shtml',
								'php',
								'asp',
								'aspx',
								'jsp',
								'pl',
								'cgi'
						);
						if (empty ( $tmpary [$tmpint] ) || ! in_array ( $tmpary [$tmpint], $typeary )) {
							$errtext .= sprintf ( $this->jieqiLang ['article'] ['upload_filetype_error'], $v ) . '<br />';
						} elseif (in_array ( $tmpary [$tmpint], $denyary )) {
							$errtext .= sprintf ( $this->jieqiLang ['article'] ['upload_filetype_limit'], $tmpary [$tmpint] ) . '<br />';
						}
						if (eregi ( "\.(gif|jpg|jpeg|png|bmp)$", $v )) {
							$fclass = 'image';
							if ($data ['attachfile'] ['size'] [$k] > (intval ( $this->jieqiConfigs ['article'] ['maximagesize'] ) * 1024))
								$errtext .= sprintf ( $this->jieqiLang ['article'] ['upload_filesize_toolarge'], $v, intval ( $this->jieqiConfigs ['article'] ['maximagesize'] ) ) . '<br />';
						} else {
							$fclass = 'file';
							if ($data ['attachfile'] ['size'] [$k] > (intval ( $this->jieqiConfigs ['article'] ['maxfilesize'] ) * 1024))
								$errtext .= sprintf ( $this->jieqiLang ['article'] ['upload_filesize_toolarge'], $v, intval ( $this->jieqiConfigs ['article'] ['maxfilesize'] ) ) . '<br />';
						}
						if (! empty ( $errtext )) {
							jieqi_delfile ( $data ['attachfile'] ['tmp_name'] [$k] );
						} else {
							$attachary [$attachnum] = $k;
							$infoary [$attachnum] = array (
									'name' => $v,
									'class' => $fclass,
									'postfix' => $tmpary [$tmpint],
									'size' => $data ['attachfile'] ['size'] [$k]
							);
							$attachnum ++;
						}
					}
				}
			}
			//老附件，新附件不能大于$maxfilenum
			if (count ( $data ['oldattach'] ) + $attachnum > $maxfilenum)
				$errtext .= $this->jieqiLang ['article'] ['attachment_limit'] .$maxfilenum. '<br />';
		}
		// 有附件的话允许章节没内容，否则必须有
		if (count ( $data ['oldattach'] ) == 0 && $attachnum == 0 && $data ['chaptertype'] == 0 && strlen ( $data ['chaptercontent'] ) == 0)
			$errtext .= $this->jieqiLang ['article'] ['need_chapter_content'] . '<br />';
		if (empty ( $errtext )) {
			$tmptime = JIEQI_NOW_TIME;
			// 处理旧附件
			$tmpvar = $oldChapter ['attachment'];
			if (! empty ( $tmpvar )) {
				$tmpattachary = unserialize ( $tmpvar );
				if (! is_array ( $tmpattachary ))
					$tmpattachary = array ();
				if (! is_array ( $data ['oldattach'] )) {
					if (is_string ( $data ['oldattach'] ))
						$data ['oldattach'] = array (
								$data ['oldattach']
						);
					else
						$data ['oldattach'] = array ();
				}
				$oldattachary = array ();
				foreach ( $tmpattachary as $val ) {
					if (in_array ( $val ['attachid'], $data ['oldattach'] )) {
						$oldattachary [] = $val;
					} else {
						// 删除旧附件
						// include_once($jieqiModules['article']['path'].'/class/articleattachs.php');
						// $attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');
						$this->db->init ( 'attachs', 'attachid', 'article' );
						$this->db->delete ( $val ['attachid'] );
						$afname = jieqi_uploadpath ( $this->jieqiConfigs ['article'] ['attachdir'], 'article' ) . jieqi_getsubdir ( $data ['articleid'] ) . '/' . $data ['articleid'] . '/' . $data ['chapterid'] . '/' . $val ['attachid'] . '.' . $val ['postfix'];
						jieqi_delfile ( $afname );
					}
				}
			} else {
				$oldattachary = array ();
			}
			// 新附件入库
			if ($attachnum > 0) {
				$this->db->init ( 'attachs', 'attachid', 'article' );
// 				$attachdir = jieqi_uploadpath ( $this->jieqiConfigs ['article'] ['attachdir'], 'article' );
// 				if (! file_exists ( $attachdir ))
// 					jieqi_createdir ( $attachdir );
// 				$attachdir .= jieqi_getsubdir ( $data ['articleid'] );
// 				if (! file_exists ( $attachdir ))
// 					jieqi_createdir ( $attachdir );
// 				$attachdir .= '/' . $data ['articleid'];
// 				if (! file_exists ( $attachdir ))
// 					jieqi_createdir ( $attachdir );
// 				$attachdir .= '/' . $data ['chapterid'];
// 				if (! file_exists ( $attachdir ))
// 					jieqi_createdir ( $attachdir );
				$attachdir = $this->getAttchDir($data ['articleid'],$data ['chapterid']);
					// 判断是否加水印
				$make_image_water = false;
				if (function_exists ( 'gd_info' ) && $this->jieqiConfigs ['article'] ['attachwater'] > 0 && JIEQI_MODULE_VTYPE != '' && JIEQI_MODULE_VTYPE != 'Free') {
					if (strpos ( $this->jieqiConfigs ['article'] ['attachwimage'], '/' ) === false && strpos ( $this->jieqiConfigs ['article'] ['attachwimage'], '\\' ) === false)
						$water_image_file = $this->apath . '/images/' . $this->jieqiConfigs ['article'] ['attachwimage'];
					else
						$water_image_file = $this->jieqiConfigs ['article'] ['attachwimage'];
					if (is_file ( $water_image_file )) {
						$make_image_water = true;
						include_once (JIEQI_ROOT_PATH . '/lib/image/imagewater.php');
					}
				}
				foreach ( $attachary as $k => $v ) {
					$newAttach = array ();
					$newAttach ['articleid'] = $data ['articleid'];
					$newAttach ['chapterid'] = $data ['chapterid'];
					$newAttach ['name'] = $infoary [$k] ['name'];
					$newAttach ['class'] = $infoary [$k] ['class'];
					$newAttach ['postfix'] = $infoary [$k] ['postfix'];
					$newAttach ['size'] = $infoary [$k] ['size'];
					$newAttach ['hits'] = 0;
					$newAttach ['needexp'] = 0;
					$newAttach ['uptime'] = $tmpattachary ['postdate'];
					$attachid = $this->db->add ( $newAttach );
					if ($attachid) {
						// $attachid=$newAttach->getVar('attachid');
						$infoary [$k] ['attachid'] = $attachid;
					} else {
						$infoary [$k] ['attachid'] = 0;
					}
					$attach_save_path = $attachdir . '/' . $infoary [$k] ['attachid'] . '.' . $infoary [$k] ['postfix'];
					$tmp_attachfile = dirname ( $data ['attachfile'] ['tmp_name'] [$v] ) . '/' . basename ( $attach_save_path );
					@move_uploaded_file ( $data ['attachfile'] ['tmp_name'] [$v], $tmp_attachfile );
					// 图片加水印
					if ($make_image_water && eregi ( "\.(gif|jpg|jpeg|png)$", $tmp_attachfile )) {
						$img = new ImageWater ();
						$img->save_image_file = $tmp_attachfile;
						$img->codepage = JIEQI_SYSTEM_CHARSET;
						$img->wm_image_pos = $this->jieqiConfigs ['article'] ['attachwater'];
						$img->wm_image_name = $water_image_file;
						$img->wm_image_transition = $this->jieqiConfigs ['article'] ['attachwtrans'];
						$img->jpeg_quality = $this->jieqiConfigs ['article'] ['attachwquality'];
						$img->create ( $tmp_attachfile );
						unset ( $img );
					}
					jieqi_copyfile ( $tmp_attachfile, $attach_save_path, 0777, true );
				}
			}

			foreach ( $infoary as $val ) {
				$oldattachary [] = $val;
			}
			if (count ( $oldattachary ) > 0)
				$attachinfo = serialize ( $oldattachary );

			$data ['attachment'] = $attachinfo;
			$data ['posterid'] = 0;
			$data ['poster'] = '';
			// $data['articleid']= $article['articleid'];
			$auth = $this->getAuth();
			if (! empty ( $auth['uid'])) {
				$data ['posterid'] = $auth['uid'];
				$data ['poster'] = $auth['username'];
			}

			// 文字过滤
			if (! empty ( $this->jieqiConfigs ['article'] ['hidearticlewords'] )) {
				$articlewordssplit = (strlen ( $this->jieqiConfigs ['article'] ['articlewordssplit'] ) == 0) ? ' ' : $this->jieqiConfigs ['article'] ['articlewordssplit'];
				$filterary = explode ( $articlewordssplit, $this->jieqiConfigs ['article'] ['hidearticlewords'] );
				$data ['chaptercontent'] = str_replace ( $filterary, '', $data ['chaptercontent'] );
			}
			// 内容排版
			if ($this->jieqiConfigs ['article'] ['authtypeset'] == 2 || ($this->jieqiConfigs ['article'] ['authtypeset'] == 1 && $data ['typeset'] == 1)) {
				include_once (JIEQI_ROOT_PATH . '/lib/text/texttypeset.php');
				$texttypeset = new TextTypeset ();
				$data ['chaptercontent'] = $texttypeset->doTypeset ( $data ['chaptercontent'] );
			}
			$chaptersize = jieqi_strlen ( $data ['chaptercontent'] );
			//isvip,saleprice
			if($article['articletype'] && $data['isvip']){
				//$data ['isvip'] = $data['isvip'];
				//计算定价
				if(!empty($chaptersize)){
					$this->calculatorPrice($article, $data, $chaptersize, $data['saleprice']);
				}
			}else{
				//免费章节0
				$data['saleprice'] = 0;
			}
			//display的4种状态
			//0审核完成
			//1未审核
			//2审核完定时
			//9未审核定时
			//发章节是否不需要审核
			//$audit = $this->checkpower ($this->jieqiPower ['article'] ['nocheckchapter'], $this->getUsersStatus (), $this->getUsersGroup (), true );
			//审核状态


			//$display = 1;
            $display = $oldChapter ['display'];
			if(($oldChapter['display'] == 9 || $oldChapter['display'] == 2) && isset($data['postdate'])){
				//可以修改定时
				$data ['postdate'] = $this->handlePostdate($data['postdate']);
				if($data ['postdate']<=JIEQI_NOW_TIME) {
				    if($oldChapter['display'] == 9 ) $data['display'] = 1;
					elseif($oldChapter['display'] == 2)  $data['display'] = 0;
					$display = $data['display'];
				}
				$data ['lastupdate'] = $data ['postdate'];
			} else $data ['lastupdate'] = JIEQI_NOW_TIME;





			/*
			if(empty($data['postdate'])){//手动发布，审核完成即为发布
				$data ['postdate'] = 0;
				$display = 1;//未审核
				if($audit){
					$data ['postdate'] = JIEQI_NOW_TIME;
					$display = 0;//不需要审核，直接显示。
				}
			}else{//定时发布，审核完成并且时间到即为发布
				//$postdate大于当前时间
				$data ['postdate'] = $this->handlePostdate($data['postdate']);
				$display = 9;//定时，未审核
				if($audit){
					//什么操作，将状态更新为0，难道是定时器？-
					$display = 2;//不需要审核，定时。
				}
			}*/



			//$data['display'] = $display;
			// $beforesize=$oldChapter['size'];
			$data['size'] = $chaptersize;
			$chaptercontent = $data ['chaptercontent'];
			$fullflag = $data ['fullflag'];
			// 更新前移除不需要的数据
			unset ( $data ['oldattach'] );
			unset ( $data ['typeset'] );
			unset ( $data ['attachfile'] );
			unset ( $data ['chaptertype'] );
			unset ( $data ['fullflag'] );
			unset ( $data ['chaptercontent'] );
			$this->db->init ( 'chapter', 'chapterid', 'article' );
			if (! $this->db->edit ( $data ['chapterid'], $data ))
				$this->printfail ( $this->jieqiLang ['article'] ['chapter_edit_failure'] );
			else {
				$update = false;
				if(!$display){
					$article ['size'] = $article ['size'] + $chaptersize - $oldChapter ['size'];
					if ($data ['chapterid'] == $article ['lastchapterid']) {
						$article ['lastchapter'] = $data ['chaptername'];
					}
					$update = true;
				}
				//为空，前台disableds是完结状态，这里不做任何动作。
				if (!empty($fullflag) && $article ['fullflag'] != $fullflag) {
					$article ['fullflag'] = $fullflag;
					$update = true;
				}
				if($update){
					$this->db->init ( 'article', 'articleid', 'article' );
					$this->db->edit ( $article ['articleid'], $article );
				}
				$this->instantPackage ( $article ['articleid'] );
				$this->editChapter ($data ['chapterid'], $oldChapter ['chapterorder'],  $chaptercontent,
						 array (
		 						'id' => $data ['chaptername'],
		 						'href' => $data ['chapterid'] . $jieqi_file_postfix['txt'],
		 						'media-type' => 'text/html',
		 						'content-type' => $oldChapter ['chaptertype'] ? 'volume' : 'chapter',
		 						'display' => $display,
		 						'postdate' => $data ['postdate'] ? $data ['postdate'] : $oldChapter ['postdate'],
		 						'lastupdate' => $data ['lastupdate'],
		 						'saleprice' => $data['saleprice'],
		 						'isvip' => $data['isvip'],
		 						'size' => $chaptersize,
		 						'intro' => isset($data ['manual']) ?  $data ['manual'] : $oldChapter ['manual']
		 				)
				);
			}
		} else {
			$this->printfail ( $errtext );
		}
	}
	/**
	 * 新文章，入库成功返回新文章数组
	 * @param unknown $data
	 */
	public function newArticle($data){
		$newArticle = array ();
		$this->db->init ( 'article', 'articleid', 'article' );
		$this->db->setCriteria ();
		$errtext = '';
		include_once (JIEQI_ROOT_PATH . '/lib/text/textfunction.php');
// 		// 检查标题
// 		if (strlen ( $data ['articlename'] ) == 0)
// 			$errtext .= $this->jieqiLang ['article'] ['need_article_title'] . '<br />';
// 		elseif (! jieqi_safestring ( $data ['articlename'] ))
// 			$errtext .= $this->jieqiLang ['article'] ['limit_article_title'] . '<br />';
		// 检查标题和简介有没有违禁单词
		/*
		if (! empty ( $this->jieqiConfigs ['system'] ['postdenywords'] )) {
			include_once (JIEQI_ROOT_PATH . '/include/checker.php');
			$checker = new JieqiChecker ();
			$matchwords1 = $checker->deny_words ( $data ['articlename'], $this->jieqiConfigs ['system'] ['postdenywords'], true );
			$matchwords2 = $checker->deny_words ( $data ['intro'], $this->jieqiConfigs ['system'] ['postdenywords'], true );
			if (is_array ( $matchwords1 ) || is_array ( $matchwords2 )) {
				if (! isset ( $this->jieqiLang ['system'] ['post'] ))
					jieqi_loadlang ( 'post', 'system' );
				$matchwords = array ();
				if (is_array ( $matchwords1 ))
					$matchwords = array_merge ( $matchwords, $matchwords1 );
				if (is_array ( $matchwords2 ))
					$matchwords = array_merge ( $matchwords, $matchwords2 );
				$errtext .= sprintf ( $this->jieqiLang ['system'] ['post_words_deny'], implode ( ' ', jieqi_funtoarray ( 'htmlspecialchars', $matchwords ) ) );
			}
		}*/

		// 检查封面
// 		$typeary = explode ( ' ', trim ( $this->jieqiConfigs ['article'] ['imagetype'] ) );
// 		foreach ( $typeary as $k => $v ) {
// 			if (substr ( $v, 0, 1 ) != '.')
// 				$typeary [$k] = '.' . $typeary [$k];
// 		}
// 		if (! empty ( $data['articlespic'] ['name'] )) {
// 			$simage_postfix = strrchr ( trim ( strtolower ( $data['articlespic'] ['name'] ) ), "." );
// 			if (eregi ( "\.(gif|jpg|jpeg|png|bmp)$", $data['articlespic'] ['name'] )) {
// 				if (! in_array ( $simage_postfix, $typeary ))
// 					$errtext .= sprintf ( $this->jieqiLang ['article'] ['simage_type_error'], $this->jieqiConfigs ['article'] ['imagetype'] ) . '<br />';
// 			} else {
// 				$errtext .= sprintf ( $this->jieqiLang ['article'] ['simage_not_image'], $data['articlespic'] ['name'] ) . '<br />';
// 			}
// 			if (! empty ( $errtext ))
// 				jieqi_delfile ( $data['articlespic'] ['tmp_name'] );
// 		}
// 		if (! empty ( $data ['articlelpic'] ['name'] )) {
// 			$limage_postfix = strrchr ( trim ( strtolower ( $data ['articlelpic'] ['name'] ) ), "." );
// 			if (eregi ( "\.(gif|jpg|jpeg|png|bmp)$", $data ['articlelpic'] ['name'] )) {
// 				if (! in_array ( $limage_postfix, $typeary ))
// 					$errtext .= sprintf ( $this->jieqiLang ['article'] ['limage_type_error'], $this->jieqiConfigs ['article'] ['imagetype'] ) . '<br />';
// 			} else {
// 				$errtext .= sprintf ( $this->jieqiLang ['article'] ['limage_not_image'], $data ['articlelpic'] ['name'] ) . '<br />';
// 			}
// 			if (! empty ( $errtext ))
// 				jieqi_delfile ( $data ['articlelpic'] ['tmp_name'] );
// 		}
		if (empty ( $errtext )) {
			// include_once($jieqiModules['article']['path'].'/class/article.php');
			// $article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
			// 检查文章是否已经发表
			//articleModel中已经验证
// 			if ($this->jieqiConfigs ['article'] ['samearticlename'] != 1) {
// 				$this->db->criteria->add ( new Criteria ( 'articlename', $data ['articlename']) );
// 				if ($this->db->getCount () > 0) {
// 					$this->printfail ( sprintf ( $this->jieqiLang ['article'] ['articletitle_has_exists'], jieqi_htmlstr ( $data ['articlename'] ) ) );
// 				}
// 			}
			$newArticle ['siteid'] = $data ['siteid'] ? $data['siteid'] : 0;
			// 			$newArticle ['postdate'] = JIEQI_NOW_TIME;
			//api采集的文章可以指定参数
			$newArticle ['lastupdate'] = $data ['lastupdate'] ? $data['lastupdate'] : JIEQI_NOW_TIME;
			$newArticle ['articlename'] = $data ['articlename'];
			$newArticle ['keywords'] = $data ['keywords'];
			$newArticle ['tag'] = $data ['tag'];
			$newArticle ['initial'] = jieqi_getinitial ( $data ['articlename'] );
			//$newArticle ['agentid'] = $data ['agentid'];
			//$newArticle ['agent'] = $data ['agent'];
			$newArticle ['authorid'] = $data ['authorid'] ? $data ['authorid'] : 0;
			$newArticle ['author'] = $data ['author'];
			$newArticle ['firstflag'] = $data ['firstflag'] ? $data ['firstflag'] : 0;
			$newArticle ['permission'] = $data ['permission'] ? $data ['permission'] : 0;
			$newArticle ['signdate'] = $data ['signdate'];
			$auth = $this->getAuth();
			$users_handler = $this->getUserObject();
			$newArticle ['agent'] = '';
			$newArticle ['agentid'] = 0;
			if (! empty ( $data ['agent'] )){
				if ($agentobj = $users_handler->getByname ( $data ['agent'], 3 )) {
					$newArticle ['agentid'] = $agentobj->getVar ( 'uid' );
					$newArticle ['agent'] = $agentobj->getVar ( 'uname', 'n' );
				}
			}
			/*$auth = $this->getAuth();
			if ($this->checkpower ( $this->jieqiPower ['article'] ['transarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )) {
				//允许转载的情况
				if (empty ( $data ['author'] ) || ($data ['author'] == $auth ['username'])) {
					$newArticle ['authorid'] = $auth ['uid'];
					$newArticle ['author'] = $auth ['username'];
				} else {
					// 转载作品
					$newArticle ['author'] = $data ['author'];
					if ($data['authorflag']) {
						$authorobj = $users_handler->getByname ( $data ['author'], 3 );
						if (is_object ( $authorobj )) $newArticle ['authorid'] = $authorobj->getVar ( 'uid' );
						else $newArticle ['authorid'] = 0;
					} else {
					    $newArticle ['authorid'] = 0;
					}
				}
				$newArticle ['permission'] = $data ['permission'];
			    if($data ['permission']>= 4) $newArticle ['signdate'] = JIEQI_NOW_TIME;
				$newArticle ['firstflag'] = $data ['firstflag'] ? $data ['firstflag'] : 0;
			} else {
				$newArticle ['authorid'] = $auth ['uid'] ;
				$newArticle ['author'] = $auth ['username'];
			}*/
			$newArticle ['posterid'] = $auth ['uid'];
			$newArticle ['poster'] = $auth ['username'];
			$newArticle ['lastchapterid'] = 0;
			$newArticle ['lastchapter'] = '';
			// $newArticle['lastvolumeid']= 0;
			$newArticle ['lastvolume'] = '';
			$newArticle ['chapters'] = 0;
			$newArticle ['size'] = $data ['size'] ? $data['size'] : 0;
			$newArticle ['fullflag'] = $data ['fullflag'] ? $data['fullflag'] : 0;
			$newArticle ['sortid'] = intval ( $data ['sortid'] );
			// 			$newArticle ['typeid'] = intval ( $param ['typeid'] );
			// 内容排版
			if ($this->jieqiConfigs ['article'] ['authtypeset']) {
				include_once (JIEQI_ROOT_PATH . '/lib/text/texttypeset.php');
				$texttypeset = new TextTypeset ();
				$data ['intro'] = $texttypeset->doTypeset ( $data ['intro'] );
			}
			$newArticle ['intro'] = $data ['intro'];
			$newArticle ['notice'] = $data ['notice'];
			$newArticle ['setting'] = '';
			// $newArticle['lastvisit']= 0;
			// $newArticle['dayvisit']= 0;
			// $newArticle['weekvisit']= 0;
			// $newArticle['monthvisit']= 0;
			// $newArticle['allvisit']= 0;
			// $newArticle['lastvote']= 0;
			// $newArticle['dayvote']= 0;
			// $newArticle['weekvote']= 0;
			// $newArticle['monthvote']= 0;
			// $newArticle['allvote']= 0;
			// $newArticle['goodnum']= 0;
			// $newArticle['badnum']= 0;
			// $newArticle['toptime']= 0;
			// $newArticle['saleprice']= 0;
			// $newArticle['salenum']= 0;
			// $newArticle['totalcost']= 0;
			// $newArticle['power']= 0;
			//api采集的文章可以指定参数
			$newArticle ['articletype'] = 0;
			if($data ['articletype']){
				$newArticle ['articletype'] = 1;
			}

			//提示：新建文章默认封面
			// 			$newArticle ['imgflag'] = 0;
			// 			$imgflag = 0;//默认封面
			// 			//如果没有小封面，小封面默认以使用大封面
			// 			$imgtary = array (
			// 					1 => '.gif',
			// 					2 => '.jpg',
			// 					3 => '.jpeg',
			// 					4 => '.png',
			// 					5 => '.bmp'
			// 			);
			// 			if (! empty ( $data['articlespic'] ['name'] )) {
			// 				$imgflag = $imgflag | 1;
			// 				$tmpvar = intval ( array_search ( $simage_postfix, $imgtary ) );
			// 				if ($tmpvar > 0)
				// 					$imgflag = $imgflag | ($tmpvar * 4);
				// 			}
				// 			if (! empty ( $data ['articlelpic'] ['name'] )) {
				// 				$imgflag = $imgflag | 2;
				// 				$tmpvar = intval ( array_search ( $limage_postfix, $imgtary ) );
				// 				if ($tmpvar > 0)
					// 					$imgflag = $imgflag | ($tmpvar * 32);
					// 			}

			$newArticle ['imgflag'] = $this->calculatorImgflag($data ['articlelpic'], $data['articlespic']);
			$newArticle ['postdate'] = $data ['postdate'] ? $data['postdate'] : JIEQI_NOW_TIME;//提交时间。
			//用户组需不需要检查权限
// 			$newArticle ['display'] = 1;//需要检查
			//检查，发表文章不需要检查
			//api采集的文章可以指定参数
			if($data ['display']){//手动指定优先>权限判断>判断
				$newArticle ['display'] = 1;//接口可以指定是否vip
			}elseif($this->checkpower ($this->jieqiPower ['article'] ['needcheck'], $this->getUsersStatus (), $this->getUsersGroup (), true )){
				$newArticle ['display'] = 0;
			}else{
				$newArticle ['display'] = 1;
			}//print_r($newArticle);exit;
			$id = $this->db->add ( $newArticle );
			if (! $id) {
				$this->printfail ( $this->jieqiLang ['article'] ['article_add_failure'] );
			} else {
				$newArticle['articleid'] = $id;
				// $id=$newArticle->getVar('articleid');
				// 加载自定义类
				// 				$articleLib->instantPackage ( $id ); // 实例化Package
				// 				$articleLib->initPackage ( array (
				// 						'id' => $id,
				// 						'title' => $newArticle ['articlename'],
				// 						'creatorid' => $newArticle ['authorid'],
				// 						'creator' => $newArticle ['author'],
				// 						'subject' => $newArticle ['keywords'],
				// 						'description' => $newArticle ['intro'],
				// 						'publisher' => JIEQI_SITE_NAME,
				// 						'contributorid' => $newArticle ['posterid'],
				// 						'contributor' => $newArticle ['poster'],
				// 						'sortid' => $newArticle ['sortid'],
				// 						'typeid' => $newArticle ['typeid'],
				// 						'articletype' => $newArticle ['articletype'],
				// 						'permission' => $newArticle ['permission'],
				// 						'firstflag' => $newArticle ['firstflag'],
				// 						'fullflag' => $newArticle ['fullflag'],
				// 						'imgflag' => $newArticle ['imgflag'],
				// 						'display' => $newArticle ['display']
				// 				) );
				//生成opf
				$this->loadOPF($id);
				// 保存大图
				if (! empty ( $data ['articlelpic'] ['name'] )) {
					$limage_postfix = strrchr ( trim ( strtolower ( $data ['articlelpic'] ['name'] ) ), "." );
					jieqi_copyfile ( $data ['articlelpic'] ['tmp_name'], $this->getDir ( 'imagedir' ) . '/' . $id . 'l' . $limage_postfix, 0777, true );
				}
				// 保存小图
				if (! empty ( $data['articlespic'] ['name'] )) {
					$simage_postfix = strrchr ( trim ( strtolower ( $data['articlespic'] ['name'] ) ), "." );
					jieqi_copyfile ( $data['articlespic'] ['tmp_name'], $this->getDir ( 'imagedir' ) . '/' . $id . 's' . $simage_postfix, 0777, true );
				}elseif (! empty ( $data ['articlelpic'] ['name'] )){
					//暂时不需要这个功能
					//小图默认使用大图的缩小版,缩小百分之50
					/*
					 include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
					$imgresize = new ImageResize();
					$imgresize->load($articleLib->getDir ( 'imagedir' ) . '/' . $id . 'l' . $limage_postfix);
					$imgresize->resize(null,null,0.5);
					$imgresize->save($articleLib->getDir ( 'imagedir' ) . '/' . $id . 's' . $limage_postfix,true);*/
				}
				// 增加发文积分
				if (! empty ( $this->jieqiConfigs ['article'] ['scorearticle'] )) {
					$users_handler->changeScore ( $auth ['uid'], $this->jieqiConfigs ['article'] ['scorearticle'], true );
				}
				// 				// 更新最新入库
				// 				if ($newArticle ['display'] == 0) {
				// 					jieqi_getcachevars ( 'article', 'articleuplog' );
				// 					if (! is_array ( $jieqiArticleuplog ))
					// 						$jieqiArticleuplog = array (
					// 								'articleuptime' => 0,
					// 								'chapteruptime' => 0
					// 						);
					// 					$jieqiArticleuplog ['articleuptime'] = JIEQI_NOW_TIME;
					// 					jieqi_setcachevars ( 'articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article' );
					// 				}
			return $newArticle;
			}
		} else {
			$this->printfail ( $errtext );
		}
	}
	/**
	 * 修改文章
	 * @param $data 更新的文章数据
	 */
	public function updateArticle($data) {
		$auth = $this->getAuth();
		$users_handler = $this->getUserObject();
		$articlerow = $this->isExists ( $data ['articleid'] );//print_r($article);exit;
		$allowmodify = $this->checkpower ( $this->jieqiPower ['article'] ['articlemodify'], $this->getUsersStatus(), $this->getUsersGroup(), true );
		$errtext = '';
// 		include_once (JIEQI_ROOT_PATH . '/lib/text/textfunction.php');
		// 检查标题
// 		if (strlen ( $data ['articlename'] ) == 0)
// 			$errtext .= $this->jieqiLang ['article'] ['need_article_title'] . '<br />';
// 		elseif (! jieqi_safestring ( $data ['articlename'] ))
// 			$errtext .= $this->jieqiLang ['article'] ['limit_article_title'] . '<br />';
			// 检查标题和简介有没有违禁单词
// 		if (! empty ( $this->jieqiConfigs ['system'] ['postdenywords'] )) {
// 			include_once (JIEQI_ROOT_PATH . '/include/checker.php');
// 			$checker = new JieqiChecker ();
// 			$matchwords1 = $checker->deny_words ( $data ['articlename'], $this->jieqiConfigs ['system'] ['postdenywords'], true );
// 			$matchwords2 = $checker->deny_words ( $data ['intro'], $this->jieqiConfigs ['system'] ['postdenywords'], true );
// 			if (is_array ( $matchwords1 ) || is_array ( $matchwords2 )) {
// 				if (! isset ( $this->jieqiLang ['system'] ['post'] ))
// 					jieqi_loadlang ( 'post', 'system' );
// 				$matchwords = array ();
// 				if (is_array ( $matchwords1 ))
// 					$matchwords = array_merge ( $matchwords, $matchwords1 );
// 				if (is_array ( $matchwords2 ))
// 					$matchwords = array_merge ( $matchwords, $matchwords2 );
// 				$errtext .= sprintf ( $this->jieqiLang ['system'] ['post_words_deny'], implode ( ' ', jieqi_funtoarray ( 'htmlspecialchars', $matchwords ) ) );
// 			}
// 		}
		// 检查封面图片

// 		$typeary = explode ( ' ', trim ( $this->jieqiConfigs ['article'] ['imagetype'] ) );
// 		foreach ( $typeary as $k => $v ) {
// 			if (substr ( $v, 0, 1 ) != '.')
// 				$typeary [$k] = '.' . $typeary [$k];
// 		}
// 		if (! empty ( $data ['articlespic'] ['name'] )) {
// 			$simage_postfix = strrchr ( trim ( strtolower ( $data ['articlespic'] ['name'] ) ), "." );
// 			if (eregi ( "\.(gif|jpg|jpeg|png|bmp)$", $data ['articlespic'] ['name'] )) {
// 				if (! in_array ( $simage_postfix, $typeary ))
// 					$errtext .= sprintf ( $this->jieqiLang ['article'] ['simage_type_error'], $this->jieqiConfigs ['article'] ['imagetype'] ) . '<br />';

// 			} else {
// 				$errtext .= sprintf ( $this->jieqiLang ['article'] ['simage_not_image'], $data ['articlespic'] ['name'] ) . '<br />';
// 			}
// 			if (! empty ( $errtext ))
// 				jieqi_delfile ( $data ['articlespic'] ['tmp_name'] );
// 		}
// 		if (! empty ( $data ['articlelpic'] ['name'] )) {
// 			$limage_postfix = strrchr ( trim ( strtolower ( $data ['articlelpic'] ['name'] ) ), "." );
// 			if (eregi ( "\.(gif|jpg|jpeg|png|bmp)$", $data ['articlelpic'] ['name'] )) {
// 				if (! in_array ( $limage_postfix, $typeary ))
// 					$errtext .= sprintf ( $this->jieqiLang ['article'] ['limage_type_error'], $this->jieqiConfigs ['article'] ['imagetype'] ) . '<br />';
// 			} else {
// 				$errtext .= sprintf ( $this->jieqiLang ['article'] ['limage_not_image'], $data ['articlelpic'] ['name'] ) . '<br />';
// 			}
// 			if (! empty ( $errtext ))
// 				jieqi_delfile ( $data ['articlelpic'] ['tmp_name'] );
// 		}

        $article = array();
		if (empty ( $errtext )) {
			$this->db->init ( 'article', 'articleid', 'article' );
			$this->db->setCriteria ();
			// 检查文章是否已经发表
// 			if ($article ['articlename'] != $data ['articlename'] && $this->jieqiConfigs ['article'] ['samearticlename'] != 1) {
// 				$this->db->criteria->add ( new Criteria ( 'articlename', $data ['articlename']) );
// 				if ($this->db->getCount () > 0)
// 					$this->printfail ( sprintf ( $this->jieqiLang ['article'] ['articletitle_has_exists'], jieqi_htmlstr ( $data ['articlename'] ) ) );
// 			}
			$article ['articlename'] = $data ['articlename'];
			$article ['keywords'] = $data ['keywords'];
			$article ['tag'] = $data ['tag'];
			$article ['initial'] = jieqi_getinitial ( $data ['articlename'] );
// 			$article ['agentid'] = 0;
// 			$article ['agent'] = '';
// 			include_once (JIEQI_ROOT_PATH . '/class/users.php');
// 			$users_handler = & JieqiUsersHandler::getInstance ( 'JieqiUsersHandler' );
// 			$agentobj = false;
			if ($this->checkpower ( $this->jieqiPower ['article'] ['manageallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )) {
				if (! empty ( $data ['agent'] ))
					$agentobj = $users_handler->getByname ( $data ['agent'], 3 );
				if (is_object ( $agentobj )) {
					$article ['agentid'] = $agentobj->getVar ( 'uid' );
					$article ['agent'] = $agentobj->getVar ( 'uname', 'n' );
				}
			}
			if ($this->checkpower ( $this->jieqiPower ['article'] ['transarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )) {
				//允许转载的情况
				if (empty ( $data ['author'] )) {
					$article ['authorid'] = $auth ['uid'] ;
					$article ['author'] = $auth ['username'] ;
				} else {
					// 转载作品
					$article ['author'] = $data ['author'];
 					if ($data ['authorflag']) {
						$authorobj = $users_handler->getByname ( $data ['author'], 3 );
						if (is_object ( $authorobj ))
							$article ['authorid'] = $authorobj->getVar ( 'uid' );
 					} else {
 						$article ['authorid'] = 0;
 					}
				}
				$article ['permission'] = $data ['permission'];
				if ($article ['permission'] != $data ['permission'] && $data ['permission'] >= 4 && $articlerow ['permission'] < 4) $article ['signdate'] = JIEQI_NOW_TIME;
				$article ['firstflag'] = $data ['firstflag'];
			} /*else {
				// 不允许转载的情况
				$article['authorid']= $auth ['uid'];
				$article['author']= $auth ['username'];
			}*/
			//$article ['permission'] = $data ['permission'];
			$article ['posterid'] = $auth ['uid'];
			$article ['poster'] = $auth ['username'];
			$article ['fullflag'] = $data ['fullflag'];
			$article ['sortid'] = intval ( $data ['sortid'] );
			if(isset($data['siteid'])) $article ['siteid'] = intval ( $data['siteid'] );
			// $article['typeid']= intval($_POST['typeid']);
			// 内容排版
			if ($this->jieqiConfigs ['article'] ['authtypeset']) {
				include_once (JIEQI_ROOT_PATH . '/lib/text/texttypeset.php');
				$texttypeset = new TextTypeset ();
				$data ['intro'] = $texttypeset->doTypeset ( $data ['intro'] );
			}
			$article ['intro'] = $data ['intro'];
			$article ['notice'] = $data ['notice'];
			// 封面图片标志
			//管理他人文章的权限
			if($this->checkpower ( $this->jieqiPower ['article'] ['manageallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )){
				//封面图片标志
				$old_imgflag = $articlerow ['imgflag'];
				$imgflag=$old_imgflag;
				$imgtary=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
				if (!empty($data ['articlespic'] ['name'])){
					$simage_postfix = strrchr ( trim ( strtolower ( $data ['articlespic'] ['name'] ) ), "." );
					$imgflag = $imgflag | 1;
					$tmpvar = intval(array_search($simage_postfix, $imgtary));
					if($tmpvar > 0) $imgflag = $imgflag & 227 | ($tmpvar * 4);
				}
				if (!empty($data ['articlelpic'] ['name'])){
					$limage_postfix = strrchr ( trim ( strtolower ( $data ['articlelpic'] ['name'] ) ), "." );
					$imgflag =$imgflag | 2;
					$tmpvar = intval(array_search($limage_postfix, $imgtary));
					if($tmpvar > 0) $imgflag = $imgflag & 31 | ($tmpvar * 32);
				}
				$article['imgflag'] =  $imgflag;
			}
// 			$imgtary = array (
// 					1 => '.gif',
// 					2 => '.jpg',
// 					3 => '.jpeg',
// 					4 => '.png',
// 					5 => '.bmp'
// 			);
// 			if (! empty ( $data ['articlespic'] ['name'] )) {
// 				$imgflag = $imgflag | 1;
// 				$tmpvar = intval ( array_search ( $simage_postfix, $imgtary ) );
// 				if ($tmpvar > 0)
// 					$imgflag = $imgflag & 227 | ($tmpvar * 4);
// 			}

// 			if (! empty ( $data ['articlelpic'] ['name'] )) {
// 				$imgflag = $imgflag | 2;
// 				$tmpvar = intval ( array_search ( $limage_postfix, $imgtary ) );
// 				if ($tmpvar > 0)
// 					$imgflag = $imgflag & 31 | ($tmpvar * 32);
// 			}
			// 互换链接
// 			if ($this->jieqiConfigs ['article'] ['eachlinknum'] > 0) {
// 				// $_POST['eachlinkids']=trim($_POST['eachlinkids']);
// 				$setting = unserialize ( $article ['setting'] );
// 				if (! empty ( $setting ['eachlink'] ['ids'] ))
// 					$linkvalue = implode ( ' ', $setting ['eachlink'] ['ids'] );
// 				else
// 					$linkvalue = '';
// 				if ($linkvalue != $data ['eachlinkids']) {
// 					$tmpary = array_unique ( explode ( ' ', $data ['eachlinkids'] ) );
// 					foreach ( $tmpary as $k => $v ) {
// 						if (! is_numeric ( $v ))
// 							unset ( $tmpary [$k] );
// 						else
// 							$tmpary [$k] = intval ( $tmpary [$k] );
// 					}
// 					$linkids = array ();
// 					$linknames = array ();
// 					if (count ( $tmpary > 0 )) {
// 						$sql = "SELECT articleid, articlename FROM " . jieqi_dbprefix ( 'article_article' ) . " WHERE articleid IN (" . implode ( ',', $tmpary ) . ")";
// 						$query = JieqiQueryHandler::getInstance ( 'JieqiQueryHandler' );
// 						$query->execute ( $sql );
// 						$linknum = 0;
// 						while ( ($arow = $query->getRow ()) && ($linknum < $this->jieqiConfigs ['article'] ['eachlinknum']) ) {
// 							if ($arow ['articleid'] != $article->getVar ( 'articleid', 'n' )) {
// 								$linkids [$linknum] = $arow ['articleid'];
// 								$linknames [$linknum] = $arow ['articlename'];
// 								$linknum ++;
// 							}
// 						}
// 					}
// 					$setting ['eachlink'] ['ids'] = $linkids;
// 					$setting ['eachlink'] ['names'] = $linknames;
// 					$article->setVar ( 'setting', serialize ( $setting ) );
// 				}
// 			}

			//允许修改统计的情况
			/*
			if ($allowmodify) {
				if ($data ['size'])
					$article ['size'] = $data ['size'];
				$statary = array (
						'dayvisit',
						'weekvisit',
						'monthvisit',
						'allvisit',
						'dayvote',
						'weekvote',
						'monthvote',
						'allvote',
						'goodnum'
				);
				foreach ( $statary as $v ) {
					if (isset ( $data [$v] ))
						$article [$v] = intval ( $data [$v] );
				}
			}*/
			if (! $this->db->edit ( $data ['articleid'], $article ))
				$this->printfail ( $this->jieqiLang ['article'] ['article_edit_failure'] );
			else {
				// $id=$article->getVar('articleid');
				// include_once($jieqiModules['article']['path'].'/class/package.php');
				// $package=new JieqiPackage($id);
				$this->article_repack($data ['articleid'], array('makeopf'=>1));
// 				$this->instantPackage ( $data ['articleid'] );
// 				$this->editPackage ( array (
// 						'id' => $article ['articleid'],
// 						'title' => $article ['articlename'],
// 						'creatorid' => $article ['authorid'],
// 						'creator' => $article ['author'],
// 						'subject' => $article ['keywords'],
// 						'description' => $article ['intro'],
// 						'publisher' => JIEQI_SITE_NAME,
// 						'contributorid' => $article ['posterid'],
// 						'contributor' => $article ['poster'],
// 						'sortid' => $article ['sortid'],
// 						'typeid' => $article ['typeid'],
// 						'articletype' => $article ['articletype'],
// 						'permission' => $article ['permission'],
// 						'firstflag' => $article ['firstflag'],
// 						'fullflag' => $article ['fullflag'],
// 						'imgflag' => $article ['imgflag'],
// 						'power' => $article ['power'],
// 						'display' => $article ['display'],
// 						'size' => $article ['size']
// 				) );
// 				$this->instantPackage($data ['articleid']);//article_repack中已经初始化了pageckage
				if($this->checkpower ( $this->jieqiPower ['article'] ['manageallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )){
					// 删除老封面
					if ($old_imgflag != $imgflag) {
						$imgtary = array (
								1 => '.gif',
								2 => '.jpg',
								3 => '.jpeg',
								4 => '.png',
								5 => '.bmp'
						);
						$tmpvar = ($old_imgflag >> 2) & 7;
						if (isset ( $imgtary [$tmpvar] )) {
							if (is_file ( $this->getDir ( 'imagedir' ) . '/' . $data ['articleid'] . 's' . $imgtary [$tmpvar] ))
								jieqi_delfile ( $this->getDir ( 'imagedir' ) . '/' . $data ['articleid'] . 's' . $imgtary [$tmpvar] );
						}
						$tmpvar = $old_imgflag >> 5;
						if (isset ( $imgtary [$tmpvar] )) {
							if (is_file ( $this->getDir ( 'imagedir' ) . '/' . $data ['articleid'] . 'l' . $imgtary [$tmpvar] ))
								jieqi_delfile ( $this->getDir ( 'imagedir' ) . '/' . $data ['articleid'] . 'l' . $imgtary [$tmpvar] );
						}
					}
					// 保存大图
					if (! empty ( $data ['articlelpic'] ['name'] )) {
						$limage_postfix = strrchr ( trim ( strtolower ( $data ['articlelpic'] ['name'] ) ), "." );
						jieqi_copyfile ( $data ['articlelpic'] ['tmp_name'], $this->getDir ( 'imagedir' ) . '/' . $data ['articleid'] . 'l' . $limage_postfix, 0777, true );
					}
					// 保存小图
					if (! empty ( $data ['articlespic'] ['name'] )) {
						$simage_postfix = strrchr ( trim ( strtolower ( $data ['articlespic'] ['name'] ) ), "." );
						jieqi_copyfile ( $data ['articlespic'] ['tmp_name'], $this->getDir ( 'imagedir' ) . '/' . $data ['articleid'] . 's' . $simage_postfix, 0777, true );
					}

// 					// 保存小图
// 					if (! empty ( $data ['articlespic'] ['name'] )) {
// 						$simage_postfix = strrchr ( trim ( strtolower ( $data ['articlespic'] ['name'] ) ), "." );
// 						jieqi_copyfile ( $data ['articlespic'] ['tmp_name'], $this->getDir ( 'imagedir' ) . '/' . $data ['articleid'] . 's' . $simage_postfix, 0777, true );
// 					}elseif (! empty ( $data ['articlelpic'] ['name'] )){
// 						//小图默认使用大图的缩小版,缩小50%
// 						//不使用默认大图
// 						// 					include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
// 						// 					$imgresize = new ImageResize();
// 						// 					$imgresize->load($this->getDir ( 'imagedir' ) . '/' . $data ['articleid'] . 'l' . $limage_postfix);
// 						// 					$imgresize->resize(null,null,0.5);
// 						// 					$imgresize->save($this->getDir ( 'imagedir' ) . '/' . $data ['articleid'] . 's' . $limage_postfix,true);
// 					}
				}
			}
		} else {
			$this->printfail ( $errtext );
		}
	}
	/**
	 * 更新卷
	 *
	 * @param $article 文章
	 * @param $volume 卷信息数组
	 */
	public function updateVolume($article, $volume) {
		$auth = $this->getAuth();
		$errtext = '';
		// 检查标题
		if (strlen ( $volume ['chaptername'] ) == 0)
			$errtext .= $this->jieqiLang ['article'] ['need_chapter_title'] . '<br />';
		if (empty ( $errtext )) {
			if (! empty ( $auth ['uid'] )) {
				$volume ['posterid'] = $auth ['uid'];
				$volume ['poster'] = $auth ['username'];
			} else {
				$volume ['posterid'] = 0;
				$volume ['poster'] = '';
			}
			$volume ['lastupdate'] = JIEQI_NOW_TIME;

			$this->db->init ( 'chapter', 'chapterid', 'article' );
		    $oldChapter = $this->db->get ( $volume ['chapterid'] );
            $display = $oldChapter ['display'];
			if(($oldChapter['display'] == 9 || $oldChapter['display'] == 2) && isset($volume['postdate'])){
				//可以修改定时
				$volume ['postdate'] = $this->handlePostdate($volume['postdate']);
				if($volume ['postdate']<=JIEQI_NOW_TIME) {
				    if($oldChapter['display'] == 9 ) $volume['display'] = 1;
					elseif($oldChapter['display'] == 2)  $volume['display'] = 0;
					$display = $volume['display'];
				}
			}else unset($volume['postdate']);//不修改时间

			//$volume ['postdate'] = $this->handlePostdate($volume['postdate']);
			//$this->db->init ( 'chapter', 'chapterid', 'article' );
			//卷说明保存在txt中
			if (! $this->db->edit ( $volume ['chapterid'], $volume ))
				$this->printfail ( $this->jieqiLang ['article'] ['chapter_edit_failure'] );
			else {
				// 更新文章的最新卷，如果不是最新分卷则不用更新
				if ($volume ['chapterid'] == $article ['lastvolumeid']) {
					$article ['lastvolume'] = $volume ['chaptername'];
					$this->db->init ( 'article', 'articleid', 'article' );
					$this->db->edit ( $article ['articleid'], $article );
				}
				$this->article_repack($article ['articleid'], array('makeopf'=>1));
// 				$volume = $this->db->get ( $volume ['chapterid'] );
// 				$this->instantPackage ( $article ['articleid'] );
// 				$this->editChapter ( $volume ['chaptername'], $volumemanual, $volume ['chaptertype'], $volume ['chapterorder'], $volume ['chapterid'], $volume ['postdate'], $volume ['lastupdate'], 0 );
// 				jieqi_jumppage ( $this->geturl ( 'article', 'article', 'SYS=method=articleManage&aid=' . $article ['articleid'] ), LANG_DO_SUCCESS, $this->jieqiLang ['article'] ['chapter_edit_success'] );
			}
		} else {
			$this->printfail ( $errtext );
		}
	}
	/**
	 * 内部方法，减少文章和所有章节积分，清空文章或者删除文章适用<br>
	 * 章节根据chatper内的posterid判断<br>
	 * 文章积分根据article内的posterid判断<br>
	 * @param unknown $article
	 */
	private function subtractionScore($article){
		$posterary = array ();
		//章节积分
		if (! empty ( $this->jieqiConfigs ['article'] ['scorechapter'] )) {
			$this->db->init ( 'chapter', 'chapterid', 'article' );
			$this->db->setCriteria ( new Criteria ( 'articleid', $article ['articleid'], '=' ) );
			$this->db->queryObjects ();
			while ( $chapterobj = $this->db->getObject () ) {
				$posterid = intval ( $chapterobj->getVar ( 'posterid' ) );
				if (isset ( $posterary [$posterid] ))
					$posterary [$posterid] += $this->jieqiConfigs ['article'] ['scorechapter'];
				else
					$posterary [$posterid] = $this->jieqiConfigs ['article'] ['scorechapter'];
			}
		}
		//文章积分
		include_once (JIEQI_ROOT_PATH . '/class/users.php');
		$users_handler = & JieqiUsersHandler::getInstance ( 'JieqiUsersHandler' );
		if (! empty ( $this->jieqiConfigs ['article'] ['scorearticle'] )) {
			$posterid = intval ( $article ['posterid'] );
			if (isset ( $posterary [$posterid] ))
				$posterary [$posterid] += $this->jieqiConfigs ['article'] ['scorearticle'];
			else
				$posterary [$posterid] = $this->jieqiConfigs ['article'] ['scorearticle'];
		}
		foreach ( $posterary as $pid => $pscore ) {
			$users_handler->changeScore ( $pid, $pscore, false );
		}
	}
	/**
	 * 私有方法，变更作者的章节积分,
	 * @param unknown $posterid	章节的发表者ID
	 * @param unknown $bool		true=>新增章节加分 false=>删除章节减分
	 */
	private function changeScoreByPosterId($posterid,$bool=true){
		$this->addConfig('article','configs');
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		$score = $this->getConfig('article','scorechapter');
		if(!empty($score)){
			$users_handler->changeScore($posterid, $score, $bool);
		}
	}
	/**
	 * 删除一篇文章
	 *
	 * @param
	 *        	$article
	 */
	public function articleDelete($article, $usescore = true) {
		$this->db->init ( 'article', 'articleid', 'article' );
		// 删除文章
		$this->db->delete ( $article ['articleid'] );
		// 更新最新文章
// 		$jieqiArticleuplog ['articleuptime'] = JIEQI_NOW_TIME;
// 		jieqi_setcachevars ( 'articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article' );
		// 删除文本、html及zip
		$this->instantPackage( $article ['articleid'] );
		$this->delete ();
		// 删除章节
		// 检查这篇文章章节发表人，扣积分用
		$this->db->init ( 'chapter', 'chapterid','article');
		$this->db->setCriteria ( new Criteria ( 'articleid', $article ['articleid'], '=' ) );
		// 真正删除章节
		// $criteria=new CriteriaCompo(new Criteria('articleid', $aid, '='));
		$this->db->delete ( $this->db->criteria );
		// 删除附件               jieqi_article_attachs
		$this->db->init ( 'attachs', 'attachid', 'article' );
		// include_once($jieqiModules['article']['path'].'/class/articleattachs.php');
		// $attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');
		$this->db->delete ( $this->db->criteria );
		// 删除书评
		$this->db->init ( 'reviews', 'topicid', 'article' );
		$this->db->setCriteria ( new Criteria ( 'ownerid', $article ['articleid'], '=' ) );
		// $criteria1=new CriteriaCompo(new Criteria('ownerid', $aid, '='));
		// include_once($jieqiModules['article']['path'].'/class/reviews.php');
		// $reviews_handler =& JieqiReviewsHandler::getInstance('JieqiReviewsHandler');
		$this->db->delete ( $this->db->criteria );

		$this->db->init ( 'replies', 'postid', 'article' );
		// include_once($jieqiModules['article']['path'].'/class/replies.php');
		// $replies_handler =& JieqiRepliesHandler::getInstance('JieqiRepliesHandler');
		$this->db->delete ( $this->db->criteria );
		/*
		 * include_once($jieqiModules['article']['path'].'/class/review.php'); $review_handler =& JieqiReviewHandler::getInstance('JieqiReviewHandler'); $review_handler->delete($criteria);
		 */
		// 删除封面
		$imagedir = jieqi_uploadpath ( $this->jieqiConfigs ['article'] ['imagedir'], 'article' ) . jieqi_getsubdir ( $article ['articleid'] ) . '/' . $article ['articleid'];
		if (is_dir ( $imagedir ))
			jieqi_delfolder ( $imagedir, true );

			// 记录删除日志
		$this->db->init ( 'articlelog', 'logid', 'article' );
		// include_once($jieqiModules['article']['path'].'/class/articlelog.php');
		// $articlelog_handler =& JieqiArticlelogHandler::getInstance('JieqiArticlelogHandler');
		$newlog = array ();
		$newlog ['siteid'] = $article['siteid'];
		$newlog ['logtime'] = JIEQI_NOW_TIME;
		$newlog ['userid'] = $_SESSION ['jieqiUserId'];
		$newlog ['username'] = $_SESSION ['jieqiUserName'];
		$newlog ['articleid'] = $article ['articleid'];
		$newlog ['articlename'] = $article ['articlename'];
		$newlog ['chapterid'] = 0;
		$newlog ['chaptername'] = '';
		$newlog ['reason'] = '';
		$newlog ['chginfo'] = $this->jieqiLang ['article'] ['delete_article'];
		$newlog ['chglog'] = '';
		$newlog ['ischapter'] = '0';
		$newlog ['isdel'] = '1';
		$newlog ['databak'] = serialize ( $article );
		$this->db->add ( $newlog );

		// 减少文章和章节积分
		if ($usescore) {
			$this->subtractionScore($article);
		}
		// ////////////////////090712定制功能////////////////////////////////////
		// 删除定制缓存
		// include_once($jieqiModules['article']['path'].'/include/setarticlecache.php');
		// jieqi_article_editcache('info', $aid);
		// ///////////////////////////////////////////////////////

		// return $article;
	}
	/**
	 * 清空一篇文章，删除内所有章节
	 *
	 * @param unknown $aid
	 * @param unknown $usescore
	 */
	public function articleClean($article, $usescore) {
// 		global $jieqiModules;
// 		global $article_handler;
// 		global $chapter_handler;
// 		global $jieqiArticleuplog;
// 		global $jieqiConfigs;
		// 清除文章统计
		$this->db->init ( 'article', 'articleid', 'article' );
		// $criteria = new CriteriaCompo(new Criteria('articleid', $aid));
		$fields = array (
				'lastchapter' => '',
				'lastchapterid' => 0,
				'lastvolume' => '',
				'lastvolumeid' => 0,
				'chapters' => 0,
				'size' => 0
		);
		// $article_handler->updatefields($fields, $criteria);
		$this->db->updatetable ( 'article_article', $fields, 'articleid = ' . $article ['articleid'] );
		// print_r($criteria);exit('dd');
		// 更新最新文章
// 		$jieqiArticleuplog ['articleuptime'] = JIEQI_NOW_TIME;
// 		$jieqiArticleuplog ['chapteruptime'] = JIEQI_NOW_TIME;
// 		jieqi_setcachevars ( 'articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article' );

		// 删除文本、html及zip
		$this->instantPackage ( $article ['articleid'] );
		// $package=new JieqiPackage($_REQUEST['id']);
		$this->delete ();
		// 新建一个opf
		$this->loadOPF($article ['articleid']);
// 		$this->initPackage ( array (
// 				'id' => $article ['articleid'],
// 				'title' => $article ['articlename'],
// 				'creatorid' => $article ['authorid'],
// 				'creator' => $article ['author'],
// 				'subject' => $article ['keywords'],
// 				'description' => $article ['intro'],
// 				'publisher' => JIEQI_SITE_NAME,
// 				'contributorid' => $article ['posterid'],
// 				'contributor' => $article ['poster'],
// 				'sortid' => $article ['sortid'],
// // 				'typeid' => $article ['typeid'],
// 				'articletype' => $article ['articletype'],
// 				'permission' => $article ['permission'],
// 				'firstflag' => $article ['firstflag'],
// 				'fullflag' => $article ['fullflag'],
// 				'imgflag' => $article ['imgflag'],
// 				//'power' => $article ['power'],
// 				'display' => $article ['display']
// 		) );
		// 删除章节

		// 检查这篇文章章节发表人，扣积分用
		$this->db->init ( 'chapter', 'chapterid', 'article' );
		$this->db->setCriteria ( new Criteria ( 'articleid', $article ['articleid'], '=' ) );

		// 真正删除章节
		// $criteria=new CriteriaCompo(new Criteria('articleid', $aid, '='));
		$this->db->delete ( $this->db->criteria );
		// 删除附件
		$this->db->init ( 'attachs', 'attachid', 'article' );
		// include_once($jieqiModules['article']['path'].'/class/articleattachs.php');
		// $attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');
		$this->db->delete ( $this->db->criteria );

		if ($usescore) {
			$this->subtractionScore($article);
		}
		// return $article;
	}
	/**
	 * 删除指定章节/卷
	 * @param unknown $aid	文章id
	 * @param unknown $cid	章节id
	 * @param number $ctype	章节类型 1卷 0章节
	 */
	function delChapterById($aid,$cid,$ctype=0){
		$this->delPower($aid);
		if($ctype==1) $typename=$this->jieqiLang['article']['volume_name'];
		else $typename=$this->jieqiLang['article']['chapter_name'];
		$article = $this->isExists($aid);
		//删除章节
		$this->db->init('chapter','chapterid','article');
		$chapter=$this->db->get($cid);//$this->printfail($chapter['chapterorder']);
		$this->db->delete($cid);
		//后面章节的序号前移一位
		//chapters值记录 显示 状态的章节总数量，这里需要查询所有章节数
		$this->db->setCriteria ( new Criteria ( 'articleid', $aid));
		$chaptercount = $this->db->getCount($this->db->criteria);
		$chapters = $this->db->getCount();
		if($chapter['chapterorder'] < $chapters){
			$this->db->updatetable ( 'article_chapter', array (
					'chapterorder' => '--'
			), 'articleid = ' . $aid . ' and chapterorder > ' . $chapter['chapterorder'] );
		}
		//如果删除最后卷或者章节
		$updateblock=false;
// 		$this->db->init('article','articleid','article');
		//删除的章是不是display=0
		if($chapter['display'] == 0){
			$article['chapters'] = $article['chapters']-1;
			$article['size'] = $article['size'] - $chapter['size'];

			//更新最新卷，最新章节
			$this->db->setCriteria();
			if($cid>0 && $cid==$article['lastchapterid']){
				$this->db->criteria->add(new Criteria('articleid', $article['articleid']));
				$this->db->criteria->add(new Criteria('chaptertype', 0, '='));
				$this->db->criteria->add(new Criteria('display', 0, '='));
				$this->db->criteria->setSort('chapterorder');
				$this->db->criteria->setOrder('DESC');
				$this->db->queryObjects();
				$tmpchapter=$this->db->getObject();
				if($tmpchapter){
					$article['lastchapter'] = $tmpchapter->getVar('chaptername');
					$article['lastchapterid'] = $tmpchapter->getVar('chapterid');
					unset($tmpchapter);
				}else{
					$article['lastchapter'] = '';
					$article['lastchapterid'] = 0;
				}
				$updateblock=true;
			}elseif($cid>0 && $cid==$article['lastvolumeid']){
				$this->db->criteria->add(new Criteria('articleid', $article['articleid']));
				$this->db->criteria->add(new Criteria('chaptertype', 1, '='));
				$this->db->criteria->add(new Criteria('display', 0, '='));
				$this->db->criteria->setSort('chapterorder');
				$this->db->criteria->setOrder('DESC');
				$this->db->queryObjects();
				$tmpchapter=$this->db->getObject();
				if($tmpchapter){
					$article['lastvolume'] = $tmpchapter->getVar('chaptername');
					$article['lastvolumeid'] = $tmpchapter->getVar('chapterid');
					unset($tmpchapter);
				}else{
					$article['lastvolume'] = '';
					$article['lastvolumeid'] = 0;
				}
				$updateblock=true;
			}
			$this->db->init('article','articleid','article');
			//更新最新卷和最新章节
			$this->db->edit($article['articleid'],$article);
		}
		//生成opf
		////$this->article_repack($article['articleid'], array('makeopf'=>1));
		$this->instantPackage ( $aid );
		//if($chapter['display'] == 0){
		$this->delChapter($chapter['chapterorder'], $chapter['chapterid'], $chapter['display']);
		//}

// 		include_once($jieqiModules['article']['path'].'/class/package.php');
// 		$package=new JieqiPackage($article['articleid']);
		//删除附件记录
// 		include_once($jieqiModules['article']['path'].'/class/articleattachs.php');
// 		$attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');
// 		$criteria=new CriteriaCompo(new Criteria('chapterid', $_REQUEST['id']));
// 		$attachs_handler->delete($criteria);

		$this->db->init ( 'attachs', 'attachid', 'article' );
		$this->db->setCriteria(new Criteria('chapterid', '('.$cid.')', 'IN'));
		$this->db->delete( $this->db->criteria );
		//减少章节积分
		$this->changeScoreByPosterId($chapter['posterid'],false);
	}
	/**
	 * 批量删除章节
	 * @param unknown $article	文章对象
	 * @param unknown $cids		查询对象		章节Id，规则是：1,2,3,4,5,6......
	 * @param string $usescore	更新文章和章节积分
	 */
	function batchDelChapter($article,$cids,$usescore = false){
		global $jieqi_file_postfix;
		$this->delPower($article['articleid']);
		$this->db->init('chapter','chapterid','article');
		$this->db->setCriteria(new Criteria('chapterid', '('.$cids.')', 'IN'));
		$this->db->criteria->add(new Criteria('articleid',$article['articleid'], '='));

		//查询符合条件章节
		$posterary=array();
		$this->db->queryObjects();
		$chapterary = array();
		$k = 0;
// 		$ccids = '';
		while($chapterobj = $this->db->getObject()){
			$chapterary[$k]['id'] = intval($chapterobj->getVar('chapterid'));
// 			if($ccids != '') $ccids .= ',';
// 			$ccids .= $chapterary[$k]['id'];
			$chapterary[$k]['size'] = $chapterobj->getVar('size');
			$chapterary[$k]['attach'] = $chapterobj->getVar('attachment', 'n') == '' ? 0 : 1;

			$k++;
			//章节积分
			if(!empty($this->jieqiConfigs['article']['scorechapter'])){
				$posterid = intval($chapterobj->getVar('posterid'));
				if(isset($posterary[$posterid])) $posterary[$posterid] += $this->jieqiConfigs['article']['scorechapter'];
				else  $posterary[$posterid] = $this->jieqiConfigs['article']['scorechapter'];
			}
		}
		//删除章节
// 		$chapter_handler->delete($criteria);
		$this->db->delete ( $this->db->criteria );
		//删除附件数据库
		if($cids != ''){
			$this->db->init ( 'attachs', 'attachid', 'article' );
			$this->db->setCriteria(new Criteria('chapterid', '('.$cids.')', 'IN'));
			$this->db->delete( $this->db->criteria );
		}
		//删除文本文件、附件文件、html
		$aid = $article['articleid'];
		$txtdir = jieqi_uploadpath($this->jieqiConfigs['article']['txtdir'], 'article').jieqi_getsubdir($aid).'/'.$aid;
		$htmldir = jieqi_uploadpath($this->jieqiConfigs['article']['htmldir'], 'article').jieqi_getsubdir($aid).'/'.$aid;
		$attachdir = jieqi_uploadpath($this->jieqiConfigs['article']['attachdir'], 'article').jieqi_getsubdir($aid).'/'.$aid;
		foreach($chapterary as $c){
			if(is_file($txtdir.'/'.$c['id'].$jieqi_file_postfix['txt'])) jieqi_delfile($txtdir.'/'.$c['id'].$jieqi_file_postfix['txt']);
			if(is_file($htmldir.'/'.$c['id'].$this->jieqiConfigs['article']['htmlfile'])) jieqi_delfile($htmldir.'/'.$c['id'].$this->jieqiConfigs['article']['htmlfile']);
			if(is_dir($attachdir.'/'.$c['id'])) jieqi_delfolder($attachdir.'/'.$c['id']);
		}
		//重新生成网页和打包
// 		include_once($jieqiModules['article']['path'].'/include/repack.php');
		$ptypes=array('makeopf'=>1, 'makehtml'=>0, 'makezip'=>$this->jieqiConfigs['article']['makezip'], 'makefull'=>$this->jieqiConfigs['article']['makefull'], 'maketxtfull'=>$this->jieqiConfigs['article']['maketxtfull'], 'makeumd'=>$this->jieqiConfigs['article']['makeumd'], 'makejar'=>$this->jieqiConfigs['article']['makejar']);
// 		article_repack($aid, $ptypes, 0);
		$this->article_repack($aid, $ptypes);
		//减少文章和章节积分
		if($usescore){
			//删除章节需要减文章积分吗？
// 			include_once(JIEQI_ROOT_PATH.'/class/users.php');
// 			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
// 			if(!empty($this->jieqiConfigs['article']['scorearticle'])){
// 				$posterid = intval($article['posterid']);
// 				if(isset($posterary[$posterid])) $posterary[$posterid] += $this->jieqiConfigs['article']['scorearticle'];
// 				else  $posterary[$posterid] = $this->jieqiConfigs['article']['scorearticle'];
// 			}
			$users_handler = $this->getUserObject();
			foreach($posterary as $pid=>$pscore){
				$users_handler->changeScore($pid, $pscore, false);
			}
		}
		//更新最新文章
// 		$jieqiArticleuplog['articleuptime']=JIEQI_NOW_TIME;
// 		$jieqiArticleuplog['chapteruptime']=JIEQI_NOW_TIME;
// 		jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
// 		return $article;
	}
	/**
	 * 章节排序
	 * @param  $aid		文章id
	 * @param  $fromid	动作位置
	 * @param  $toid	0=>最前面，其余则是目标位置+1
	 */
	function chapterSort($article,$fromid,$toid){
// 		global $this->jieqiLang,$jieqiConfigs,$jieqiPower,$jieqiModules;
// 		jieqi_loadlang('article', 'article');
// 		jieqi_getconfigs('article', 'power');
// 		jieqi_getconfigs('article', 'configs');
		//管理别人文章权限
		$aid = $article['articleid'];
		$this->db->init('chapter','chapterid','article');
		$this->db->setCriteria(new Criteria('articleid', $aid));
		$this->db->criteria->add(new Criteria('chapterorder', $fromid));
// 		$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid']));
// 		$criteria->add(new Criteria('chapterorder', $_REQUEST['fromid']));
		$this->db->queryObjects();
		$chapter=$this->db->getObject();//chapter is object
// 		unset($criteria);
		if($chapter){
			$cid=$chapter->getVar('chapterid');
			if($fromid<$toid){

				$this->db->updatetable ( 'article_chapter', array (
						'chapterorder' => '--'
				), 'articleid = ' . $aid . ' and chapterorder > ' . $fromid . ' and chapterorder <= ' . $toid );


// 				$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid'], '='));
// 				$criteria->add(new Criteria('chapterorder', $_REQUEST['fromid'], '>'));
// 				$criteria->add(new Criteria('chapterorder', $_REQUEST['toid'], '<='));
// 				$chapter_handler->updatefields('chapterorder=chapterorder-1', $criteria);
// 				unset($criteria);

				$this->db->updatetable ( 'article_chapter', array (
						'chapterorder' => $toid
				), 'chapterid = ' . $cid  );

// 				$criteria=new CriteriaCompo(new Criteria('chapterid', $cid, '='));
// 				$chapter_handler->updatefields('chapterorder='.$_REQUEST['toid'], $criteria);
// 				unset($criteria);
			}else{

				$this->db->updatetable ( 'article_chapter', array (
						'chapterorder' => '++'
				), 'articleid = ' . $aid . ' and chapterorder < ' . $fromid . ' and chapterorder > ' . $toid );


// 				$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid'], '='));
// 				$criteria->add(new Criteria('chapterorder', $_REQUEST['fromid'], '<'));
// 				$criteria->add(new Criteria('chapterorder', $_REQUEST['toid'], '>'));
// 				$chapter_handler->updatefields('chapterorder=chapterorder+1', $criteria);
// 				unset($criteria);



				$this->db->updatetable ( 'article_chapter', array (
						'chapterorder' => $toid+1
				), 'chapterid = ' . $cid  );

// 				$criteria=new CriteriaCompo(new Criteria('chapterid', $cid, '='));
// 				$chapter_handler->updatefields('chapterorder='.($_REQUEST['toid']+1), $criteria);
// 				unset($criteria);
			}
			$this->instantPackage($aid);
// 			include_once($jieqiModules['article']['path'].'/class/package.php');
// 			$package=new JieqiPackage($_REQUEST['aid']);
			$this->sortChapter($fromid,$toid);
			//检查最新卷和最新章节
// 			$this->db->init('article','articleid','article');
			$this->db->setCriteria(new Criteria('articleid', $aid, '='));
// 			$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid'], '='));
			$this->db->criteria->setSort('chapterorder');
			$this->db->criteria->setOrder('DESC');
// 			$criteria->setSort('chapterorder');
// 			$criteria->setOrder('DESC');
			$this->db->queryObjects();
			$v=$this->db->getObject();
			if($v){
				$nolastchapter=true;
				$nolastvolume=true;
				$lastchapter='';
				$lastchapterid=0;
				$lastvolume='';
				$lastvolumeid=0;
				do{
					if(!$nolastchapter && !$nolastvolume) break;
					if($v->getVar('chaptertype')==1){
						if($nolastvolume){
							$lastvolume=$v->getVar('chaptername', 'n');
							$lastvolumeid=$v->getVar('chapterid', 'n');
							$nolastvolume=false;
						}
					}else{
						if($nolastchapter){
							$lastchapter=$v->getVar('chaptername', 'n');
							$lastchapterid=$v->getVar('chapterid', 'n');
							$nolastchapter=false;
						}
					}
				}while($v = $this->db->getObject());
				$updateblock=false;
				if($article['lastchapterid'] != $lastchapterid){
					$article['lastchapterid']= $lastchapterid;
					$article['lastchapter']= $lastchapter;
					$updateblock=true;
				}
				if($article['lastvolumeid'] != $lastvolumeid){
					$article['lastvolumeid']= $lastvolumeid;
					$article['lastvolume']= $lastvolume;
					$updateblock=true;
				}
				if($updateblock){
					$this->db->init('article','articleid','article');
					$this->db->edit($aid,$article);
					//更新最新文章
// 					if($article['display']=='0'){
// 						jieqi_getcachevars('article', 'articleuplog');
// 						if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
// 						$jieqiArticleuplog['chapteruptime']=JIEQI_NOW_TIME;
// 						jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
// 					}
				}
			}
		}else{
			$this->printfail($this->jieqiLang['article']['chapter_sort_notexists']);
		}
	}
	/**
	 * 书架数据列表
	 * @param  $page 页
	 * @return multitype:string NULL unknown number Ambigous <multitype:, number> Ambigous <multitype:, string>
	 */
	function getBcList($page){
		$auth = $this->getAuth();
		$data = array();
		$markclassrows=array();
		for($i=1;$i<=$this->jieqiConfigs['article']['maxmarkclass'];$i++){
			$markclassrows[]['classid']=$i;
		}
		$data['markclassrows'] = $markclassrows;
		//最大收藏数
		jieqi_getconfigs('system', 'honors');
		jieqi_getconfigs('article', 'right');
// 		jieqi_getconfigs('article', 'sort');
		$maxnum=$this->jieqiConfigs['article']['maxbookmarks'];
		$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
		if($honorid && isset($jieqiRight['article']['maxbookmarks']['honors'][$honorid]) && is_numeric($jieqiRight['article']['maxbookmarks']['honors'][$honorid])) $maxnum = intval($jieqiRight['article']['maxbookmarks']['honors'][$honorid]);

// 		$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');
// 		$data['checkall'] = '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">';
// 		jieqi_includedb();
		$this->db->init('bookcase ','caseid','article');
// 		$bookcase_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
		$this->db->setCriteria(new Criteria('userid', $auth['uid']));
// 		$criteria=new CriteriaCompo(new Criteria('userid', $_SESSION['jieqiUserId']));
// 		$criteria->setTables(jieqi_dbprefix('article_bookcase'));
// 		$jieqiTpl->assign('nowbookcase', $bookcase_query->getCount($criteria));
		$data['nowbookcase'] = $this->db->getCount();
// 		unset($criteria);
// 		$criteria=new CriteriaCompo(new Criteria('c.userid', $_SESSION['jieqiUserId']));
// 		if(is_numeric($classid)){
// 			$criteria->add(new Criteria('c.classid', $_REQUEST['classid']));
// 			$this->db->criteria->add(new Criteria('classid',$classid));
// 		}
// 		if($jieqiModules['obook']['publish']){
// 			//有电子书时候查询电子书最新章节
// 			$criteria->setTables(jieqi_dbprefix('article_bookcase').' c LEFT JOIN '.jieqi_dbprefix('article_article').' a ON c.articleid=a.articleid LEFT JOIN '.jieqi_dbprefix('obook_obook').' o ON a.articleid=o.articleid');
// 			$criteria->setFields('c.*, a.articleid, a.lastupdate, a.articlename, a.lastchapterid, a.lastchapter, o.obookid, o.lastvolume as obookvolume, o.lastvolumeid as obookvolumeid, o.lastchapter as obookchapter, o.lastchapterid as obookchapterid, o.lastupdate as obookupdate, o.size as obooksize, o.publishid as obookpublishid');
// 			$criteria->setSort('o.lastupdate DESC, a.lastupdate');
// 			$criteria->setOrder('DESC');
// 		}else{

			$this->db->criteria->setTables(jieqi_dbprefix('article_bookcase').' c LEFT JOIN '.jieqi_dbprefix('article_article').' a ON c.articleid=a.articleid');
			$this->db->criteria->setFields('c.*, a.articleid, a.lastupdate, a.articlename, a.authorid, a.author, a.sortid, a.lastchapterid, a.lastchapter, a.fullflag,a.imgflag,a.siteid');
			$this->db->criteria->setSort('c.joindate');
			$this->db->criteria->setOrder('DESC');
// 		}
// 			$this->jieqiConfigs ['article'] ['pagenum'] = 1;

		$data ['articlerows'] = $this->db->lists ( $this->jieqiConfigs ['article'] ['pagenum'], $page,JIEQI_PAGE_TAG);
		// 处理页面跳转
		$data ['url_jumppage'] = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME,'article','evalpage=0','SYS=method=bcView'));
// 		$data ['url_jumppage'] = $this->db->getPage ();

		foreach($data ['articlerows'] as $k=>$v){
			$data ['articlerows'][$k] = $this->article_vars($v);
		}

// 		$this->db->criteria->setLimit(20);
// 		$this->db->queryObjects();
// 		$bookcaserows=array();
		$k=0;
// 		while($v = $this->db->getObject()){
// 			//电子书部分信息
// 			/*
// 			if($jieqiModules['obook']['publish']){
// 				$bookcaserows[$k]['obookid']=$v->getVar('obookid');
// 				$bookcaserows[$k]['obookvolume']=$v->getVar('obookvolume');
// 				$bookcaserows[$k]['obookvolumeid']=$v->getVar('obookvolumeid');
// 				$bookcaserows[$k]['obookchapter']=$v->getVar('obookchapter');
// 				$bookcaserows[$k]['obookchapterid']=$v->getVar('obookchapterid');
// 				$bookcaserows[$k]['obookupdate']=$v->getVar('obookupdate');
// 				$bookcaserows[$k]['obooksize']=$v->getVar('obooksize');
// 				$bookcaserows[$k]['obookpublishid']=$v->getVar('obookpublishid');
// 				$bookcaserows[$k]['lastobookdate']=date(JIEQI_DATE_FORMAT, $v->getVar('obookupdate'));
// 			}*/
// 			$bookcaserows[$k]['caseid']=$v->getVar('caseid');
// 			$bookcaserows[$k]['articleid']=$v->getVar('articleid');
// 			$bookcaserows[$k]['lastchapterid']=$v->getVar('lastchapterid');
// 			$bookcaserows[$k]['chapterid']=$v->getVar('chapterid');
// 			$bookcaserows[$k]['sortid']=$v->getVar('sortid');
// 			$bookcaserows[$k]['typeid']=$v->getVar('typeid');
// 			$bookcaserows[$k]['sort']=$this->jieqiConfigs['sort'][$v->getVar('sortid')]['shortname'];
// // 			$bookcaserows[$k]['type']=$this->jieqiConfigs['sort'][$v->getVar('sortid')]['types'][$v->getVar('typeid')];
// 			$bookcaserows[$k]['authorid']=$v->getVar('authorid');
// 			$bookcaserows[$k]['author']=$v->getVar('author');

// 			$bookcaserows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('caseid').'">';
// 			$tmpvar=$v->getVar('articlename');
// 			if(!empty($tmpvar)) {
// 				//$bookcaserows[$k]['url_articleinfo']=$article_dynamic_url.'/readbookcase.php?aid='.$v->getVar('articleid').'&bid='.$v->getVar('caseid');
// 				//$bookcaserows[$k]['url_index']=$bookcaserows[$k]['url_articleinfo'].'&indexflag=1';
// 				$bookcaserows[$k]['articlename']=$v->getVar('articlename');
// 			}else{
// 				$bookcaserows[$k]['url_articleinfo']='#';
// 				$bookcaserows[$k]['url_index']='#';
// 				$bookcaserows[$k]['articlename']=$this->jieqiLang['article']['articlemark_has_deleted'];
// 			}

// 			if($v->getVar('lastchapter')==''){
// 				$bookcaserows[$k]['lastchapter']='';
// 				$bookcaserows[$k]['url_lastchapter']='#';
// 			}else{
// 				$bookcaserows[$k]['lastchapter']=$v->getVar('lastchapter');
// 				//$bookcaserows[$k]['url_lastchapter']=$article_dynamic_url.'/readbookcase.php?aid='.$v->getVar('articleid').'&bid='.$v->getVar('caseid').'&cid='.$v->getVar('lastchapterid');
// 			}
// 			if($v->getVar('lastupdate')>$v->getVar('lastvisit')) $bookcaserows[$k]['hasnew']=1;
// 			else $bookcaserows[$k]['hasnew']=0;

// 			if($v->getVar('chaptername')==''){
// 				$bookcaserows[$k]['articlemark']='';
// 				$bookcaserows[$k]['url_articlemark']='#';
// 			}else{
// 				$bookcaserows[$k]['articlemark']=$v->getVar('chaptername');
// // 				$bookcaserows[$k]['url_articlemark']=$article_dynamic_url.'/readbookcase.php?aid='.$v->getVar('articleid').'&bid='.$v->getVar('caseid').'&cid='.$v->getVar('chapterid');
// 			}
// 			$bookcaserows[$k]['lastupdate']=$v->getVar('lastupdate');
// 			//移除链接
// 			$bookcaserows[$k]['url_delete']=$this->geturl ( 'article', 'article', 'SYS=method=bcRem&classid=' . $classid.'&caseid='.$v->getVar('caseid') );
// 			$k++;
// 		}
// 		$data['bookcaserows'] = $bookcaserows;
		$data['maxbookcase'] = $maxnum;
// 		$data['classbookcase'] = count($bookcaserows);
		$data['maxmarkclass'] = $this->jieqiConfigs['article']['maxmarkclass'];
// 		$jieqiTpl->assign_by_ref('bookcaserows', $bookcaserows);
// 		$jieqiTpl->assign('maxbookcase', $maxnum);
// 		$jieqiTpl->assign('classbookcase', count($bookcaserows));
// 		$jieqiTpl->assign('classid', $_REQUEST['classid']);
		return $data;
	}
	/**
	 * 批量删除收藏流程。
	 * <br>批量删除的同时获取文章的aid，在方法内处理两张属性表的goodnum数据，构成一个书架移除的流程操作。
	 * @param  $caseids	格式：1,2,3,4,5,6
	 */
	function baBatchDel($caseids){
	    $auth = $this->getAuth();
		$this->db->init('bookcase ','caseid','article');
		$this->db->setCriteria(new Criteria('userid', $auth['uid']));
		$this->db->criteria->add(new Criteria('caseid', '('.$caseids.')', 'IN'));
		$this->db->queryObjects();
		$aids;
		$i=0;
		while($bookcase = $this->db->getObject()){
			if(!empty($aids)) $aids.=', ';
			$aids.=intval($bookcase->getVar('articleid'));
			$i++;
		}

		//批量删除
		if ($this->db->delete( $this->db->criteria ))
		{
			$this->addConfig('article','configs');
			$jieqiConfigs['article'] = $this->getConfig('article','configs');
			if($jieqiConfigs['article']['addcasescore']>0){
				//加积分
				$users_handler = $this->getUserObject();
				$users_handler->changeScore($auth['uid'], $jieqiConfigs['article']['addcasescore']*$i, false);
			}

			$this->changeGoodnum($aids);
		}else{
			$this->printfail();
		}

	}
	/**
	 * 私有方法<br>
	 * 从书架移除或者批量移除，收藏总量需要更新，同步更新article_stat和article_statamout中的goodnum信息
	 * @param  $aids	格式：1,2,3,4,5,6
	 */
	private function changeGoodnum($aids){
		//属性表
		$this->db->updatetable ( 'article_stat ', array (
				'totalnum' => '--' , 'lasttime' => JIEQI_NOW_TIME
		), ' articleid in (' . $aids . ') and mid = \'goodnum\'');
		//总量属性表
		$this->db->updatetable ( 'article_statamout ', array (
				'goodnum' => '--'
		), ' articleid in (' . $aids .')');
	}


///////////////////////////////////////新////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

	/**
	 * 重新生成opf
	 * @param unknown $aid
	 * @param unknown $params
	 * @param number $syn    默认0 异步, 1 同步
	 * @return boolean
	 */
	function article_repack($aid, $params=array(), $syn=0)
	{
	     $this->db->init ( 'article', 'articleid', 'article' );
		 $this->db->setCriteria(new Criteria('articleid',$aid, '='));
		 $article = $this->db->get($this->db->criteria);
		 if(!is_object($article)){
			   return false;
		 }else{
		 	//初始化
		 	$this->instantPackage($aid);
		 	if($syn === 0){
// 		 		echo '开始异步';
		 		$url = $this->geturl ( 'article', 'article', 'SYS=method=synchronousMakePack&key='.urlencode(md5(JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME.JIEQI_SITE_KEY)).'&aid='.$aid);
// 		 		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
// 		 		$url=$article_static_url.'/makepack.php?key='.urlencode(md5(JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME)).'&id='.intval($id);
// 		 		$url=trim($url);
// 		 		if(strtolower(substr($url,0,7)) != 'http://') $url='http://'.$_SERVER['HTTP_HOST'].$url;
		 		foreach($params as $k=>$v){
		 			if($v){
// 		 				$url.='&packflag[]='.urlencode($k);
		 				$url.='&'.$k.'='.$v;
		 			}
		 		}
		 		return jieqi_socket_url($url);
		 	}else if($syn === 1){
// 		 		echo '测试异步、同步<br>';
// // 		 		echo str_repeat(' ',1024*4);
// 		 		for ($i = 0; $i < 10; $i++) {
// 		 			echo '<div style="float:left">&nbsp;'.$i.'</div>';
// 		 			sleep(2);
// 		 			ob_flush();
// 		 			flush();
// 		 		}
		 		global $jieqi_file_postfix;

		 		////开始读取章节
		 		$this->db->init ( 'chapter', 'chapterid', 'article' );
		 		$this->db->setCriteria(new Criteria ( 'articleid', $aid, '=' ));
		 		$this->db->criteria->setSort ( 'chapterorder ASC, chapterid' );
		 		$this->db->criteria->setOrder ( 'ASC' );
		 		$this->db->queryObjects();
		 		$i = 0;
		 		$articlesize = $lastvolumeid_tmp = $chapters = 0;
		 		$intro = $lastvolume_tmp = '';
				//$chapters = $article->getVar('chapters', 'n');
			    $lastvolumeid = $article->getVar('lastvolumeid', 'n');
			    $lastvolume = $article->getVar('lastvolume', 'n');
				$lastchaptervip = $article->getVar('lastchaptervip', 'n');
			    $lastchapterid = $article->getVar('lastchapterid', 'n');
			    $lastchapter = $article->getVar('lastchapter', 'n');
				$lastupdate = $article->getVar('lastupdate', 'n');
//                $chapters_array = array();
				$chapters_array = array();
		 		while ( $chapter = $this->db->getObject() ) {
		 			if (! $chapter->getVar ( 'display', 'n' )) {//显示的章节循环赋值
					    $chaptername = mb_ereg_replace('^(　| )+', '', $chapter->getVar ( 'chaptername', 'n' ));
					    $chaptername = mb_ereg_replace('(　| |)+$', '', $chaptername);
						if ($chapter->getVar ( 'chaptertype', 'n' ) == 1){
							$contenttype = 'volume';
							$intro = $chapter->getVar ( 'manual', 'n' );//@$this->getContent($chapter->getVar ( 'chapterid', 'n' ));
							$lastvolumeid_tmp = $chapter->getVar ( 'chapterid', 'n' );
						    $lastvolume_tmp = $chaptername;
						}else{
						    $chapters++;
							$contenttype = 'chapter';
							//if($chapter->getVar ( 'isvip', 'n' )) $intro = $chapter->getVar ( 'manual', 'n' );
							//else $intro = '';
							$intro = $chapter->getVar ( 'manual', 'n' );
							$articlesize = $articlesize + intval ( $chapter->getVar ( 'size', 'n' ) );
							$lastupdate = $chapter->getVar('postdate', 'n');
							$lastchapterid = $chapter->getVar ( 'chapterid', 'n' );
							$lastchapter = $chaptername;
							$lastvolumeid = $lastvolumeid_tmp;
						    $lastvolume = $lastvolume_tmp;
							$lastchaptervip = $chapter->getVar ( 'isvip', 'n' );
						}
		 				$chapters_array[$i] = array (
		 						'id' => $chaptername,
		 						'href' => $chapter->getVar ( 'chapterid', 'n' ) . $jieqi_file_postfix['txt'],
		 						'media-type' => 'text/html',
		 						'content-type' => $contenttype,
		 						'display' => $chapter->getVar ( 'display', 'n' ),
		 						'postdate' => $chapter->getVar ( 'postdate', 'n' ),
		 						'lastupdate' => $chapter->getVar ( 'lastupdate', 'n' ),
		 						'saleprice' => $chapter->getVar ( 'saleprice', 'n' ),
		 						'isvip' => $chapter->getVar ( 'isvip', 'n' ),
		 						'size' => $chapter->getVar ( 'size', 'n' ) ,
		 						'intro' => $intro
		 				);
		 				//if ($chapter->getVar ( 'chaptertype', 'n' ) == 0) $articlesize = $articlesize + intval ( $chapter->getVar ( 'size', 'n' ) );
		 			}//章节循环赋值结束
		 			$i++;
		 			if ($chapter->getVar ( 'chapterorder', 'n' ) != $i) {
		 				$chapter->setVar ( 'chapterorder', $i );
		 				$this->db->edit($chapter->getVar ( 'chapterid', 'n' ),$chapter);
		 			}

		 		}////读取章节结束

                if($articlesize<1) {
					$lastvolumeid = 0;
					$lastvolume = '';
					$lastchaptervip = 0;
					$lastchapterid = 0;
					$lastchapter = '';
					$lastupdate = $article->getVar('postdate', 'n');
				}
		 		//检查文章信息和统计的是否对应
		 		$changeflag=false;
		 		if($article->getVar('chapters','n') != $chapters){
		 			$article->setVar('chapters', $chapters);
		 			$changeflag=true;
		 		}
		 		if($article->getVar('size','n') != $articlesize){
		 			$article->setVar('size', $articlesize);
		 			$changeflag=true;
		 		}
		 		if($article->getVar('lastupdate','n') != $lastupdate){
		 			$article->setVar('lastupdate', $lastupdate);
		 			$changeflag=true;
		 		}
		 		if($article->getVar('lastchapterid','n') != $lastchapterid){
				    $article->setVar('lastvolumeid', $lastvolumeid);
					$article->setVar('lastvolume', $lastvolume);
		 			$article->setVar('lastchapterid', $lastchapterid);
					$article->setVar('lastchapter', $lastchapter);
		 			$changeflag=true;
		 		}
		 		if($article->getVar('lastchaptervip','n') != $lastchaptervip){
		 			$article->setVar('lastchaptervip', $lastchaptervip);
		 			$changeflag=true;
		 		}
		 		if($changeflag){
		 			$this->db->init ( 'article', 'articleid', 'article' );
		 			$this->db->edit($article->getVar('articleid','n'),$article);
		 		}
				$this->initPackage(array('articleid'=>$article->getVar('articleid','n'), 'articlename'=>$article->getVar('articlename', 'n'), 'authorid'=>$article->getVar('authorid','n'), 'author'=>$article->getVar('author','n'),'agentid'=>$article->getVar('agentid','n'), 'agent'=>$article->getVar('agent','n'), 'keywords'=>$article->getVar('keywords','n'), 'intro'=>$article->getVar('intro', 'n'), 'publisher'=>JIEQI_SITE_NAME, 'posterid'=>$article->getVar('posterid', 'n'), 'poster'=>$article->getVar('poster', 'n'), 'sortid'=>$article->getVar('sortid', 'n'), 'typeid'=>$article->getVar('typeid', 'n'), 'articletype'=>$article->getVar('articletype', 'n'), 'permission'=>$article->getVar('permission', 'n'), 'firstflag'=>$article->getVar('firstflag', 'n'), 'fullflag'=>$article->getVar('fullflag', 'n'), 'notice'=>$article->getVar('notice', 'n'), 'display'=>$article->getVar('display', 'n'), 'size'=>$article->getVar('size', 'n'), 'chapters'=>$article->getVar('chapters', 'n'), 'lastvolumeid'=>$article->getVar('lastvolumeid', 'n'), 'lastvolume'=>$article->getVar('lastvolume', 'n'), 'lastchapterid'=>$article->getVar('lastchapterid', 'n'), 'lastchapter'=>$article->getVar('lastchapter', 'n'), 'postdate'=>$article->getVar('postdate', 'n'), 'lastupdate'=>$article->getVar('lastupdate', 'n'), 'signdate'=>$article->getVar('signdate', 'n')), false);
				$this->chapters = $chapters_array;
		 		// 开始生成
		 		$this->isload = true;
		 		// 生成opf
		 		if ($params['makeopf']) $this->createOPF();
		 		//20100125更新
		 		$params['startupdateOrder'] = $params['startupdateOrder']<=1 ?1 :$params['startupdateOrder'];
		 		if($params['makehtml']){
		 			$this->addConfig('article','url');
		 			$setreader = $this->getConfig('article','url','reader_main');
		 			if(isset($setreader['ishtml']) && $setreader['ishtml']){
		 				$chaptercount=count($this->chapters);
		 				for($i=$params['startupdateOrder']; $i<=$chaptercount; $i++){
		 					if($this->chapters[$i-1]['content-type']=='chapter') $this->makeHtml($i,false,true);
		 				}
		 			}
		 			//生成html目录
		 			$setindex = $this->getConfig('article','url','index_main');
		 			if(isset($setindex['ishtml']) && $setindex['ishtml']) $this->makeIndex();
		 		}
		 		//生成zip
		 		if($params['makezip']) $this->makezip();
		 		//生成umd
		 		if($params['makeumd']) $this->makeumd();
		 		//生成txt全文
		 		if($params['maketxtfull']) $this->maketxtfull();
		 		//生成jar
		 		if($params['makejar']) $this->makejar();

		 		return true;
		 	}
		 }
	}
    //载入opf文件
	function loadOPF($aid = 0)
	{
	     if($aid){
			 $this->instantPackage($aid);
			 if(!parent::loadOPF()){
				 if(!$this->article_repack($aid, array('makeopf'=>1), 1)) return false;
				 parent::loadOPF();
			 }
		 }else{
		    return parent::loadOPF();
		 }
		 return true;
	}
	//格式化opf文件的内容
	function formatOPF(){
/*		$this->instantPackage($aid);
		if(!$this->loadOPF()){
			if(!$this->article_repack($aid, array('makeopf'=>1))) return false;
		}*/
		$data = array();
		foreach($this->metas as $k=>$v){
			 if($k){
				  $tmp = explode(':',$k);
				  $data[strtolower($tmp[1])] = jieqi_htmlstr($v);
			 }
		}//$data['article']);
		return $this->article_vars($data);
	}
	
	/*
	*检查订阅情况，去除已订阅章节ID，并统计出需要订阅的章节ID和所需的金额
	*
	*/
	public function checkChapterIsBuy($cid){
	    $this->addConfig('article','power');
		$jieqiPower['article'] = $this->getConfig('article','power');
		if($this->checkpower($jieqiPower['article']['freeread'], $this->getUsersStatus (), $this->getUsersGroup (), true)) return true;
	    $auth = $this->getAuth();
		$this->db->init('sale','saleid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('accountid', $auth['uid'], '='));
		$this->db->criteria->add(new Criteria('chapterid', $cid, '='));
		return $this->db->getCount($this->db->criteria);
	}
	/*
	*检查是否自动购买
	*
	*/
	public function checkAutoBuy($aid){
	    $auth = $this->getAuth();
		$this->db->init('autobuy','autoid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('uid', $auth['uid'], '='));
		$this->db->criteria->add(new Criteria('articleid', $aid, '='));
		return $this->db->getCount($this->db->criteria);
	}
	
    //计算单个章节的价格
	public function oneChapterprice($useshuquan, $salepriceAf, $esilver){
		if($useshuquan){//使用书券
			if($esilver >= $salepriceAf){//书券支付
				$shubi = 0;
				$shuquan = $salepriceAf;
			}else{//书券不足
				$shubi = bcsub($salepriceAf, $esilver, 2);
				$shuquan = $esilver;
				$useshuquan = 0;
			}
		}else{//前30章后书海币和书券按比例支付
			$shuquanPr = 0.1;
			$egoldsPr = 0.9;
			$shuquan = bcmul($salepriceAf, $shuquanPr, 2);
			if($esilver >= $shuquan){//用户书券足够
			    $shubi = bcsub($salepriceAf, $shuquan, 2);
			}else{
			    $shubi = $salepriceAf;
				$shuquan = 0;
			}
		}
		return array('shubi'=>$shubi, 'shuquan'=>$shuquan, 'useshuquan'=>$useshuquan);
	}
	/*
	*检查订阅情况，去除已订阅章节ID，并统计出需要订阅的章节ID和所需的金额
	*main方法，购买显示
	*/
	public function batchlist($aid, $chapter)
	{
		$auth = $this->getAuth();
		$buyArr = array();
		$this->db->init('sale','saleid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('articleid', $aid));
		$this->db->criteria->add(new Criteria('accountid', $auth['uid']));
		$this->db->queryObjects($this->db->criteria);

		while($buyinfo = $this->db->getObject()){
			$buyArr[] = $buyinfo->getVar('chapterid','n') ;
		}
		//查询折扣
		$this->addConfig('system','vipgrade');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$vipgrade = jieqi_gethonorarray($auth['vip'], $jieqiVipgrade);//VIP等级数组
		if($vipgrade['setting']['dingyuezhekou']>0){
		    $zhekou = $vipgrade['setting']['dingyuezhekou'];
		}else{
			$zhekou = 1; //可直接用于乘法
		}
		
		if($auth['esilvers']) $esilver = $auth['esilvers'];
		else $esilver = 0;

		$tmpchapters = $this->getChaptersLimit($aid);

		
		$this->db->init('chapter','chapterid','article');
		$this->db->setCriteria(new Criteria('articleid', $aid));
		$this->db->criteria->add(new Criteria('chapterorder', $chapter['chapterorder'], '>='));
		$this->db->criteria->add(new Criteria('isvip', 1));
		$this->db->criteria->add(new Criteria('display', 0));
		$this->db->criteria->add(new Criteria('chaptertype', 0));
		if($buyArr) $this->db->criteria->add(new Criteria('chapterid', '','not in('.implode(',',$buyArr).')'));
		$this->db->criteria->setSort('chapterorder');
		$this->db->criteria->setOrder('ASC');
		$this->db->criteria->setLimit (10);
		$this->db->queryObjects($this->db->criteria);

		$i = 0;
		$arr = array();
		while($v = $this->db->getObject())
		{
			$arr[$i]['chapterid'] = $v->getVar('chapterid');
			$arr[$i]['chaptername'] = $v->getVar('chaptername','n');
			$arr[$i]['size_c'] = ceil($v->getVar ( 'size' )/2);
			$arr[$i]['saleprice'] = $v->getVar('saleprice', 'n');
			$arr[$i]['zhekou'] = bcmul($v->getVar('saleprice', 'n'), $zhekou, 2);
			$useshuquan = in_array($arr[$i]['chapterid'], $tmpchapters) ? 1 : 0;
			$tmpret = $this->oneChapterprice($useshuquan, $arr[$i]['zhekou'], $esilver);
			if($esilver) $esilver = $esilver-$tmpret['shuquan'];
			else $esilver = 0;
			$arr[$i]['shuquan'] = $tmpret['shuquan'];
			$i++;
		}
		//$ret['nobuychapters'] = $arr;
		return $arr;//'saleprice'=>$saleprice,
	}
	public function getChaptersLimit($aid, $limit = 10){
	    if(isset($this->chaptersLimit[$aid])) return $this->chaptersLimit[$aid];
		$cids = array();
		$this->db->init('chapter','chapterid','article');
		$this->db->setCriteria(new Criteria('articleid', $aid));
		$this->db->criteria->add(new Criteria('isvip', 1));
		$this->db->criteria->add(new Criteria('chaptertype', 0));
		$this->db->criteria->setSort ( 'chapterorder' );
		$this->db->criteria->setOrder ( 'ASC' );
		$this->db->criteria->setLimit ($limit);
		$this->db->criteria->setFields('chapterid');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){//print_r($v);
			$cids[] = intval($v->getVar('chapterid'));
		}
		$this->chaptersLimit[$aid] = $cids;
		return $cids;
	}
	/*
	*检查订阅情况，去除已订阅章节ID，并统计出需要订阅的章节ID和所需的金额
	*
	*/
	public function getAllVip($aid, $cids = array())
	{
		$auth = $this->getAuth();
		$saleprice = 0;
		$buyArr = array();
		$j = 0;
		$this->db->init('sale','saleid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('articleid', $aid, '='));
		$this->db->criteria->add(new Criteria('accountid', $auth['uid'], '='));
		$this->db->queryObjects($this->db->criteria);

		while($buyinfo = $this->db->getObject()){
			$buyArr[$j] = $buyinfo->getVar('chapterid','n') ;
			$j++;
		}
		
        $useshuquanchapters = $this->getChaptersLimit($aid);
		$auth = $this->getAuth();
		$useregold=$auth['egolds'];//书海币余额
		$useresilver=$auth['esilvers'];//书券余额
		//查询折扣
		$this->addConfig('system','vipgrade');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$vipgrade = jieqi_gethonorarray($auth['vip'], $jieqiVipgrade);//VIP等级数组
		if($vipgrade['setting']['dingyuezhekou']>0){
		    $zhekou = $vipgrade['setting']['dingyuezhekou'];
		}else{
			$zhekou = 1; //可直接用于乘法
		}
		
		$this->db->init('chapter','chapterid','article');
		$this->db->setCriteria();
		//$this->db->criteria->setTables($this->dbprefix('article_chapter'). ' as c left join '.$this->dbprefix('article_article'). ' as a on c.articleid=a.articleid');
		//$this->db->criteria->setFields('c.*,a.authorid');
		$this->db->criteria->add(new Criteria('isvip', 1, '='));
		$this->db->criteria->add(new Criteria('display', 0, '='));
		$this->db->criteria->add(new Criteria('articleid', $aid, '='));
		if($cids){
		    if(count($cids)==1) $this->db->criteria->add(new Criteria('chapterid', $cids[0], '='));
			else $this->db->criteria->add(new Criteria('chapterid', '', 'in ('.implode(',',$cids).')'));
		}
		$this->db->criteria->add(new Criteria('saleprice', 0, '>'));
		$this->db->queryObjects($this->db->criteria);
		
		$buychapters = $ret = array();
		$i = $useshuquan = $shubi = $shuquan = $salepriceAf = $saleprice = 0;
		$allsilver = $useresilver;
		while($v = $this->db->getObject()){
		     if (!in_array($v->getVar('chapterid'),$buyArr)){
				 $salepriceAf = bcmul($v->getVar('saleprice'), $zhekou, 2);
				 $useshuquan = in_array($v->getVar('chapterid', 'n'), $useshuquanchapters) ? 1 : 0; 
				 $ret = $this->oneChapterprice($useshuquan, $salepriceAf, $allsilver);
				 if($allsilver) $allsilver = bcsub($allsilver,$ret['shuquan'],2);
				 else $allsilver = 0;
				 if($ret['shubi']) $shubi=bcadd($shubi,$ret['shubi'],2);
				 if($ret['shuquan']) $shuquan=bcadd($shuquan,$ret['shuquan'],2);
				 $saleprice+= $v->getVar('saleprice');
				 $buychapters[$i]['shubi'] = $ret['shubi'];
				 $buychapters[$i]['shuquan'] = $ret['shuquan'];
				 $buychapters[$i]['chapterid'] = $v->getVar('chapterid');
				 $buychapters[$i]['chaptername'] = $v->getVar('chaptername','n');
				 $buychapters[$i]['saleprice'] = $v->getVar('saleprice');
				 $buychapters[$i]['salepriceAf'] = $salepriceAf;
				 $buychapters[$i]['pricetype'] = $ret['useshuquan'];
				 $buychapters[$i]['paynote']= '本章原价'.$buychapters[$i]['saleprice'].JIEQI_EGOLD_NAME.';';
				 if($vipgrade['setting']['dingyuezhekou']>0){
					 $buychapters[$i]['paynote'].='您是'.$vipgrade['caption'].'级用户，可享受'.($vipgrade['setting']['dingyuezhekou']*10).'折订阅折扣，折扣后价格'.$salepriceAf.JIEQI_EGOLD_NAME.';';
				 }
				 if($ret['shuquan']) $buychapters[$i]['paynote'].='其中使用书券抵扣支付'.$ret['shuquan'].JIEQI_EGOLD_NAME.'。';
				 $articlename = $v->getVar('articlename','n');
				 $authorid = $v->getVar('authorid','n');
				 $i++;
			 }
		}
/*		$i = 0;
		$arr = array();
		while($v = $this->db->getObject())
		{
			if (!in_array($v->getVar('chapterid'),$buyArr)){
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
		}*/
		if($zhekou) $salepriceAf = bcmul($saleprice, $zhekou, 2);
		else  $salepriceAf = $saleprice;
		return array('chapter'=>$buychapters,'saleprice'=>$saleprice,'salepriceAf'=>$salepriceAf,'num'=>$i,'articlename'=>$articlename,'authorid'=>$authorid,'shubi'=>$shubi,'shuquan'=>$shuquan);
	}
	
	//获取一条章节的数据
	function showChapter($cid, $obj = NULL){
		$i=0;//echo $cid;
		$num=count($this->chapters);
		while($i<$num){
			$tmpvar=$this->getCid($this->chapters[$i]['href']);
			if($tmpvar==$cid){
				//if($this->chapters[$i]['display']) return 2;//待审
				return $this->makeHtml($i+1, true, true, $obj);
			}
			$i++;
		}
		return false;
	}

	function makeHtml($nowid, $show=false, $filter=false, $obj = NULL){
		$data = array();
		$data['article'] = $this->formatOPF();
		if($nowid<=0) return false;
		$chaptercount=count($this->chapters);
		if($nowid>$chaptercount) return false;
		$chapter=jieqi_htmlstr($this->chapters[$nowid-1]['id']);//章节名
		$void=$nowid-2;
		$volume='';
		while($void>=0 && $this->chapters[$void]['content-type']!='volume') $void--;
		if($void>=0) $volume=jieqi_htmlstr($this->chapters[$void]['id']);
		$preid=$nowid-2;
		while($preid>=0 && $this->chapters[$preid]['content-type']=='volume') $preid--;
		$preid++;
		$nextid=$nowid;
		while($nextid<$chaptercount && $this->chapters[$nextid]['content-type']=='volume') $nextid++;
		if($nextid>=$chaptercount) $nextid=0;
		else $nextid++;
		$chapterid=$this->getCid($this->chapters[$nowid-1]['href']);
		global $jieqiConfigs,$jieqiLang;
		if(!isset($jieqiConfigs)){
			$this->addConfig('article','configs');
			$jieqiConfigs['article'] = $this->getConfig('article','configs');
		}
		if(!isset($jieqiLang)){
			$this->addLang('article','article');
			$jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
		}
		 //非本频道书跳转到相应频道
		 if(!in_array(JIEQI_MODULE_NAME,array('3gwap','wap','3g')) && $show){//echo $this->getModule($data['article']['sortid']);exit;
		 	$p_tmp = parse_url(JIEQI_CURRENT_URL);
			 $c_url = $this->geturl($this->getModule($data['article']['sortid']), 'reader', 'aid='.$this->id,'cid='.$chapterid);
			 if(!preg_match("/{$p_tmp['host']}/",$c_url)){
			    header( "HTTP/1.1 301 Moved Permanently" );
			    header( "Location: {$c_url}" );
			    exit;
			 }
		 }
		$data['chapter']['chapterid'] = $chapterid;
		$data['chapter']['articleid'] = $this->id; 
		$data['chapter']['chaptername'] = $chapter;
		$data['chapter']['volume'] = $volume;
		$data['chapter']['title'] = $volume.' '.$chapter;
		$data['chapter']['postdate'] = $this->chapters[$nowid-1]['postdate'];
		$data['chapter']['lastupdate'] = $this->chapters[$nowid-1]['lastupdate'];
		$data['chapter']['size'] = $this->chapters[$nowid-1]['size'];
		$data['chapter']['saleprice'] = $this->chapters[$nowid-1]['saleprice'];
		$data['chapter']['isvip'] = $this->chapters[$nowid-1]['isvip'];
		$data['chapter']['chapterorder'] = $nowid;
		//$data['chapter']['useshuquan'] = $this->useshuquan($this->id,$chapterid);
		//print_r($data);
		//内容赋值
		include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
		$ts=TextConvert::getInstance('TextConvert');

		if($this->chapters[$nowid-1]['intro']){
			$data['chapter']['intro'] = $ts->makeClickable(jieqi_htmlstr($this->chapters[$nowid-1]['intro']));
		}else $data['chapter']['intro'] = '';
		$tmpvar = '';
		$ip = $this->getIp();
		//if($ip == '113.140.9.50'){//没有管理他人文章权限，需要验证码
//			$data['showfree'] = $this->showFree($this->id);
		//}
		if(!$this->chapters[$nowid-1]['display'] && !$data['article']['display'] ){
		     if(defined('RETURN_READER_DATA') && RETURN_READER_DATA){
			    //如果是VIP，并且要返回章节数组
				$auth = $this->getAuth();
				if($data['chapter']['isvip'] && !defined('RETURN_READER_CONTENT') && isset($auth['uid']) && $auth['uid'] || $_SESSION['jieqiFreeAid']==$this->id){
				    if(!$data['chapter']['saleprice'] || $_SESSION['jieqiFreeAid']==$this->id) define('RETURN_READER_CONTENT', true);
					else{
							//判断是否购买
							/* No1:liuxiangbin,2014-12-29,添加书包购买验证 */
							/*$bpLib = $this->load('bookpackage', 'article');
							$users = $this->getAuth();
							if ($usersale = $bpLib->is_bpuser($data['chapter']['articleid'], $users['uid'])) {
                                //$this->dump($usersale);
								// TODO::添加验证并增加点击数
								define('RETURN_READER_CONTENT', true);
								$bpLib->add_bpclick($usersale['id'], $usersale['bpid'], $data['chapter']['articleid'], $data['chapter']['chapterid'], $usersale['accountid'], $usersale['account']);
							} else */if ($this->checkChapterIsBuy($chapterid)) {
							/* No1修改之前:if($this->checkChapterIsBuy($chapterid)){ */
							/* No1:end */
								define('RETURN_READER_CONTENT', true);
//							}elseif(JIEQI_NOW_TIME>=1419955200&&JIEQI_NOW_TIME<1420300800){ //当前用户已将该文章加入限免
//								if(!in_array(JIEQI_MODULE_NAME,array('wap','3gwap'))){ //不是简版、触屏版，只有主站
//									if($this->checkIsFree($this->id)) define('RETURN_READER_CONTENT', true);
//								}
							}else{//没有购买
//								if($ip == '113.140.9.50'){//没有管理他人文章权限，需要验证码
//									if(JIEQI_NOW_TIME>=1419955200&&JIEQI_NOW_TIME<1420300800){ //当前用户已将该文章加入限免
//										if(!in_array(JIEQI_MODULE_NAME,array('wap','3gwap'))){ //不是简版、触屏版，只有主站
//											if($this->checkIsFree($this->id)) define('RETURN_READER_CONTENT', true);
//										}
//									}
//								}
								if(!defined('RETURN_READER_CONTENT') || !RETURN_READER_CONTENT){
									$data['autobuy'] = $this->checkAutoBuy($this->id);
									$data['vip'] = $this->getAllVip($this->id);	
									//查询折扣
									$this->addConfig('system','vipgrade');
									$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
									$data['vipgrade'] = jieqi_gethonorarray($auth['vip'], $jieqiVipgrade);//VIP等级数组
	
									if($data['vipgrade']['setting']['dingyuezhekou']>0){
										$zhekou = $data['vipgrade']['setting']['dingyuezhekou'];
									}else{
										$zhekou = 1; //可直接用于乘法
									}
									$salepriceAf = $data['chapter']['saleprice'] * $zhekou;
									if($auth['esilvers']) $esilver = $auth['esilvers'];
									else $esilver = 0;
									$tmpchapters = $this->getChaptersLimit($this->id);
									$useshuquan = in_array($data['chapter']['chapterid'], $tmpchapters) ? 1 : 0;//当前章节是否使用书券
									$ret = $this->oneChapterprice($useshuquan, $salepriceAf, $esilver);
	
									$data['nobuychapters'] = $this->batchlist($this->id, $data['chapter']);
									$data['chapter']['useshuquan'] = $ret['useshuquan'];
									$data['chapter']['shubi'] = $ret['shubi'];
									$data['chapter']['shuquan'] = $ret['shuquan'];
								}
							}
					}
				}
			}
			if(!$data['chapter']['isvip'] || (defined('RETURN_READER_CONTENT') && RETURN_READER_CONTENT)){//不是VIP才生成内容

				$tmpvar=@$this->getContent($chapterid);
				if($tmpvar){
					//文字过滤
					if($filter && !empty($jieqiConfigs['article']['hidearticlewords'])){
						$articlewordssplit = (strlen($jieqiConfigs['article']['articlewordssplit'])==0) ? ' ' : $jieqiConfigs['article']['articlewordssplit'];
						$filterary=explode($articlewordssplit, $jieqiConfigs['article']['hidearticlewords']);
						$tmpvar=str_replace($filterary, '', $tmpvar);
					}
					//网址改成可以点击的
					if(defined('NORETURN_FORMAT_DATA') && NORETURN_FORMAT_DATA) $tmpvar=$ts->makeClickable($tmpvar);
					else $tmpvar=$ts->makeClickable(jieqi_htmlstr($tmpvar));
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
				}/*else{//无内容
					$tmpvar=$jieqiLang['article']['userchap_not_exists'];
				}*/
				$attachurl = jieqi_uploadurl($jieqiConfigs['article']['attachdir'], $jieqiConfigs['article']['attachurl'], 'article').jieqi_getsubdir($this->id).'/'.$this->id.'/'.$chapterid;
				if(!$jieqiConfigs['article']['packdbattach']){
					//检查附件(检查文件是否存在)
					$attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article').jieqi_getsubdir($this->id).'/'.$this->id.'/'.$chapterid;
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
				if(!$tmpvar){//无内容
					$tmpvar=$jieqiLang['article']['userchap_not_exists'];
				}
			}
		}else $tmpvar=$jieqiLang['article']['chapter_not_insale'];
		
		$data['chapter']['content'] = $tmpvar;
		$url = parse_url($this->geturl('article', 'index', 'aid='.$this->id));
// 		$data['chapter']['index_page'] = ($this->geturl('article', 'index', 'aid='.$this->id));//basename
		$data['chapter']['index_page'] = $url['path'];//basename
		$data['chapter']['addBookCase'] = $this->getUrl('article','huodong','SYS=method=addBookCase&aid='.$this->id.'&cid='.$chapterid);
		if($preid>0){
			$tmpvar=$this->getCid($this->chapters[$preid-1]['href']);
			$data['chapter']['preview_page'] = basename($this->geturl('article', 'reader', 'aid='.$this->id,'cid='.$tmpvar));
			$data['chapter']['pre_chapterid'] = $tmpvar;
			$data['chapter']['first_page'] = 0;
			//2014-8-11 add 程元 注：添加上一章节名
			$data['chapter']['pre_chaptername'] = $this->chapters[$preid-1]['id'];
			$data['chapter']['pre_isvip'] = $this->chapters[$preid-1]['isvip'];
		}else{
			$data['chapter']['preview_page'] = $data['chapter']['index_page'];
			$data['chapter']['first_page'] = 1;
		}
		if($nextid>0){//print_r($this->chapters[$nextid-1]['href']);
			$tmpvar=$this->getCid($this->chapters[$nextid-1]['href']);
			$data['chapter']['next_page'] = basename($this->geturl('article', 'reader', 'aid='.$this->id,'cid='.$tmpvar));
			$data['chapter']['next_chapterid'] = $tmpvar;
			$data['chapter']['last_page'] = 0;
			//2014-8-11 add 程元 注：添加下一章节名
			$data['chapter']['next_chaptername'] = $this->chapters[$nextid-1]['id'];
			$data['chapter']['next_isvip'] = $this->chapters[$nextid-1]['isvip'];
		}else{
			$data['chapter']['next_page'] = $data['chapter']['index_page'];
			$data['chapter']['last_page'] = 1;
		}
		//2015-5-6 add 程元 验证码状态
		$data['show_checkcode'] = $this->checkcodeState();
		if(defined('RETURN_READER_DATA') && RETURN_READER_DATA) return $data;
		if(!is_object($obj)){
		     static $obj;
			 include_once $GLOBALS['jieqiModules']['article']['path'].'/controller/readerController.php';
			 $obj = new readerController();
		}

		if(!$show){//如果生成HTML
		     $old_display = Application::$_DISPLAY;
			 $old_runpath = Application::$_HLM_RUN_PATH;
			 $old_viewpath = Application::$_HLM_VIEW_PATH;
			 Application::$_DISPLAY = false;
			 Application::$_HLM_RUN_PATH = $GLOBALS['jieqiModules']['article']['path'];
			 Application::$_HLM_VIEW_PATH = Application::$_HLM_RUN_PATH.'/templates';
		}
		if($data['chapter']['isvip']>0){//如果是VIP章节，则显示另一套模板
		    $tpl_dir = Application::$_HLM_VIEW_PATH.'/'.$obj->template_name;//echo $tpl_dir.'_vip'.HLM_TPL_SUFFIX;
			if(is_file($tpl_dir.'_vip'.HLM_TPL_SUFFIX)){
			     $old_tpl = $obj->template_name;
			     $obj->template_name = $obj->template_name.'_vip';
			}
			$data['chapter']['readvip'] = $this->geturl($this->getModule($data['article']['sortid']), 'reader', 'method=readvip', 'aid='.$this->id,'cid='.$chapterid,'SYS=method=readvip');
		}
		if($show){
		    return $obj->display($data);
		}else{

			$P = parse_url($this->geturl('article', 'reader', 'aid='.$this->id,'cid='.$chapterid));
		    $this->swritefile(HLM_ROOT_PATH.$P['path'], $obj->display($data));

			Application::$_DISPLAY = $old_display;
			Application::$_HLM_RUN_PATH = $old_runpath;
			Application::$_HLM_VIEW_PATH = $old_viewpath;
			$obj->template_name = $old_tpl;
			return true;

		}
	}
	//是否vip前30章
	/*function useshuquan($aid, $cid){
		$sids = array();
		$this->db->init('chapter','chapterid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('articleid', $aid));
		$this->db->criteria->add(new Criteria('isvip', 1));
		$this->db->criteria->add(new Criteria('chaptertype', 0));
		$this->db->criteria->setSort ( 'chapterorder' );
		$this->db->criteria->setOrder ( 'ASC' );
		$this->db->criteria->setLimit (30);
		$this->db->criteria->setFields('chapterid');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){//print_r($v);
			$sids[] = intval($v->getVar('chapterid'));
		}//print_r($sids);
		if(in_array($cid, $sids)) return 1;
		else return 0;
	}*/

	//显示html目录
	function showIndex($obj = NULL){
		$this->makeIndex(true, $obj);
	}

	//生成html目录
	function makeIndex($show=false, $obj = NULL){
		 global $jieqiConfigs,$jieqiLang;
		 if(!isset($jieqiConfigs)){
			 $this->addConfig('article','configs');
			 $jieqiConfigs['article'] = $this->getConfig('article','configs');
		 }
		 if(!isset($jieqiLang)){
			 $this->addLang('article','article');
			 $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
		 }
	     $data = array();
		 $data['article'] = $this->formatOPF();
		 //非本频道书跳转到相应频道
		 if(!in_array(JIEQI_MODULE_NAME,array('3gwap','wap','3g')) && $show){
			 $p_tmp = parse_url(JIEQI_CURRENT_URL);
			 if(!preg_match("/{$p_tmp['host']}/",$data['article']['url_articleindex'])){
			    header( "HTTP/1.1 301 Moved Permanently" );
			    header( "Location: {$data['article']['url_articleindex']}" );
			    exit;
			 }
		 }
		 if(isset($jieqiConfigs['article']['indexcols']) && $jieqiConfigs['article']['indexcols']>0) $cols=intval($jieqiConfigs['article']['indexcols']);
		 else $cols=4;
		 $indexrows=array();
		 $i=0;
		 $idx=0;
		 $this->preid=0; //前一章
		 $this->nextid=0; //下一章
		 $preview_page='';
		 $next_page='';
		 $lastvolume='';
		 $lastchapter='';
		 $lastchapterid = $start = $isvip = $lastvolumeorder = 0;
		foreach($this->chapters as $k => $chapter){//处理章节开始
		  if(!$chapter['display'] && !$data['article']['display']){
				$start++;
				//分卷
				if($chapter['content-type']=='volume' && $start>=1){
						if($i>0) $idx++;
						$i=0;
						$indexrows[$idx]['ctype']='volume';
						$indexrows[$idx]['vurl']='';
						$indexrows[$idx]['vname']=@jieqi_htmlstr($chapter['id']);
						$indexrows[$idx]['vid']=$this->getCid($chapter['href']);
						$indexrows[$idx]['intro'] = @jieqi_htmlstr($chapter['intro']);
						$lastvolume=$chapter['id'];
						$lastvolumeorder = $idx;
						$idx++;
				}else{
						if($start==1){
							 $i=0;
							 $indexrows[$idx]['ctype']='volume';
							 $indexrows[$idx]['vurl']='';
							 $indexrows[$idx]['vname']='正文';
							 $indexrows[$idx]['vid']=0;
							 $indexrows[$idx]['intro'] = '';
							 $lastvolume = $lastvolumeorder = 0;
							 $idx++;
						}
						$k=$k+1;
						if($k < $this->nowid) $this->preid=$k;
						elseif($k > $this->nowid && $this->nextid==0) $this->nextid=$k;
						$tmpvar=$this->getCid($chapter['href']);
						$i++;
						$indexrows[$idx]['ctype']='chapter';
						$indexrows[$idx]['cname'.$i]=jieqi_htmlstr($chapter['id']);
						$indexrows[$idx]['cid'.$i]=$tmpvar;
						if($chapter['isvip'] && !$isvip){
							$isvip = 1;
							$indexrows[$lastvolumeorder]['isvip'] = 1;
						}
						$indexrows[$idx]['isvip'.$i]=$chapter['isvip'];

						if(true){
							$indexrows[$idx]['time'.$i] = $chapter['postdate'];
							$indexrows[$idx]['lastupdate'.$i] = $chapter['lastupdate'];
							$indexrows[$idx]['size'.$i] = $chapter['size'];
							$indexrows[$idx]['size_c'.$i] = ceil($indexrows[$idx]['size'.$i]/2);
						}

						$lastchapter=$chapter['id'];
						$lastchapterid=$tmpvar;
						$indexrows[$idx]['curl'.$i]=basename($this->geturl('article', 'reader', 'aid='.$this->id,'cid='.$tmpvar));
						if(empty($next_page)) $next_page=$tmpvar;
						$preview_page=$tmpvar;
						if($i==$cols){
							$idx++;
							$i=0;
						}

				  }
			}
		}//处理章节结束
		$data['article']['preview_page'] = basename($this->geturl('article', 'reader', 'aid='.$this->id,'cid='.$preview_page));
		$data['article']['next_page'] = basename($this->geturl('article', 'reader', 'aid='.$this->id,'cid='.$next_page));
		$data['indexrows'] = $indexrows;//print_r($indexrows);
		
        //static $obj;
		if(!is_object($obj)){
			 include_once $GLOBALS['jieqiModules']['article']['path'].'/controller/indexController.php';
			 $obj = new indexController();
		}
		if($show) return $obj->display($data);
		else{
		    $old_display = Application::$_DISPLAY;
			$old_runpath = Application::$_HLM_RUN_PATH;
			$old_viewpath = Application::$_HLM_VIEW_PATH;

			Application::$_DISPLAY = false;
			Application::$_HLM_RUN_PATH = $GLOBALS['jieqiModules']['article']['path'];
			Application::$_HLM_VIEW_PATH = Application::$_HLM_RUN_PATH.'/templates';

			$P = parse_url($this->geturl('article', 'index', 'aid='.$this->id));
		    $this->swritefile(HLM_ROOT_PATH.$P['path'], $obj->display($data));

			Application::$_DISPLAY = $old_display;
			Application::$_HLM_RUN_PATH = $old_runpath;
			Application::$_HLM_VIEW_PATH = $old_viewpath;
			return true;

		}
	}
    function getModule($tid, $type = 'sortid'){
	     if($type == 'sortid'){
			 if($tid<=100) return 'article';
			 elseif($tid<=200) return 'mm';
			 elseif($tid<=300) return 'wenxue';
		 }else{
			 if ($tid){
			    $source =  $this->getSources();
				if (!isset($source['channel'][$tid]['module'])){
					$module = $source['channel'][$tid]['module'];
				}else{
					$module = JIEQI_MODULE_NAME;
				}
			 }else{
				$module = 'article';
			 }
		 }
	}
	/**
	 * 传入小说实例对象，返回适合模板赋值的小说信息数组
	 *
	 * @param      object      $article 实例对象OR数组
	 * @access     public
	 * @return     array
	 */
	function article_vars($article){
		$source =  $this->getSources();
	    global $jieqiSort,$jieqiLang,$jieqiOption;
		/*if(!isset($jieqiSort)){
			$this->addConfig('article','sort');
			$jieqiSort['article'] = $this->getConfig('article','sort');
		}*/
		if(!isset($jieqiLang)){
			$this->addLang('article','list');
			$jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
		}
		if(!isset($jieqiOption)){
		     $option = $this->getSources();
			 if(!isset($jieqiSort)) $jieqiSort['article'] = $option['sortrows'];
			 $jieqiOption['article']['permission'] = $option['permission'];
			 $jieqiOption['article']['firstflag'] = $option['firstflag'];
			 $jieqiOption['article']['fullflag'] = $option['fullflag'];
		}
		$ret = array();
		if(is_object($article)){
			foreach($article->getVars() as $k=>$v){
				 $ret[$k] = $article->getVar($k);
			}
			 $M = $this->getModule($ret['sortid']);
			 $ret['url_articleinfo']=$this->geturl($M, 'articleinfo', 'aid='.$ret['articleid']);
			 $ret['url_module_articleinfo']=$this->geturl(JIEQI_MODULE_NAME, 'articleinfo', 'aid='.$ret['articleid']);
			 $ret['url_class']=$this->geturl($M, 'channel', 'sortid='.$ret['sortid'],'class='.$jieqiSort['article'][$ret['sortid']]['class']);
			 $ret['url_module_class']=$this->geturl(JIEQI_MODULE_NAME, 'channel', 'sortid='.$ret['sortid'],'class='.$jieqiSort['article'][$ret['sortid']]['class']);
			 if($article->getVar('lastchapter')==''){
				 $ret['lastchapterid']=0;  //章节序号
				 $ret['lastchapter']='';  //章节名称
				 $ret['url_articleindex'] = '#';

				 $ret['url_lastchapter']='#';  //章节地址
			 }else{
				 $ret['lastchapter']=$article->getVar('lastchapter','n');
				 //$ret['lastchapter']=$article->getVar('lastchapter');
				 $ret['url_articleindex']=$this->geturl($M, 'index', 'aid='.$ret['articleid']);
				 $ret['url_lastchapter']=$this->geturl($M, 'reader', 'aid='.$ret['articleid'],'cid='.$ret['lastchapterid']);
			 }
			 if(isset($jieqiSort['article'][$ret['sortid']]['caption'])){
				 $ret['sort']=$jieqiSort['article'][$ret['sortid']]['caption'];  //类别
				 $ret['shortcaption']=$jieqiSort['article'][$ret['sortid']]['shortcaption'];
				 $ret['class']=$jieqiSort['article'][$ret['sortid']]['class'];  //类别
			 }else{
				 $ret['sort']='';
				 $ret['shortcaption']='';
				 $ret['class']='';
			 }
			 $ret['typeid']=$article->getVar('typeid');  //字类别序号
			 if($ret['typeid'] > 0 && isset($jieqiSort['article'][$ret['sortid']]['types'][$ret['typeid']])) $ret['type']=$jieqiSort['article'][$ret['sortid']]['types'][$ret['typeid']];  //类别
			 else $ret['type']='';
			 $ret['size_k']=ceil($article->getVar('size')/1024);
			 $ret['size_c']=ceil($article->getVar('size')/2);
			 $ret['size_w']=number_format($ret['size_c'] / 10000,1);//万字
			 $ret['url_image'] = jieqi_geturl('article', 'cover', $ret['articleid'], 's', $ret['imgflag']);
			 $ret['url_image_l'] = jieqi_geturl('article', 'cover', $ret['articleid'], 'l', $ret['imgflag']);
			 @$ret['fullflag_tag']=$jieqiOption['article']['fullflag']['items'][$ret['fullflag']];
			 @$ret['firstflag_tag']=$jieqiOption['article']['firstflag']['items'][$ret['firstflag']];
			 @$ret['permission_tag']=$jieqiOption['article']['permission']['items'][$ret['permission']];
			 $ret['intro']=htmlspecialchars_array($article->getVar('intro', 'n'));//jieqi_htmlstr
			 $ret['notice']=htmlspecialchars_array($article->getVar('notice', 'n'));
			 $ret['channel'] = $source['channel'][$article->getVar('siteid')]['name'];
			return $ret;
		}else{
		     $M = $this->getModule($article['sortid']);
		     $article['url_articleinfo']=$this->geturl($M, 'articleinfo', 'aid='.$article['articleid']);
			 $article['url_module_articleinfo']=$this->geturl(JIEQI_MODULE_NAME, 'articleinfo', 'aid='.$article['articleid']);
			 $article['url_class']=$this->geturl($M, 'channel', 'sortid='.$article['sortid'],'class='.$jieqiSort['article'][$article['sortid']]['class']);
			 $article['url_module_class']=$this->geturl(JIEQI_MODULE_NAME, 'channel', 'sortid='.$article['sortid'],'class='.$jieqiSort['article'][$article['sortid']]['class']);
			 if($article['lastchapter']==''){
				  $article['lastchapterid']=0;  //章节序号
				  $article['lastchapter']='';  //章节名称
				  $article['url_articleindex'] = '#';

				  $article['url_lastchapter']='#';  //章节地址
			}else{
				  //$article['lastchapterid']=$article['lastchapterid'];
				  //$article['lastchapter']=$article['lastchapter'];
				  $article['url_articleindex']=$this->geturl($M, 'index', 'aid='.$article['articleid']);
				  $article['url_lastchapter']=$this->geturl($M, 'reader', 'aid='.$article['articleid'],'cid='.$article['lastchapterid']);
			}
				  if(isset($jieqiSort['article'][$article['sortid']]['caption'])){
				      $article['sort']=$jieqiSort['article'][$article['sortid']]['caption'];  //类别
					  $article['shortcaption']=$jieqiSort['article'][$article['sortid']]['shortcaption'];  //类别
				  }else{
				      $article['sort']='';
					  $article['shortcaption']='';
				  }
				  $article['typeid']=$article['typeid'];  //字类别序号
				  if($article['typeid'] > 0 && isset($jieqiSort['article'][$article['sortid']]['types'][$article['typeid']])) $article['type']=$jieqiSort['article'][$article['sortid']]['types'][$article['typeid']];  //类别
				  else $article['type']='';
				  $article['size_k']=ceil($article['size']/1024);
				  $article['size_c']=ceil($article['size']/2);
				  $article['size_w']=number_format($article['size_c'] / 10000,1);//万字
				  $article['url_image'] = jieqi_geturl('article', 'cover', $article['articleid'], 's', $article['imgflag']);
				  $article['url_image_l'] = jieqi_geturl('article', 'cover', $article['articleid'], 'l', $article['imgflag']);
				  @$article['fullflag_tag']=$jieqiOption['article']['fullflag']['items'][$article['fullflag']];
				  @$article['firstflag_tag']=$jieqiOption['article']['firstflag']['items'][$article['firstflag']];
				  @$article['permission_tag']=$jieqiOption['article']['permission']['items'][$article['permission']];
				  $article['intro']=htmlspecialchars_array($article['intro']);//jieqi_htmlstr
				  $article['notice']= empty($article['notice'])? '':htmlspecialchars_array($article['notice']);
				  $article['channel'] = $source['channel'][$article['siteid']]['name'];
			return $article;
		}
	    //print_r($ret);exit;

	}
	function getUser($uid){
		$this->db->init('users','uid','system');
		$this->db->setCriteria(new Criteria( 'uid', $uid, '=' ));
		$userObj = $this->db->get($this->db->criteria);
	    return $userObj;
	}
	//添加文章统计
	function addArticleStat($articleid,$authorid, $mid, $stat = 1, $ext = array()){
	     $statArray = $this->getStatArray();
		 if(!array_key_exists($mid, $statArray) || !$stat || !$articleid) return false;
		 $_USER = $this->getAuth();
		 if($statArray[$mid]['writelog']){//if($mid!='visit'){
			 //记录日志
			 $this->db->init( 'statlog', 'statlogid', 'article' );
			 $data = array(
					   'mid'=>$mid,
					   'addtime'=>JIEQI_NOW_TIME,
					   'uid'=>$_USER['uid'],
					   'username'=>$_USER['username'],
					   'articleid'=>$articleid,
					   'authorid'=>$authorid,
					   'stat'=>$stat
			 );
			 if($mid == 'sale'){
			     $data['chapterid'] = $ext['chapterid'];
				 $data['chaptername'] = $ext['chaptername'];
			 }
			 if(!$this->db->add($data)){
				 return false;
			 } else{
			 	 $auth = $this->getAuth();
				 $this->setSetting($auth['uid'],'jieqiNewTongZhi',false);
			 }
		 }
		 if($ext['deduct']){//针对使用书券的情况，不做统计
		      $stat = $stat - $ext['deduct'];
			  if(!$stat) return true;
		 }
		 //记录文章类型统计
		 $this->db->init( 'stat', 'statid', 'article' );
		 $this->db->setCriteria(new Criteria( 'articleid', $articleid, '=' ));
		 $this->db->criteria->add(new Criteria( 'mid', $mid, '=' ));
		 if($statObj = $this->db->get($this->db->criteria)){//如果存在则修改

		      $lastdate=date('Y-m-d', $statObj->getVar('lasttime', 'n'));
			  $nowdate=date('Y-m-d',  JIEQI_NOW_TIME);
			  $nowweek=date('w');

			  $total = bcadd($statObj->getVar('total', 'n'),$stat,2);
			  $totalnum = $statObj->getVar('totalnum', 'n')+1;
			  if($nowdate==$lastdate){
			       $month = bcadd($statObj->getVar('month', 'n'),$stat,2);
				   $week = bcadd($statObj->getVar('week', 'n'),$stat,2);
				   $day = bcadd($statObj->getVar('day', 'n'),$stat,2);
				   $monthnum = $statObj->getVar('monthnum', 'n')+1;
				   $weeknum = $statObj->getVar('weeknum', 'n')+1;
				   $daynum = $statObj->getVar('daynum', 'n')+1;
			  }else{
			       //if($nowweek==1){//如果是新的一周
				   if($statObj->getVar('lasttime', 'n') < $this->getTime('week')){//如果是新的一周
						$week = $stat;
						$weeknum = 1;
				   }else{
						$week= bcadd($statObj->getVar('week', 'n'),$stat,2);
						$weeknum = $statObj->getVar('weeknum', 'n')+1;
				   }
				   if(substr($nowdate,0,7)==substr($lastdate,0,7)){
						$month=bcadd($statObj->getVar('month', 'n'),$stat,2);
						$monthnum = $statObj->getVar('monthnum', 'n')+1;
				   }else{
						$month=$stat;
						$monthnum = 1;
				   }
				   $day = $stat;
				   $daynum = 1;
			  }

			  $statObj->setVar('total', $total);
			  $statObj->setVar('month', $month);
			  $statObj->setVar('week', $week);
			  $statObj->setVar('day', $day);
			  $statObj->setVar('totalnum', $totalnum);
			  $statObj->setVar('monthnum', $monthnum);
			  $statObj->setVar('weeknum', $weeknum);
			  $statObj->setVar('daynum', $daynum);
			  $statObj->setVar('lasttime', JIEQI_NOW_TIME);
			  if(!$this->db->edit($statObj->getVar('statid', 'n'),$statObj)) return false;
		 }else{//新增
			  if(!$this->db->add(
				   array(
				       'articleid'=>$articleid,
					   'mid'=>$mid,
					   'total'=>$stat,
					   'month'=>$stat,
					   'week'=>$stat,
					   'day'=>$stat,
					   'totalnum'=>1,
					   'monthnum'=>1,
					   'weeknum'=>1,
					   'daynum'=>1,
					   'lasttime'=>JIEQI_NOW_TIME
				   )
			 )) return false;
		 }

		 //记录文章扩展属性表总统计
		 $this->db->init( 'statamout', 'statamoutid', 'article' );
		 $this->db->setCriteria(new Criteria( 'articleid', $articleid, '=' ));
		 if($statamoutObj = $this->db->get($this->db->criteria)){//如果存在则修改
		       foreach($statArray as $field=>$bak){
			        if(array_key_exists($field, $statArray) && $field ==$mid){
					     //$this->printfail($mid.'='.$field.'='.$statamoutObj->getVar($mid, 'n').'='.$stat);
					     //$statamoutObj->setVar($field, ($statamoutObj->getVar($mid, 'n')+$stat));
						 $statamoutObj->setVar($field, bcadd($statamoutObj->getVar($mid, 'n'),$stat,2));
					}
			   }
			   if(!$this->db->edit($statamoutObj->getVar('statamoutid', 'n'),$statamoutObj)) return false;
		 }else{//新增
		      $data = array();
			  $data['articleid'] = $articleid;
		      foreach($statArray as $field=>$bak){
			       if(array_key_exists($field, $statArray) && $field == $mid){
				        $data[$field] = $stat;
				   }
			  }
			  if(!$this->db->add($data)) return false;
		 }
		 return true;
	}

	//获取统计类型数组
	function getStatArray(){
	     return array(
			  'visit'=>array('name'=>'点击','writelog'=>false,'format'=>'访问了作品《<a href="{$url_articleinfo}" target="_blank">{$articlename}</a>》。'),
			  'vote'=>array('name'=>'推荐','writelog'=>true,'format'=>'给作品《<a href="{$url_articleinfo}" target="_blank">{$articlename}</a>》投了{$stat}张推荐票。'),
		      'goodnum'=>array('name'=>'收藏','writelog'=>true,'format'=>'把作品《<a href="{$url_articleinfo}" target="_blank">{$articlename}</a>》放入了书架。'),
		      'vipvote'=>array('name'=>'月票','writelog'=>true,'format'=>'给作品《<a href="{$url_articleinfo}" target="_blank">{$articlename}</a>》投了{$stat}张月票。'),
			  'sale'=>array('name'=>'销售','writelog'=>true,'format'=>'订阅了作品《<a href="{$url_articleinfo}" target="_blank">{$articlename}</a>》：<a href="{$url_chapterurl}" target="_blank">{$chaptername}</a>。'),
			  'reward'=>array('name'=>'打赏','writelog'=>true,'format'=>'打赏了作品《<a href="{$url_articleinfo}" target="_blank">{$articlename}</a>》{$stat}'.JIEQI_EGOLD_NAME.'。'),
	     	  'shuquansale'=>array('name'=>'书券销售','writelog'=>false,'format'=>'')	     		
		 );
	}
	function getStatStr(){
	     $type = array();
	     foreach($this->getStatArray() as $k=>$v){
		     $type[]=$k;
		 }
		 return implode('|',$type);
	}

	function setSetting($uid,$type,$cancel=false){
	     $users_handler =  $this->getUserObject();//查询用户是否存在
		 $jieqiUsers = $users_handler->get($uid);
		 $userset=unserialize($jieqiUsers->getVar('setting','n'));
		 if ($cancel)
		 {
			$userset[$type] = 0;
		 }else{
			$userset[$type] = intval($userset[$type]) +1;
		 }

		 $jieqiUsers->setVar('setting', serialize($userset));
		 $this->db->init('users','uid','system');
		 $this->db->edit($jieqiUsers->getVar('uid','n'),$jieqiUsers);
	}
	function getFormatStat($ret){
	     $statArray = $this->getStatArray();
		 if(!array_key_exists($ret['mid'], $statArray)) $content_tag = '';
		 else{
		      $content_tag = $statArray[$ret['mid']]['format'];
			  extract($ret);
			  $M = $this->getModule($sortid);
			  $url_articleinfo = $this->geturl($M,'articleinfo','aid='.$articleid);
			  if($chapterid) $url_chapterurl =$this->geturl($M, 'reader', 'aid='.$articleid,'cid='.$chapterid);
			  else  $url_chapterurl = $this->geturl($M,'index','aid='.$articleid);
			  if($content_tag) eval('$content_tag = "'.addslashes_array($content_tag).'";');
			  $ret['content_tag'] = $content_tag;
		 }
	     return $ret;
	}
	/**
	 * 传入日志对象，返回适合模板赋值的小说日志信息数组
	 *
	 * @param      object      $article 实例对象OR数组
	 * @access     public
	 * @return     array
	 */
	function article_statlog_vars($statlog){
	    $ret = array();
		if(is_object($statlog)){
		    foreach($statlog->getVars() as $k=>$v){
			     $ret[$k] = $statlog->getVar($k);
				 //@$ret['content_tag'] =
			}
		}else{
		    $ret = $statlog;
		}
		return $this->getFormatStat($ret);
	}

	function getChapter($cid)
	{
		//获得章节信息
		$this->db->init('chapter','chapterid','article');
		$this->db->setCriteria(new Criteria('chapterid',$cid, '='));
		$v = $this->db->get($this->db->criteria);
		return $v;
	}

	/**
	 * 文章点击统计，
	 * @param unknown $article	文章数组对象
	 */
	function statisticsVisit($article){
		//1：验证是否是有效的点击
		//2：缓存累计点击量
		//3：累计饱和后与数据库同步点击数据
		//4：同步数据时没有则新增，有则更新
		if(is_array($article) && !empty($article['articleid'])){
// 			$dat = array();
			//载入统计处理函数
			include_once(JIEQI_ROOT_PATH.'/include/funstat.php');
			if(jieqi_visit_valid($article['articleid'], 'article_articleviews')){
				//每次点击加几个点击数
				$addnum=1;
				if(isset($this->jieqiConfigs['article']['visitstatnum']) && is_numeric($this->jieqiConfigs['article']['visitstatnum']) && intval($this->jieqiConfigs['article']['visitstatnum'])>=0){
					$addnum=intval($this->jieqiConfigs['article']['visitstatnum']);
				}
				//jieqi_article_stat object
				$this->db->init('stat','statid','article');
				$this->db->setCriteria(new Criteria('mid','visit'));
				$this->db->criteria->add ( new Criteria ( 'articleid', $article['articleid'], '=' ));
				$this->db->queryObjects ();
				// 这个是对象，不是数组
				$statObj = $this->db->getObject();
				$lasttime = JIEQI_NOW_TIME;
				$statid;
				if(is_object($statObj)){
					$statid = $statObj->getVar('statid', 'n');
					$lasttime = $statObj->getVar('lasttime', 'n');
				}else{
					//初始化点击数据，默认量都为0
					$statid = $this->db->add(array('articleid'=>$article['articleid'],'mid'=>'visit'));
					if(!$statid){
						return;
// 						$this->db->setCriteria(new Criteria('statid',$statid));
// 						$statObj = $this->db->get($this->db->criteria);
// 						if(!is_object($statObj))return;
					}
				}
				unset($statObj);
				//ids包含多个文章的缓存点击量统计信息，10%的触发率更新返回ids，否则继续缓存
				//ids更新缓存内的所有文章，而并非当前触发事件的文章
				if($ids = jieqi_visit_ids($statid, 'article_articleviews', $lasttime)){

// 					addArticleStat($articleid, $mid, $stat = 1, $ext = array());


					$nowdate=date('Y-m-d',  JIEQI_NOW_TIME);
					$nowweek=date('w', JIEQI_NOW_TIME);
					//if($nowweek==0) $nowweek=7;
					foreach($ids as $k=>$v){
						//$k is statid
// 						$stat = $this->db->get($k);
// 						$lastdate=date('Y-m-d', $v['lastvisit']);
// 						$lastweek=date('w', $v['lastvisit']);
						$lastvisit = $v['lastvisit'];
						//if($lastweek==0) $lastweek=7;
						$visitnum = $v['visitnum'];//点击次数
						$v['visitnum'] = intval($v['visitnum'] * $addnum);//计算点击总量
						$this->addArticleStat($article['articleid'],$article['authorid'],'visit',$v['visitnum']);
						/*
// 						$allstr='allvisit=allvisit+'.$v['visitnum'];
						$stat['total'] = $stat['total']+$v['visitnum'];//总量累加
						$stat['totalnum'] = $stat['totalnum']+$visitnum;//总次数累加
						if($nowdate==$lastdate || JIEQI_NOW_TIME < $v['lastvisit']){
							//当天的情况
// 							$daystr='dayvisit=dayvisit+'.$v['visitnum'];
// 							$weekstr='weekvisit=weekvisit+'.$v['visitnum'];
// 							$monthstr='monthvisit=monthvisit+'.$v['visitnum'];
							//量
							$stat['day'] = $stat['day']+$v['visitnum'];
							$stat['week'] = $stat['week']+$v['visitnum'];
							$stat['month'] = $stat['month']+$v['visitnum'];
							//次数
							$stat['daynum'] = $stat['daynum']+$visitnum;
							$stat['weeknum'] =$stat['weeknum']+$visitnum;
							$stat['monthnum'] = $stat['monthnum']+$visitnum;
						}else{
// 							$daystr='dayvisit='.$v['visitnum'];
							$stat['day'] = $v['visitnum'];
							$stat['daynum'] = $visitnum;
							if($nowweek == 1 || JIEQI_NOW_TIME - $v['lastvisit'] > 604800){//周一，一周内
// 								$weekstr='weekvisit='.$v['visitnum'];
								$stat['week'] = $v['visitnum'];
								$stat['weeknum'] = $visitnum;
							}else{
// 								$weekstr='weekvisit=weekvisit+'.$v['visitnum'];
								$stat['week'] = $stat['week']+$v['visitnum'];
								$stat['weeknum'] = $stat['weeknum']+$visitnum;
							}
							//同一个月
							if(substr($nowdate,0,7) == substr($lastdate,0,7)){
// 								$monthstr='monthvisit=monthvisit+'.$v['visitnum'];
								$stat['month'] = $stat['month']+$v['visitnum'];
								$stat['monthnum'] = $stat['monthnum']+$visitnum;
							}else{
// 								$monthstr='monthvisit='.$v['visitnum'];
								$stat['month'] = $v['visitnum'];
								$stat['monthnum'] = $visitnum;
							}
						}
// 						$this->db->updatetable ( 'article_stat', array (
// 								'chapterorder' => '++'
// 						), 'statid = ' . $statid );
						$stat['lasttime'] = JIEQI_NOW_TIME;
						$this->db->edit($k,$stat);
// 						$this->db->updatetable ( 'article_stat',$stat, 'statid = ' . $k );

// 						$sql = 'UPDATE '.jieqi_dbprefix('article_article').' SET lastvisit='.JIEQI_NOW_TIME.', '.$daystr.', '.$weekstr.', '.$monthstr.', '.$allstr.' WHERE articleid='.intval($k);
// 						$article_handler->db->query($sql);*/
					}
				}
			}

		}
	}
	//是否限免状态，判断是否显示内容，还要加时间限制
	function checkIsFree($aid){
		if(JIEQI_NOW_TIME>=1419955200&&JIEQI_NOW_TIME<1420300800){ //大于等于2014年12月31日0点0分，小于2015年1月4日0点0分
			$auth = $this->getAuth();
			$this->db->init ( 'free', 'freeid', 'article' );
			$this->db->setCriteria(new Criteria('uid', $auth['uid']));
			$this->db->criteria->add(new Criteria('articleid', $aid));
			return $this->db->getCount($this->db->criteria);
		}else{
			return 0;
		}
	}
	//是否显示限免窗口，用于前台判断，返回值1表示显示，0表示不显示
	function showFree($aid){
		if(JIEQI_NOW_TIME>=1419955200&&JIEQI_NOW_TIME<1420300800){ //大于等于2014年12月31日0点0分，小于2015年1月4日0点0分
			$auth = $this->getAuth();
			//查询当前用户VIP等级
			$this->addConfig('system','vipgrade');
			$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
			$vipgrade = jieqi_gethonorarray($auth['vip'], $jieqiVipgrade);//VIP等级数组
			$vip = $vipgrade['setting']['baodiyuepiao']; //该VIP等级下保底月票数量，相当于VIP等级
			
			if($vip<1){ //普通会员
				$freeaids = array();
				$freeaids = $this->getFreeLimit();
				
				if(in_array($aid,$freeaids)){ //当前文章在20本内
					$vip = 1;
					$isfree = $this->checkIsFree($aid); //1为已经加入限免表，0为未加入
					if($isfree>0){ //已经限免，则不显示
						return 0;
					}else{
						$this->db->init ( 'free', 'freeid', 'article' );
						$day = $this->getTime();
						$this->db->setCriteria(new Criteria('uid', $auth['uid']));
						$this->db->criteria->add(new Criteria('addtime', $day, '>='));
						$today = $this->db->getCount(); //该用户今日加入限免的文章数
						
						if($today<$vip){ //没有超过今日限免数量
							return 1;
						}else{ //超过今日限免数量
							return 0;
						}
					}
				}else{
					return 0;
				}
			}else{ //VIP1级以上
				$isfree = $this->checkIsFree($aid); //1为已经加入限免表，0为未加入
				if($isfree>0){ //已经限免，则不显示
					return 0;
				}else{ //未限免，需要判断是否已超过今日限免数量
					$this->db->init ( 'free', 'freeid', 'article' );
					$day = $this->getTime();
					$this->db->setCriteria(new Criteria('uid', $auth['uid']));
					$this->db->criteria->add(new Criteria('addtime', $day, '>='));
					$today = $this->db->getCount(); //该用户今日加入限免的文章数
					
					if($today<$vip){ //没有超过今日限免数量
						return 1;
					}else{ //超过今日限免数量
						return 0;
					}
				}
			}
		}else{
			return 0;
		}
	}
	//普通会员可限免的20本书
	function getFreeLimit(){
		return array(33705,24668,3992,32924,33731,24585,33761,33864,32511,33778,32526,24692,33768,32354,33758,32334,33847,174,33858,32923); //20本限免文章，普通会员可限免
	}
	
	
	
	/**
	 * 获取key合并的来源渠道数组
	 * @return multitype:string
	 */
	public function getSitesCombine(){
		static $arr = array("0"=>'主站',
				'300,302'=>'android',//android
	   			'301,303'=>'ios',//ios
				'304,305'=>'windows phone',//windows phone
				'400'=>'wap');
		return $arr;
	}
	/**
	 * 通过siteid获取来源渠道
	 * @param unknown $siteid
	 * @return Ambigous <string>|string
	 */
	public function getFromBySiteid($siteid){//301 302 303 304
	   static $arr = array("0"=>'主站',
				'300'=>'android',//android
				'302'=>'android',//android 单本apk
	   			'301'=>'ios',//ios
				'303'=>'ios',//ios 单本apk
				'304'=>'windows phone',//windows phone
				'305'=>'windows phone',//windows 单本apk
				'400'=>'wap');
	   if(array_key_exists($siteid,$arr)){
	   		return $arr[$siteid];
	   }
	   return '';
	}
	
	/**
	 * 是否需要验证码登录：1|0
	 * <p>
	 * 是否启用验证码
	 * <p>
	 * 免验证码登录次数
	 * @author chengyuan 2015-5-6 下午4:22:18
	 */
	public function checkcodeState(){
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		if($jieqiConfigs['system']['checkcodelogin']){
			if($this->free_checkcode_login === 0){
				return 1;
			}else{
				//免验证码登录2次
				if(isset($_SESSION['free_login_count']) && $_SESSION['free_login_count']>=$this->free_checkcode_login){
					return 1;
				}else{
					return 0;
				}
			}
		} else{
			return 0;
		}
	}
	
	/**
	 * 判断是否是传媒公司用户
	 * @author chengyuan 2015-5-12 上午10:33:09
	 */
	public function mediaUser(){
		$auth = $this->getAuth();
		if($auth['groupid'] == 12 || $auth['groupid'] == 14){
			return true;
		}else{
			return false;
		}
	}
}
?>