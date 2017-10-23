<?php
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/icollect.php');
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/baseCollect.php');
// include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
/**
 * api采集默认的自定义类
 * @author chengyuan
 *
 */
class MyCollectDefault extends baseCollect implements iCollect {
	
	/*
	 * 采集列表，包含文章的基本信息：书号、书名
	 */
	public function collectList($cid,$page){
		$data = array();
		if(!$cid){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		$this->db->init( 'channel', 'channelid', 'pooling' );
		$channelLib = $this->load('channel', 'pooling');//加载channel自定义类
		if(!$channel = $channelLib->get($cid,true)) $this->printfail(LANG_ERROR_PARAMETER);
		if(!$channel['url']){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		$rdata = $this->parseXmlToArray($channel['url']);
		$row = array();
		//一条数据item=array还是多条数据itme=array('key'=>array())
		$items =$rdata['document']['items']['item'];
		if(!array_key_exists(0,$items)){
			//一条数据特殊处理
			$temp = $items;
			$items = array($temp);
		}
		foreach ( $items as $item ){
			$article = $this->simplePackingArticle($item, $channel);
			$row[$article['articleid']] = $article;
		}
		$data['rows'] = $row;
		$data['channel'] = $channel;
		return $data;
	}
	public function simplePackingArticle($item,$channel){
		return $this->packingArticle($item, $channel,false);
	}
	
	/**
	 * 获取渠道采集的文章列表，支持aids筛选
	 * @param unknown $cid
	 * @param unknown $page 暂时不支持页码
	 * @param unknown $aids 第三方书号
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
		if(!$channel['url']){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		$row = array();
		$rdata = $this->parseXmlToArray($channel['url']);
		//一条数据item=array还是多条数据itme=array('key'=>array())
		$items =$rdata['document']['items']['item'];
		if(!array_key_exists(0,$items)){
			//一条数据特殊处理
			$temp = $items;
			$items = array($temp);
		}
		foreach ( $items as $item ){
			//筛选
			if(!empty($aids) && !in_array($item['bookid']['value'],$aids)){
				continue;
			}
			$data = $this->parseXmlToArray($item['url']['value']);
			$article = $this->packingArticle($data['document']['info'],$channel);
			$row[$article['articleid']] = $article;
			if(!empty($aids) && count($row) == count($aids)){
				break;
			}
		}
		$data['rows'] = $row;
		$data['channel'] = $channel;
		return $data;
	}
	/**
	 * 封装文章对象
	 * @param unknown $item
	 * @param unknown $channel
	 * @return Ambigous <multitype:NULL string number Ambigous <NULL, string, number> Ambigous <unknown, number, unknown> , unknown>
	 * 2014-8-22 上午11:34:21
	 */
	public function packingArticle($item,$channel,$loadLocalArticle = true){
		$article = array();
		$article['articleid'] = $item['bookid']['value'];//第三方的书号
		$article['articlename'] = trim($item['title']['value']);
		$article['intro'] = $item['comment']['value'];
		$article['author'] = $item['author']['value'];
		$article['sort'] = $item['category']['value'];
		//默认时间轴
		//转化成时间轴，统一格式
		$article['lastupdate'] = $item['lastupdate']['value'];
		// 		if(strchr($article['lastupdate'], '-')){
		// 			$article['lastupdate'] = strtotime($article['lastupdate']);
		// 		}
		$article['postdate'] = $item['postdate']['value'];
		// 		if(strchr($article['postdate'], '-')){
		// 			$article['postdate'] = strtotime($article['postdate']);
		// 		}
		// 		$article['lastupdate'] = strtotime($item['lastupdate']['value']);
		// 		$article['postdate'] = strtotime($item['postdate']['value']);
		$article['isvip'] = $item['isvip']['value'];
		$article['fullflag'] = $item['fullflag']['value'];
		$article['size'] = $item['size']['value'];
		$article['articlelpic'] = $item['image_big']['value'];
		$article['articlespic'] = $item['image_small']['value'];
		$article['chaptersurl'] = $item['chaptersurl']['value'];
	
		$article['keywords'] = '';
		$article['agent'] = '';
		$article['authorid'] = 0;
		$article['permission'] = 1;//授权作品
		$article['firstflag'] = $channel['setting']['getdata']['firstflag'];
// 		$article['sortid'] = 1;//缺省
		$article['sortid'] = $this->getLocalSortId($item['category']['value'],$channel);
// 		if(is_array($channel['setting']['getdata']['category']) && array_key_exists($item['category']['value'], $channel['setting']['getdata']['category'])){
// 			$article['sortid'] = $channel['setting']['getdata']['category'][$item['category']['value']];//对应本站的sortid
// 		}
		$this->addConfig('article','sort');
		$sort = $this->getConfig('article','sort');
		$article['siteid'] = $sort[$article['sortid']]['siteid'];//分类所属渠道
		$article['notice'] = '';
		$article['articletype'] = $article['isvip'];//1=>vip
		$article['display'] = 1;//需要审核
		if($loadLocalArticle){
			$article['new'] = 1;
			$mappingArticle = $this->isExists($article['articleid'],$channel['channelid']);
			if(!empty($mappingArticle)){
				$article['new'] = 0;
				$article['mappingArticle'] = $mappingArticle;
			}
		}
		return $article;
	}
	//通过前台的配置获取匹配关系
	//'渠道分类'=>'书海分类'
// 	public function getSortMapping(){
// // 		return $this->setting['getdata']['category'];
// 	}
	/* (non-PHPdoc)
	 * @see iCollect::getArticleUrl()
	*/
	public function parseChapters($url,$lastchapterid=0) {
		$rdata = $this->parseXmlToArray($url);
		$items =$rdata['document']['items']['item'];
		if(!array_key_exists(0,$items)){
			$temp = $items;
			$items = array($temp);
		}
		if(is_numeric($lastchapterid) && $lastchapterid > 0){
			//倒叙定位
			for ($i = count($items)-1; $i >= 0; $i--) {
				if($lastchapterid == $items[$i]['cid']['value']){
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
		// TODO Auto-generated method stub
	}
	/**
	 * 封装章节
	 * @param unknown $item
	 * @return multitype:NULL number string
	 */
	public function packingChapter($item){
		$chapter = array();
		$chapter['cid'] = $item['cid']['value'];
		$chapter['chaptername'] = $item['chaptername']['value'];
		$chapter['typeset'] = 1;//自动排版
		$chapter['chaptertype'] = $item['chaptertype']['value'];
		// 		$chapter['fullflag'] = $item['']['value'];
		$chapter['manual'] = '';
		$chapter['volumeid'] = '';
		$chapter['isvip'] = $item['isvip']['value'];
		//默认当前时间
		$chapter['postdate'] = $item['postdate']['value']?$item['postdate']['value']:JIEQI_NOW_TIME;
		// 		if(strchr($chapter['postdate'], '-')){
		// 			$chapter['postdate'] = strtotime($chapter['postdate']);
		// 		}
		// 		$chapter['saleprice'] = $item['saleprice']['value'];
		$chapter['chaptercontent'] = '';
		if($item['chapterurl']['value']){
			$rdata = $this->parseXmlToArray($item['chapterurl']['value']);
			$chapter['chaptercontent'] = $rdata['document']['chaptercontent']['value'];
		}
		return $chapter;
	}
}
?>