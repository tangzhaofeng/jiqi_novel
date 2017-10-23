<?php 
/**
 * 数据表类(jieqi_news_content  - 文章信息表)
 *
 * 数据表类(jieqi_news_content  - 文章信息表)
 * 
 * 调用模板：无
 * 
 * @category   cms
 * @package    news
 * @copyright  Copyright (c) huliming
 * @author     $Author: huliming $
 * @version    $Id: content.php 300 2010-06-02 04:36:06Z huliming QQ329222795 $
 */

jieqi_includedb();

class JieqiContent extends JieqiObjectData
{
	//构建函数
	function JieqiContent()
	{
		$this->JieqiObjectData();
		$this->initVar('contentid', JIEQI_TYPE_INT, 0, '序号', false, 8);
		$this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
		$this->initVar('catid', JIEQI_TYPE_INT, 0, '栏目ID', false, 5);
		$this->initVar('typeid', JIEQI_TYPE_INT, 0, '分类ID', false, 5);
		$this->initVar('areaid', JIEQI_TYPE_INT, 0, '地区ID ', false, 5);
		$this->initVar('title', JIEQI_TYPE_TXTBOX, '', '标题', false, 80);
		$this->initVar('style', JIEQI_TYPE_TXTAREA, '', '标题样式', false, 5);
		$this->initVar('thumb', JIEQI_TYPE_TXTAREA, '', '缩略图', false, 100);
		$this->initVar('keywords', JIEQI_TYPE_TXTBOX, '', '关键字', false, 40);
		$this->initVar('description', JIEQI_TYPE_TXTBOX, '', '文章参数', false, 255);
		$this->initVar('posids', JIEQI_TYPE_INT, 0, '推荐位', false, 1);
		$this->initVar('url', JIEQI_TYPE_TXTBOX, '', '链接地址', false, 50);
		$this->initVar('status', JIEQI_TYPE_INT, 0, '状态', false, 3);
		$this->initVar('userid', JIEQI_TYPE_INT, 0, '用户ID', false, 8);
		$this->initVar('username', JIEQI_TYPE_TXTBOX, '', '用户名', true, 20);
		$this->initVar('inputtime', JIEQI_TYPE_INT, 0, '发布时间 ', false, 10);
		$this->initVar('updatetime', JIEQI_TYPE_INT, 0, '更新时间 ', false, 10);
		$this->initVar('prefix', JIEQI_TYPE_TXTBOX, '', '生成文件名', false, 20);
		//$this->initVar('hits', JIEQI_TYPE_INT, 0, '点击量 ', false, 8);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiContentHandler extends JieqiObjectHandler
{

	function JieqiContentHandler($db='')
	{
		$this->JieqiObjectHandler($db);
		$this->basename='content';
		$this->autoid='contentid';
		$this->dbname='news_content';
	}
}

?>