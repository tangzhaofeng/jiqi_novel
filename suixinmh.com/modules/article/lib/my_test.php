<?php 
/** 
 * article业务类继承了老版本的JieqiPackage类 * @copyright   Copyright(c) 2014 
 * @author      chengyuan* @version     1.0 
 */ 
include_once($GLOBALS['jieqiModules']['article']['path'].'/class/package.php');
class MyTest extends JieqiPackage{
	//public $myMessage = 'test variable';
	//public global  $jieqiLang;
	//public $controller;
	//public $lang;
	//public $package;
	//$package=new JieqiPackage($id);	
	function __construct() {
		//echo '__construc';
		//$this->getLang('article', 'article');
		//jieqi_loadlang('article', 'article');
	} 
	//如果需要使用package必须调用此先初始化package
	function initPackage($id){
		$this->JieqiPackage($id);
	}
	
	
	//获取下拉列表分类
	function  getSort(){
		//$this->db = Application::$_lib['database'];
		//$this->db = Application::$_lib['database'];
		//print_r($this->db);
		//print_r();
		//exit;
		global $jieqiLang,$jieqiConfigs;
		//$jieqiSort
		$data = array();
		//包含区块参数(定制区块)
		jieqi_getconfigs('article', 'authorblocks', 'jieqiBlocks');
		//$data['url_newarticle'] = $this->geturl('article','article','newArticle');
		//$jieqiTpl->assign('url_newarticle',);
		//jieqi_getconfigs('article', 'sort', 'jieqiSort');
		$this->addConfig('article', 'sort');
		$data['sortrows'] = $this->getConfig('article', 'sort');
		//$data['sortrows'] = $jieqiSort['article'];
		//$jieqiTpl->assign_by_ref('sortrows', );
		//jieqi_getconfigs('article', 'option', 'jieqiOption');
		$this->addConfig('article', 'option');
		$jieqiOption['article'] = $this->getConfig('article', 'option');
		foreach($jieqiOption['article'] as $k=>$v){
			//$jieqiTpl->assign_by_ref($k, $jieqiOption['article'][$k]);
			$data[$k] =$jieqiOption['article'][$k];
		}
		//$jieqiTpl->assign('authorarea', 1);
		//$jieqiTpl->setCaching(0);
		//$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/newarticle.html';
		//include_once(JIEQI_ROOT_PATH.'/footer.php');
		//$this->display($data,'newarticle'); 
		return $data;
	}
	
	
	
	//test
	public function newArticle1($data){
		
		//if(!$this->db->add($article)) jieqi_printfail($this->getLang('system', 'add_url_failure'));
		//else{}
		echo $this->myMessage;
		 $this->db = Application::$_lib['database'];
		 $this->db->init('article','articleid','article');
		 //print_r($this->db->get(4));exit;
		 $this->db->setCriteria();
		 //$this->db->criteria->setTables('jieqi_article_article');
		 //$this->db->criteria->add(new Criteria('initial', "D"));
		 $this->db->criteria->setSort('articleid');
		 $this->db->criteria->setOrder('DESC');
		 print_r($this->db->lists(20,$_REQUEST['page']));
		print_r($db);
	}
	
	
	//保存章节或者卷
	public function saveChapter(){
		$this->db = Application::$_lib['database'];
		$this->db->init('chapter','chapterid','article');
		//if(!defined('JIEQI_ROOT_PATH')) exit;
		//include_once($GLOBALS['jieqiModules']['article']['path'].'/class/chapter.php');
		//$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
		$newChapter = array();
		$newChapter['siteid']= JIEQI_SITE_ID;
		$chaptercount=$article->getVar('chapters');
		if(empty($volumeid)) $volumeid=$chaptercount+1;
		//如果是插入章节，则原来章节的序号加一位
		if($volumeid<=$chaptercount){
			$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid']));
			$criteria->add(new Criteria('chapterorder', $volumeid, '>='));
			$chapter_handler->updatefields('chapterorder=chapterorder+1', $criteria);
			unset($criteria);
		}
		
		$chaptersize=jieqi_strlen($_POST['chaptercontent']);
		$newChapter->setVar('articleid', $article->getVar('articleid', 'n'));
		$newChapter->setVar('articlename', $article->getVar('articlename', 'n'));
		$newChapter->setVar('volumeid', 0);
		if(!empty($_SESSION['jieqiUserId'])){
			$newChapter->setVar('posterid', $_SESSION['jieqiUserId']);
			$newChapter->setVar('poster', $_SESSION['jieqiUserName']);
		}else{
			$newChapter->setVar('posterid', 0);
			$newChapter->setVar('poster', '');
		}
		$newChapter->setVar('postdate', JIEQI_NOW_TIME);
		$newChapter->setVar('lastupdate', JIEQI_NOW_TIME);
		$newChapter->setVar('chaptername', $_POST['chaptername']);
		$newChapter->setVar('chapterorder', $volumeid);
		$newChapter->setVar('size', $chaptersize);
		if($_POST['chaptertype']==2){
			$newChapter->setVar('chaptertype', 1);
		}else{
			$newChapter->setVar('chaptertype', 0);
		}
		$newChapter->setVar('saleprice', 0);
		$newChapter->setVar('salenum', 0);
		$newChapter->setVar('totalcost', 0);
		$newChapter->setVar('attachment', $attachinfo);
		$newChapter->setVar('isvip', 0);
		$newChapter->setVar('power', 0);
		$display = 0;
		$newChapter->setVar('display', $display);
		if (!$chapter_handler->insert($newChapter)) jieqi_printfail($jieqiLang['article']['add_chapter_failure']);
		else {
			if($_POST['chaptertype'] != 2){
				//增加或插入章节，最新卷可能也会变化
				//暂时默认插入的章节就是本卷最后章节，否则最新章节可能不是插入的章节
				$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid']));
				$criteria->add(new Criteria('chapterorder', $volumeid, '<'));
				$criteria->add(new Criteria('chaptertype', 1, '='));
				$criteria->setSort('chapterorder');
				$criteria->setOrder('DESC');
				$criteria->setLimit(1);
				$chapter_handler->queryObjects($criteria);
				$tmpchapter=$chapter_handler->getObject();
				if(is_object($tmpchapter)){
					$lastvolume=$tmpchapter->getVar('chaptername', 'n');
					$lastvolumeid=$tmpchapter->getVar('chapterid', 'n');
				}else{
					$lastvolume='';
					$lastvolumeid=0;
				}
				unset($tmpchapter);
				unset($criteria);
		
				$article->setVar('lastchapter', $_POST['chaptername']);
				$article->setVar('lastchapterid', $newChapter->getVar('chapterid', 'n'));
				//插入章节时，卷也可能变化
				if($article->getVar('lastvolumeid') != $lastvolumeid){
					$article->setVar('lastvolume', $lastvolume);
					$article->setVar('lastvolumeid', $lastvolumeid);
				}
			}else{
				//增加分卷，最新卷可能也会变化
				$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid']));
				$criteria->add(new Criteria('chapterorder', $volumeid, '>'));
				$criteria->add(new Criteria('chaptertype', 0, '='));
				$criteria->setSort('chapterorder');
				$criteria->setOrder('DESC');
				$chapter_handler->queryObjects($criteria);
				$tmpchapter=$chapter_handler->getObject();
				if(is_object($tmpchapter)){
					if($tmpchapter->getVar('chapterid', 'n') == $article->getVar('lastchapterid', 'n')){
						$article->setVar('lastvolume', $_POST['chaptername']);
						$article->setVar('lastvolumeid', $newChapter->getVar('chapterid', 'n'));
					}
				}
				unset($tmpchapter);
				unset($criteria);
			}
			$article->setVar('chapters', $article->getVar('chapters')+1);
			$article->setVar('size', $article->getVar('size')+$chaptersize);
			if($_POST['chaptertype']==1){
				$article->setVar('fullflag', 1);
			}
			$article->setVar('lastupdate', JIEQI_NOW_TIME);
			$article_handler->insert($article);
			//更新最新文章
			if($_POST['chaptertype'] != 2 && $article->getVar('display')=='0'){
				jieqi_getcachevars('article', 'articleuplog');
				if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
				$jieqiArticleuplog['chapteruptime']=JIEQI_NOW_TIME;
				jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
			}
			
			//判断是否加水印
			$make_image_water = false;
			if(function_exists('gd_info') && $jieqiConfigs['article']['attachwater'] > 0 && JIEQI_MODULE_VTYPE != '' && JIEQI_MODULE_VTYPE != 'Free'){
				if(strpos($jieqiConfigs['article']['attachwimage'], '/')===false && strpos($jieqiConfigs['article']['attachwimage'], '\\')===false) $water_image_file = $GLOBALS['jieqiModules']['article']['path'].'/images/'.$jieqiConfigs['article']['attachwimage'];
				else $water_image_file = $jieqiConfigs['article']['attachwimage'];
				if(is_file($water_image_file)){
					$make_image_water = true;
					include_once(JIEQI_ROOT_PATH.'/lib/image/imagewater.php');
				}
			}
			//处理上传文件
			if($attachnum>0 && is_object($attachs_handler)){
				$attachs_handler->db->query("UPDATE ".jieqi_dbprefix('article_attachs')." SET chapterid=".$newChapter->getVar('chapterid')." WHERE articleid=".$article->getVar('articleid', 'n')." AND chapterid=0");
				$attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article');
				if (!file_exists($attachdir)) jieqi_createdir($attachdir);
				$attachdir .= jieqi_getsubdir($newChapter->getVar('articleid'));
				if (!file_exists($attachdir)) jieqi_createdir($attachdir);
				$attachdir .= '/'.$newChapter->getVar('articleid');
				if (!file_exists($attachdir)) jieqi_createdir($attachdir);
				$attachdir .= '/'.$newChapter->getVar('chapterid');
				if (!file_exists($attachdir)) jieqi_createdir($attachdir);
				foreach($attachary as $k=>$v){
					$attach_save_path = $attachdir.'/'.$infoary[$k]['attachid'].'.'.$infoary[$k]['postfix'];
					$tmp_attachfile = dirname($_FILES['attachfile']['tmp_name'][$v]).'/'.basename($attach_save_path);
					@move_uploaded_file($_FILES['attachfile']['tmp_name'][$v], $tmp_attachfile);
					//图片加水印
					if($make_image_water && eregi("\.(gif|jpg|jpeg|png)$",$tmp_attachfile)){
						$img = new ImageWater();
						$img->save_image_file = $tmp_attachfile;
						$img->codepage = JIEQI_SYSTEM_CHARSET;
						$img->wm_image_pos = $jieqiConfigs['article']['attachwater'];
						$img->wm_image_name = $water_image_file;
						$img->wm_image_transition  = $jieqiConfigs['article']['attachwtrans'];
						$img->jpeg_quality = $jieqiConfigs['article']['attachwquality'];
						$img->create($tmp_attachfile);
						unset($img);
					}
					jieqi_copyfile($tmp_attachfile, $attach_save_path, 0777, true);
				}
			}
			//////////////////////090712定制功能////////////////////////////////////
			//更新定制缓存
			include_once($jieqiModules['article']['path'].'/include/setarticlecache.php');
			jieqi_article_editcache('info', $_REQUEST['aid']);
			/////////////////////////////////////////////////////////
			//保存文章内容和生成html
			include_once($GLOBALS['jieqiModules']['article']['path'].'/class/package.php');
			$package=new JieqiPackage($article->getVar('articleid', 'n'));
			if($_POST['chaptertype']==2){
				$package->addChapter($newChapter->getVar('chapterid'), $_POST['chaptername'], $_POST['chaptercontent'], 1, $volumeid,$display,$newChapter->getVar('postdate'),$newChapter->getVar('lastupdate'),$chaptersize);
			}else{
				$package->addChapter($newChapter->getVar('chapterid'), $_POST['chaptername'], $_POST['chaptercontent'], 0, $volumeid,$display,$newChapter->getVar('postdate'),$newChapter->getVar('lastupdate'),$chaptersize);
			}
			if($from_draft) $draft_handler->delete($_REQUEST['draftid']);
			//增加章节积分
			jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
			$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
			$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		
			if(!empty($jieqiConfigs['article']['scorechapter'])){
				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
				$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scorechapter'], true);
				if($_REQUEST['userchappid']>0 && !empty($jieqiConfigs['article']['scoreauthuserchap'])) $users_handler->changeScore($_REQUEST['userchappid'], $jieqiConfigs['article']['scoreauthuserchap'], true);
			}
		
			jieqi_jumppage($article_static_url.'/articlemanage.php?id='.$_REQUEST['aid'], LANG_DO_SUCCESS, sprintf($jieqiLang['article']['add_chapter_success'], $article_static_url.'/articlemanage.php?id='.$_REQUEST['aid'], jieqi_geturl('article', 'article', $_REQUEST['aid'], 'info'), $article_static_url.'/newchapter.php?aid='.$_REQUEST['aid']));
		}
	}
} 
?>