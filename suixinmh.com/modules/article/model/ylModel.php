<?php 
/**
 * article模型
 * @author chengyuan  2014-4-4
 *
 */
class ylModel extends Model{ 

		
		/**
		 * 上书表单内的资源
		 * @return number
		 */
		function newArticleView(){
		    //载入自定义类 即公共方法类
			$articleLib =  $this->load('article',false);
			//公共的资源，比如：类别，分类
			$data =  $articleLib->getSources();
			if($this->checkpower($jieqiPower['article']['transarticle'],$this->getUsersStatus(), $this->getUsersGroup(), true)) $data['allowtrans'] = 1;
			else $data['allowtrans'] = 1;
			$data['authorarea'] = 1;
			//工具栏，原创文章区块
			jieqi_getconfigs('article', 'authorblocks', 'jieqiBlocks');
			return $data;
		}
		function test(){
//			$users_handler =  $this->getUserObject();//查询用户是否存在
//
//			 $ret=$users_handler->income(218329, 1, 0, 1, 3000);
//			 echo 'zx';
//			echo 'a';
//		$package =  $this->load('vipuptask','task');//加载VIP升级任务类
//		$package->isFinish(218329, 5000);//判断是否升级
//		echo 'ccccccccccccc';
//			$this->addConfig('pay','paytype');
//			$paytyperows = $this->getConfig('pay','paytype');
//	//		$this->addConfig('system','configs');
//	//		$jieqiConfigs['system'] = $this->getConfig('system','configs');
			$sql = "SELECT s.articleid,a.articlename,a.author,a.agent,s.username,s.addtime,s.stat,u.setting FROM `jieqi_article_statlog` as s right join 
`jieqi_article_article` as a on s.articleid = a.articleid right join `jieqi_system_users` as u on s.uid = u.uid WHERE `addtime`>=1430409600 and `addtime`<1433088000 and `mid`='vipvote'";
//			$sql = 'SELECT *FROM '.jieqi_dbprefix("pay_paylog").' WHERE buytime between 1422720000 and 1425139200 and payflag=1';
			$users = $this->db->selectsql ($sql);
			echo '<table>';
//			echo '<tr><th colspan=7>统计2014-8-1 00:00:00-2014-8-10 23:59:59注册的用户总数：'.count($users).'</th></tr>';
			echo '<tr><th>aid</th><th>aname</th><th>作者</th><th>责编</th><th>username</th><th>addtime</th><th>stat</th><th>setting</th></tr>';
//			$i = 0;
	//		$users_handler =  $this->getUserObject();//查询用户是否存在
	//		$this->db->init('message','messageid','system');
			foreach ( $users as $k => $v ) {//echo $k;
//				$i++;
//				$paytype = $paytyperows[$v['paytype']]['name']?$paytyperows[$v['paytype']]['name']:$v['paytype'];
$data[$k] = unserialize($v['setting']);//echo ;
				$temp = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s".$data[$k]['lastip']."</td></tr>";
				//print_r($data);$data[]['lastip']
				echo sprintf($temp,$v['articleid'],$v['articlename'],$v['author'],$v['agent'],$v['username'],date('Y-m-d H:i:s',$v['addtime']),$v['stat'],'');
			}
			echo '</table>';



//				$users_handler =  $this->getUserObject();
//				$ret=$users_handler->income(223898, 200);	//改造，不用income
////		$users_handler = $this->getUserObject();
////		$jieqiUsers = $users_handler->get(223898);//print_r($jieqiUsers);exit;
//		$uid = 223898;
//		$uname = 'txj1992';
//			
//		$this->db->init('message','messageid','system');
//		$newMessage = array();
//		$newMessage['siteid']= JIEQI_SITE_ID;
//		$newMessage['postdate']= JIEQI_NOW_TIME;
//		$newMessage['fromid']= 6;
//		$newMessage['fromname']= '系统管理员';
//		$newMessage['toid']= $uid;
//		$newMessage['toname']= $uname;
//		$newMessage['title']= '赠送书海币说明';
//		$newMessage['content']= '您好，感谢您及时向我们反馈客户端系统问题，特此送您200书海币以表感谢，请您查收！';
//		$newMessage['messagetype']= 0;
//		$newMessage['isread']= 0;
//		$newMessage['fromdel']= 0;
//		$newMessage['todel']= 0;
//		$newMessage['enablebbcode']= 1;
//		$newMessage['enablehtml']= 0;
//		$newMessage['enablesmilies']= 1;
//		$newMessage['attachsig']=0;
//		$newMessage['attachment']= 0;
//		$this->db->add($newMessage);	
		}
		/**
		 * 保存新书
		 */
		function newArticle(){
			$newArticle = array();
			$_REQUEST = $this->getRequest();
			global $jieqiLang,$jieqiConfigs,$jieqiSort;
			jieqi_loadlang('article', 'article');
			//handle article table
			$this->db->init('article','articleid','article');
			$this->db->setCriteria();
			$_REQUEST['articlename'] = trim($_REQUEST['articlename']);
			$_REQUEST['author'] = trim($_REQUEST['author']);
			$_REQUEST['agent'] = trim($_REQUEST['agent']);
			$errtext='';
			include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
			//检查标题
			if (strlen($_REQUEST['articlename'])==0) $errtext .= $jieqiLang['article']['need_article_title'].'<br />';
			elseif (!jieqi_safestring($_REQUEST['articlename'])) $errtext .= $jieqiLang['article']['limit_article_title'].'<br />';
			//检查标题和简介有没有违禁单词
			if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
			if(!empty($jieqiConfigs['system']['postdenywords'])){
				include_once(JIEQI_ROOT_PATH.'/include/checker.php');
				$checker = new JieqiChecker();
				$matchwords1 = $checker->deny_words($_REQUEST['articlename'], $jieqiConfigs['system']['postdenywords'], true);
				$matchwords2 = $checker->deny_words($_REQUEST['intro'], $jieqiConfigs['system']['postdenywords'], true);
				if(is_array($matchwords1) || is_array($matchwords2)){
					if(!isset($jieqiLang['system']['post'])) jieqi_loadlang('post', 'system');
					$matchwords=array();
					if(is_array($matchwords1)) $matchwords = array_merge($matchwords, $matchwords1);
					if(is_array($matchwords2)) $matchwords = array_merge($matchwords, $matchwords2);
					$errtext .= sprintf($jieqiLang['system']['post_words_deny'], implode(' ', jieqi_funtoarray('htmlspecialchars',$matchwords)));
				}
			}
			
			//检查封面
			$typeary=explode(' ',trim($jieqiConfigs['article']['imagetype']));
			foreach($typeary as $k=>$v){
				if(substr($v,0,1) != '.') $typeary[$k]='.'.$typeary[$k];
			}
			if (!empty($_FILES['articlespic']['name'])){
				$simage_postfix = strrchr(trim(strtolower($_FILES['articlespic']['name'])),".");
				if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$_FILES['articlespic']['name'])){
					if(!in_array($simage_postfix, $typeary)) $errtext .= sprintf($jieqiLang['article']['simage_type_error'], $jieqiConfigs['article']['imagetype']).'<br />';
				}else{
					$errtext .= sprintf($jieqiLang['article']['simage_not_image'], $_FILES['articlespic']['name']).'<br />';
				}
				if(!empty($errtext)) jieqi_delfile($_FILES['articlespic']['tmp_name']);
			}
			if (!empty($_FILES['articlelpic']['name'])){
				$limage_postfix = strrchr(trim(strtolower($_FILES['articlelpic']['name'])),".");
				if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$_FILES['articlelpic']['name'])){
					if(!in_array($limage_postfix, $typeary)) $errtext .= sprintf($jieqiLang['article']['limage_type_error'], $jieqiConfigs['article']['imagetype']).'<br />';
				}else{
					$errtext .= sprintf($jieqiLang['article']['limage_not_image'], $_FILES['articlelpic']['name']).'<br />';
				}
				if(!empty($errtext)) jieqi_delfile($_FILES['articlelpic']['tmp_name']);
			}
			if(empty($errtext)) {
				//include_once($jieqiModules['article']['path'].'/class/article.php');
				//$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
				//检查文章是否已经发表
				if($jieqiConfigs['article']['samearticlename'] != 1){
					$this->db->criteria->add(new Criteria('articlename', trim($_REQUEST['articlename']), '='));
					if($this->db->getCount() > 0){
						$this->printfail(sprintf($jieqiLang['article']['articletitle_has_exists'], jieqi_htmlstr($_REQUEST['articlename'])));
					} 
				}
				//users,online 使用老版本的功能
				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
				//$newArticle = $article_handler->create();
				$newArticle['siteid']= JIEQI_SITE_ID;
				$newArticle['postdate']= JIEQI_NOW_TIME;
				$newArticle['lastupdate']= JIEQI_NOW_TIME;
				$newArticle['articlename']= $_REQUEST['articlename'];
				$newArticle['keywords']= trim($_REQUEST['keywords']);
				$newArticle['initial']= jieqi_getinitial($_REQUEST['articlename']);
				$agentobj=false;
				if(!empty($_REQUEST['agent'])) $agentobj=$users_handler->getByname($_REQUEST['agent'],3);
				if(is_object($agentobj)){
					$newArticle['agentid'] = $agentobj->getVar('uid');
					$newArticle['agent'] =  $agentobj->getVar('uname', 'n');
				}else{
					$newArticle['agentid'] = 0;
					$newArticle['agent'] =   '';
				}
				if($this->checkpower($jieqiPower['article']['transarticle'], $this->getUsersStatus(), $this->getUsersGroup(), true)){
					//允许转载的情况
					if(empty($_REQUEST['author']) || (!empty($_SESSION['jieqiUserId']) && $_REQUEST['author']==$_SESSION['jieqiUserName'])){
						if(!empty($_SESSION['jieqiUserId'])){
							$newArticle['authorid'] =$_SESSION['jieqiUserId'];
							$newArticle['author'] =  $_SESSION['jieqiUserName'];
						}else{
							//$newArticle->setVar('authorid', 0);
							//$newArticle->setVar('author', '');
							$newArticle['authorid'] = 0;
							$newArticle['author'] = '';
						}
					}else{
						//转载作品
						$newArticle['author'] = $_REQUEST['author'];
						if($_REQUEST['authorflag']){
							$authorobj=$users_handler->getByname($_REQUEST['author'],3);
							if(is_object($authorobj)) $newArticle['authorid'] = $authorobj->getVar('uid');
							else $newArticle['authorid'] =  0;
						}else{
							//$newArticle->setVar('authorid', 0);
							$newArticle['authorid'] =  0;
						}
					}
					//$newArticle->setVar('permission', $_REQUEST['permission']);
					$newArticle['permission'] =  $_REQUEST['permission'];
					$newArticle['signdate'] =  JIEQI_NOW_TIME;
					//$newArticle->setVar('signdate', JIEQI_NOW_TIME);
				}else{
					//不允许转载的情况
					if(!empty($_SESSION['jieqiUserId'])){
						//$newArticle->setVar('authorid', $_SESSION['jieqiUserId']);
						//$newArticle->setVar('author', $_SESSION['jieqiUserName']);
						
						$newArticle['authorid'] =  $_SESSION['jieqiUserId'];
						$newArticle['author'] =  $_SESSION['jieqiUserName'];
					}else{
						//$newArticle->setVar('authorid', 0);
						//$newArticle->setVar('author', '');
						
						$newArticle['authorid'] =  0;
						$newArticle['author'] ='';
					}
				}
				if(!empty($_SESSION['jieqiUserId'])){
					$newArticle['posterid']= $_SESSION['jieqiUserId'];
					$newArticle['poster']= $_SESSION['jieqiUserName'];
				}else{
					$newArticle['posterid']=0;
					$newArticle['poster']='';
				}
	
				$newArticle['lastchapterid']=0;
				$newArticle['lastchapter']= '';
				//$newArticle['lastvolumeid']= 0;
				$newArticle['lastvolume']= '';
				$newArticle['chapters']= 0;
				$newArticle['size']= 0;
				$newArticle['fullflag']= 0;
				$newArticle['sortid']= intval($_REQUEST['sortid']);
				$newArticle['typeid']= intval($_REQUEST['typeid']);
				$newArticle['intro']= $_REQUEST['intro'];
				$newArticle['notice']= $_REQUEST['notice'];
				$newArticle['setting']= '';
				//$newArticle['lastvisit']= 0;
				//$newArticle['dayvisit']= 0;
				//$newArticle['weekvisit']= 0;
				//$newArticle['monthvisit']= 0;
				//$newArticle['allvisit']= 0;
				//$newArticle['lastvote']= 0;
				//$newArticle['dayvote']= 0;
				//$newArticle['weekvote']= 0;
				//$newArticle['monthvote']= 0;
				//$newArticle['allvote']= 0;
				//$newArticle['goodnum']= 0;
				//$newArticle['badnum']= 0;
				//$newArticle['toptime']= 0;
				//$newArticle['saleprice']= 0;
				//$newArticle['salenum']= 0;
				//$newArticle['totalcost']= 0;
				//$newArticle['power']= 0;
				$newArticle['articletype']= 0;
				$newArticle['firstflag']= $_REQUEST['firstflag'] ? $_REQUEST['firstflag'] :1;
				$imgflag=0;
				$imgtary=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
				if (!empty($_FILES['articlespic']['name'])){
					$imgflag = $imgflag | 1;
					$tmpvar = intval(array_search($simage_postfix, $imgtary));
					if($tmpvar > 0) $imgflag = $imgflag | ($tmpvar * 4);
				}
				if (!empty($_FILES['articlelpic']['name'])){
					$imgflag =$imgflag | 2;
					$tmpvar = intval(array_search($limage_postfix, $imgtary));
					if($tmpvar > 0) $imgflag = $imgflag | ($tmpvar * 32);
				}
				$newArticle['imgflag']=$imgflag;
				if($this->checkpower($jieqiPower['article']['needcheck'], $this->getUsersStatus(), $this->getUsersGroup(), true)){
					$newArticle['display']= 0;
				}else{
					$newArticle['display']= 1;  //待审文章
				}
				$id = $this->db->add($newArticle);
				if(!$id){
					 $this->printfail($jieqiLang['article']['article_add_failure']);
				}else {
					//$id=$newArticle->getVar('articleid');
					//加载自定义类
					$articleLib = $this->load('article',false);
					$articleLib->initPackage($id);//实例化Package
					//include_once($jieqiModules['article']['path'].'/class/package.php');
					$articleLib->initPackage(array('id'=>$newArticle['articleid'], 'title'=>$newArticle['articlename'], 'creatorid'=>$newArticle['authorid'], 'creator'=>$newArticle['author'], 'subject'=>$newArticle['keywords'], 'description'=>$newArticle['intro'], 'publisher'=>JIEQI_SITE_NAME, 'contributorid'=>$newArticle['posterid'], 'contributor'=>$newArticle['poster'], 'sortid'=>$newArticle['sortid'], 'typeid'=>$newArticle['typeid'], 'articletype'=>$newArticle['articletype'], 'permission'=>$newArticle['permission'], 'firstflag'=>$newArticle['firstflag'], 'fullflag'=>$newArticle['fullflag'], 'imgflag'=>$newArticle['imgflag'],'display'=>$newArticle['display']));
					//保存小图
					if (!empty($_FILES['articlespic']['name'])){
						jieqi_copyfile($_FILES['articlespic']['tmp_name'], $articleLib->getDir('imagedir').'/'.$id.'s'.$simage_postfix, 0777, true);
					}
					//保存大图
					if (!empty($_FILES['articlelpic']['name'])){
						jieqi_copyfile($_FILES['articlelpic']['tmp_name'], $articleLib->getDir('imagedir').'/'.$id.'l'.$limage_postfix, 0777, true);
					}
					//增加发文积分
					if(!empty($jieqiConfigs['article']['scorearticle'])){
						$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scorearticle'], true);
					}
					//更新最新入库
					if($newArticle['display']==0){
						jieqi_getcachevars('article', 'articleuplog');
						if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
						$jieqiArticleuplog['articleuptime']=JIEQI_NOW_TIME;
						jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
					}
					$this->jumppage($this->geturl('article','article','method=articleManage','id='.$id), LANG_DO_SUCCESS, $jieqiLang['article']['article_add_success']);
				}
			}else{
				$this->printfail($errtext);
			}
		}
		
		//我的文章列表
		function myArticleList(){
			global $jieqiLang,$jieqiConfigs;
			$data = array();
			$_REQUEST = $this->getRequest();
			$this->checkpower($jieqiPower['article']['authorpanel'], $this->getUsersStatus(), $this->getUsersGroup(), false);
			jieqi_loadlang('list', 'article');
			jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
			jieqi_getconfigs('article', 'configs');
			//页码
			$this->db->init('article','articleid','article');
			$this->db->setCriteria();
			$articletitle=$jieqiLang['article']['my_all_article'];
			if(empty($_REQUEST['display'])) $_REQUEST['display']='all';
			switch($_REQUEST['display']){
				case 'author':
					$this->db->criteria->add(new Criteria('authorid', $_SESSION['jieqiUserId'], '='));
					$articletitle=$jieqiLang['article']['my_post_article'];
					break;
				case 'poster':
					$this->db->criteria->add(new Criteria('authorid', $_SESSION['jieqiUserId'], '!='));
					$this->db->criteria->add(new Criteria('posterid', $_SESSION['jieqiUserId'], '='));
					$articletitle=$jieqiLang['article']['my_trans_article'];
					break;
				case 'agent':
					$this->db->criteria->add(new Criteria('authorid', $_SESSION['jieqiUserId'], '!='));
					$this->db->criteria->add(new Criteria('posterid', $_SESSION['jieqiUserId'], '!='));
					$this->db->criteria->add(new Criteria('agentid', $_SESSION['jieqiUserId'], '='));
					$articletitle=$jieqiLang['article']['my_agent_article'];
					break;
				case 'all':
				default:
					$this->db->criteria->add(new Criteria('authorid', $_SESSION['jieqiUserId'], '='), 'OR');
					$this->db->criteria->add(new Criteria('posterid', $_SESSION['jieqiUserId'], '='), 'OR');
					$this->db->criteria->add(new Criteria('agentid', $_SESSION['jieqiUserId'], '='), 'OR');
					$articletitle=$jieqiLang['article']['my_all_article'];
			}
			
			$data['articletitle'] =  $articletitle;
			//$data['url_article'] = '/masterpage.php';
			//$this->db->criteria->setSort('initial ASC,articlename');
			$this->db->criteria->setSort('postdate');
			$this->db->criteria->setOrder('desc');
			$data['articlerows'] = $this->db->lists($jieqiConfigs['article']['pagenum'],$_REQUEST['page']);
			//处理页面跳转
			$data['url_jumppage'] = $this->db->getPage();
			$data['authorarea'] = 1;
			return $data;
		}
		
		/**
		 * 文章管理
		 * @param unknown $id
		 * @return multitype:NULL string unknown number Ambigous <multitype:, string>
		 */
		function articleManage($id){ 
			global $jieqiLang,$jieqiConfigs,$jieqiSort;
			$data = array();
			if(empty($id)) $this->printfail(LANG_ERROR_PARAMETER);
			jieqi_loadlang('manage', 'article');
			//include_once($jieqiModules['article']['path'].'/class/article.php');
			//$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
			$this->db->init('article','articleid','article');
			$article=$this->db->get($id);
			if(!$article) $this->printfail($jieqiLang['article']['article_not_exists']);
			//检查权限
			//jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
			//管理别人文章权限
			$canedit=$this->checkpower($jieqiPower['article']['manageallarticle'], $this->getUsersStatus(), $this->getUsersGroup(), true);
			if(!$canedit && !empty($_SESSION['jieqiUserId'])){
				//除了斑竹，作者、发表者和代理人可以修改文章
				$tmpvar=$_SESSION['jieqiUserId'];
				if($tmpvar>0 && ($article['authorid']==$tmpvar || $article['posterid']==$tmpvar || $article['agentid']==$tmpvar)){
					$canedit=true;
				}
			}
			if(!$canedit) jieqi_printfail($jieqiLang['article']['noper_manage_article']);
			//包含区块参数
			jieqi_getconfigs('article', 'authorblocks', 'jieqiBlocks');
			jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
			jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
			//include_once(JIEQI_ROOT_PATH.'/header.php');
			//$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
			//$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
			//$jieqiTpl->assign('article_static_url',$article_static_url);
			//$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
			
			//采集
			$setting=unserialize($article['setting']);	$url_collect=$article_static_url.'/admin/collect.php?toid='.$article['articleid'];
			if(is_numeric($setting['fromarticle'])) $url_collect.='&fromid='.$setting['fromarticle'];
			if(is_numeric($setting['fromsite'])) $url_collect.='&siteid='.$setting['fromsite'];
			//$jieqiTpl->assign('url_collect', $url_collect);
			$data['url_collect'] = $url_collect;
			//文章属性
			//$jieqiTpl->assign('articleid', $article->getVar('articleid'));
//			$jieqiTpl->assign('articlename', $article->getVar('articlename'));
//			$jieqiTpl->assign('authorid', $article->getVar('authorid'));
//			$jieqiTpl->assign('author', $article->getVar('author'));
//			$jieqiTpl->assign('url_articleinfo', jieqi_geturl('article', 'article', $article->getVar('articleid'), 'info'));
//			$jieqiTpl->assign('url_articleindex', jieqi_geturl('article', 'article', $article->getVar('articleid'), 'index'));
			$data['articleid'] = $article['articleid'];
			$data['articlename'] = $article['articlename'];
			$data['authorid'] = $article['authorid'];
			$data['author'] = $article['author'];
			$data['url_articleinfo'] =  jieqi_geturl('article', 'article', $article['articleid'], 'info');
			$data['url_articleindex'] = jieqi_geturl('article', 'article', $article['articleid'], 'index');
			
			if(!is_numeric($jieqiConfigs['article']['articlevote'])) $jieqiConfigs['article']['articlevote']=0;
			else $jieqiConfigs['article']['articlevote']=intval($jieqiConfigs['article']['articlevote']);
			//$jieqiTpl->assign('articlevote', $jieqiConfigs['article']['articlevote']);
			$data['articlevote'] =$jieqiConfigs['article']['articlevote'];
			
			
			
			//章节属性
			$this->db->init('chapter','chapterid','article');
			//include_once($jieqiModules['article']['path'].'/class/chapter.php');
			//$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
			$this->db->setCriteria();
			$this->db->criteria->add(new Criteria('articleid',$id, '='));
			//$id$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['id'], '='));
			$this->db->criteria->setSort('chapterorder');
			$this->db->criteria->setOrder('ASC');
			//$chapter_handler->queryObjects($criteria);
			$this->db->queryObjects();
			$i=0;
			$chapterrows=array();
			$k=0;
			while($chapter = $this->db->getObject()){
				$chapterrows[$k]['chapterid'] = $chapter->getVar('chapterid');
				$chapterrows[$k]['chaptertype'] = $chapter->getVar('chaptertype');
				$chapterrows[$k]['chaptername'] = $chapter->getVar('chaptername');
				$chapterrows[$k]['chapterorder'] = $chapter->getVar('chapterorder');
				$chapterrows[$k]['display'] = $chapter->getVar('display');
				$chapterrows[$k]['url_chapterread'] = $article_static_url.'/reader.php?aid='.$article['articleid'].'&cid='.$chapter->getVar('chapterid');
				if($chapter->getVar('chaptertype')==0){
					//chapter
					$chapterrows[$k]['url_chapterread'] = jieqi_geturl('article', 'chapter', $chapter->getVar('chapterid'), $article['articleid']);
					//$chapterrows[$k]['url_chapteredit'] = $article_static_url.'/chapteredit.php?id='.$chapter->getVar('chapterid').'&chaptertype=0';
					$chapterrows[$k]['url_chapteredit'] = $this->getUrl('article','chapter','method=editChapterView','cid='.$chapter->getVar('chapterid'));
					$chapterrows[$k]['url_chapterdelete'] = $article_static_url.'/chapterdel.php?id='.$chapter->getVar('chapterid').'&chaptertype=0';
				}else{
					//volume
					$chapterrows[$k]['url_chapterread'] = $article_static_url.'/showvolume.php?aid='.$article['articleid'].'&vid='.$chapter->getVar('chapterid');
					//$chapterrows[$k]['url_chapteredit'] = $article_static_url.'/chapteredit.php?id='.$chapter->getVar('chapterid').'&chaptertype=1';
					$chapterrows[$k]['url_chapteredit'] = $this->getUrl('article','volume','method=editVolumeView','vid='.$chapter->getVar('chapterid'));
					$chapterrows[$k]['url_chapterdelete'] = $article_static_url.'/chapterdel.php?id='.$chapter->getVar('chapterid').'&chaptertype=1';
				}
				$k++;
			}
			//$jieqiTpl->assign_by_ref('chapterrows', $chapterrows);
			$data['chapterrows'] = $chapterrows;
			//管理章节显示/隐藏
			//$jieqiTpl->assign('editchapterstatus', $this->checkpower($jieqiPower['article']['editchapterstatus'], $this->getUsersStatus(), $this->getUsersGroup(), true));
			$data['editchapterstatus'] = $this->checkpower($jieqiPower['article']['editchapterstatus'], $this->getUsersStatus(), $this->getUsersGroup(), true);
			//功能属性
//			$jieqiTpl->assign('url_chaptersort', $article_static_url.'/chaptersort.php?do=submit');
//			$jieqiTpl->assign('url_chaptersdel', $article_static_url.'/chaptersdel.php?do=submit');
//			$jieqiTpl->assign('url_repack', $article_static_url.'/repack.php?do=submit');
			$data['url_chaptersort'] = $article_static_url.'/chaptersort.php?do=submit';
			$data['url_chaptersdel'] = $article_static_url.'/chaptersdel.php?do=submit';
			$data['url_repack'] = $article_static_url.'/repack.php?do=submit';
			
			$packflag = array();
			if($jieqiConfigs['article']['makeopf']) $packflag[] = array('value'=>'makeopf', 'title'=>$jieqiLang['article']['repack_opf']);
			if($jieqiConfigs['article']['makehtml']) $packflag[] = array('value'=>'makehtml', 'title'=>$jieqiLang['article']['repack_html']);
			if($jieqiConfigs['article']['makezip']) $packflag[] = array('value'=>'makezip', 'title'=>$jieqiLang['article']['repack_zip']);
			if($jieqiConfigs['article']['makefull']) $packflag[] = array('value'=>'makefull', 'title'=>$jieqiLang['article']['repack_fullpage']);
			if($jieqiConfigs['article']['maketxtfull']) $packflag[] = array('value'=>'maketxtfull', 'title'=>$jieqiLang['article']['repack_txtfullpage']);
			if($jieqiConfigs['article']['makeumd']) $packflag[] = array('value'=>'makeumd', 'title'=>$jieqiLang['article']['repack_umdpage']);
			if($jieqiConfigs['article']['makejar']) $packflag[] = array('value'=>'makejar', 'title'=>$jieqiLang['article']['repack_jarpage']);
			//$jieqiTpl->assign_by_ref('packflag', $packflag);
			$data['packflag'] = $packfla;
			//$jieqiTpl->assign('authorarea', 1);
			$data['authorarea'] = 1;
			//$jieqiTpl->setCaching(0);
			//$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/articlemanage.html';
			//include_once(JIEQI_ROOT_PATH.'/footer.php');
			
			
			return $data;
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
        function getArticles123(){ 
			 $tt =  $this->load('article',false);
			 //$tt->init($this);
			 $tt->newArticle();
			 exit;
             $this->db->init('article','articleid','article');
			 //print_r($this->db->get(4));exit;
			 $this->db->setCriteria();
			 //$this->db->criteria->setTables('jieqi_article_article');
			 //$this->db->criteria->add(new Criteria('initial', "D"));
			 $this->db->criteria->setSort('articleid');
	         $this->db->criteria->setOrder('DESC');
			 return array(
			     'data'=>$this->db->lists(20,$_REQUEST['page']),
				 'jumppage'=>$this->db->getPage()
			 );
        } 
		
		 function getArticles(){ 
			 $tt =  $this->load('article',false);
			 //$tt->init($this);
			 $tt->newArticle();
        } 
		
		/**
		 * 获取文章属性信息
		 * @param 小说ID $id
		 * @return multitype:NULL string unknown number Ambigous <multitype:, string>
		 */
		function getArticleInfo($id){ 
		
		    //载入自定义类 即公共方法类
			$articleLib =  $this->load('article',false);
			//获取分类列表key:sort，管理授权key:，授权级别key:，首发状态key:
			$data =  $articleLib->getSources();
			$this->db->init('article','articleid','article');
			$article=$this->db->get($id);
		}
} 

?>