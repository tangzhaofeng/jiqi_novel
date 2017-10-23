<?php
/**
 * 数据池 ，类型：展示；数据格式：json 渠道接口
 * <p>
//  * 此接口定了：书籍列表分页，书籍列表，书籍详情，章节列表，章节内容接口
 * @author chengyuan
 *
 */
interface iDisplayJson
{
	/**
	 * 书籍列表分页接口
	 * <p>
	 * 返回实现接口渠道的数据
	 * @param unknown $date
	 * @return $array
	 */
	public function articlePage($date);
	
	/**
	 * 书籍列表接口
	 * @param unknown $Array
	 */
	public function articleList($date);
	
	/**
	 * 书籍详情接口
	 * @param unknown $Array
	 */
	public function articleInfo($date);
	/**
	 * 章节列表
	 * @param unknown $Array
	 */
	public function chapterList($date);
	
	/**
	 * 章节内容接口
	 * @param unknown $Array
	 */
	public function chapterContent($date);
}
?>