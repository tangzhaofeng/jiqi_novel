<?php
/**
 * 渠道文章预览
 * @author zhangxue
 *
 */
class previewModel extends Model{
	/**
	 * 默认函数,分页参数是：page，采集方会自动计算页数，只需要通过参数page计算当前页的数据。
	 * @param unknown $params
	 * 2014-6-16 下午2:46:13
	 */
	public function main($params = array()){
		//$params['page'],$params['begin_time'],$params['end_time'],$params['page']
		$data = array();
		$channel = $this->getChannel();
		$data['channel'] = $channel;
		if(isset($params['pushflag']) && intval($params['pushflag']) === 0){
			$ids = $this->getArticleList($channel['channelid'],0);
		}else{
			$ids = $this->getArticleList($channel['channelid']);
		}
		$articleids  = array_keys($ids);//推送的文章ID
		//如果ids则推送全部信息
		//jieqi_pooling_article
		$this->db->init('article', 'paid', 'pooling');
		$this->db->setCriteria();
		if(count($articleids)>0){
			//指定文章
			$this->db->criteria->setTables(jieqi_dbprefix('pooling_article')."  AS pa  LEFT JOIN ".jieqi_dbprefix('article_article')." AS aa ON pa.articleid=aa.articleid LEFT JOIN ".jieqi_dbprefix('article_statamout').' as ast  ON aa.articleid=ast.articleid');
			$this->db->criteria->setFields('ast.visit,pa.*,aa.articleid,aa.articlename as articlename_old ,aa.articletype,aa.author,aa.sortid,aa.imgflag,aa.keywords,aa.postdate,aa.lastupdate,aa.lastchapterid,aa.lastchapter,aa.chapters,aa.size,aa.fullflag');
			$this->db->criteria->add(new Criteria('pa.channelid', $channel['channelid'],'='));//渠道
			$this->db->criteria->add(new Criteria('pa.pushflag', 1,'='));//可抓取状态
			$this->db->criteria->add(new Criteria('pa.articleid', '('.implode(',',$articleids).')','in'));
		}else{
			//全部文章
			$this->db->criteria->add(new Criteria('aa.display', 0));
			$this->db->criteria->setTables(jieqi_dbprefix('article_article')." AS aa LEFT JOIN ".jieqi_dbprefix('article_statamout').' as ast  ON aa.articleid=ast.articleid');
			$this->db->criteria->setFields('ast.visit,aa.articleid,aa.articlename,aa.intro,aa.articletype,aa.author,aa.sortid,aa.imgflag,aa.keywords,aa.postdate,aa.lastupdate,aa.lastchapterid,aa.lastchapter,aa.chapters,aa.size,aa.fullflag');
		}
		$params['begin_time'] = $params['begin_time'] ? $params['begin_time'] : $params['timestamp'];
		//指定更新的时间周期
		if($params['begin_time']){
			if(strlen($params['begin_time'])==10){
				$params['begin_time'] = $params['begin_time'].'00';
			}
			$this->db->criteria->add(new Criteria('aa.lastupdate',strtotime($params['begin_time']),'>='));
		}
		if($params['end_time']){
			if(strlen($params['end_time'])==10){
				$params['end_time'] = $params['end_time'].'00';
			}
			$this->db->criteria->add(new Criteria('aa.lastupdate',strtotime($params['end_time']),'<='));
		}

		if($params['data']=='new'){
			$starttime = strtotime(date("Y-m-d",strtotime("-1 day")));
			$this->db->criteria->add(new Criteria('aa.lastupdate', $starttime,'>='));
	    }

		$this->db->criteria->add(new Criteria('aa.size', 0,'>'));
		$this->db->criteria->setSort('aa.lastupdate');
// 		$this->db->criteria->setSort('aa.articletype');
		$this->db->criteria->setOrder('DESC');
		$pagesize = $channel['setting']['getdata']['pagesize'] ? $channel['setting']['getdata']['pagesize'] : 500;
		$data['pagesize'] = $pagesize;
		$data['rows'] = $this->db->lists($pagesize, $params['page'],JIEQI_PAGE_TAG);
// 		echo $this->db->returnsql($this->db->criteria);
// 		exit;
		
		$articleLib =  $this->load('article','article');
		foreach($data['rows'] as $k=>$v){
			$intro = $v['intro'];
            $lastchapterid = $v['lastchapterid'];
			//article_vars中对intro进行了html标签的转码处理，这里不需要转码
			$data ['rows'][$k] = $articleLib->article_vars($v);
			$data['rows'][$k]['intro'] = $intro;
            $data['rows'][$k]['lastchapterid'] = $lastchapterid;
			if($data ['rows'][$k]['articletype'] > 0){
				//兼容老程序，开v的文章1
				$data ['rows'][$k]['articletype'] = 1;
			}
			if(!$data ['rows'][$k]['visit']){
				$data ['rows'][$k]['visit'] = 0;
			}
		}
		$data['totalcount'] = $this->db->getVar('totalcount');
		$data['totalpage'] = $this->db->jumppage->getVar('totalpage');
		//xml格式不需要
		$data['url_jumppage'] = $this->db->getPage ($this->getUrl('pooling','home','evalpage=0','SYS=method=main'));
		$data['HTTP_HOST'] = $_SERVER['HTTP_HOST'];
		$data['cover_dir'] =  JIEQI_URL."/api/api_image";
		$data['begin_time'] =  $params['begin_time'];
		
// 		print_r($data['rows']);
// 		exit;
		return $data;
	}
	
	/**
	 * c_2345特殊处理,模板：c_2345.html
	 * @param unknown $params
	 * @return multitype:multitype: unknown number 渠道数组对象
	 * 2014-6-23 下午3:40:10
	 */
	public function c_2345($params = array()){
		if(!$params['aid']) $params['aid'] = $params['book_id'];
		if(!$params['cid']) $params['cid'] = $params['chapter_id'];
// 		$isvip = false;
// 		if(substr($params['cid'],0,1)==1) $isvip = false;
// 		else $isvip = true;
// 		$params['cid'] = substr($params['cid'],1);
		//如果指定limit参数，则按limit参数去cid后续的等数的章节
		//如果未指定，则只取cid的一章
		$params['limit']=intval($params['limit']);
		if(!$params['limit']) $params['limit']=1;
		$c_limit = 0;
		$start = false;
		$articleLib =  $this->load('article','article');
		//取内容
		$articleLib->instantPackage($params['aid']);
		$data = array();
		$channel = $this->getChannel();
		$data['channel'] = $channel;
		$data['HTTP_HOST'] = $_SERVER['HTTP_HOST'];
		$this->db->init ('chapter','chapterid','article' );
		$this->db->setCriteria ( new Criteria ( 'articleid', $params['aid']));
		//根据渠道配置信息添加查询条件：读取全部章节2|读取全部免费章节1
// 		if($isvip){
// 			$this->db->criteria->add(new Criteria('isvip', 0,'>'));//是否仅仅加载vip
// 		}
		$this->db->criteria->add(new Criteria('display',2,'<'));//审核通过的
		$this->db->criteria->add(new Criteria('chaptertype',0,'='));
		$this->db->criteria->setSort('chapterorder');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		$data['chapters']  = array();;
		$k=0;
		while($v = $this->db->getObject()){
			if($params['cid'] && $params['cid'] != $v->getVar('chapterid') && !$start){
				continue;
			}else{
				$start = true;
				$c_limit++;//
				if($c_limit>$params['limit']) break;
				$data['chapters'][$k] = array(
						'chapterid'=>$v->getVar('chapterid'),
						'size'=>$v->getVar('size','n'),
						'content'=>@$articleLib->getContent($v->getVar('chapterid')),
						'isvip'=>$v->getVar('isvip','n')
				);
				$k++;
			}
		}
		$data['articleid'] = $params['aid'];
		return $data;
	}
	/**
	 * 获取分类列表
	 *
	 * 2014-6-23 上午11:34:43
	 */
	public function sort(){
		$articleLib =  $this->load('article','article');
		$data = $articleLib->getSources();
		$data['channel'] = $this->getChannel();
		return $data;
	}
	/**
	 * 章节内容
	 * @param unknown $params
	 * @return unknown
	 * 2014-6-18 下午4:57:45
	 */
	public function chapter($params = array()){
		$articleLib =  $this->load('article','article');
		$data = array();
		$channel = $this->getChannel();
		$data['channel'] = $channel;
		$ids = $this->getArticleList($channel['channelid']);//推送文章和数据池文章ID对应数组
		$articleids  = array_keys($ids);//推送的文章ID
		if(count($articleids)>0) {
			if(!in_array($params['aid'],$articleids)) exit('3');
			$this->db->init('article', 'paid', 'pooling');
			$data['article'] = $this->db->get ($ids[$params['aid']]);
		}else{
			$this->db->init('article', 'articleid', 'article');
			$data['article'] = $this->db->get($params['aid']);
		}
		//书海|数据池
		if(isset($channel['setting']['getdata']['chaptersource']) && $channel['setting']['getdata']['chaptersource']){
			//通过chapterid查询
// 			$this->db->init('chapter', 'pcid', 'pooling');
// 			$data['chapter'] = $this->db->get ($params['cid']);
			$chapters = $this->db->selectsql ( 'select * from ' . jieqi_dbprefix ( "pooling_chapter" ) . " where channelid=" . $channel['channelid'] . " and articleid=" . $params['aid']. " and chapterid=" . $params['cid'] );
			$ct = count($chapters);
			if(is_array($chapters) && $ct == 1){
				$data['chapter'] = $chapters[0];
			}else{
				exit('chapter is error');
			}
		}else{
			$this->db->init('chapter', 'chapterid', 'article');
			$data['chapter'] = $this->db->get ($params['cid']);
			//取内容
			$articleLib->instantPackage($params['aid']);
			$tmpvar=@$articleLib->getContent($params['cid']);
			//print_r($tmpvar);
			$data['chapter']['content'] = $tmpvar;
			//模板内会判定vip章节
			$data['url_vip'] = $this->geturl('article', 'reader', 'aid='.$params['aid'],'cid='.$params['cid']);
		}
		return $data;
	}
	/**
	 * 文章详情
	 * @param unknown $params
	 * @return Ambigous <number, unknown, multitype:unknown NULL 渠道数组对象 >
	 * 2014-6-23 上午10:48:09
	 */
	function info($params = array()){
		$articleLib =  $this->load('article','article');
		$data = array();
		$channel = $this->getChannel();
		$data['channel'] = $channel;
		$data['HTTP_HOST'] = $_SERVER['HTTP_HOST'];
		//配置信息不被读取
		if(!$channel['setting']['getdata']['nosetchapters'])exit;
		$ids = $this->getArticleList($channel['channelid']);//数据池书ID
		$articleids  = array_keys($ids);
		$this->db->init('article', 'articleid', 'article');
		$this->db->setCriteria();
		//联合查询+jieqi_article_stat(weekvisit,monthvisit,allvisit)
		if(count($articleids)>0) {
			//读取的文章不在渠道数据池内
			if(!in_array($params['aid'],$articleids)){
				exit('3');//渠道数据池没有此文章
			}else{
				//数据池内的文章
				$this->db->criteria->setTables(jieqi_dbprefix('pooling_article')."  AS pa  LEFT JOIN ".jieqi_dbprefix('article_article')." AS aa ON pa.articleid=aa.articleid LEFT JOIN ".jieqi_dbprefix('article_stat').' as ast  ON aa.articleid=ast.articleid');
				$this->db->criteria->setFields('ast.total as allvisit,ast.month as monthvisit,ast.week as weekvisit,ast.day as dayvisit,pa.*,aa.articleid,aa.articlename as articlename_old,aa.chapters,aa.articletype,aa.author,aa.sortid,aa.imgflag,aa.keywords,aa.postdate,aa.lastupdate,aa.lastchapterid,aa.size,aa.fullflag');
				$this->db->criteria->add(new Criteria('pa.channelid', $channel['channelid'],'='));//渠道
				$this->db->criteria->add(new Criteria('pa.pushflag', 1,'='));//可抓取状态
			}
		}else{
			//渠道的数据池内没有文章，则读取源文章
			$this->db->criteria->setTables(jieqi_dbprefix('article_article')." AS aa LEFT JOIN ".jieqi_dbprefix('article_stat').' as ast  ON aa.articleid=ast.articleid');
			$this->db->criteria->setFields('ast.total as allvisit,ast.month as monthvisit,ast.week as weekvisit,ast.day as dayvisit,aa.articlename,aa.articleid,aa.chapters,aa.intro,aa.articletype,aa.author,aa.sortid,aa.imgflag,aa.keywords,aa.postdate,aa.lastupdate,aa.lastchapterid,aa.size,aa.fullflag');
		}
		$this->db->criteria->add(new Criteria('ast.mid','visit','='));
		$this->db->criteria->add(new Criteria('aa.articleid', $params['aid'],'='));
		//$this->db->criteria->add(new Criteria('aa.display', 0));
		$this->db->criteria->add(new Criteria('aa.size', 0,'>'));
		//查询
		$this->db->queryObjects();
		$aobj=$this->db->getObject();

		//article_vars中对intro进行了html标签的转码处理，这里不需要转码
		if(is_object($aobj)){
			$intro = $aobj->getVar('intro', 'n');
			if($channel['url'] == '360.com'){
				$sort = $aobj->getVar('sort', 'n');
			}
			$data['article'] = $articleLib->article_vars($aobj);
			$data['article']['intro'] = $intro;
			if($sort){
				$data['article']['sort'] = $sort;
			}
			if($data['article']['articletype'] > 0){
				//兼容老程序，开v的文章1
				$data['article']['articletype'] = 1;
			}
			if(!$data['article']['weekvisit']){
				$data['article']['weekvisit'] = 0;
			}
			if(!$data['article']['monthvisit']){
				$data['article']['monthvisit'] = 0;
			}
			if(!$data['article']['allvisit']){
				$data['article']['allvisit'] = 0;
			}
			//image|limage|simage
			if($data['article']['image']){//大封面
				$data['article']['url_image_l'] = JIEQI_URL."/api/api_image".$data['article']['image'];
			}
			if($data['article']['limage']){//小封面
				$data['article']['url_image'] = JIEQI_URL."/api/api_image".$data['article']['limage'];
			}
		}else{
			exit('null');
		}
		return $data;
	}
	/**
	 * 章节更新数量通过chapterId参数判定
	 * @param unknown $params
	 * @return unknown
	 * 2014-6-23 下午4:57:19
	 */
	public function cquire($params = array()){
		$params['aid'] = $params['aid'] ? $params['aid'] : $params['bookId'];
		$data = $this->chapters($params);
		$data['updatenum'] = 0;
		$start = false;
		if($params['chapterId']){
			foreach($data['chapters'] as $k=>$v){
				if($start) $data['updatenum']=$data['updatenum']+1;
				if($params['chapterId']==$v['chapterid']) $start = true;
			}
		}else $data['updatenum']=count($data['chapters']);
		$data['aid'] = $params['aid'];
		return $data;
	}
	/**
	 * 推送文章的章节目录,章节目录直接提取源文章的所有章节目录，根据渠道的配置参数分为：读取全部章节，读取全部免费章节，不读取章节
	 * @param unknown $params
	 * 2014-6-17 下午5:21:43
	 */
	public function chapters($params = array()){
		$articleLib =  $this->load('article','article');
		$data = array();
		$channel = $this->getChannel();
		$data['channel'] = $channel;
		// 		$data['cover_dir'] =  JIEQI_URL."/api/api_image";
		$data['HTTP_HOST'] = $_SERVER['HTTP_HOST'];
		//不读取章节
		if(!$channel['setting']['getdata']['nosetchapters'])exit;
		$ids = $this->getArticleList($channel['channelid']);//推送书ID
		$articleids  = array_keys($ids);
		if(count($articleids)>0) {
			//读取的文章不在渠道数据池内
			if(!in_array($params['aid'],$articleids)) exit('3');
		}
		$this->db->init ( 'article', 'articleid', 'article' );
		$article = $this->db->get ($params['aid'] );
		if(!$article) exit('4');
		$data ['article'] = $articleLib->article_vars($article);
		//jieqi_article_stat 属性榜
		$this->db->init ( 'stat', 'statid', 'article' );
		$this->db->setCriteria ( new Criteria ( 'articleid', $params['aid']));
		$this->db->criteria->add(new Criteria('mid','visit','='));
		$this->db->queryObjects();
		$statamout=$this->db->getObject();
		if(is_object($statamout)){
			$data ['article']['allvisit'] = $statamout->getVar('total');
			$data ['article']['monthvisit'] = $statamout->getVar('month');
			$data ['article']['weekvisit'] = $statamout->getVar('week');
			$data ['article']['dayvisit'] = $statamout->getVar('day');
		}else{
			$data ['article']['allvisit'] = $data ['article']['monthvisit'] = $data ['article']['weekvisit'] = $data ['article']['dayvisit'] = 0;
		}
		
		$articletype=intval($data ['article']['articletype']);
		if(($articletype & 2)>0) $data['article']['articletype'] = 1;
		
		if(isset($channel['setting']['getdata']['chaptersource']) && $channel['setting']['getdata']['chaptersource']){
			//数据池章节数据
			$this->db->init ('chapter','pcid','pooling' );
			$this->db->setCriteria ();
			$this->db->criteria->setFields('chaptertype,chapterid,chaptername,chapterorder,size,adddate,isvip,adddate');
			$this->db->criteria->add(new Criteria('paid',$ids[$params['aid']]));
			$this->db->criteria->add(new Criteria('channelid',$channel['channelid']));
			$this->db->criteria->add(new Criteria ( 'articleid', $params['aid']));
			if($params['read_chapter']){
				//只读取章节
				$this->db->criteria->add(new Criteria('chaptertype', 0));
			}
			$this->db->criteria->setSort('chapterorder');
			$this->db->criteria->setOrder('ASC');
			$this->db->queryObjects();
			$data['chapters']  = array();;
			$k=0;
			while($v = $this->db->getObject()){
				$data['chapters'][$k] = array(
						'chaptertype'=>$v->getVar('chaptertype', 'n'),
						'chaptertype_tag'=>$v->getVar('chaptertype', 'n')==0?'章节':'卷',
// 						'chapterid'=>$v->getVar('pcid'),//数据池章节ID
						'chapterid'=>$v->getVar('chapterid'),//书海站章节Id
						'chapterorder'=>$v->getVar('chapterorder'),
						'chaptername'=>str_replace(array('&nbsp;','<br />','&amp;nbsp;'),array(' ','',' '),$v->getVar('chaptername','n')),
						'size'=>$v->getVar('size','n'),
						'saleprice'=>$v->getVar('saleprice','n'),//数据池没有此数据
						'lastupdate'=>$v->getVar('adddate','n'),
						'url'=>'http://'.$_SERVER['HTTP_HOST'].'/?ac=chapter&aid='.$params['aid'].'&cid='.$v->getVar('chapterid'),
						'isvip'=>$v->getVar('isvip','n'),
						'vchapterid'=>$v->getVar('vchapterid','n'),
						'postdate'=>$v->getVar('adddate','n')
				);
				$k++;
			}
		}else{
			//书海站章节数据
			$this->db->init ('chapter','chapterid','article' );
			$this->db->setCriteria ( new Criteria ( 'articleid', $params['aid']));
			//根据渠道配置信息添加查询条件：读取全部章节2|读取全部免费章节1
			//if($channel['setting']['getdata']['nosetchapters'] == 1){
				//$this->db->criteria->add(new Criteria('isvip', 0,'='));//免费
			//}
			$this->db->criteria->add(new Criteria('display', 2,'<'));//审核通过的
			if($params['read_chapter']){
				//只读取章节
				$this->db->criteria->add(new Criteria('chaptertype', 0));
			}
			$this->db->criteria->setSort('chapterorder');
			$this->db->criteria->setOrder('ASC');
			$this->db->queryObjects();
			$data['chapters']  = array();;
			$k=0;
			while($v = $this->db->getObject()){
			    if($channel['setting']['getdata']['nosetchapters'] == 1){
				     if($v->getVar('isvip','n')) break;
				}
				$data['chapters'][$k] = array(
						'chaptertype'=>$v->getVar('chaptertype', 'n'),
						'chaptertype_tag'=>$v->getVar('chaptertype', 'n')==0?'章节':'卷',
						'chapterid'=>$v->getVar('chapterid'),
						'chapterorder'=>$v->getVar('chapterorder'),
						'chaptername'=>str_replace(array('&nbsp;','<br />','&amp;nbsp;'),array(' ','',' '),$v->getVar('chaptername','n')),
						'size'=>$v->getVar('size','n'),
						'saleprice'=>$v->getVar('saleprice','n'),
						'lastupdate'=>$v->getVar('lastupdate','n'),
						'url'=>'http://'.$_SERVER['HTTP_HOST'].'/?ac=chapter&aid='.$params['aid'].'&cid='.$v->getVar('chapterid'),
						'isvip'=>$v->getVar('isvip','n'),
						'vchapterid'=>$v->getVar('vchapterid','n'),
						'postdate'=>$v->getVar('postdate','n')
				);
				$k++;
			}
		}
		// 		$data['chapters'] = $chapters;
		//数据池文章属性 优先于 源文章属性
		//articlename,intro,image,limage,simage
		$this->db->init('article', 'paid', 'pooling');
		$particle = $this->db->get ($ids[$params['aid']]);
		if($particle['articlename']){
			$data['article']['articlename'] = trim( $particle['articlename']);
		}
		if($particle['intro']){
			$data['article']['intro'] = trim($particle['intro']);
		}
		if($particle['image']){
			//$data['article']['url_image_l'] = JIEQI_URL."/api/api_image".$particle['image'];
		}
		return $data;
	}
	/**
	 *
	 * 获取有效的渠道 配置域名
	 * 2014-6-16 下午1:49:42
	 */
	private function getChannelURL(){
	//print_r($_SERVER['HTTP_HOST']);
		$params = explode('.',$_SERVER['HTTP_HOST']);
		if(!$params[0] || !$params[1]){
			$this->printfail(LANG_ERROR_PARAMETER);
		}else {
			return  $params[0].'.'.$params[1];
		}
	}
	/**
	 * 查询渠道下的数据池文章列表，每个渠道下数据池文章是唯一的，根据此特性返回的数据格式为：key:articleid value:paid以方便调用通过articleid获取数据池文章。
	 * @param unknown $channelid	渠道Id
	 * @param unknown $pushflag		状态，0封禁 1正常
	 * @return multitype:array(articleid(推送文章id)=>paid(数据池文章id))
	 * 2014-6-16 下午3:57:28
	 */
	function getArticleList($channelid,$pushflag=1) {
		$this->db->init('article', 'paid', 'pooling');
		$this->db->setCriteria(new Criteria('channelid', $channelid));
		$this->db->criteria->add(new Criteria('pushflag', $pushflag));
		$this->db->criteria->setSort('lastdate');
		$this->db->criteria->setOrder('DESC');
		$this->db->queryObjects();
		$ids = array();
		while($v = $this->db->getObject()){
			$ids[$v->getVar('articleid','n')] = $v->getVar('paid','n');
		}
		return $ids;
	}
	/**
	 * 获取渠道信息
	 * @return 渠道数组对象
	 * 2014-6-16 下午2:55:00
	 */
	function getChannel(){
		$url =$this->getChannelURL();
		if(!$url) exit('0');
		$ch = $this->load('channel', 'pooling');//加载自定义缓存类
		$ch->setCriteria(new Criteria('url', $url));
		$ch->queryObjects();
		$channel = $ch->getObject();
		if (is_object ( $channel )) {
			//验证data，ip，open
			eval('$setting = '.$channel->getVar('setting','n').';');//字符串转数组
			//判断IP
			$ip = $this->getIp();
			if(isset($setting['getdata']) && preg_match('/('.$setting['getdata']['ip'].')/is', $ip) && $channel->getVar('statu','n')){
				$channel->setVar('setting',$setting);
				//对象转数组
				foreach($channel->getVars() as $k=>$v){
					$ret[$k] = $channel->getVar($k,'n');
				}
				return $ret;
			}
		}
		exit('channel is null');
	}

/**
	 * 显示老板电子书阅读图片
	 * @param unknown $aid
	 * @param unknown $cid
	 * @param unknown $sign
	 * 2014-8-25 上午10:55:20
	 */
	public function getOldVipContent($aid,$cid,$sign,$type){

		//cid匹配vchapterid，找到现在cid，查询内容
		if($type != 'newchapter'){
			$this->db->init ( 'chapter', 'chapterid', 'article' );
			$this->db->setCriteria ( new Criteria ( 'vchapterid', $cid) );
			if($this->db->getCount($this->db->criteria)){
				$this->db->queryObjects();
				$object=$this->db->getObject();
				//object-array
				$cid = $object->getVar ( 'chapterid' );
			}else{
				exit();
			}
		}
		$articleLib = $this->load ( 'article', 'article' );
		$articleLib->instantPackage ( $aid );
		$content = @$articleLib->getContent ( $cid );
		
		define ( 'JIEQI_NOCONVERT_CHAR', '1' );
		@ini_set ( 'memory_limit', '64M' ); // 设置允许使用的内存
		
		// 		jieqi_getconfigs ( JIEQI_MODULE_NAME, 'configs' );
		// 		$jieqiConfigs ['obook'] ['obkpictxt'] = '20000'; // 一张图片显示多少字节
		// 		$jieqiConfigs ['obook'] ['obkpictxt'] = intval ( $jieqiConfigs ['obook'] ['obkpictxt'] );
		
		
		include_once (JIEQI_ROOT_PATH . '/include/changecode.php');
		include_once (JIEQI_ROOT_PATH . '/lib/text/textfunction.php');
		include_once (JIEQI_ROOT_PATH . '/lib/image/imagetext.php');
		$outstr = preg_replace ( array (
				'/[\t]+/'
		), '', $content);
		// 		if ($_REQUEST ['pic'] > 0) {
		// 			$_REQUEST ['pic'] = intval ( $_REQUEST ['pic'] );
		// 			$outstr = jieqi_substr ( $outstr, ($_REQUEST ['pic'] - 1) * $jieqiConfigs ['obook'] ['obkpictxt'], $jieqiConfigs ['obook'] ['obkpictxt'], '' );
		// 		}
		// 		if (! empty ( $jieqiConfigs ['obook'] ['obookreadhead'] ))
			// 			$outstr = $jieqiConfigs ['obook'] ['obookreadhead'] . "\r\n" . $outstr;
			// 		if (! empty ( $jieqiConfigs ['obook'] ['obookreadfoot'] ))
				// 			$outstr .= "\r\n" . $jieqiConfigs ['obook'] ['obookreadfoot'];
		$outstr = jieqi_limitwidth ( $outstr, 80 );
		
		
		
		
		
		// 文字水印
		$tmp = '<{$userid}>';
		$watertext = str_replace ( array (
				'<{$userid}>',
				'<{$username}>',
				'<{$date}>',
				'<{$time}>'
		), array (
				$_SESSION ['jieqiUserId'],
				$_SESSION ['jieqiUserName'],
				date ( JIEQI_DATE_FORMAT, JIEQI_NOW_TIME ),
				date ( JIEQI_TIME_FORMAT, JIEQI_NOW_TIME )
		), $tmp );
		if (strlen ( $watertext ) < 10)
			$watertext = sprintf ( '%10s', $watertext );
		
		$charsetary = array (
				'gb2312' => 'gb',
				'gbk' => 'gb',
				'gb' => 'gb',
				'big5' => 'big5',
				'utf-8' => 'utf8',
				'utf8' => 'utf8'
		);
		$fontcharset = JIEQI_SYSTEM_CHARSET;
		// 段首空格问题
		if (JIEQI_SYSTEM_CHARSET == 'gb2312' || JIEQI_SYSTEM_CHARSET == 'gbk')
			$outstr = str_replace ( '    ', chr ( 161 ) . chr ( 161 ) . chr ( 161 ) . chr ( 161 ), $outstr );
		elseif (JIEQI_SYSTEM_CHARSET == 'big5')
		$outstr = str_replace ( '    ', chr ( 161 ) . chr ( 64 ) . chr ( 161 ) . chr ( 64 ), $outstr );
		if (JIEQI_SYSTEM_CHARSET != JIEQI_CHAR_SET) {
			if ((JIEQI_SYSTEM_CHARSET == 'gb2312' || JIEQI_SYSTEM_CHARSET == 'gbk') && JIEQI_CHAR_SET == 'big5') {
				if (! empty ( $jieqiConfigs ['obook'] ['obkcharconvert'] )) {
					$outstr = jieqi_gb2big5 ( $outstr );
					$watertext = jieqi_gb2big5 ( $watertext );
					$fontcharset = JIEQI_CHAR_SET;
				}
			} elseif (JIEQI_SYSTEM_CHARSET == 'big5' && (JIEQI_CHAR_SET == 'gb2312' || JIEQI_CHAR_SET == 'gbk')) {
				if (! empty ( $jieqiConfigs ['obook'] ['obkcharconvert'] )) {
					$outstr = jieqi_big52gb ( $outstr );
					$watertext = jieqi_big52gb ( $watertext );
					$fontcharset = JIEQI_CHAR_SET;
				}
			}
		}
		$changefun = '';
		if (isset ( $charsetary [$fontcharset] ))
			$changefun = 'jieqi_' . $charsetary [$fontcharset] . '2utf8';
		if (function_exists ( $changefun )) {
			$outstr = call_user_func ( $changefun, $outstr );
			$watertext = call_user_func ( $changefun, $watertext );
		}
		$img = new ImageText ();
		$img->set ( 'text', $outstr );
		$img->set ( 'startx', 20 );
		$img->set ( 'starty', 50 );
		$img->set ( 'fontsize', 15 );
		$img->set ( 'fontfile', JIEQI_ROOT_PATH.'/wqy-microhei.ttc');
		$img->set ( 'angle', 0);
		$img->set ( 'imagecolor', '#CCDAED' );
		$img->set ( 'textcolor', '#000000' );
		$img->set ( 'shadowcolor','#000000');
		$img->set ( 'shadowdeep', 0);
		$img->set ( 'imagetype', 'jpg');
		// if(isset($jieqiConfigs['obook']['obkwatertext'])) $img->set('watertplace', intval($jieqiConfigs['obook']['obkwatertext']));
		// else $img->set('watertplace', 2); //默认平蹋思嫒菀郧俺绦?
		// $img->set('watertext', $watertext);
		$img->set ( 'watercolor', '#ff6600' );
		$img->set ( 'watersize',16);
		$img->set ( 'waterangle', 45 );
		$img->set ( 'waterpct', 30 );
		// 		$jieqiConfigs ['obook'] ['jpegquality'] = intval ( $jieqiConfigs ['obook'] ['jpegquality'] );
		// 		if ($jieqiConfigs ['obook'] ['jpegquality'] >= 0 && $jieqiConfigs ['obook'] ['jpegquality'] <= 100)
		$img->set ( 'jpegquality', 90 );
		// 图片水印
		// 		$jieqiConfigs ['obook'] ['obookwater'] = intval ( $jieqiConfigs ['obook'] ['obookwater'] );
		// 		if ($jieqiConfigs ['obook'] ['obookwater'] > 0)
			// 			$img->set ( 'wateriplace', $jieqiConfigs ['obook'] ['obookwater'] );
			// 		$jieqiConfigs ['obook'] ['obookwtrans'] = intval ( $jieqiConfigs ['obook'] ['obookwtrans'] );
			// 		if ($jieqiConfigs ['obook'] ['obookwtrans'] >= 1 && $jieqiConfigs ['obook'] ['obookwtrans'] <= 100)
		$img->set ( 'wateritrans', 30 );
		
			// 		if (! empty ( $jieqiConfigs ['obook'] ['obookwimage'] ) && is_file ( $jieqiModules ['obook'] ['path'] . '/images/' . $jieqiConfigs ['obook'] ['obookwimage'] ))
		
		global $jieqiModules;
		$img->set ( 'waterimage', $jieqiModules ['system'] ['path'] . '/images/qqonline.gif');
		$img->display ();
	}





/*------------------------------- 以下为数据同步  ---------------------------------------------*/
	
	
	function handleStatamout(){
		$this->db->init('statamout','statamoutid','article');
		//处理jieqi_article_statamout的重复数据
		//取出所有的重复articleid
		//循环处理，查询出重复记录合并为一条数据，然后删除一条
		$setarticle = $this->db->selectsql ('SELECT articleid, COUNT( * ) AS count FROM jieqi_article_statamout GROUP BY articleid HAVING count >1');
		$i = 0;
		foreach ($setarticle as $k=>$v){
			$stat = $this->db->selectsql ('SELECT * FROM jieqi_article_statamout where articleid = '.$v['articleid']);
			if(count($stat) == 2){
				$i++;
				//合并数据
				$stat[0]['visit'] = $stat[0]['visit']+$stat[1]['visit'];
				$stat[0]['vote'] = $stat[0]['vote']+$stat[1]['vote'];
				$stat[0]['goodnum'] = $stat[0]['goodnum']+$stat[1]['goodnum'];
				$stat[0]['vipvote'] = $stat[0]['vipvote']+$stat[1]['vipvote'];
				$stat[0]['sale'] = $stat[0]['sale']+$stat[1]['sale'];
				$this->db->edit($stat[0]['statamoutid'], $stat[0]);
				$this->db->delete ( $stat[1]['statamoutid'] );
				echo $i.'：articleid='.$v['articleid'].'...ok<br>';
			}else{
				echo 'is not 2';
			}
		}
		echo '...end';
	}
	
	/**
	 * 同步 读取和推送的数据 到 pooling模块
	 *
	 * 2014-6-25 上午10:21:42
	 */
	function sync(){
		$map = $this->sync_outapi();
		$this->sync_outarticle($map);
		$map1 = $this->sync_apisite();
		$this->sync_apiarticle($map1);
		exit;
	}
	function sync_article_api($params){
		if(!$params['type']){
			exit('typ is null');
		}
		if(!$params['cid']){
			exit('channelid is null');
		}
		$this->db->init('api', 'id', 'article');
		$this->db->setCriteria();
		$this->db->criteria->setSort('id');
		$this->db->criteria->add ( new Criteria ( 'type', $params['type'] ) );
		$this->db->queryObjects();
		//渠道原始数据
		$data = array();
		while($v = $this->db->getObject()){
			$row = array();
			foreach($v->getVars() as $k=>$v){
				$row[$k] = 	$v['value'];
			}
			$data[] = $row;
		}
		$type = array(
				1=>'souhu',
				2=>'panda',
				3=>'zhangyue',
				4=>'sq',
				5=>'baidu',
				6=>'baiyue',
				7=>'g3',
				8=>'ifeng',
				9=>'mobile',
				10=>0,
				11=>'mi',
				12=>'sina',
				13=>'tian',
				14=>'kj',
				15=>0,
				16=>'azrd',
				17=>'hawei',
				18=>'mpu'
		);
		$i = 0;
		foreach($data as $k=>$v){
			$i++;
			$this->db->init ( 'article', 'articleid', 'article' );
			$ae = $this->db->get ( $data[$k]['articleid']);
			if(!$ae){
				echo '无效的articleid：'.$data[$k]['articleid'];
				continue;
			}

			//获取指定渠道的文章
			$article = array();
			$article['channelid'] = $params['cid'];//渠道ID使用新ID
			if($params['type'] == 9){
				//移动
				$article['articlename'] = $data[$k]['articlename'];
				$article['apiId'] = $data[$k]['apiname'];//移动端的ID
			}elseif ($params['type'] == 7){//3g原始书名
				$article['articlename'] = $ae['articlename'];
			}else{
				$article['articlename'] = $data[$k]['apiname'] ? $data[$k]['apiname'] : $data[$k]['articlename'];
			}
			$article['articleid'] = $data[$k]['articleid'];
			$article['pushflag'] = 1;
			$article['adddate'] = $data[$k]['uploadtime'];
			$article['intro'] = $ae['intro'];
			//jieqi_article_channel
			$this->db->init('article', 'paid', 'pooling');
			//渠道下的书不重复添加
			$this->db->setCriteria ( new Criteria ( 'channelid', $params['cid'] ) );
			$this->db->criteria->add ( new Criteria ( 'articleid', $article['articleid'] ) );
			$this->db->criteria->setLimit ( 1 );
			$this->db->queryObjects ();
			$tmpa = $this->db->getObject ();
			if (is_object ( $tmpa )) {
				echo $i.':重复的文章：'.$data[$k]['articlename'].'<br/>';
				continue;
			}
			$attachid = $this->db->add ($article);
			if($attachid){
				echo $i.':同步完成：'.$article['articlename'].'<br/>';
			}else{
				echo $i.':同步失败：'.$article['articlename'].'<br/>';
			}
		}
	}
	//jieqi_manage_apiarticle
	private function sync_apiarticle($map){
		echo '<br>------------------开始同步jieqi_manage_apiarticle-------------------';
		$this->db->init('apiarticle', 'id', 'manage');
		$this->db->setCriteria();
		$this->db->criteria->setSort('id');
		$this->db->queryObjects();
		//渠道原始数据
		$data = array();
		while($v = $this->db->getObject()){
			$row = array();
			foreach($v->getVars() as $k=>$v){
				$row[$k] = 	$v['value'];
			}
			$data[] = $row;
		}
		$this->db->init('article', 'paid', 'pooling');
		foreach($data as $k=>$v){
			$article = array();
			if(!empty($map) && !empty($map[$data[$k]['sid']])){
				$article['channelid'] = $map[$data[$k]['sid']];//渠道ID使用新ID
			}else {
				echo 'error';
				exit;
			}
			$article['articlename'] = $data[$k]['articlename'];
			$article['articleid'] = $data[$k]['articleid'];
// 			$article['lastvolumeid'] = $data[$k]['lastvolumeid'];
// 			$article['lastvolume'] = $data[$k]['lastvolume'];
// 			$article['lastchapterid'] = $data[$k]['lastchapterid'];
// 			$article['lastchapter'] = $data[$k]['lastchapter'];
// 			$article['outchapters'] = $data[$k]['outchapters'];
			$article['fullflag'] = $data[$k]['fullflag'];
			$article['pushflag'] = $data[$k]['statu'];
			$article['lastdate'] = $data[$k]['lastupdate'];
			$article['adddate'] = $data[$k]['postdate'];
			$article['image'] = $data[$k]['image'];
			$article['limage'] = $data[$k]['image'];
			$article['simage'] = $data[$k]['simage'];
			$article['intro'] = $data[$k]['intro'];
			$attachid = $this->db->add ($article);
			if($attachid){
				echo '<br>同步完成：'.$article['articlename'];
			}else{
				echo '<br>同步失败：'.$article['articlename'];
			}
		}
		echo '<br>------------------jieqi_manage_apiarticle处理完成-------------------';
	}
	//jieqi_manage_apisite
	private function sync_apisite(){
		echo '<br>------------------开始同步jieqi_manage_apisite-------------------';
		$this->db->init('apisite', 'sid', 'manage');
		$this->db->setCriteria();
		$this->db->criteria->setSort('sid');
		$this->db->queryObjects();
		//渠道原始数据
		$data = array();
		while($v = $this->db->getObject()){
			$row = array();
			foreach($v->getVars() as $k=>$v){
				$row[$k] = 	$v['value'];
			}
			$data[] = $row;
		}
		//jieqi_pooling_channel
		$this->db->init('channel', 'channelid', 'pooling');
		$map = array();
		foreach($data as $k=>$v){
			$channel = array();
			// 			$channel['channelid'] = $data[$k]['sid'];
			$channel['channelname'] = $data[$k]['sitename'];
			$channel['type'] = 1;//0推送1读取
			$channel['url'] = $data[$k]['siteurl'];
			$channel['setting'] = $data[$k]['setting'];
			$channel['statu'] = $data[$k]['statu'];
			$channel['listorder'] = $data[$k]['listorder'];
			$channel['editdate'] = $data[$k]['editdate'];
			$channel['postdate'] = $data[$k]['postdate'];
			//description 使用 setting中的备注
			eval('$setting = '.$channel['setting'].';');//字符串转数组
			if($setting['beizhu']){
				$channel['description'] = $setting['beizhu'];
			}
			$attachid = $this->db->add ($channel);
			if($attachid){
				$map[$data[$k]['sid']] = $attachid;
				echo '<br>同步完成：'.$channel['channelname'].'。原id='.$data[$k]['sid'].'新id='.$attachid;
			}else{
				echo '<br>同步失败：'.$channel['channelname'];
			}
		}
		echo '<br>------------------jieqi_manage_apisite处理完成-------------------';
		return $map;
	}
	//jieqi_manage_outarticle
	private function sync_outarticle($map){
		echo '<br>----------------开始同步jieqi_manage_outarticle------------------------';
		$this->db->init('outarticle ', 'id', 'manage');
		$this->db->setCriteria();
		$this->db->criteria->setSort('id');
		$this->db->queryObjects();
		//渠道原始数据
		$data = array();
		while($v = $this->db->getObject()){
			$row = array();
			foreach($v->getVars() as $k=>$v){
				$row[$k] = 	$v['value'];
			}
			$data[] = $row;
		}

		foreach($data as $k=>$v){
			$article = array();
			if(!empty($map) && !empty($map[$data[$k]['sid']])){
				$article['channelid'] = $map[$data[$k]['sid']];//渠道ID使用新ID
			}else {
				echo 'error';
				exit;
			}
			$article['articlename'] = $data[$k]['articlename'];
			$article['articleid'] = $data[$k]['articleid'];
			$article['lastvolumeid'] = $data[$k]['lastvolumeid'];
			$article['lastvolume'] = $data[$k]['lastvolume'];
			//lastchapterid使用新的ID
			if($data[$k]['lastchapter'] && $data[$k]['lastchapterid']){
				if(is_numeric($data[$k]['lastchapterid'])){
					$this->db->init('chapter', 'chapterid', 'article');
					$chapter = $this->db->get($data[$k]['lastchapterid']);
				}elseif(strtolower(substr($data[$k]['lastchapterid'],0,1)) == 'v'){
					$cid = substr($data[$k]['lastchapterid'],1);
// 					$this->db->init('ochapter ', 'ochapterid', 'obook');
// 					$ochapter = $this->db->get($cid);
// 					if($ochapter){
						//这里应该添加articleid，vchapterid查询新的chapter
						//数据测试临时添加articleid，chaptername后续chapter和ochapter同步后需要改正。
						$chapters = $this->db->selectsql ( 'select * from ' . jieqi_dbprefix ( "article_chapter" ) . " where vchapterid=" .$cid. " and articleid=" . $article ['articleid'] );
						if(count($chapters) == 1){
							$chapter = $chapters[0];
						}else {
							echo '没有找到'.$article['articlename'].'最后更新的章节：'.$cid;
// 							$chapters = $this->db->selectsql ( 'select * from ' . jieqi_dbprefix ( "article_chapter" ) . " where chaptername='" . trim($ochapter['chaptername'],'　') . "' and articleid=" . $article ['articleid']);
// 							if(count($chapters) == 1){
// 								$chapter = $chapters[0];
// 							}else{
// 								echo $data[$k]['id'].'chapter error'.'select * from ' . jieqi_dbprefix ( "article_chapter" ) . " where chaptername='" . $ochapter['chaptername'] . "' and articleid=" . $article ['articleid'];
// 							}
						}
// 					}else{
// 						echo 'ochapter del';
// 					}
				}
				if(!empty($chapter) && $chapter['articleid'] == $article['articleid']){
					$article['lastchapterid'] = $chapter['chapterid'];
					$article['lastchapter'] = $chapter['chaptername'];
				}
			}else{
				$article['lastchapterid'] = 0;
				$article['lastchapter'] = '';
			}
			$article['outchapters'] = $data[$k]['outchapters'];
			$article['fullflag'] = $data[$k]['fullflag'];
			$article['pushflag'] = $data[$k]['statu'];
			$article['lastdate'] = $data[$k]['lastupdate'];
			$article['adddate'] = $data[$k]['postdate'];
			$article['image'] = $data[$k]['image'];
// 			$article['limage'] = $data[$k]['image'];
// 			$article['simage'] = $data[$k]['simage'];
			$article['intro'] = $data[$k]['intro'];
			//jieqi_article_channel
			$this->db->init('article', 'paid', 'pooling');
			$attachid = $this->db->add ($article);
			if($attachid){
				echo '<br>同步完成：'.$article['articlename'];
			}else{
				echo '<br>同步失败：'.$article['articlename'];
			}
		}
		echo '<br>----------------jieqi_manage_outarticle处理完成------------------------';
		//数据池文章
	}
	//jieqi_manage_outapi
	private function sync_outapi(){
		echo '<br>---------------开始同步jieqi_manage_outapi数据---------------------';
		$this->db->init('outapi', 'sid', 'manage');
		$this->db->setCriteria();
		$this->db->criteria->setSort('sid');
		$this->db->queryObjects();
		//渠道原始数据
		$data = array();
		while($v = $this->db->getObject()){
			$row = array();
			foreach($v->getVars() as $k=>$v){
				$row[$k] = 	$v['value'];
			}
			$data[] = $row;
		}
		//jieqi_pooling_channel
		$this->db->init('channel', 'channelid', 'pooling');
		$map = array();
		foreach($data as $k=>$v){
			$channel = array();
// 			$channel['channelid'] = $data[$k]['sid'];
			$channel['channelname'] = $data[$k]['sitename'];
			$channel['type'] = 0;//0推送1读取
			$channel['url'] = $data[$k]['siteurl'];
			$channel['setting'] = $data[$k]['setting'];
			$channel['statu'] = $data[$k]['statu'];
			$channel['listorder'] = $data[$k]['listorder'];
			$channel['editdate'] = $data[$k]['editdate'];
			$channel['postdate'] = $data[$k]['postdate'];
			//description 使用 setting中的备注
			eval('$setting = '.$channel['setting'].';');//字符串转数组
			if($setting['beizhu']){
				$channel['description'] = $setting['beizhu'];
			}
			$attachid = $this->db->add ($channel);
			if($attachid){
				$map[$data[$k]['sid']] = $attachid;
				echo '<br>同步完成：'.$channel['channelname'].'。原id='.$data[$k]['sid'].'新id='.$attachid;
			}else{
				echo '<br>同步失败：'.$channel['channelname'];
			}
		}
		echo '<br>---------------jieqi_manage_outapi处理完成---------------';
		return $map;
	}
	/**
	 * 处理isvip=1 saleprice=0的章节
	 *
	 * 2014-7-16 上午11:14:45
	 */
	public function handleChapter(){
		$articleLib =  $this->load('article','article');
		//查询所有 isvip=1 size>0 saleprice=0 chaptertype=0的章节
		$this->db->init('chapter', 'chapterid', 'article');
		$this->db->setCriteria ( new Criteria ( 'isvip', 1 ) );
		$this->db->criteria->add ( new Criteria ( 'size', 0 ,'>') );
		$this->db->criteria->add ( new Criteria ( 'saleprice', 0) );
		$this->db->criteria->add ( new Criteria ( 'chaptertype', 0 ) );
		$this->db->queryObjects ();
		$i = 0;
		$articleLib->jieqiConfigs ['article'] ['wordsperegold'] = ceil ( $articleLib->jieqiConfigs ['article'] ['wordsperegold'] ) * 2; // 2倍整数
		while($chapter = $this->db->getObject()){
			$i++;
			$saleprice = 0;
			$size = $chapter->getVar('size');
			if ($articleLib->jieqiConfigs ['article'] ['priceround'] == 1) {
				$saleprice = floor ( $size / $articleLib->jieqiConfigs ['article'] ['wordsperegold'] ); // 向下舍入，取整。
			} elseif ($this->jieqiConfigs ['article'] ['priceround'] == 2) {
				$saleprice = ceil ( $size / $articleLib->jieqiConfigs ['article'] ['wordsperegold'] ); // 向上舍入，取整。
			} else {
				$saleprice = round ( $size / $articleLib->jieqiConfigs ['article'] ['wordsperegold'] ); // 四舍五入
			}
			if(!$saleprice) $saleprice = 1;
			$this->db->edit($chapter->getVar('chapterid'),array('saleprice'=>$saleprice));
			echo $i.'、'.'size：'.$size.'；saleprice：'.$saleprice.'<br/>';
		}
		echo '....end';
	}
}
?>
