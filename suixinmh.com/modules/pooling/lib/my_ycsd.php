<?php
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/icollect.php');
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/baseCollect.php');
// include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
/**
 * 原创书殿采集接口实现类
 * @author chengyuan  2014-8-21
 *
 */
class MyYcsd extends baseCollect implements iCollect {
	/**
	 *	获取采集文章列表Url
	 * @return string
	 * 2014-9-16 下午1:58:58
	 */
	protected  function getBookListUrl(){
		return 'api.ycsd.cn/interface/shuhai/booklist/0';
	}
	/**
	 * 获取采集文章信息Url
	 * @return string
	 * 2014-9-16 下午1:58:58
	 */
	protected  function getBookInfoUrl($bookId){
		return 'api.ycsd.cn/interface/shuhai/book/'.$bookId;
	}
	
	public function collectList($cid,$page){
		$data = array();
		if(!$cid){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		$this->db->init( 'channel', 'channelid', 'pooling' );
		$channelLib = $this->load('channel', 'pooling');//加载channel自定义类
		if(!$channel = $channelLib->get($cid,true)) $this->printfail(LANG_ERROR_PARAMETER);
		$rdata = $this->parseXmlToArray($this->getBookListUrl());
		$row = array();
		//一条数据item=array还是多条数据itme=array('key'=>array())
		$items =$rdata['books']['book'];
		if(!array_key_exists(0,$items)){
			//一条数据特殊处理
			$temp = $items;
			$items = array($temp);
		}
		foreach ( $items as $item ){
			$article = $this->simplePackingArticle($item,$channel);
			$row[$article['articleid']] = $article;
		}
		$data['rows'] = $row;
		$data['channel'] = $channel;
		return $data;
	}
	public function simplePackingArticle($item,$channel){
		$article = array();
		$article['articleid'] = $item['id']['value'];
		$article['articlename'] = trim($item['booktitle']['value']);
		$article['lastupdate'] = $item['updatetime']['value'];
		return $article;
	}
	/**
	 * 获取渠道采集的文章列表，可以通过aids指定渠道文章。
	 * @param unknown $cid
	 * @param unknown $page 暂时不支持页码
	 * @param unknown $aids 指定渠道内书号array(123,345,678);
	 * 2014-8-19 下午1:52:02
	 */
	public function articleList($cid,$page,$aids=array()){
		$data = array();
		if(!$cid){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		$this->db->init( 'channel', 'channelid', 'pooling' );
		$channelLib = $this->load('channel', 'pooling');//加载channel自定义类
		if(!$channel = $channelLib->get($cid,true)) $this->printfail(LANG_ERROR_PARAMETER);
		$rdata = $this->parseXmlToArray($this->getBookListUrl());
		$row = array();
		//一条数据item=array还是多条数据itme=array('key'=>array())
		$items =$rdata['books']['book'];
		if(!array_key_exists(0,$items)){
			//一条数据特殊处理
			$temp = $items;
			$items = array($temp);
		}
		if(is_array($aids) && !empty($aids)){
			$ct = count($aids);
			$t = 0;
			foreach ( $items as $item ){
				if(in_array($item['id']['value'], $aids)){
					$t++;
					//获取书籍信息
					$data = $this->parseXmlToArray($this->getBookInfoUrl($item['id']['value']));
					$bookinfo = $data['book'];
					$bookinfo['updatetime'] = array('value'=>$item['updatetime']['value']);
					$article = $this->packingArticle($bookinfo,$channel);
					$row[$article['articleid']] = $article;
					if($t == $ct){
						break;
					}
				}
			}
		}else{
			foreach ( $items as $item ){
				//获取书籍信息
				$data = $this->parseXmlToArray($this->getBookInfoUrl($item['id']['value']));
				$bookinfo = $data['book'];
				$bookinfo['updatetime'] = array('value'=>$item['updatetime']['value']);
				$article = $this->packingArticle($bookinfo,$channel);
				$row[$article['articleid']] = $article;
			}
		}
		$data['rows'] = $row;
		$data['channel'] = $channel;
		return $data;
	}
	/**
	 * 实现接口，重写父类方法
	 * @param unknown $item
	 * @param unknown $channel
	 * @return Ambigous <multitype:NULL string number Ambigous <NULL, string, number> , unknown>
	 * 2014-8-22 上午11:36:58
	 */
	public function packingArticle($item,$channel,$loadLocalArticle = true){
		$article = array();
		$article['articleid'] = $item['id']['value'];
		$article['articlename'] = trim($item['title']['value']);
		$article['intro'] = $item['summary']['value'];
		$article['author'] = $item['author']['value'];
		$article['sort'] = $item['category']['value'];
		//转化成时间轴，统一格式
		$article['lastupdate'] = $item['updatetime']['value'];
// 		if(strchr($article['lastupdate'], '/') && $article['lastupdate']){
// 			$article['lastupdate'] = strtotime($article['lastupdate']);
// 		}
		$article['postdate'] = $article['lastupdate'];
// 		if(strchr($article['postdate'], '/') && $article['postdate']){
// 			$article['postdate'] = strtotime($article['postdate']);
// 		}
		$article['isvip'] = $item['isVip']['value'];
		$article['fullflag'] = $item['isFull']['value'];
		$article['size'] = $item['size']['value'];
		$article['articlelpic'] = $item['cover']['value'];
		$article['articlespic'] = $item['cover']['value'];
		$article['chaptersurl'] = $item['url']['value'];

		$article['keywords'] = $item['tag']['value'];
		$article['agent'] = '';
		$article['authorid'] = 0;
		$article['permission'] = 1;//授权作品
		$article['firstflag'] = $channel['setting']['getdata']['firstflag'];
		$article['sortid'] = $this->getLocalSortId($item['sort']['value'],$channel);//重新计算和本站的对应关系
		$article['siteid'] = $this->getSiteIdBySortId($article['sortid']);
		$article['notice'] = $item['role']['value'];
		$article['articletype'] = $article['isvip'];//1=>vip
		$article['display'] = 1;//需要审核
		//是否已经入库，书名+首发状态 唯一条件
		if($loadLocalArticle){
			$article['new'] = 1;
			$mateArticle = $this->isExists($article['articleid'],$channel['channelid']);
			if(!empty($mateArticle)){
				$article['new'] = 0;
				$article['mappingArticle'] = $mateArticle;
			}
		}
		return $article;
	}
	/**
	 * 原创书殿分类关系
	 * @see iCollect::getSortMapping()
	 */
// 	public function getSortMapping(){
// 		//todo 补全分类对应关系
// 		return array(
// 				'玄幻仙侠'=>'玄幻',
// 				'热血校园'=>'竞技',
// 				'都市暧昧'=>'都市',
// 				'军事历史'=>'历史',
// 				'职场丽人'=>'言情',
// 				'游戏竞技'=>'网游',
// 				'灵异鬼怪'=>'恐怖'
// 			);
// 	}
	/* (non-PHPdoc)
	 * @see iCollect::getArticleUrl()
	*/
	public function parseChapters($url,$lastchapterid=0) {
		$rdata = $this->parseXmlToArray($url);
		$items =$rdata['chapters']['chapter'];
		if(!array_key_exists(0,$items)){
			$temp = $items;
			$items = array($temp);
		}
		if(is_numeric($lastchapterid) && $lastchapterid > 0){
			//倒叙定位
			for ($i = count($items)-1; $i >= 0; $i--) {
				if($lastchapterid == $items[$i]['id']['value']){
					return array_slice($items,$i);
				}
			}
			// 			foreach ($items as $key => $value) {
			// 				if($lastchapterid == $value['cid']['value']){
			// 					return array_slice($items,$key+1);
			// 				}
			// 			}
			return array();
		}
		return $items;
	}

	/**
	 * 重写封装章节，封装的格式请参考my_article中saveChatper函数
	 * @param unknown $item
	 * @return multitype:NULL number string
	 * 2014-8-22 下午4:46:13
	 */
	public function packingChapter($item){
		$chapter = array();
		$chapter['cid'] = $item['id']['value'];
		$chapter['chaptername'] = $item['title']['value'];
		$chapter['typeset'] = 1;//自动排版
		$chapter['chaptertype'] = $item['isVol']['value'];
		// 		$chapter['fullflag'] = $item['']['value'];
		$chapter['manual'] = '';
		$chapter['volumeid'] = '';
		$chapter['isvip'] = $item['isVip']['value'];
		$chapter['postdate'] = $item['updatetime']['value']?$item['updatetime']['value']:JIEQI_NOW_TIME;
// 		if(strchr($chapter['postdate'], '-')){
// 			$chapter['postdate'] = strtotime($chapter['postdate']);
// 		}
		$chapter['saleprice'] = $item['saleprice']['value'];
		$chapter['chaptercontent'] = '';
		if($item['url']['value']){
			$rdata = $this->parseXmlToArray($item['url']['value']);
			$chapter['chaptercontent'] = $rdata['content']['value'];
		}
		return $chapter;
	}
}
?>