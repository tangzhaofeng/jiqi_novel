<?php
/**
 * api接口，合作的api需要实现此接口
 * @author chengyuan  2014-6-27
 *
 */
interface iApi
{
	/**
	 * 推送渠道下数据池的文章
	 * @param unknown $channleid	渠道ID
	 * @param unknown $article			数据池文章
	 * 2014-7-1 上午9:17:14
	 */
	public function push($channleid,$article);
	/**
	 * URL请求
	 * @param unknown $url
	 * @param unknown $mode
	 * @param unknown $params
	 * @param string $header
	 * 2014-7-3 上午11:36:21
	 */
	public function request($url, $mode, $params=array(),$header = 'Content-Type: text/plain; charset=utf-8;');
	/**
	 * 获取最后推送书的信息
	 * @param unknown $articleid	推送文章ID
	 * 2014-7-2 上午10:18:12
	 */
	public function get_lastupdate($articleid);
	/**
	 * 推送文章
	 * @param unknown $articleid	推送的文章本站id
	 * @param unknown $data			文章信息
	 * 2014-7-2 上午10:24:47
	 */
	public function addBook($articleid,$data);
	/**
	 * 推送一个章节
	 * @param unknown $message
	 * 2014-7-3 上午10:05:58
	 */
	public function addChapter($message);
}
?>