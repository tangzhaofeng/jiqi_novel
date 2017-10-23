<?php
/*
	[Cms News] (C) 2010-2012 CMS Inc.
	$Id: function_db.php  2010-07-25 17:50:09Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

//执行SQL
function sql_execute($sql)
{
	global $_SGLOBAL;
	dbconnect();
    $sqls = sql_split($sql);
	if(is_array($sqls))
    {
		foreach($sqls as $sql)
		{
			if(trim($sql) != '')
			{
				$_SGLOBAL['db']->db->query($sql);
			}
		}
	}
	else
	{
		$_SGLOBAL['db']->db->query($sqls);
	}
	return true;
}

//SQL分隔成数组
function sql_split($sql)
{
	global $_SGLOBAL;
	if(dbversion() > '4.1' && JIEQI_DB_CHARSET)
	{
		$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "TYPE=\\1 DEFAULT CHARSET=".JIEQI_DB_CHARSET,$sql);
	}
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query)
	{
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		$queries = array_filter($queries);
		foreach($queries as $query)
		{
			$str1 = substr($query, 0, 1);
			if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
		}
		$num++;
	}
	return($ret);
}
?>