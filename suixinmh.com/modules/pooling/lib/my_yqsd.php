<?php
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/lib/my_ycsd.php');
/**
 * 言情书殿采集类，继承自原创书殿（一家api提供商的两个渠道，xml的模板格式相同）
 * @author chengyuan  2014-8-21
 *
 */
class MyYqsd extends MyYcsd{
	/**
	 * override
	 * @return string
	 * 2014-9-16 下午1:58:01
	 */
	protected function getBookListUrl(){
		return 'api.yqsd.cn/interface/shuhai/booklist/0';
	}
	/**
	 * override
	 * @return string
	 * 2014-9-16 下午1:58:01
	 */
	protected function getBookInfoUrl($bookId){
		return 'api.yqsd.cn/interface/shuhai/book/'.$bookId;
	}
	
	/**
	 * override
	 * @see MyCollectYcsd::getSortMapping()
	 */
// 	public function getSortMapping(){
// 		//todo 补全分类对应关系
// 		return array(
// 				'豪门总裁'=>'言情',
// 				'青春校园'=>'言情',
// 				'穿越架空'=>'言情',
// 				'种田重生'=>'言情',
// 				'玄幻奇幻'=>'玄幻',
// 				'都市异能'=>'都市',
// 				'都市高干'=>'都市',
// 				'综合其他'=>'网游',
// 				'仙侠幻情'=>'武侠',
// 				'历史军事'=>'历史',
// 				'悬疑灵异'=>'恐怖',
// 				'耽美同人'=>'同人'
// 		);
// 	}
}
?>