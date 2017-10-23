<?php
/**
 * 后台系统管理->渠道管理控制器
 * @author chengyuan  2014-6-12
 *
 */
class channelModel extends Model{

	/**
	 * 列表
	 * @param unknown $params
	 * @return multitype:unknown
	 */
	public function main($params = array()){
		$this->addConfig('pooling','configs');
		$this->addConfig('pooling','power');
		$_PAGE['apiurl'] = $this->getConfig('pooling','configs','apiurl');
		$_PAGE['apijoinurl'] = $this->getConfig('pooling','configs','apijoinurl');
		//管理所有渠道权限
		$_PAGE['manageallarticle'] = $this->checkpower($this->getConfig('pooling','power','manageallarticle'), $this->getUsersStatus (), $this->getUsersGroup (), true);
	    //初始化标签对象
		$channel = $this->load('channel', 'pooling');
		$channel->setCriteria();
// 		$channel->criteria->add(new Criteria('siteid', JIEQI_SITE_ID));
		if(!$this->checkisadmin() && !$_PAGE['manageallarticle']){//非管理员，查询自己负责的渠道
			$auth = $this->getAuth();
			$uid = $this->getFormat($auth['uid'],'q');
			$channel->criteria->add(new Criteria('FIND_IN_SET('.$uid, '',',uid)'));
		}
		
		//传媒公司渠道 特殊处理
		//2015-5-12 add chengyuan
		$articleLib = $this->load('article','article');
		if($articleLib->mediaUser()){
			//屏蔽掉书海渠道
			$channel->criteria->add(new Criteria('channelid', '62','!='));
		}
		
		if($params['keyword']){
			$channel->criteria->add(new Criteria('channelname', '%'.$params['keyword'].'%', 'LIKE'));
			$_PAGE['keyword']=$params['keyword'];
		}
		$channel->criteria->setSort('listorder,type');
		$channel->criteria->setOrder('ASC');
		$_PAGE['rows'] = $channel->lists(30, $params['page']);
		foreach($_PAGE['rows'] as $k=>$v){
			eval('$setting = '.$v['setting'].';');//字符串转数组
			$v['setting'] = $setting;
			if($v['uid']){
				//
				//SELECT IF( realName !='', GROUP_CONCAT(realName), GROUP_CONCAT(uname) ) AS unames FROM `jieqi_system_users`
// 				$resutl = $this->db->selectsql ("SELECT GROUP_CONCAT(uname) AS unames FROM `jieqi_system_users` WHERE uid in ({$v['uid']})");
				$resutl = $this->db->selectsql ("SELECT IF( realName !='', GROUP_CONCAT(realName), GROUP_CONCAT(uname) ) AS unames FROM `jieqi_system_users` WHERE uid in ({$v['uid']})");
				$v['unames'] = $resutl[0]['unames'];
			}
			$_PAGE['rows'][$k] = $v;
		}
		$_PAGE['url_jumppage'] = $channel->getPage();
		if($_PAGE['manageallarticle']){
			$_PAGE['agents'] = $this->getUsers();
		}
		return array('_PAGE'=>$_PAGE);
	}
	/**
	 * 获取所有可以进入渠道管理的用户列表
	 */
	private function getUsers(){
		$this->addConfig('pooling','power');
		$power = $this->getConfig('pooling','power','authorpanel');
		if($power['groups']){
			$group_array = $power['groups'];
			if(is_array($group_array)){
				$this->db->init('users','uid','system');
				$this->db->setCriteria();
				$this->db->criteria->setFields("uid,uname,realName,name,groupid");
				foreach($group_array as $k=>$groupid){
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
	 * 分配渠道管理员
	 * @param unknown $uids	uid array
	 */
	public function schedule($channelid,$uids){
		if($this->submitcheck() && $channelid){
			if(is_array($uids) && !empty($uids)){
				$uids_str = implode(',',$uids);
			}
			$channel = $this->load('channel', 'pooling');//加载channel自定义类
			if(!$channel->edit($channelid,array('uid'=>$uids_str))){
				$this->printfail(LANG_ERROR_PARAMETER);
			}else{
				$this->jumppage(null,null);
			}
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	}
	/**
	 * 删除
	 * @param unknown $params
	 */
	public function del($params = array()){
		if($params['cid']){
			$_OBJ['channel'] = $this->load('channel', 'pooling');
			if($_OBJ['channel']->delete($params['cid'])){
				//删除 数据池文章，章节
				$this->db->init ( 'article', 'paid', 'pooling' );
				$this->db->setCriteria(new Criteria('channelid', $params['cid']));
				if($this->db->delete( $this->db->criteria )){
					$this->db->init ( 'chapter', 'pcid', 'pooling' );
					$this->db->setCriteria(new Criteria('channelid', $params['cid']));
					if($this->db->delete( $this->db->criteria )){
						$this->jumppage($this->getAdminurl('channel','','pooling'));
					}else{
						$this->printfail('章节删除失败');
					}
				}else{
					$this->printfail('渠道删除失败');
				}
			}else{
				$this->printfail('渠道删除失败');
			}
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	}
	/**
	 * 排序
	 * @param unknown $params
	 * 2014-6-12
	 */
	public function order($params = array()){
	    $_OBJ['channel'] = $this->load('channel', 'pooling');
		if($this->submitcheck()){
			 if($_OBJ['channel']->order($_OBJ['channel']->order, $params['order'])){
			     $_OBJ['channel']->cache();//更新缓存
			     jieqi_jumppage($this->getAdminurl('channel','','pooling'));
			 }else jieqi_printfail();
		}
	}
	/**
	 * 新增，修改和相关视图跳转
	 * @param unknown $params
	 * @return multitype:string unknown
	 */
	public function add($params = array()){
		$this->db->init( 'channel', 'channelid', 'pooling' );
		$_OBJ['channel'] = $this->load('channel', 'pooling');//加载channel自定义类
		//add or edit
		if($this->submitcheck()){
		//print_r($params);exit;
			 //如果是自定义区块，则优先处理
			 /*if($_REQUEST['setting']['custom']){

				 if($block=$blocks_handler->get($_REQUEST['setting']['bid'])){
					 //自定义内容
					 if($block->getVar('canedit')==1){
						 $block->setVar('content', $_REQUEST['setting']['content']);
					 }
				 }
				 if($blocks_handler->insert($block)){
				   $blocks_handler->saveContent($block->getVar('bid'), $block->getVar('modname'), JIEQI_CONTENT_HTML, $_REQUEST['setting']['content']);
				 }
				 $_REQUEST['setting']['content'] = '';
			 }*/
			 $data = $params['info'];
			 $data['editdate'] = JIEQI_NOW_TIME;
			 
			 //regexp handle
			 preg_match_all ( "/(.+)=(.+)|\\".PHP_EOL."/", trim($params['setting']['getdata']['category']), $matches );
			 if($matches[1] && $matches[2]){
			 	$params['setting']['getdata']['category'] = array_filter(array_combine($matches[1],$matches[2]));
			 }else{
			 	$params['setting']['getdata']['category'] = '';
			 }
			 //split handle 
			 // 			 $category = array();
			 // 			 foreach(explode(PHP_EOL,$params['setting']['getdata']['category']) as $k=>$v){
			 // 			 	$tmp = explode("=",$v);
			 // 			 	$category[$tmp[0]] = $tmp[1];
			 // 			 }
			 // 			 $params['setting']['getdata']['category'] = $category;
			 
			 
			 
			 $data['setting'] = ($this->arrayeval($params['setting']));
			 //addslashes_array
			 //更新数据
			 if($params['cid']){//channelid 更新
				 $statu = $_OBJ['channel']->edit($params['cid'],$data); //修改
				 $cid = $params['cid'];
			 }else{
			 	 $data['postdate'] = JIEQI_NOW_TIME;
				 $statu = $_OBJ['channel']->add($data);//增加
				 $cid = $statu;
			 }
			 //消息
			 if($statu){
				$_OBJ['channel']->cacheOne($cid);
				jieqi_jumppage($this->getAdminurl('channel','','pooling'));
			 } else jieqi_printfail();
		}

		////////////////////////////构造表单//////////////////////////////
		//如果修改状态
		if($params['cid']){
			 //获取修改栏目内容
			 $_SGLOBAL['channel'] = $_OBJ['channel']->get($params['cid']);
// 			 print_r($_SGLOBAL['channel']);exit;
		}else{//添加状态
			$_SGLOBAL['position']['type'] = $params['type'];
			if($_SGLOBAL['position']['type']!=2) $_SGLOBAL['position']['setting']['bid'] = $params['bid'];
		}
		if($params['step']){
			//添加数据表单
			//取得设置
			$this->db->setCriteria(new Criteria('custom',0,'='));
			$this->db->criteria->setSort('weight');
			$this->db->criteria->setOrder('ASC');
			$this->db->queryObjects($criteria);
			$blockary = array();
			while($v = $this->db->getObject()){
				$blockary[$k]['bid']=$v->getVar('bid');
				$blockary[$k]['blockname']=$v->getVar('blockname');
				$blockary[$k]['modname']=$v->getVar('modname', 'n');
				//$blockary[$k]['side']=$blocks_handler->getSide($v->getVar('side', 'n'));
				$blockary[$k]['weight']=$v->getVar('weight');
				//$blockary[$k]['weight']=$v->getVar('weight');
				//$blockary[$k]['template']=$blocks_handler->getPublish($v->getVar('template', 'n'));
				$k++;
			}
			$_PAGE['block'] = $blockary;
		}
		if($_SGLOBAL['position']['type']==1){//查询区块
		     $this->db->setCriteria(new Criteria('bid', $_SGLOBAL['position']['setting']['bid']));
			 if(($block = $this->db->get($this->db->criteria))){//echo $_SGLOBAL['position']['setting']['bid'];
				 //$_SGLOBAL['position']['setting'] = array();
				 foreach($block->vars as $k=>$v){
					 if(in_array($k,array('template', 'vars')) && $params['posid']) continue;
					 $_SGLOBAL['position']['setting'][$k] = $block->getVar($k,'n');
				 }
				 //$_SGLOBAL['position']['setting']['filename'] = $block->getVar('filename','n');
				 //$_SGLOBAL['position']['setting']['description'] = $block->getVar('description','n');
				 $_SGLOBAL['position']['setting']['module'] = $block->getVar('modname','n');
			 }
		}

		//设置默认排序权值
		$_SGLOBAL['position']['listorder'] = $_SGLOBAL['position']['listorder'] ?$_SGLOBAL['position']['listorder'] :'0';
		//设置默认模板
		if(!$_SGLOBAL['position']['setting']['template']){
			switch($_SGLOBAL['position']['type']){
				case '2':
					 $_SGLOBAL['position']['setting']['template'] = 'block_content.html';
				break;
			}
		}
		$this->addConfig('article','option');
		$this->jieqiConfigs['option'] = $this->getConfig('article','option');
		$_SGLOBAL['position']['firstflag'] = $this->jieqiConfigs['option']['firstflag'];
		return array('_PAGE'=>$_PAGE,'_SGLOBAL'=>$_SGLOBAL);
	}
	/**
	 * 删除推送的文章，函数会自动处理id和id[]数组删除方式
	 * @param unknown $channelid	渠道ID
	 * @param unknown $id	id或者id数组
	 * 2014-6-13 下午1:27:38
	 */
	public function delArticle($channelid,$id){
		if(!$id || !$channelid) $this->printfail(LANG_ERROR_PARAMETER);
		$ids = array();//存放待删除的文章ID容器
		if(!is_array($id))  $ids[] = $id;
		else  $ids = $id;
		foreach($ids as $id){
			$this->db->init('article', 'paid', 'pooling');
			if($article = $this->db->get($id)){
				$this->db->delete($id);
				//todo 2014-11-17添加功能
				//记录数据池文章删除日志
				$this->db->init ( 'articlelog', 'logid', 'article' );
				$newlog = array ();
				$newlog ['siteid'] = JIEQI_SITE_ID;
				$newlog ['logtime'] = JIEQI_NOW_TIME;
				$newlog ['userid'] = $_SESSION ['jieqiUserId'];
				$newlog ['username'] = $_SESSION ['jieqiUserName'];
				$newlog ['articleid'] = $article ['articleid'];
				$newlog ['articlename'] = $article ['articlename'];
				$newlog ['chapterid'] = 0;
				$newlog ['chaptername'] = '';
				$newlog ['reason'] = '';
				$newlog ['chginfo'] = '删除数据池文章';
				$newlog ['chglog'] = '';
				$newlog ['ischapter'] = '0';
				$newlog ['isdel'] = '1';
				$newlog ['databak'] = serialize ( $article );
				$this->db->add ( $newlog );
			}
		}
		//删除缓冲池的章节
		//2014-9-23添加
		$this->db->init('chapter', 'pcid', 'pooling');
		foreach($ids as $id){
			$this->db->setCriteria ( new Criteria ( 'paid',$id ) );
			$this->db->criteria->add(new Criteria('channelid', $channelid));
			$this->db->delete ( $this->db->criteria );
		}
		$this->jumppage($this->getAdminurl('channel','method=pushView&cid='.$channelid,'pooling'));
	}
	/**
	 * 解禁，封禁
	 * @param unknown $channelid	渠道id
	 * @param unknown $id			文章id或id[]数组
	 * @param unknown $statu		解禁，封禁
	 * 2014-6-13 下午2:24:38
	 */
	public function editStatu($channelid,$id,$statu){
		if(!$channelid || !$id || !isset($statu)) $this->printfail(LANG_ERROR_PARAMETER);
		$this->db->init('article', 'paid', 'pooling');
		$ids = array();//存放待删除的文章ID容器
		if(!is_array($id))  $ids[] = $id;
		else  $ids = $id;
		foreach($ids as $id){
			$this->db->edit($id,array('pushflag'=>$statu));
		}
		$this->jumppage($this->getAdminurl('channel','method=pushView&cid='.$channelid,'pooling'));
	}
	/**
	 * 批量设置推送的文章
	 * @param unknown $cid
	 * @param unknown $aids	1,2,3,4,5格式
	 * 2014-6-12 下午2:59:09
	 */
	public function pushArticles($cid,$aids,$statu){
		$channelLib = $this->load('channel', 'pooling');
		$channel = $channelLib->get($cid,true);
		
		
		$data = explode(",", $aids);
		//先检测出有效的articleid
		$temp = array();//有效的article
		$this->db->init( 'article', 'articleid', 'article' );
		foreach($data as $k=>$v){
			$articleid = trim($v);
			if($article = $this->db->get($articleid)){
				$temp[] = $article;
			}
		}
		//统一添加
		$this->db->init('article', 'paid', 'pooling');
		foreach($temp as $k=>$v){
			//过滤掉已经存在的
			$this->db->setCriteria ( new Criteria ( 'channelid', $cid ) );
			$this->db->criteria->add ( new Criteria ( 'articleid', $v['articleid'] ) );
			$this->db->criteria->setLimit ( 1 );
			$this->db->queryObjects ();
			$tmpa = $this->db->getObject ();
			if (is_object ( $tmpa )) {
				continue;
			}
			$_PAGE['data'] = array();
			$_PAGE['data']['channelid'] = $cid;
			$_PAGE['data']['articleid'] = $v['articleid'];
			$_PAGE['data']['articlename'] = $v['articlename'];
			if($channel['url'] == '360.com'){//特殊情况
				$_PAGE['data']['sort'] = $this->getCategory_360($v['sortid']);//书海分类名称
			}
			$_PAGE['data']['intro'] = $v['intro'];
			$_PAGE['data']['fullflag'] = $v['fullflag'];
			$_PAGE['data']['pushflag'] = $statu;
			$_PAGE['data']['adddate'] = JIEQI_NOW_TIME;
			$this->db->add($_PAGE['data']);
		}
		$this->jumppage($this->getAdminurl('channel','method=pushView&cid='.$cid,'pooling'));
		//添加成功
	}
	private function getCategory_360($category = 0) { // 360的分类
		$sort = array (
				1 => '玄幻',
				2 => '都市',
				3 => '仙侠',
				4 => '游戏',
				5 => '科幻',
				6 => '武侠',
				7 => '奇幻',
				8 => '历史',
				9 => '军事',
				10 => '悬疑',
				11 => '耽美同人',
				12 => '现代言情'
		);
		if (isset ( $sort [$category] ))
			return $sort [$category];
		else
			return '都市';
	}
	/**
	 * 获取api采集类
	 * <p>
	 * 默认my_collectDefault采集类
	 * <p>
	 * 自定义my_$channel["url"]采集类
	 * @param unknown $channel
	 */
	private function getCollectLib($channel){
		//默认采集类 或者 自定义类采集类
		$url = "collectDefault";
		if($channel["url"] && file_exists($GLOBALS ['jieqiModules'] ['pooling'] ['path']."/lib/my_{$channel["url"]}.php")){
			$url = $channel["url"];
		}
		return $this->load($url,'pooling');
	}
	public function collectList($cid,$page){
		define ( 'JIEQI_USE_GZIP', '0' );
		ini_set ( 'zlib.output_compression', 0 );
		ini_set ( 'implicit_flush', 1 );
		ini_set("max_execution_time", 120); // s 40 分钟
		ob_start ();
		ob_end_flush ();
		ob_implicit_flush ();
		@set_time_limit ( 0 );
		@session_write_close ();
		$this->db->init( 'channel', 'channelid', 'pooling' );
		$channelLib = $this->load('channel', 'pooling');
		$channel = $channelLib->get($cid,true);
		//默认采集类 或者 自定义类采集类
		$collectLib = $this->getCollectLib($channel);
		return $collectLib->collectList($cid,$page);
	}
	/**
	 * 合作渠道api采集接口中的书批量入库|更新
	 * <p>
	 * modify 2015-7-17 11:44 by chengyuan 采集一个章节，记录位置。 
	 * @param unknown $cid	渠道id
	 * @param unknown $aid	接口articleid
	 * @param number $page	接口内的页
	 * 2014-8-22 上午9:47:06
	 */
	public function newArticle($cid,$aid = array(),$page=1){
		if(!$cid || empty($aid)){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		define ( 'JIEQI_USE_GZIP', '0' );
		ini_set ( 'zlib.output_compression', 0 );
		ini_set ( 'implicit_flush', 1 );
		ini_set("max_execution_time", 0); // s 40 分钟
		ob_start ();
		ob_end_flush ();
		ob_implicit_flush ();
		@set_time_limit ( 0 );
		@session_write_close ();
		$channelLib = $this->load('channel', 'pooling');
		$channel = $channelLib->get($cid,true);
// 		$collectLib = $this->load($channel['url'],'pooling');
		$collectLib = $this->getCollectLib($channel);
		$this->db->init( 'channel', 'channelid', 'pooling' );
		$data = $collectLib->collectList($cid,$page,$aid);
		$articleLib = $this->load('article','article');
		foreach ( $aid as $id ){
			if(array_key_exists($id,$data['rows'])){
				$timing = false;
				$article = $data['rows'][$id];
				$collectLib->out_msg('请不要关闭此页面，防止采集中断！！！！');
				$collectLib->out_msg($article['articlename'].'....采集开始!请不要关闭此页面，防止采集中断。');
				//入库或者更新
				if($article['new']){//入库
					if($article['fullflag']){
						$timing = true;//全本的书进行，定时发布。
					}
					$article['articlelpic'] = array('name'=>$article['articlelpic'],'tmp_name'=>$article['articlelpic']);
					$article['articlespic'] = array('name'=>$article['articlespic'],'tmp_name'=>$article['articlespic']);
					$apiId = $article['articleid'];
					unset($article['articleid']);//这个是第三方站点中的书号
					$article['fullflag'] = 0;//统一状态：连载
					$localArticle =  $articleLib->newArticle($article);
					if(is_array($localArticle) && $localArticle['articleid']){
						$collectLib->out_msg('《'.$localArticle['articlename'].'》添加成功');
						$lastchapterid = 0;
						//第三->数据池->书海
						//数据池记录采集信息
						//前面已经做了是否新书的判断，这里直接入数据池记录。
						$this->db->init('article', 'paid', 'pooling');
						$paid = $this->db->add(array(
								'channelid'=>$cid,//渠道id
								'articlename'=>$localArticle['articlename'],//第三方书名
								'articleid'=>$localArticle['articleid'],//书海id
								'lastvolumeid'=>0,
								'lastvolume'=>"",
								'lastchapterid'=>0,
								'lastchapter'=>"",
								'adddate'=>JIEQI_NOW_TIME,
								'intro'=>$article['intro'],//第三方简介
								'apiId'=>$apiId//第三方书号
						));
						if($paid){
							$collectLib->out_msg('数据池映射成功');
							$article['mappingArticle']['paid']=$paid;
						}
					}
				}else{//更新
					$lastchapterid = $article['mappingArticle']['lastchapterid'];
					$this->db->init('article', 'articleid', 'article');
					$localArticle = $this->db->get($article['mappingArticle']['articleid']);
// 					$update = 0;
// 					$localArticle = $article['localArticle'];
// 					if($localArticle['lastupdate'] != $article['lastupdate']){
// 						$localArticle['lastupdate'] = $article['lastupdate'];
// 						$update = 1;
// 					}
// 					if($localArticle['sortid'] != $article['sortid']){
// 						$localArticle['sortid'] = $article['sortid'];
// 						$update = 1;
// 					}
// 					if($localArticle['articletype'] != $article['isvip']){
// 						$localArticle['articletype'] = $article['isvip'];
// 						$update = 1;
// 					}
// // 					if($localArticle['fullflag'] != $article['fullflag']){
// // 						$localArticle['fullflag'] = $article['fullflag'];
// // 						$update = 1;
// // 					}
// 					if($update){
// 						$this->db->init('article','articleid','article');
// 						$this->db->edit($localArticle['articleid'],$localArticle);
// 					}
					//已经入库的章节总数
// 					$this->db->init('chapter','chapterid','article');
// 					$this->db->setCriteria(new Criteria('articleid', $localArticle['articleid']));
// 					$chapterCount = $this->db->getCount($this->db->criteria);
				}
// 				$collect = false;
				//上次更新的信息
				$lastvolumeid = $article['mappingArticle']['lastvolumeid'];
				$lastvolume = $article['mappingArticle']['lastvolume'];
				$lastchapter = $article['mappingArticle']['lastchapter'];
// 				if($lastchapterid == 0){
// 					$collect = true;
// 				}
				//新增或者更新统一处理
				//入库章节
				//print_r($article);exit;
				if($article['chaptersurl']){
					$items =$collectLib->parseChapters($article['chaptersurl'],$lastchapterid);
					if(!empty($items)){
						$end_item = end($items);
						$end_chapter = $collectLib->packingChapter($end_item);
						if($end_chapter['cid']){
							if($lastchapterid == $end_chapter['cid']){
								$collectLib->out_msg('没有需要更新的章节。');
							}else{
								//从已经入库的章节下一章开始
								$collectLib->out_msg ( '<table border=1><tr><th>序</th><th>类型</th><th>章节名称</th><th>状态</th><th>时间</th><th>采集结果</th><th>记录采集位置</th></tr>',false);
								$ps= 0;
								$day= 1;
								for ($i = 0;$i < count($items);$i++){//过滤掉上次更新
// 									if($i ==6)break;
									$chapter = $collectLib->packingChapter($items[$i]);
									// 							if(!$collect){//定位
									// 								if($lastchapterid == $chapter['cid']){
									// 									$collect = true;//开始采集
									// 									continue;//这个可以不用写，只是为了标识逻辑
									// 								}
									// 							}else{
									$chapter['articleid'] =$localArticle['articleid'];
									if($timing){//处理定时发布
										//首次发布的章节数，每天更新的章节数，更新日期为(一个更新周期)当前日期累计+1;
										//先处理首次发布的章节数
										if(is_numeric($channel['setting']['getdata']['firstnum']) && $channel['setting']['getdata']['firstnum'] && $channel['setting']['getdata']['postnum'] && is_array($channel['setting']['getdata']['postdate']) && !empty($channel['setting']['getdata']['postdate'][0])){
											//默认
											if(($i) >= $channel['setting']['getdata']['firstnum']){//过滤掉首发的章节数
												//处理定时
												$posttime = $channel['setting']['getdata']['postdate'][$ps];
												//计算日期
												//当前日期的时间轴
												//日期（计算strtotime("+1 day"))）+时间（$posttime）
												$chapter['postdate'] =  strtotime(date("Y-m-d",strtotime("+".$day." day")).' '.$posttime);
												$ps++;
												if($ps == count($channel['setting']['getdata']['postdate'])){
													$ps= 0;
													$day++;
												}
											}
										}
									}
									$name = '章节';
									if($chapter['chaptertype'] == 1){//卷
										$name = '卷';
										$newChapter = $articleLib->saveVolume($localArticle,null,$chapter['chaptername'],'',$chapter['postdate']);
										$lastvolumeid = $chapter['cid'];
										$lastvolume = $chapter['chaptername'];
									}else{//章节
										// 内容过滤
										if ($channel['setting']['getdata']['filter']) {
											$filterary = explode ( PHP_EOL, $channel['setting']['getdata']['filter']);
											$chapter ['chaptercontent'] = str_replace ( $filterary, '', $chapter ['chaptercontent'] );
										}
										//清除p标签的换行。
										$chapter ['chaptercontent'] = str_replace ( array('</p><p>','<p>','</p>'), array(PHP_EOL,'',''), $chapter ['chaptercontent'] );
										$newChapter = $articleLib->saveChapter($chapter);
										$lastchapterid = $chapter['cid'];
										$lastchapter = $chapter['chaptername'];
									}
									if(is_array($newChapter) && $newChapter['chapterid']){
										$tmp = date('Y-m-d H:i:s',$chapter['postdate']);
										$state = '发布';
										if($chapter['postdate'] > JIEQI_NOW_TIME){
											$state = '<font color="red">定时</font>';
										}
										$collectLib->out_msg ( "<tr><td>".($i+1)."</td><td>{$name}</td><td>{$newChapter['chaptername']}</td><td>{$state}</td><td>{$tmp}</td><td>成功</td>",false);
									
										//更新数据池映射模型
										$this->db->init('article', 'paid', 'pooling');
										if($this->db->edit($article['mappingArticle']['paid'],array(
												'lastvolumeid'=>$lastvolumeid,
												'lastvolume'=>$lastvolume,
												'lastchapterid'=>$lastchapterid,
												'lastchapter'=>$lastchapter,
												'outchapters'=>$article['mappingArticle']['outchapters']+($i-1),
												'lastdate'=>JIEQI_NOW_TIME
										))){
											$collectLib->out_msg('<td>成功</td></tr>',false);
										}else{
											$collectLib->out_msg('<td>失败</td></tr>',false);
											break;
										}
									}
									// 							}
								}
								$collectLib->out_msg( "</table>",false);
							}
						}else{
							$collectLib->out_msg_err('无效的章节ID');
						}
					}else{
						$collectLib->out_msg_err('无法定位上次更新位置，章节名称：'.$lastchapter);
					}
				}
				$collectLib->out_msg($article['articlename'].'....采集结束!');
			}
		}
	}
	/**
	 * 渠道推送的文章列表视图
	 * @param unknown $params
	 * 2014-6-12 下午2:45:28
	 */
	public function pushView($params = array()){
		$this->db->init( 'channel', 'channelid', 'pooling' );
		$_OBJ['channel'] = $this->load('channel', 'pooling');//加载channel自定义类
		if(!$_PAGE['channel'] = $_OBJ['channel']->get($params['cid'],true)) jieqi_jumppage('?ac=apisite', LANG_NOTICE, LANG_DO_FAILURE);
		//getparameter('page');
		//$_OBJ['view'] = new View('apiarticle', 'id', 'manage');
		//getparameter('type');
// 		if($_PAGE['type']=='del'){
// 			$id = $_PAGE['_GET']['id'] ?$_PAGE['_GET']['id'] :$_PAGE['_POST']['ids'];
// 			if(!$id) printfail(LANG_ERROR_PARAMETER);
// 			$ids = array();//存放待删除的文章ID容器
// 			if(!is_array($id))  $ids[] = $id;
// 			else  $ids = $id;
// 			foreach($ids as $id){
// 				$_OBJ['view']->delete($id);
// 			}
// 			jumppage();
// 		}
// 		if($_PAGE['type']=='statu'){
// 			$value = $_PAGE['_GET']['value'];
// 			$id = $_PAGE['_GET']['id'] ?$_PAGE['_GET']['id'] :$_PAGE['_POST']['ids'];
// 			if(!$id) printfail(LANG_ERROR_PARAMETER);
// 			$ids = array();//存放待删除的文章ID容器
// 			if(!is_array($id))  $ids[] = $id;
// 			else  $ids = $id;
// 			foreach($ids as $id){
// 				$_OBJ['view']->edit($id,array('statu'=>$value));
// 			}
// 			jumppage();
// 		}
// 		//提交数据添加文章
// 		if($_PAGE['type']=='add'){
// 			if(submitcheck("dosubmit")){
// 				$_OBJ['article'] = new View('article', 'articleid', 'article');
// 				$_PAGE['data'] = getparameter('info');
// 				$articleids = getparameter('articleids');
// 				$data = explode(",", $articleids);
// 				foreach($data as $k=>$v){
// 					$_PAGE['data'] = array();
// 					$articleid = trim($v);
// 					if(!$article = $_OBJ['article']->get($articleid,true)) continue;
// 					$_PAGE['data']['sid'] = $_PAGE['_GET']['sid'];
// 					$_PAGE['data']['articleid'] = $articleid;
// 					$_PAGE['data']['articlename'] = $article['articlename'];
// 					$_PAGE['data']['intro'] = saddslashes($article['intro']);
// 					$_PAGE['data']['fullflag'] = 0;
// 					$_PAGE['data']['postdate'] = _NOW_;
// 					$_PAGE['data']['lastupdate'] = _NOW_;
// 					$_OBJ['view']->add($_PAGE['data'],true);
// 				}
// 				jumppage();
// 			}
// 		}
		$this->db->init( 'article', 'paid', 'pooling' );
		$this->db->setCriteria(new Criteria('channelid', $params['cid']));
		//推送	推送时间逆序，
		//展示
		//采集
		if($_PAGE['channel']['type'] == 1){
			$this->db->criteria->setSort('adddate');
			$this->db->criteria->setOrder('desc');
		}else{
			$this->db->criteria->setSort('lastdate desc,adddate');
			$this->db->criteria->setOrder('desc');
		}
		$_PAGE['rows'] = $this->db->lists(200, $params['page']);
		$_PAGE['url_jumppage'] = $this->db->getPage();//print_r($_PAGE['rows']);
		return $_PAGE;
		/*	if(isset($_PAGE['apisite']['setting']['getdata']['articleids']) && $_PAGE['apisite']['setting']['getdata']['articleids']!=''){
		 $_OBJ['view'] = new View('article', 'articleid', 'article');
		$_OBJ['view']->setHandler('article');
		$_OBJ['view']->criteria->setFields('r.articleid,r.articlename,r.author,if(r.lastupdate>a.lastupdate,r.lastupdate,a.lastupdate) as lastupdate');
		$_OBJ['view']->criteria->setTables(jieqi_dbprefix('article_article')."  AS r LEFT JOIN ".jieqi_dbprefix('obook_obook')." AS a ON r.articleid=a.articleid");
		$_OBJ['view']->criteria->add(new Criteria('r.articleid', '('.$_PAGE['apisite']['setting']['getdata']['articleids'].')','in'));
		//$_OBJ['view']->criteria->add(new Criteria('r.display', 0));
		//$_OBJ['view']->criteria->add(new Criteria('r.siteid',0,'='));
		$_OBJ['view']->criteria->add(new Criteria('r.sortid', 30,'!='));
		$_OBJ['view']->criteria->add(new Criteria('r.size', 0,'>'));
		//$_OBJ['view']->criteria->setSort('lastupdate');
		//$_OBJ['view']->criteria->setOrder('DESC');
		$_PAGE['rows'] = $_OBJ['view']->lists(200, $_PAGE['page']);
		$_PAGE['url_jumppage'] = $_OBJ['view']->getPage();
		}*/
	}
	public function editArticle($params = array()){
		$data = array();
		$this->db->init( 'article', 'paid', 'pooling' );
		if(!$data['article'] = $this->db->get($params['paid'])) $this->jumppage('?ac=apisite', LANG_NOTICE, LANG_ERROR_PARAMETER);
		if($data['article']['setting']){
			eval('$setting = '.$data['article']['setting'].';');//字符串转数组
			$data['article']['setting'] = $setting;
		}
// 		$_OBJ['view'] = new View('apiarticle', 'id', 'manage');
		//提交数据
		if($this->submitcheck()){//修改
			$article = $params['info'];
			//setting采用合并设置，而不是覆盖原setting。
			$setting = $params['setting'];
			if(is_array($data['article']['setting']) && is_array($setting)){
				$setting = array_merge($data['article']['setting'],$setting);
			}
			$article['setting'] = $this->arrayeval($setting);//数组转字符结构
			if($_FILES['articleimage']['name']){
				include_once(JIEQI_ROOT_PATH.'/lib/my_httpupload.php');
				// 上传保存位置，默认为当前文件夹“./”
				$savepath = JIEQI_ROOT_PATH."/api/api_image";
				// 上传文件要求的mime类型，默认为text,image
				$mimetype = "image";
				// 文件扩展名要求，默认为“jpg,bmp,png,gif,jpeg”
				$fileextname = "jpg,jpeg,gif,png";
				// 文件大小要求，默认为512000 （500K）
				$maxsize = 1024000;
				// 是否重命名，0为不重命名，1为重命名，默认为1
				$filerename = 1;
				// 建立年月日文件夹
				$savedir = date('Y/md', time());
				// 当设置不重命名并且文件存在时是否覆盖 0为不覆盖，1为覆盖并上传，2为重命名并上传
				$overwrite = 1;

				$upload = new HttpUpload('articleimage',$savepath,$mimetype,$fileextname, $maxsize, $filerename, $savedir, $overwrite);
				if($params['old_image']) $upload->__set("upload_filename",$savepath.$params['old_image']);
				$up = $upload -> upfile();
				if($up[upfile_file_error]) $this->printfail('上传封面时发生错误，错误代号：'.$up[upfile_file_error]);
				else{
					$article['image']  = str_replace($savepath,'',$up[upfile_file_path]);
					//中图
					if($_FILES['larticleimage']['name']){
						$upload = new HttpUpload('larticleimage',$savepath,$mimetype,$fileextname, $maxsize, $filerename, $savedir, $overwrite);
						if($params['lold_image']) $upload->__set("upload_filename",$savepath.$params['lold_image']);
						$up = $upload -> upfile();
						$article['limage']  = str_replace($savepath,'',$up[upfile_file_path]);
					}
					//小图
					if($_FILES['sarticleimage']['name']){
						$upload = new HttpUpload('sarticleimage',$savepath,$mimetype,$fileextname, $maxsize, $filerename, $savedir, $overwrite);
						if($params['sold_image']) $upload->__set("upload_filename",$savepath.$params['sold_image']);
						$up = $upload -> upfile();
						$article['simage']  = str_replace($savepath,'',$up[upfile_file_path]);
					}
				}
			}
// 			$data['lastdate'] = JIEQI_NOW_TIME;
			//if($_REQUEST['setting']['open']) $data['setting'] = saddslashes(arrayeval($_REQUEST['setting']));
			//else $data['setting'] = '';
			if($this->db->edit($params['paid'],$article)) $this->jumppage($this->getAdminurl('channel','method=pushView&cid='.$params['cid'],'pooling'));//修改
			else $this->printfail();
		}
// 		if($_PAGE['data']['chapterids']){
// 			eval('$apisiteSetting['.$_PAGE['_GET']['id'].'] = '.$_PAGE['data']['chapterids'].';');
// 			$_PAGE['data']['chapterids'] = $apisiteSetting[$_PAGE['_GET']['id']];
// 			$_SGLOBAL['api'] = $_PAGE['data']['chapterids'];//print_r($_SGLOBAL['api']);
// 		}
		$this->db->init( 'channel', 'channelid', 'pooling' );
		$data['channel'] = $this->db->get($params['cid']);
		return $data;
	}
	/**
	 * 单篇文章推送，推送api自定义类实现了iapi接口，接口内定义push的方法（参数：cid渠道Id，paid推送文章Id），这里通过api渠道调用对应自定义类中实现的接口方法
	 * @param unknown $params
	 * 2014-7-1 下午3:16:36
	 */
	public function push($params){
		if($params['cid']){
			$channelLib = $this->load('channel', 'pooling');//加载channel自定义类
			if(!$channel = $channelLib->get($params['cid'])){
				$this->printfail(LANG_ERROR_PARAMETER);//LANG_ERROR_PARAMETER
			}
			if(!$channel['statu']){
				$this->printfail('api '.$channel["channelname"].' is close!');
			}
			$mod = $channel['url'];
			if($mod && file_exists($GLOBALS ['jieqiModules'] ['pooling'] ['path']."/lib/my_{$mod}.php")){
				$apiLib = $this->load($mod,'pooling');
				$paids = array();
				$paid = $params['paid'];
				if($paid){
					//指定单个或多个
					if(strchr($paid,',') !== false){
						$paids = explode(',',$paid);
					}else{
						$paids[] = $paid;
					}
				}elseif ($params['paids']){
					//form批量
					$paids = $params['paids'];
				}
				/*-- 开始推送 begin--*/
				$articles = $apiLib->poolArticle($params['cid'],$paids);
				if($articles){
					@set_time_limit('0');
					@session_write_close();
					ini_set('memory_limit', '800M');
					ini_set("max_execution_time",'0');
// 					for($i=0;$i<40;$i++) {
// 						$apiLib->out_msg($i);
// 						sleep(1);
// 					}
// 					exit;
					$apiLib->out_msg ( '-->渠道：'.$channel['channelname'].' 获取' . count ( $articles ) . '本书准备推送！' );
					$i = 0;
					
					foreach ( $articles as $article){
						if($article['setting']){
							eval('$setting = '.$article['setting'].';');//字符串转数组
							$article['setting'] = $setting;
						}
						$i++;
						$apiLib->out_msg ( '-----------------------------------------------------' );
						$apiLib->out_msg ( '-->第'.$i.'本书《'.$article['articlename'].'》开始推送' );
						if(!$article ['size']){
							$apiLib->out_msg_err ( '-->article size is 0' );
						}else{
// 							if ($article['setting']['daychapter']
// 									&& is_numeric($article['setting']['daychapter'])
// 									&& $article['setting']['daychapter'] > 0
// 									&& date('Y-m-d', $article['lastdate']) == date("Y-m-d")){
// 								$apiLib->out_msg_err ( '-->今天已经推送过了，明天再来吧！' );
// 							}else{
								$apiLib->push($params['cid'],$article);
// 							}
						}
						$apiLib->out_msg ( '-->第'.$i.'本书《'.$article['articlename'].'》结束推送' );
					}
					$apiLib->out_msg ( '-----------------------------------------------------' );
					$apiLib->out_msg ( '-->渠道：'.$channel['channelname'].' 推送完成' );
				}else{
					$apiLib->out_msg('-->'.$channel['channelname'].'渠道无推送的文章');
				}
				/*-- 开始推送 end--*/
			}else{
				$this->printfail(LANG_ERROR_PARAMETER);
			}
		}else $this->printfail(LANG_ERROR_PARAMETER);
	}
}
?>