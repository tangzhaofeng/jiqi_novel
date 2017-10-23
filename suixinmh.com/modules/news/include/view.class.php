<?php
/*
    *公用的数据视图处理类
	[Cms News] (C) 2009-2012 Cms Inc.
	$Id: view.class.php 12398 2010-09-08 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

class View extends GlobalData{
    var $module = '';
	/**
	 * 构造函数
	 * 
	 * @param      void       
	 * @access     private
	 * @return     void
	 */
	function View($table, $idfield, $module = ''){
	     if(!$this->module){
			 if($module) $this->module = $module;
			 elseif(!$this->module) $this->module = _MODULE_;
		 }
		 $this->tablepre = $this->module."_";
		 $this->table = $this->tablepre.$table;
		 $this->idfield = $idfield;
	}
	
	/**
	 * 获取一条数据实例
	 * 
	 * @access     public
	 * @return     empty
	 */	
	function get($id, $isreture = false)
	{
		//从数据库获取
		$where = " where {$this->idfield} = '{$id}'";
		$data = selectsql('select * from '.jieqi_dbprefix("{$this->table}")." {$where}");
		if(!$data){
			if(!$isreture) jieqi_printfail(lang_replace('data_not_exists'));
			else return false;
		}	
		return $data[0];
	}	
	
	/**
	 * 增加一条数据
	 * 
	 * @access     public
	 * @return     int
	 */
	function add($data, $ishtml = true){
		if(!is_array($data)) return false;
		//不允许HTML
		if(!$ishtml) $data = shtmlspecialchars($data);
		if($id = inserttable($this->table, $data, true)){
			return $id;
		}else{
		    return false;
		}
	}		

	/**
	 * 修改
	 * 
	 * @access     public
	 * @return     bool
	 */
	function edit($id, $data, $ishtml = true){
		if(!is_array($data)) return false;
		//不允许HTML
		if(!$ishtml) $data = shtmlspecialchars($data);
		if(updatetable($this->table, $data,"{$this->idfield}='{$id}'")){
		    return true;
		}else {
		    return false;
		}
	}
	
	function cache(){
	    //
	}
}
?>