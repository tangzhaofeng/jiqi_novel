<?php
/**
 * article模型
 * @author chengyuan  2014-4-4
 *
 */
class articleModel extends Model {
	/**
	 * 当前登录作者有没有创建过书
	 */
	function createArticle(){
		$bool = 0;
		$auth = $this->getAuth();
		$articleLib =  $this->load('article','article');
		$data =  $articleLib->articleByAuthorid($auth['uid']);
		if(!empty($data)){
			$bool = 1;
		}
		return $bool;
	}

	/**
	 * 第三步，视图资源
	 * @param unknown $param
	 * @return multitype:NULL
	 */
	function step3($param){
		if(!empty($param['aid']) && !empty($param['cid'])){
			$data = array();
			$articleLib =  $this->load('article',false);
			$article = $articleLib->isExists($param['aid']);
			$data['article'] = $articleLib->article_vars($article);
			$this->db->init ( 'chapter', 'chapterid', 'article' );
			$chapter = $this->db->get($param['cid']);
			$data['firstchaptername'] = $chapter['chaptername'];
			$data['firstchaptersize'] = jieqi_strlen ( $chapter['chaptername'] );
			return $data;
		}else{
			$this->printfail(LANG_DO_FAILURE);
		}
	}
	/**
	 * 申请为作者
	 * @param unknown $param
	 */
	function applyWriter($param){
		//作品管理链接
		$url = $this->geturl ( 'article', 'article', 'SYS=method=masterPage&display=author' );
		$articleLib = $this->load ( 'article', 'article' );
		if($this->checkpower($articleLib->jieqiPower['article']['newarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true)) {
			$this->printfail(sprintf($articleLib->jieqiLang['article']['has_been_writer'],$url,$url));
		}
		$applyText = trim($param['simpletext']);
		if(empty($applyText) || strlen($applyText) == 0){
			$this->printfail($articleLib->jieqiLang['article']['need_simple_chapter']);
		}
		$bool = $articleLib->saveSimpleChapter($applyText);
		if($bool){
			$this->jumppage($url, LANG_DO_SUCCESS, sprintf($articleLib->jieqiLang['article']['apply_writer_success'],$articleLib->jieqiConfigs['article']['writergroup']));
		}
	}
	/**
	 * 删除一篇文章
	 *
	 * @param $aid 文章id
	 */
	function articleDelete($aid) {
		$articleLib = $this->load ( 'article', 'article' );
		$article = $articleLib->isExists ( $aid );
		// 检查权限
		$articleLib->canedit($article);
		$articleLib->delPower($aid);
		$articleLib->articleDelete ( $article, true );
		$this->jumppage ( $this->geturl ( 'article', 'article', 'SYS=method=masterPage' ), LANG_DO_SUCCESS, sprintf($articleLib->jieqiLang['article']['article_delete_success'],$article['articlename']));
	}
	/**
	 * 清空文章，即删除文章内的所有章节
	 *
	 * @param
	 *        	$aid
	 */
	function articleClean($aid, $jumpurl = '') {
		$articleLib = $this->load ( 'article','article' );
		$article = $articleLib->isExists ( $aid );
		// 检查权限
		$articleLib->canedit($article);
		$articleLib->delPower($aid);
		$articleLib->articleClean ( $article, false );
		$jumpurl = $jumpurl ? $jumpurl : $this->geturl ( 'article', 'chapter', 'SYS=method=cmView&aid=' . $aid );
		$this->jumppage ( $jumpurl, LANG_DO_SUCCESS, sprintf($articleLib->jieqiLang['article']['article_clean_success'],$article['articlename']));
	}
	/**
	 * 上书表单内的资源
	 * @return $data
	 */
	function newArticleView() {
		// 载入自定义类 即公共方法类
		$articleLib = $this->load ( 'article', 'article' );
		// 公共的资源，比如：类别，分类
		$data = $articleLib->getSources();
		//修改权限的级别  manageallarticle > articlemodify
		$data['manageallarticle'] = 0;
		if($this->checkpower ( $articleLib->jieqiPower ['article'] ['manageallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )){
			$data['manageallarticle'] = 1;//最高级别的权限
		}
		//转载作品的权限
		$data ['allowtrans'] = 0;
		if ($this->checkpower ($articleLib->jieqiPower ['article'] ['transarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )){
			$data ['allowtrans'] = 1;
		}
		//发表文章需不需要审核
		$data ['display'] = 0;
		if ($this->checkpower ($articleLib->jieqiPower ['article'] ['needcheck'], $this->getUsersStatus (), $this->getUsersGroup (), true )) {
			$data ['display'] = 1;//不需要审核
		}
		unset($data['channel']['400']);//屏蔽掉wap渠道
		//unset($data['channel']['100']);//屏蔽掉mm渠道
		
		//标签
		//默认是当前控制器模块的标签
		$tagMod = $this->model('tag','article');
		$tags = $tagMod->getAllSiteTag();
		$data['tags'] = $tags;
		return $data;
	}
	/**
	 * 文章基本信息修改视图
	 * @param unknown $param
	 */
	function editArticleView($param){
		$aid = $param['aid'];
		$articleLib = $this->load ( 'article', 'article' );
		$article = $articleLib->isExists($aid);
		$articleLib->canedit($article);//有没有修改权限 manageallarticle > authorid|posterid|agentid，没有权限直接提示
		$data = array();
		$auth = $this->getAuth();
		$data = $articleLib->getSources ();
		$data['articles'] = $articleLib->articleByAuthorid($auth['uid']);
		$articleLib->handleManageallarticle($data['articles'],$article);
		foreach ( $data['articles'] as $k => $v ) {
			if(is_array($v) && $v['articleid'] == $aid){
				$data['article'] = $v;
				break;
			}
		}
		//是否授权给作者
		if($data['article']['authorid']>0) $data['article']['authorflag'] = 1;
		else $data['article']['authorflag'] = 0;
		//修改权限的级别  manageallarticle > articlemodify
		$data['manageallarticle'] = 0;
		if($this->checkpower ( $articleLib->jieqiPower ['article'] ['manageallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )){
			$data['manageallarticle'] = 1;//最高级别的权限
		}
		$data['transarticle'] = 0;
		if($this->checkpower ( $articleLib->jieqiPower ['article'] ['transarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )){
			$data['transarticle'] = 1;//最高级别的权限
		}
		unset($data['channel']['400']);//屏蔽掉wap渠道
		//unset($data['channel']['100']);//屏蔽掉mm渠道
		
		//标签
		$tagMod = $this->model('tag','article');
		$tags = $tagMod->getAllSiteTag();
		$data['tags'] = $tags;
		return $data;
	}
	/**
	 * 修改文章基本信息
	 * @param unknown $param
	 */
	function editArticle($param) {
	    $data = $param;
		$data ['articleid'] = trim ( $param ['aid'] );
		$data ['articlelpic'] = $_FILES ['articlelpic'];
		$data ['articlespic'] = $_FILES ['articlespic'];
		$data ['tag'] = implode(",",$param ['tag'] );
		//验证数据
		$errtext =  $this->checkArticlename($data ['articleid'], $data ['articlename'],true);
		if(empty($errtext)){
			$errtext =  $this->checkIntro($data ['intro'],true);
		}
		if(empty($errtext)){
			$errtext =  $this->checkCover($data ['articlelpic']);
		}
		if(empty($errtext)){
			$errtext =  $this->checkCover($data ['articlespic']);
		}
		if(!empty($errtext)){
			$this->printfail($errtext);
		}else{
			$articleLib = $this->load ( 'article', 'article' );
			$articleLib->updateArticle ( $data );
			header('Location: '.$this->geturl ( 'article', 'article', 'SYS=method=editArticleView&aid='.$data ['articleid']));
		}
	}
	/**
	 * 保存新书，根据 用户审核权限 重定向。
	 * <br>需要审核->第二步：上传内容
	 * <br>不审核->章节管理
	 */
	function newArticle($param) {
		//检查验证码
		if(empty($param['checkcode']) || strtolower($param['checkcode']) != $_SESSION['jieqiCheckCode']){
			$this->addLang('system', 'users');
			$jieqiLang['system'] = $this->getLang('system');
			$this->printfail($jieqiLang['system']['error_checkcode']);
		}
		$data ['articlename'] = trim ( $param ['articlename'] );
		$data ['author'] = trim ( $param ['author'] );
		$data ['agent'] = trim ( $param ['agent'] );
		$data ['keywords'] = trim ( $param ['keywords'] );
		$data ['tag'] = implode(",",$param ['tag'] );
		$data ['intro'] = trim ( $param ['intro'] );
		$data ['notice'] = trim ( $param ['notice'] );
		$data ['permission'] = trim ( $param ['permission'] );
		$data ['siteid'] = trim ( $param ['siteid'] );
		$data ['sortid'] = trim ( $param ['sortid'] );
		$data ['firstflag'] = 0;
		$data ['authorflag'] = trim ( $param ['authorflag'] );
/*		if(!empty($param ['firstflag'])){
			$data ['firstflag'] = trim ( $param ['firstflag'] );
		}*/
		$data ['articlelpic'] = $_FILES ['articlelpic'];
		$data ['articlespic'] = $_FILES ['articlespic'];
		//验证数据
		$errtext =  $this->checkArticlename('', $data ['articlename'],true);
		if(empty($errtext)){
			$errtext =  $this->checkIntro($data ['intro'],true);
		}
		if(empty($errtext)){
			$errtext =  $this->checkCover($data ['articlelpic']);
		}
		if(empty($errtext)){
			$errtext =  $this->checkCover($data ['articlespic']);
		}
		if(!empty($errtext)){
			$this->printfail($errtext);
		}else{
		    $articleLib = $this->load ( 'article', 'article' );
			$users_handler = $this->getUserObject();
			/*$data ['agent'] = '';
			$data ['agentid'] = 0;
			if (! empty ( $param ['agent'] )){
				if ($agentobj = $users_handler->getByname ( $param ['agent'], 3 )) {
					$data ['agentid'] = $agentobj->getVar ( 'uid' );
					$data ['agent'] = $agentobj->getVar ( 'uname', 'n' );
				}
			}*/
			$auth = $this->getAuth();
			if ($this->checkpower ( $articleLib->jieqiPower ['article'] ['transarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )) {
				//允许转载的情况
				if (empty ( $data ['author'] ) || ($data ['author'] == $auth ['username'])) {
					$data ['authorid'] = $auth ['uid'];
					$data ['author'] = $auth ['username'];
				} else {
					// 转载作品
					//$data ['author'] = $data ['author'];
					if ($data['authorflag']) {
						$authorobj = $users_handler->getByname ( $data ['author'], 3 );
						if (is_object ( $authorobj )) $newArticle ['authorid'] = $authorobj->getVar ( 'uid' );
						else $data ['authorid'] = 0;
					} else {
						$data ['authorid'] = 0;
					}
				}
				//$data ['permission'] = $data ['permission'];
				if($data ['permission']>= 4) $data ['signdate'] = JIEQI_NOW_TIME;
				//$data ['firstflag'] = $data ['firstflag'] ? $data ['firstflag'] : 0;
				if(!empty($param ['firstflag'])){
					$data ['firstflag'] = trim ( $param ['firstflag'] );
				}
			} else {
				$data ['authorid'] = $auth ['uid'] ;
				$data ['author'] = $auth ['username'];
			}

			$newArticle = $articleLib->newArticle($data);
			if($newArticle ['display'] == 1){
				header('Location: '.$this->geturl ( 'article', 'chapter', 'SYS=method=step2View&aid=' .$newArticle['articleid']));
			}else{
				header('Location: '.$this->geturl ( 'article', 'chapter', 'SYS=method=cmView&aid=' . $newArticle['articleid']));
			}
		}
	}
	/**
	 * 我的文章列表,联合查询jieqi_article_statamout表的收藏，点击，推荐
	 * @param unknown $param
	 * @return multitype:NULL
	 */
	function myArticleList($param){
		$data = array ();
		$auth = $this->getAuth();
		$articleLib = $this->load ( 'article', 'article' );
		$this->db->init ( 'article', 'articleid', 'article' );
		$this->db->setCriteria ();
		$this->db->criteria->add ( new Criteria ( 'authorid', $auth ['uid'], '=' ), 'OR' );
		$this->db->criteria->setTables(jieqi_dbprefix('article_statamout').' s right JOIN '.jieqi_dbprefix('article_article').' a ON s.articleid=a.articleid');
		$this->db->criteria->setFields('a.*, s.goodnum, s.visit, s.vote');
		$this->db->criteria->setSort ( 'postdate' );
		$this->db->criteria->setOrder ( 'desc' );
		$data ['articlerows'] = $this->db->lists ( $articleLib->jieqiConfigs ['article'] ['pagenum'], $param['page'], JIEQI_PAGE_TAG);
		foreach($data ['articlerows'] as $k=>$v){
			$data ['articlerows'][$k] = $articleLib->article_vars($v);
			//左表有可能没有数据
			if(!isset($data ['articlerows'][$k]['goodnum'])){
				$data ['articlerows'][$k]['goodnum'] = 0;
			}
			if(!isset($data ['articlerows'][$k]['visit'])){
				$data ['articlerows'][$k]['visit'] = 0;
			}
			if(!isset($data ['articlerows'][$k]['vote'])){
				$data ['articlerows'][$k]['vote'] = 0;
			}
		}
		// 处理页面跳转
		$data ['url_jumppage'] = $this->db->getPage ($this->getUrl('article','article','evalpage=0','SYS=method=masterPage'));
		return $data;
	}
	/**
	 * 重新生成静态文件
	 */
	function repack($aid,$packflag){
		$this->addLang('article','article');
		$articleLib = $this->load ( 'article', false );
		$articleLib->repack($aid,$packflag);
		jieqi_jumppage ( $this->geturl ( 'article', 'article', 'SYS=method=articleManage&aid=' . $aid ), LANG_DO_SUCCESS, $this->getLang('article','article_repack_success'));
	}
	/**
	 * 书架信息列表
	 */
	function bcList($param){
		//header('Content-Type:text/html;charset=gbk');
		$articleLib = $this->load ( 'article', 'article' );

		//默认第一页
		if(!isset($param['page']) || empty($param['page'])){
			$param['page'] = 1;
		}
		//0 默认书架组
		$data = $articleLib->getBcList($param['page']);
		return $data;
	}

	/**
	 * 删除书架内的书,已废弃
	 * @param unknown $param
	 */
	function bcDel($param){
		$caseid = $param['caseid'];
		$classid = $param['classid'];
		$articleLib = $this->load ( 'article', false );
		$articleLib->baBatchDel($caseid);
		//重定向书签视图
		$this->jumppage ($this->geturl ( 'article', 'article', 'SYS=method=bcView&classid=' . $classid), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
	}
	/**
	 * 书架批量删除操作
	 * @param unknown $param
	 */
	function bcHandle($param){
		$articleLib = $this->load ( 'article', 'article' );
		$checkids=$this->arrayToStr($param['checkid']);
		$auth = $this->getAuth();
		$this->db->init('bookcase','caseid','article');
		$this->db->setCriteria(new Criteria('userid', $auth['uid']));
		$this->db->criteria->add(new Criteria('caseid', '('.$checkids.')', 'IN'));
		$this->db->criteria->setFields('articleid');
		$this->db->queryObjects();
		$articleids = array();
		while($v = $this->db->getObject()){
			$articleids[] = intval($v->getVar('articleid'));
		}
		$this->db->init('statlog','statlogid','article');
		$this->db->setCriteria(new Criteria('uid', $auth['uid']));
		$this->db->criteria->add(new Criteria('mid', 'goodnum'));
		$this->db->criteria->add(new Criteria('articleid', '('.implode(',',$articleids).')','IN'));
		$this->db->delete( $this->db->criteria );
		$articleLib->baBatchDel($checkids);
		$this->jumppage ($this->geturl ( JIEQI_MODULE_NAME, 'article', 'SYS=method=bcView'), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
	}
	/**
	 * 获取文章属性信息
	 *
	 * @param 小说ID $id
	 * @return multitype:NULL string unknown number Ambigous <multitype:, string>
	 */
	function getArticleInfo($id) {

		// 载入自定义类 即公共方法类
		$articleLib = $this->load ( 'article', false );
		// 获取分类列表key:sort，管理授权key:，授权级别key:，首发状态key:
		$data = $articleLib->getSources ();
		$this->db->init ( 'article', 'articleid', 'article' );
		$article = $this->db->get ( $id );
	}


	/**
	 * 获取分类列表key:sort，管理授权key:，授权级别key:，首发状态key:
	 *
	 * @return multitype:$data
	 */
	public function getSources() {
		$articleLib = $this->load ( 'article', 'article' );
		$data =  $articleLib->getSources();
		return $data;
	}
	//登录条 小型书架
	//个人信息 作品列表
	public function userList($params){
		 $jieqiConfigs['article']['pagenum'] = 4;
		 $this->db->init('article','articleid','article');
		 $this->db->setCriteria();
		 $this->db->criteria->add(new Criteria('authorid',$params['uid']));
		$this->db->criteria->add ( new Criteria ( 'display', 0));
		 $this->db->criteria->setSort('lastupdate');	//按更新时间排序
		 $this->db->criteria->setOrder('DESC');
		 if (empty($params['page']))
		 {
		 	$params['page'] = 1;
		 }
		 $p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showProducts(this,\'{$prepage}\',1)" id="'.$params['page'].'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar]
[pnumurl]<A href="javascript:;" onclick="return showProducts(this,\'{$pnumurl}\',1)" id="'.$params['page'].'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showProducts(this,\'{$nextpage}\',1)" id="'.$params['page'].'">下一页</a>[/nextpage]';
		$data = $this->db->lists($jieqiConfigs['article']['pagenum'], $params['page'],$p);


		 $package = $this->load('article','article');//加载文章处理类

		 $k=0;
		 foreach($data as $k=>$v)
		 {
		 	$data[$k] = $package->article_vars($v);
			  $k++;
		 }

		 $pageurl = $this->db->getPage($this->getUrl('article','article','method=userlist','evalpage=0','SYS=uid='.$params['uid']));
		 return array(
			 'articlerows'=>$data,
			 'url_jumppage'=>$pageurl
		 );
	}
	//个人信息 收藏列表
	public function userbook($params){
		$jieqiConfigs['article']['pagenum'] = 10;
		$this->db->init('bookcase ','caseid','article');
		$this->db->setCriteria(new Criteria('userid', $params['uid']));
		$this->db->criteria->add ( new Criteria ( 'display', 0));
		$data['nowbookcase'] = $this->db->getCount();
		$this->db->criteria->setTables(jieqi_dbprefix('article_bookcase').' c RIGHT JOIN '.jieqi_dbprefix('article_article').' a ON c.articleid=a.articleid');
		$this->db->criteria->setFields('c.*, a.articleid, a.lastupdate, a.articlename, a.authorid, a.author, a.sortid, a.lastchapterid, a.lastchapter, a.fullflag');
		$this->db->criteria->setSort('a.lastupdate');
		$this->db->criteria->setOrder('DESC');
		 if (empty($params['page']))
		 {
		 	$params['page'] = 1;
		 }
		 $p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showProducts(this,\'{$prepage}\',1)" id="'.$params['page'].'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar]
[pnumurl]<A href="javascript:;" onclick="return showProducts(this,\'{$pnumurl}\',1)" id="'.$params['page'].'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showProducts(this,\'{$nextpage}\',1)" id="'.$params['page'].'">下一页</a>[/nextpage]';
		$data = $this->db->lists($jieqiConfigs['article']['pagenum'], $params['page'],$p);

		$package = $this->load('article','article');//加载文章处理类
		$k=0;
		 foreach($data as $k=>$v)
		 {
		 	$data[$k] = $package->article_vars($v);
			  $k++;
		 }

		 $pageurl = $this->db->getPage($this->getUrl('article','article','method=userbook','evalpage=0','SYS=uid='.$params['uid']));
		 return array(
			 'articlerows'=>$data,
			 'url_jumppage'=>$pageurl
		 );
	}
	/**
	 * 统计点击量
	 * @param unknown $aid
	 */
	public function statisticsVisit($aid){
		if(isset($aid) && !empty($aid) && is_numeric($aid)){
			$articleLib = $this->load ( 'article', 'article' );
			$article = $articleLib->isExists($aid);
			$articleLib->statisticsVisit($article);
		}

	}
	/**
	 * 异步生成文章对应的静态文件
	 */
	public function synchronousMakePack($param){
		$articleLib = $this->load ( 'article', 'article' );
		//控制器跳转过来已经为异步跳转，url的合法性验证通过后足矣说明此url已经为异步的请求
		//检查密钥
		if(empty($param['key'])) exit('no key');
		elseif($param['key'] != md5(JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME.JIEQI_SITE_KEY)) exit();

		//检查打包参数
		if(!is_numeric($param['aid'])) exit;
		$aid = intval($param['aid']);
		//所以这里的参数为：1
		$articleLib->article_repack($aid,$param,1);
	}
	/**
	 * 验证上传的封面文件，返回验证结果，返回空则验证通过
	 * @param unknown $cfile
	 */
	function checkCover($cfile){
		$errtext = "";
		if (!empty($cfile) && is_array($cfile)){
			$articleLib = $this->load ( 'article', 'article' );
			// 检查封面
			$typeary = explode ( ' ', trim ( $articleLib->jieqiConfigs ['article'] ['imagetype'] ) );
			foreach ( $typeary as $k => $v ) {
				if (substr ( $v, 0, 1 ) != '.')
					$typeary [$k] = '.' . $typeary [$k];
			}
			if (! empty ( $cfile['name'] )) {
				$simage_postfix = strrchr ( trim ( strtolower ( $cfile['name'] ) ), "." );
				if (eregi ( "\.(gif|jpg|jpeg|png|bmp)$", $cfile['name'] )) {
					if (! in_array ( $simage_postfix, $typeary ))
						$errtext .= sprintf ( $articleLib->jieqiLang ['article'] ['simage_type_error'], $articleLib->jieqiConfigs ['article'] ['imagetype'] ) . '<br />';
				} else {
					$errtext .= sprintf ( $articleLib->jieqiLang ['article'] ['simage_not_image'], $cfile['name'] ) . '<br />';
				}
				if (! empty ( $errtext ))
					jieqi_delfile ( $cfile['tmp_name'] );
			}
		}
		return $errtext;
	}
	/**
	 * ajax 验证作者 名称 是否有效
	 * @param unknown $intro
	 */
	function checkAuthor($author){
		$articleLib = $this->load ( 'article', 'article' );
		$author = trim($author);
		$retmsg = array();
		if(!empty($author)){
			if ($this->checkpower ( $articleLib->jieqiPower ['article'] ['transarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true )) {
				$auth = $this->getAuth();
				//允许转载的情况
				if ($author == $auth ['username']) {
					$retmsg['ok'] = $articleLib->jieqiLang ['article'] ['author_myself'] ;
				} else {
					// 转载作品
					$users_handler = $this->getUserObject();
					$authorobj = $users_handler->getByname ( $author, 3 );
					if (is_object ( $authorobj ))
						$retmsg['ok'] = '';
					else
						$retmsg['error'] = $articleLib->jieqiLang ['article'] ['author_not_exists'] ;
				}
			} else {
				$retmsg['error'] = $articleLib->jieqiLang ['article'] ['author_not_trans'] ;
			}
			exit($this->json_encode($retmsg));
		}
	}

	/**
	 * ajax 验证代理人/管理员 名称 是否有效
	 * @param unknown $intro
	 */
	function checkAgent($agent){
		$articleLib = $this->load ( 'article', 'article' );
		$agent = trim($agent);
		$retmsg = array();

		$users_handler = $this->getUserObject();
		if (! empty ($agent))
			$agentobj = $users_handler->getByname ($agent, 3 );
		if (is_object ( $agentobj )) {
			$retmsg['ok'] = '';
		}else{
			$retmsg['error'] = $articleLib->jieqiLang ['article'] ['agent_not_exists'] ;
		}
		exit($this->json_encode($retmsg));
	}
	/**
	 * 验证intro是否有违禁单词
	 * @param unknown $intro
	 * @param string $isreturn
	 */
	function checkIntro($intro,$isreturn = false){
		include_once (JIEQI_ROOT_PATH . '/lib/text/textfunction.php');
		$articleLib = $this->load ( 'article', 'article' );
		$intro = trim($intro);
		$retmsg = array();
		$errtext = '';
		// 检查标题和简介有没有违禁单词
		if (!empty( $articleLib->jieqiConfigs ['system'] ['postdenywords'] )) {
			include_once (JIEQI_ROOT_PATH . '/include/checker.php');
			$checker = new JieqiChecker ();
			$matchwords1 = $checker->deny_words ($intro, $articleLib->jieqiConfigs ['system'] ['postdenywords'], true );
			if (is_array ( $matchwords1 )) {
				if (! isset ( $articleLib->jieqiLang ['system'] ['post'] )){
					$this->addLang('system','post');
					$articleLib->jieqiLang['system'] =  $this->getLang('system');
				}
				$errtext .= sprintf ( $articleLib->jieqiLang ['system'] ['post_words_deny'], implode ( ' ', jieqi_funtoarray ( 'htmlspecialchars', $matchwords1 ) ) );
			}
		}

		if($errtext){
			$retmsg['error'] = $errtext;
		}else  $retmsg['ok'] = '';
		if(!$isreturn) exit($this->json_encode($retmsg));
		else return $errtext;
	}
	/**
	 * 验证articlename的合法性，验证四个项目：非空，影响网页的字符，违禁单词，书名是否已经占用。
	 * @param unknown $aid		可空,aid验证相同书名时，可以过滤掉aid
	 * @param unknown $string   需要验证的字符串
	 * @param string $isreturn  是否返回验证信息，默认false?琷son输出验证结果，true返回验证结果
	 * @return string  数据格式：array('error'=>'info')|array('ok'=>'info');
	 */
	function checkArticlename($aid,$string,$isreturn = false){
		include_once (JIEQI_ROOT_PATH . '/lib/text/textfunction.php');
		$articleLib = $this->load ( 'article', 'article' );
		$string = trim($string);
		$retmsg = array();
		$errtext = '';
		// 检查标题
		if (strlen ($string) == 0)
			$errtext .= $articleLib->jieqiLang ['article'] ['need_article_title'] . '<br />';
		elseif (!jieqi_safestring ($string))
			$errtext .= $articleLib->jieqiLang ['article'] ['limit_article_title'] . '<br />';
		if(!empty( $articleLib->jieqiConfigs ['system'] ['postdenywords'] )){// 检查标题和简介有没有违禁单词
			include_once (JIEQI_ROOT_PATH . '/include/checker.php');
			$checker = new JieqiChecker ();
			$matchwords1 = $checker->deny_words ($string, $articleLib->jieqiConfigs ['system'] ['postdenywords'], true );
			if (is_array($matchwords1 )) {
				if (! isset ( $articleLib->jieqiLang ['system'] ['post'] )){
					$this->addLang('system','post');
					$articleLib->jieqiLang['system'] =  $this->getLang('system');
				}
				$errtext .= sprintf ( $articleLib->jieqiLang ['system'] ['post_words_deny'], implode ( ' ', jieqi_funtoarray ( 'htmlspecialchars', $matchwords1 ) ) );
			}
		}
		if($articleLib->jieqiConfigs ['article'] ['samearticlename'] != 1) {
			$this->db->init ( 'article', 'articleid', 'article' );
			$this->db->setCriteria ( new Criteria ( 'articlename', $string) );
			if(!empty($aid) && $aid>0){
				$this->db->criteria->add ( new Criteria ( 'articleid', $aid, '!=' ));
			}
			if ($this->db->getCount () > 0) {
				$errtext .= sprintf ( $articleLib->jieqiLang ['article'] ['articletitle_has_exists'], jieqi_htmlstr ($string) ) ;
			}
		}
		if($errtext){
			$retmsg['error'] = $errtext;
		}else  $retmsg['ok'] = '';
		if(!$isreturn) exit($this->json_encode($retmsg));
		else return $errtext;
	}

	/**
	 * 定时审核章节、卷机制
	 * @param unknown $param
	 * 2014-7-4 上午7:15:22
	 */
	function regularAudits($param){
		if(empty($param['key'])) exit('no key');
		elseif($param['key'] != md5(JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME.JIEQI_SITE_KEY)) exit();
		$this->db->init ( 'chapter', 'chapterid', 'article' );
		$this->db->setCriteria ( new Criteria ( 'display', 2) );
		$this->db->criteria->add ( new Criteria ( 'postdate', JIEQI_NOW_TIME, '<=' ));
		$this->db->criteria->setGroupby ('articleid');
		$this->db->queryObjects ();
		$aids = array();
		while($v = $this->db->getObject()){
			$aids[] = $v->getVar ( 'articleid', 'n' );
		}
		if(!empty($aids)){
			//更新审核状态2-0
			$this->db->updatetable ( 'article_chapter', array (
					'display' => '0'
			), 'display = 2 and postdate <= ' . JIEQI_NOW_TIME );
			$articleLib = $this->load ( 'article', 'article' );
			foreach ( $aids as $k => $v ) {
				$articleLib->article_repack($v, array('makeopf'=>1), 1);
			}
		}
	}

	/**
	 * 七夕测试
	 * @param unknown $param
	 * 2014-7-30 下午5:12:15
	 */
	function qixi($param){
		$fid = $param['fid'];
		$sid = $param['sid'];
		$auth = $this->getAuth();
		$sql = 'SELECT a.articleid,a.articlename,SUM(c.saleprice) AS totalprice FROM '.jieqi_dbprefix("article_article").' a right join '.jieqi_dbprefix("article_chapter").' c on a.articleid = c.articleid where a.articleid in ('.$fid.','.$sid.')  group by articleid';
		$setarticle = $this->db->selectsql ($sql);
		$retmsg = array();
		$retmsg['uid'] = $auth['uid'];
		$retmsg['uname'] = $auth['useruname'];
		$retmsg['egold'] = $auth['egolds'];

		$retmsg['fid'] = $setarticle[0]['articleid'];
		$retmsg['fname'] = $setarticle[0]['articlename'];
		$retmsg['ftotalprice'] = $setarticle[0]['totalprice'];

		$retmsg['sid'] = $setarticle[1]['articleid'];
		$retmsg['sname'] = $setarticle[1]['articlename'];
		$retmsg['stotalprice'] = $setarticle[1]['totalprice'];

		$retmsg['totalprice'] = $setarticle[0]['totalprice']+$setarticle[1]['totalprice'];
		$retmsg['saletotalprice'] = floor(($setarticle[0]['totalprice']+$setarticle[1]['totalprice'])*0.77);

		exit($this->json_encode($retmsg));
	}
	function qixireg($param){
		//统计2014-8-1 00:00:00-2014-8-10 23:59:59注册的用户
		//每个用户新增77书海币，并且站内信通知
		$sql = 'SELECT *FROM '.jieqi_dbprefix("system_users").' WHERE regdate between 1406822400 and 1407686399 ORDER BY regdate';
		$users = $this->db->selectsql ($sql);
		echo '<table border=1>';
		echo '<tr><th colspan=7>统计2014-8-1 00:00:00-2014-8-10 23:59:59注册的用户总数：'.count($users).'</th></tr>';
		echo '<tr><th>序</th><th>ID</th><th>用户名称</th><th>注册时间</th><th>书海币</th><th>更新后的书海币</th><th>站内信通知</th></tr>';
		$i = 0;
		$users_handler =  $this->getUserObject();//查询用户是否存在
		$this->db->init('message','messageid','system');
		foreach ( $users as $k => $v ) {
			$i++;
			$temp = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
			$users_handler->income($v['uid'],77);//更新书海币
			$user = $users_handler->get($v['uid']);//更新后的用户信息
			//发送站内信
			$newMessage = array();
			$newMessage['siteid']= JIEQI_SITE_ID;
			$newMessage['postdate']= JIEQI_NOW_TIME;
			$newMessage['fromid']= 6;
			$newMessage['fromname']= '系统管理员';
			$newMessage['toid']= $v['uid'];
			$newMessage['toname']= $v['uname'];
			$newMessage['title']= '七夕活动-注册用户';
			$newMessage['content']= '根据七夕节书海网活动规则，您在活动期间内 注册 书海网账户，以表感谢，送您77个书海币，请查收。';
			$newMessage['messagetype']= 0;
			$newMessage['isread']= 0;
			$newMessage['fromdel']= 0;
			$newMessage['todel']= 0;
			$newMessage['enablebbcode']= 1;
			$newMessage['enablehtml']= 0;
			$newMessage['enablesmilies']= 1;
			$newMessage['attachsig']=0;
			$newMessage['attachment']= 0;
			if($this->db->add($newMessage))$msg ='发送成功';
			else $msg = '发送失败';
			echo sprintf($temp,$i,$v['uid'],$v['uname'],date('Y-m-d H:i:s',$v['regdate']),$v['egold'],$user->getVar('egold', 'n'),$msg);
		}
		echo '</table>';
	}
	/**
	 * 七夕登陆
	 * @param unknown $param
	 * 2014-8-13 下午2:45:47
	 */
	function qixilogin($param){
		//2014-8-1 00:00:00以前注册的用户在2014-8-1 00:00:00-现在登陆过的用户，赠送书海币
		//每个用户新增77书海币，并且站内信通知
		$sql = 'SELECT * FROM '.jieqi_dbprefix("system_users").' WHERE regdate < 1406822400 and lastlogin >= 1406822400 order by lastlogin';
		$users = $this->db->selectsql ($sql);
		echo '<table border=1>';
		echo '<tr><th colspan=8>统计2014-8-1 00:00:00以前注册的用户，在2014-8-1 00:00:00-'.date('Y-m-d H:i:s',JIEQI_NOW_TIME).'登陆的用户,总数：'.count($users).'</th></tr>';
		echo '<tr><th>序</th><th>ID</th><th>用户名称</th><th>注册时间</th><th>上次登录时间</th><th>书海币</th><th>更新后的书海币</th><th>站内信通知</th></tr>';
		$i = 0;
		$users_handler =  $this->getUserObject();//查询用户是否存在
		$this->db->init('message','messageid','system');
		foreach ( $users as $k => $v ) {
			$i++;
			$temp = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
			$users_handler->income($v['uid'],77);//更新书海币
			$user = $users_handler->get($v['uid']);//更新后的用户信息
			//发送站内信
			$newMessage = array();
			$newMessage['siteid']= JIEQI_SITE_ID;
			$newMessage['postdate']= JIEQI_NOW_TIME;
			$newMessage['fromid']= 6;
			$newMessage['fromname']= '系统管理员';
			$newMessage['toid']= $v['uid'];
			$newMessage['toname']= $v['uname'];
			$newMessage['title']= '七夕活动-登录用户';
			$newMessage['content']= '根据七夕节书海网活动规则，您在活动期间内 登录 书海网账户，以表感谢，送您77个书海币，请查收。';
			$newMessage['messagetype']= 0;
			$newMessage['isread']= 0;
			$newMessage['fromdel']= 0;
			$newMessage['todel']= 0;
			$newMessage['enablebbcode']= 1;
			$newMessage['enablehtml']= 0;
			$newMessage['enablesmilies']= 1;
			$newMessage['attachsig']=0;
			$newMessage['attachment']= 0;
			if($this->db->add($newMessage))$msg ='发送成功';
			else $msg = '发送失败';
			echo sprintf($temp,$i,$v['uid'],$v['uname'],date('Y-m-d H:i:s',$v['regdate']),date('Y-m-d H:i:s',$v['lastlogin']),$v['egold'],$user->getVar('egold', 'n'),$msg);
		}
		echo '</table>';
	}

	function qixisale($param){
		$sql = 'SELECT *,(select aa.articlename from jieqi_article_article aa where aa.articleid=s.articleid) as articlename FROM '.jieqi_dbprefix("article_statlog").' s WHERE addtime between 1406822400 and 1407686399 and articleid in (1162,1999,15991,2122,1935,13689,12622,8690,24655,4033,15985,9815,4143,1944,4010,1361,8822,14478,24669,24671) and mid="sale" and chaptername like "%个章节" ORDER BY addtime';
		$stat = $this->db->selectsql ($sql);
		echo '<table border=1>';
		echo '<tr><th colspan=8>统计2014-8-1 00:00:00-2014-8-10 23:59:59七夕专题销售记录</th></tr>';
		echo '<tr><th>序</th><th>ID</th><th>用户名称</th><th>购买时间</th><th>书号</th><th>书名</th></tr>';
		$i = 0;
// 		$users_handler =  $this->getUserObject();//查询用户是否存在
		foreach ( $stat as $k => $v ) {
			$temp = strval($v['stat']);
			if(strchr($temp,'.') === false){
				$i++;
				$temp = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
				// 			$users_handler->income($v['uid'],77);//更新书海币
				// 			$user = $users_handler->get($v['uid']);//更新后的用户信息
				echo sprintf($temp,$i,$v['uid'],$v['username'],date('Y-m-d H:i:s',$v['addtime']),$v['articleid'],$v['articlename']);
			}

		}
		echo '</table>';
	}
	private function out($msg){
		$sapi = php_sapi_name();
		if($sapi == 'cgi-fcgi'){
			echo str_pad($msg,1024*64);
		}else{
			if($this->first_out){
				echo str_repeat(' ',4096);
				$this->first_out = false;
			}
			echo $msg;
		}
		ob_flush();
		flush();
	}
	/**
	 * 处理jieqi_article_stat表中的重复数据
	 */
	function handleStatTable(){
		$this->db->init('stat','statid','article');
		$sql = "SELECT articleid, mid FROM jieqi_article_stat GROUP BY articleid, mid HAVING count( * ) >1";
		$repeatRows = $this->db->selectsql ($sql);//所有重复的记录
		if(!$repeatRows)exit('没有数据需要处理');
		$this->out("总共".count($repeatRows)."本书有重复记录需要处理<br>");
		$q=0;
		foreach ( $repeatRows as $k => $repeatRow ) {
			
			$articleid = $repeatRow['articleid'];
			$mid = $repeatRow['mid'];
			
			
			$sql1 = "SELECT * FROM jieqi_article_stat WHERE articleid={$articleid} and mid = '{$mid}'";//每本书重复记录
			//累加，保留一个，删除其他的
			$articleRows = $this->db->selectsql ($sql1);
			if(!$articleRows)exit('没有重复记录需要处理');
			
			$this->out(++$q.',开始处理，书号：'.$articleid.',类型：'.$mid.',有'.count($articleRows).'条重复记录<br>');
			
			$newStat = $articleRows[0];
			unset($articleRows[0]);
			foreach ( $articleRows as $key => $stat ) {
				$newStat['total'] += $stat['total'];
				$newStat['month'] += $stat['month'];
				$newStat['week'] += $stat['week'];
				$newStat['day'] += $stat['day'];
				$newStat['totalnum'] += $stat['totalnum'];
				$newStat['monthnum'] += $stat['monthnum'];
				$newStat['weeknum'] += $stat['weeknum'];
				$newStat['daynum'] += $stat['daynum'];
				if($stat['lasttime'] > $newStat['lasttime']){
					$newStat['lasttime'] = $stat['lasttime'];
				}
				//删除$stat
				if($this->db->delete ( $stat['statid'] )){
					$this->out('删除成功，statid='.$stat['statid'].'<br>');
				}else{
					$this->out('删除失败，statid='.$stat['statid'].'<br>');
				}
			}
			//更新$newStat
			if($this->db->edit ( $newStat['statid'], $newStat )){
				$this->out('更新成功，statid='.$newStat['statid'].'<br>');
			}else{
				$this->out('更新失败，statid='.$newStat['statid'].'<br>');
			}
		}
		$this->out('结束...');
	}
}

?>