<?php
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/icollect.php');
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/baseCollect.php');
// include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
/**
 * 段天采集接口实现类
 * @author chengyuan  2014-8-21
 *
 */
class Myduantian extends baseCollect implements iCollect {
	/**
	 * 文章列表URL
	 * @var unknown
	 */
	const BOOKLIST = 'spdat.duantian.com/shuhai/getspbookids/';
	
	
	/**
	 * 获取渠道采集的文章列表，支持aids筛选
	 * @param unknown $cid
	 * @param unknown $page 暂时不支持页码
	 * @param unknown $aids 第三方书号
	 * 2014-8-19 下午1:52:02
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
		$rdata = $this->parseXmlToArray(Myduantian::BOOKLIST);
		$row = array();
		//一条数据item=array还是多条数据itme=array('key'=>array())
		$items =$rdata['document']['items']['item'];
		if(!array_key_exists(0,$items)){
			//一条数据特殊处理
			$temp = $items;
			$items = array($temp);
		}
		foreach ( $items as $item ){
			//筛选，指定并且类型相同
			$article = $this->simplePackingArticle($item,$channel);
			$row[$article['articleid']] = $article;
		}
		$data['rows'] = $row;
		$data['channel'] = $channel;
		return $data;
	
	}
	
	public function simplePackingArticle($item,$channel){
		return $this->packingArticle($item, $channel,false);
	}
	/* (non-PHPdoc)
	 * @see iCollect::articleList()
	*/
	public function articleList($cid, $page, $aids = array()) {
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
		$rdata = $this->parseXmlToArray(Myduantian::BOOKLIST);
		$row = array();
		//一条数据item=array还是多条数据itme=array('key'=>array())
		$items =$rdata['document']['items']['item'];
		if(!array_key_exists(0,$items)){
			//一条数据特殊处理
			$temp = $items;
			$items = array($temp);
		}
		foreach ( $items as $item ){
			//筛选，指定并且类型相同
			if(!empty($aids) && !in_array($item['bookid']['value'],$aids)){
				continue;
			}
			$article = $this->packingArticle($item,$channel);
			$row[$article['articleid']] = $article;
			if(!empty($aids) && count($row) == count($aids)){
				break;
			}
		}
		$data['rows'] = $row;
		$data['channel'] = $channel;
		return $data;
	}

// 	public function getSortMapping(){
// 		return array(
// 				'历史'=>'历史',
// 				'玄幻'=>'玄幻',
// 				'奇幻'=>'玄幻',
// 				'武侠'=>'武侠',
// 				'仙侠'=>'修真',
// 				'都市'=>'都市',
// 				'言情'=>'言情',
// 				'校园'=>'都市',
// 				'军事'=>'军事',
// 				'科幻'=>'科幻',
// 				'网游'=>'网游',
// 				'竞技'=>'竞技',
// 				'恐怖'=>'恐怖',
// 				'同人'=>'同人',
// 				'短篇'=>'微小',
// 				'诗词'=>'古典',
// 				'其他'=>'玄幻'
// 		);
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

}
?>