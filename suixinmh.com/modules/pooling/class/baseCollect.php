<?php
/**
 * 采集实现接口的父类，提供默认的接口实现方法，子类需要实现iCollect接口
 * @author chengyuan  2014-8-21
 *
 */
include_once ($GLOBALS['jieqiModules']['pooling']['path'] . '/class/base.php');
abstract class  baseCollect extends base{

	function __construct() {
		$this->initDB();
	}
	/**
	 * 匹配本地分类ID，默认返回分类：1
	 * @param unknown $sort		渠道分类
	 * @return unknown|number
	 * 2014-8-21 下午3:38:10
	 */
	public  function getLocalSortId($sort,$channel){
		if(is_string($sort)){
			if(is_array($channel['setting']['getdata']['category']) && array_key_exists($sort, $channel['setting']['getdata']['category'])){
				return $channel['setting']['getdata']['category'][$sort];//对应本站的sortid
			}
// 			$sortArr =  $this->getSortMapping();//子类实现的接口方法
// 			$locaSort = $sortArr[$sort];
// 			$this->addConfig('article','sort');
// 			$sortArr = $this->getConfig('article','sort');
// 			foreach ( $sortArr as $k => $v ) {
// 				if($v['shortcaption'] == $locaSort){
// 					return $k;
// 				}
// 			}
		}
		return 1;
	}
	/**
	 * 解析xml为array
	 * @param unknown $url
	 * 2014-8-21 下午3:51:25
	 */
	public  function parseXmlToArray($url){
		$XML = $this->load('getxml','system');
		$rdata = $XML->getData($url);
		return $rdata;
	}
	/**
	 * 默认封装章节，封装的格式请参考my_article中saveChatper函数
	 * @param unknown $item
	 * @param unknown $channel
	 * @return multitype:NULL number string
	 * 2014-8-22 上午10:23:04
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

	
	
	/**
	 * 数据池pooling_article中是否存在
	 * <p>
	 * 如果存在，则返回pooling_article和article_article联合查询的数据数组
	 * @param unknown $apiId		第三方的书号
	 * @param unknown $channel		渠道ID
	 * @return multitype:unknown	映射关系数组
	 */
	public function isExists($apiId,$channelid){
		$poolArticle = array();
		//是否已经入库，渠道id+bookid 唯一条件
		$this->db->init('article','paid','pooling');
		$this->db->setCriteria(new Criteria('channelid', $channelid));
		$this->db->criteria->add(new Criteria('apiId', $apiId));
		$this->db->criteria->setTables(jieqi_dbprefix('pooling_article').' pa INNER JOIN '.jieqi_dbprefix('article_article').' aa ON pa.articleid=aa.articleid');
		$this->db->criteria->setFields('pa.paid,pa.lastvolumeid,pa.lastvolume,pa.lastchapterid,pa.lastchapter,pa.outchapters,aa.articleid, aa.articlename,aa.lastupdate,aa.sortid,aa.articletype');
		$count = $this->db->getCount($this->db->criteria);
		if($count == 0){
		}elseif ($count == 1){
			$this->db->queryObjects();
			$object=$this->db->getObject();
			//object-array
			foreach($object->getVars() as $k=>$v){
				$poolArticle[$k] = $v['value'];
			}
		}else{
			$this->out_msg_err('错误：数据不唯一');
			exit;
		}
		return $poolArticle;
	}
	
	
	public function getSiteIdBySortId($sortid){
		$articleLib = $this->load('article','article');
		$soruce = $articleLib->getSources();
		foreach($soruce['channel'] as $k=>$v){
			if(intval($v['minsortid']) <= $sortid && $sortid <= intval($v['maxsortid'])){
				return $k;
			}
		}
		//默认：0
		return 0;
	}
	/**
	 * icollect接口的默认实现，子类可以根据情况重写
	 * @param unknown $item
	 * @param unknown $channel
	 * @return Ambigous <multitype:NULL string number Ambigous <NULL, string, number> Ambigous <unknown, number, unknown> , unknown>
	 * 2014-8-22 上午11:34:21
	 */
	public function packingArticle($item,$channel,$loadLocalArticle=true){
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
		$article['sortid'] = $this->getLocalSortId($item['category']['value'],$channel);//重新计算和本站的对应关系
		$article['siteid'] = $this->getSiteIdBySortId($article['sortid']);//通过sortid计算siteid
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
}
?>