<?php
/**
 * api collect interface
 * @author chengyuan  2014-8-21
 */
interface iCollect
{
	/**
	 * 采集的文章类别匹配本地的类别ID,实现类的父类提供一个的默认实现，实现类可以根据情况重写。
	 * @param unknown $sort
	 * @return sortid
	 * 2014-8-21 下午3:30:29
	 */
	public function getLocalSortId($sort,$channel);
	/**
	 * 获取采集站点和本站的分类对应关系，对应本站分类shortcaption名称
	 *
	 * 2014-8-25 下午2:28:56
	 * 
	 * modify by chengyuan 2015-7-28 通过渠道配置获取
	 */
// 	public function getSortMapping();

	/**
	 * 采集文章列表
	 * <p>
	 * 每个渠道的采集列表格式不尽相同，有的只包含：书号，书名，有的是详细信息
	 * <p>
	 * 如果合作方接口提供的是书籍详细信息，则方法实现调用articleList即可。
	 * @param unknown $cid		渠道Id
	 * @param unknown $page		分页
	 */
	public function collectList($cid,$page);
	/**
	 * collectList接口的文章封装接口
	 * @param unknown $item
	 * @param unknown $channel
	 */
	public function simplePackingArticle($item,$channel);
	/**
	 * 获取渠道内的采集文章详细信息列表
	 * @param unknown $cid
	 * @param unknown $page
	 * @return array('rows'=>array('articleid'=>article,'articleid'=>article....),'channel'=>$channel)
	 * 2014-8-21 下午3:53:38
	 */
	public function articleList($cid,$page,$aids=array());
	/**
	 * 解析url指定的xml章节列表为Array，可通过$lastchapterid指定上次更新位置。
	 * @param unknown $url				章节列表url，xml文件
	 * @param number $lastchapterid		上次更新到的章节ID，默认0
	 */
	public function parseChapters($url,$lastchapterid=0);

	/**
	 * 封装成article
	 * @param unknown $item
	 * @param unknown $channel
	 * 2014-8-21 下午3:46:15
	 */
	public function packingArticle($item,$channel,$loadLocalArticle = true);
	/**
	 * 封装parseChapters接口解析的Array项为chapter格式的数组，统一各个渠道格式
	 * @param unknown $item
	 * 2014-8-22 上午9:39:06
	 */
	public function packingChapter($item);

}
?>