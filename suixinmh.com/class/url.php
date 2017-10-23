<?php
/**
 * 路径表类jieqi_system_url - 系统配置参数表)
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: chengyuan $
 * @version    $Id: url.php 312 2008-12-29 05:30:54Z juny $
 */

jieqi_includedb();
//配置类
class JieqiUrl extends JieqiObjectData
{

    //构建函数
    function JieqiUrl()
    {
        $this->initVar('id', JIEQI_TYPE_INT, 0, 'id', false, 8);
		$this->initVar('caption', JIEQI_TYPE_TXTBOX, '', '功能名称', true, 50);
        $this->initVar('modname', JIEQI_TYPE_TXTBOX, '', '模块名称', true, 50);
        $this->initVar('controller', JIEQI_TYPE_TXTBOX, '', '控制器', true, 10);
		$this->initVar('method', JIEQI_TYPE_TXTBOX, '', '方法名称', true, 20);
		$this->initVar('rule', JIEQI_TYPE_TXTBOX, '', '伪静态规则', true, 50);
		$this->initVar('params', JIEQI_TYPE_TXTBOX, '', '参数', true, 50);
		$this->initVar('description', JIEQI_TYPE_TXTAREA, '', '配置描述', false, NULL);
    }
	
}
//区块句柄
class JieqiUrlHandler extends JieqiObjectHandler
{
	function JieqiUrlHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='url';
	    $this->autoid='id';	
	    $this->dbname='system_url';
	}
}
?>